<?php
namespace ETHRpc\Objects;

class TransactionReceiptObject {

    /**
     * 区块哈希
     * @var string
     */
    public $blockHash;

    /**
     * 区块编号
     * @var int
     */
    public $blockNumber;

    /**
     * 合约地址
     * @var null | string
     */
    public $contractAddress = null;

    /**
     * 在块中执行此事务时使用的总燃料
     * @var WeiObject
     */
    public $cumulativeGasUsed;

    /**
     * 付款人地址
     * @var string
     */
    public $from;

    /**
     * 本
     * @var WeiObject
     */
    public $gasUsed;

    /**
     * @var TransactionReceiptLogsObject[]
     */
    public $logs = [];

    /**
     * 256字节 - Bloom过滤器，用于轻型客户端快速检索相关日志。
     * @var string
     */
    public $logsBloom;

    /**
     * 1 = 成功， 0 = 失败
     * @var float|int
     */
    public $status = 1;

    /**
     * 收款地址
     * @var string
     */
    public $to;

    /**
     * 转账hash
     * @var string
     */
    public $transactionHash;

    /**
     * 转账区块中的索引
     * @var float|int
     */
    public $transactionIndex;

    public function __construct(\stdClass $object)
    {
        $this->blockHash = $object->blockHash;
        $this->blockNumber = hexdec($object->blockNumber);
        $this->contractAddress = $object->contractAddress;
        $this->cumulativeGasUsed = new WeiObject($object->cumulativeGasUsed);
        $this->from = $object->from;
        $this->gasUsed = new WeiObject($object->gasUsed);
        if(!empty($object->logs)){
            foreach ($object->logs as $log){
                $this->logs[] = new TransactionReceiptLogsObject($log);
            }
        }
        $this->logsBloom = $object->logsBloom;
        $this->status = hexdec($object->status);
        $this->to = $object->to;
        $this->transactionHash = $object->transactionHash;
        $this->transactionIndex = hexdec($object->transactionIndex);
    }

}