<?php
namespace ETHRpc\Api;
use ETHRpc\ApiBase;

class MinerApi extends ApiBase
{
    /**
     * 开始挖矿（只要连接通了，都会成功，不管是否已停止挖矿）
     * @return bool
     */
    public function start() : bool {
        $result = $this->call('start');
        return $result->error == 0;
    }

    /**
     * 结束挖矿（只要连接通了，都会成功，不管是否已开始挖矿）
     * @return bool
     */
    public function stop() : bool {
        $result = $this->call('stop');
        return $result->result == 1;
    }
}