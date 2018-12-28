<?php
namespace ETHRpc\Api;
use ETHRpc\ApiBase;
use ETHRpc\Objects\BlockObject;
use ETHRpc\Objects\TransactionObject;
use ETHRpc\Objects\TransactionReceiptObject;

class EthApi extends ApiBase
{

    /**
     * 获取默认账户
     * @param int|null $id
     * @return string|null
     */
    public function coinBase(?int $id = null) : ?string
    {
        $result = $this->call('coinbase', [], $id);
        return $result->result ?? null;
    }

    /**
     * 获取账户列表
     * @param int|null $id
     * @return array
     */
    public function accounts(?int $id = null) : array
    {
        $result = $this->call('accounts', [], $id);
        return $result->result ?? [];
    }

    /**
     * 获取钱包余额（返回以太坊单位的数字）
     * @param string $address
     * @param string $quantity
     * @param int|null $id
     * @return float|int|null
     */
    public function getBalance($address = 'base', $quantity = 'latest',  ?int $id = null){
        $address = is_array($address) ? $address[0] : $address;
        if($address=='base'){
            $address = $this->coinBase($id);
            if(!$address){
                return null;
            }
        }
        $result = $this->call('getBalance', [$address, $quantity], $id);
        if($result->error===0){
            $wei = hexdec($result->result);
            if($wei>0){
                return ($wei/pow(10, 18));
            }
            return 0;
        }
        return null;
    }

    /**
     * 获取 gas price
     * 返回的是以太坊单位
     * @param int|null $id
     * @return float|int|null
     */
    public function gasPrice(?int $id = null){
        $result = $this->call('gasPrice', [], $id);
        if($result->error===0){
            //单位为Gwei, 10的10次方
            $wei = $result->result ? hexdec($result->result) : 0;
            return $wei/pow(10, 10);
        }
        return null;
    }


    /**
     * 发送交易（请先解锁账户再发起交易）
     * @param string $from
     * @param string $to
     * @param $value
     * @param int $gasPriceMulti
     * @param int|null $id
     * @return string|null
     */
    public function sendTransaction(string $from, string $to, $value, $gasPriceMulti = 10, ?int $id = null){
        //获取gasPrice
        if($gasPrice = $this->gasPrice()){
            //
            $params = [
                "from"      => $from,
                "to"        => $to,
                //默认为 90000
                "gas"       => "0x".dechex(90000),
                //单位为Gwei, 10的10次方wei，最终要转换为 wei 单位
                "gasPrice"  => "0x".dechex($gasPrice*$gasPriceMulti*pow(10, 10)),
                //转账数量，最终要转换为 wei， 1eth = 10的18次方wei
                "value"     => "0x".dechex($value*pow(10,18))
            ];
            //注意，由于可以一次发起多个转账，所以单条转账使用二维数组
            $result = $this->call('sendTransaction', [$params], $id);
            if($result->error===0){
                return $result->result ?? null;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    /**
     * 根据哈希值获取交易详情
     * @param $hash
     * @param int|null $id
     * @return null|TransactionObject
     */
    public function getTransactionByHash($hash, ?int $id=null){
        $hash = is_array($hash) ? $hash[0] : $hash;
        $result = $this->call('getTransactionByHash', $hash, $id);
        if($result->result){
            return new TransactionObject($result->result);
        }
        return null;
    }

    public function getTransactionReceipt($hash, ?int $id=null){
        $hash = is_array($hash) ? $hash[0] : $hash;
        $result = $this->call('getTransactionReceipt', $hash, $id);
        if($result->result){
            return new TransactionReceiptObject($result->result);
        }
        return null;
    }

    /**
     * 创建一个过滤器
     * @param string $from
     * @param string|null $to
     * @param string|null $address
     * @param int|null $id
     * @return null|string
     */
    public function newFilter(string $from = "latest", string $to = "latest", string $address = null, int $id = null){
        $params = [
            "from"  => $from,
            "to"    => $to,
            "address" => $address
        ];
        $result = $this->call('newFilter', [$params], $id);
        return $result->result ?? null;
    }

    /**
     * 新区块产生，一般应用于，遍历新区块的上一个区块所有交易
     * @param int|null $id
     * @return null
     */
    public function newBlockFilter(int $id = null){
        $result = $this->call('newBlockFilter', [], $id);
        return $result->result ?? null;
    }

    /**
     * 获取新区块生成的通知，会执行回调，回调参数是区块对象
     * @param $filterId
     * @param \Closure $callback
     * @param bool $fullTransaction
     * @param int $seconds
     * @param int|null $id
     */
    public function getBlockFilterChanges($filterId, \Closure $callback, bool $fullTransaction = false, int $seconds = 10, int $id = null){
        while (true){
            if($hashList = $this->getFilterChanges($filterId, $id)){
                //获取到转账hash，根据hash获取转账详情
                foreach ($hashList as $hash){
                    if($block = $this->getBlockByHash($hash, $fullTransaction, $id)){
                        $callback($block);
                    }
                }
            }
            sleep($seconds);
        }
    }

    /**
     * 创建一个进账过虑器
     * @param int|null $id
     * @return string|null
     */
    public function newPendingTransactionFilter(?int $id = null){
        $result = $this->call('newPendingTransactionFilter', [], $id);
        return $result->result ?? null;
    }

    /**
     * 获取转账变动通知
     * @param $filterId
     * @param \Closure $callback
     * @param int $seconds
     * @param int|null $id
     */
    public function getPendingTransactionFilterChanges($filterId, \Closure $callback, int $seconds = 10, ?int $id = null){
        while (true){
            if($hashList = $this->getFilterChanges($filterId, $id)){
                //获取到转账hash，根据hash获取转账详情
                foreach ($hashList as $hash){
                    if($transaction = $this->getTransactionByHash($hash, $id)){
                        $callback($transaction);
                    }
                }
            }
            sleep($seconds);
        }
    }

    /**
     * 移除过滤器
     * @param $filterId
     * @param int|null $id
     * @return \stdClass
     */
    public function uninstallFilter($filterId, ?int $id = null){
        $filterId = is_array($filterId) ? $filterId[0] : $filterId;
        return $this->call('uninstallFilter', $filterId, $id);
    }

    /**
     * 获取过虑器变动结果
     * @param $filterId
     * @param int|null $id
     * @return array|null
     */
    public function getFilterChanges($filterId, ?int $id = null){
        $filterId = is_array($filterId) ? $filterId[0] : $filterId;
        $result = $this->call('getFilterChanges', $filterId, $id);
        return $result->result ? (array)$result->result : null;
    }

    /**
     * 根据哈希获取获取区块
     * @param string $hash
     * @param bool $fullTransaction 是否获取完整的交易对象，否则仅返回交易哈希
     * @param int|null $id
     * @return BlockObject|null
     */
    public function getBlockByHash(string $hash, bool $fullTransaction = false, int $id = null) : ?BlockObject {
        $result = $this->call('getBlockByHash', [$hash, $fullTransaction], $id);
        if($result->result){
            return new BlockObject($result->result, $fullTransaction);
        }
        return null;
    }

    /**
     * 根据区块编号获取区块
     * @param int $number
     * @param bool $fullTransaction 是否获取完整的交易对象，否则仅返回交易哈希
     * @param int|null $id
     * @return BlockObject|null
     */
    public function getBlockByNumber(int $number, bool $fullTransaction = false, int $id = null) : ?BlockObject {
        $result = $this->call('getBlockByNumber', [dechex($number), $fullTransaction], $id);
        if($result->result){
            return new BlockObject($result->result, $fullTransaction);
        }
        return null;
    }
}