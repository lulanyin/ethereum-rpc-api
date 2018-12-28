<?php
namespace ETHRpc\Objects;

/**
 * 合约数据
 * Class TransactionReceiptLogsObject
 * @package ETHRpc\Objects
 */
class TransactionReceiptLogsObject {

    /**
     * 合约地址
     * @var string
     */
    public $address;

    /**
     *
     * @var array
     */
    public $topics = [];

    /**
     * 转账数据数量
     * @var WeiObject
     */
    public $data;

    /**
     * 所属区块
     * @var int
     */
    public $blockNumber;

    /**
     * 转账HASH
     * @var string
     */
    public $transactionHash;

    /**
     * 转账所属区块索引
     * @var int
     */
    public $transactionIndex;

    /**
     * 所属区块哈希
     * @var string
     */
    public $blockHash;

    /**
     * 索引
     * @var int
     */
    public $logIndex;

    /**
     * @var ...
     */
    public $removed;

    public function __construct(\stdClass $object)
    {
        if(empty($object)){
            return null;
        }
        $this->address = $object->address;
        $this->blockHash = $object->blockHash;
        $this->blockNumber = hexdec($object->blockNumber);
        $this->data = new WeiObject($object->data);
        $this->logIndex = $object->logIndex;
        $this->removed = $object->removed;
        $this->topics = $object->topics;
        $this->transactionHash = $object->transactionHash;
        $this->transactionIndex = hexdec($object->transactionIndex);
    }
}