<?php
namespace ETHRpc\Objects;

class BlockObject {

    /**
     * 区块编号，null当它的挂起块。
     * @var integer|null
     */
    public $number;

    /**
     * 区块哈希值，null当它的挂起块。
     * @var string|null
     */
    public $hash;

    /**
     * 父级区块哈希值
     * @var string
     */
    public $parentHash;

    /**
     * DATA，8字节 - 生成的工作证明的哈希值。null当它的挂起块。
     * @var string
     */
    public $nonce;

    /**
     * DATA，32字节 - 块中的uncles数据的SHA3。
     * @var string
     */
    public $sha3Uncles;

    /**
     * DATA，256字节 - 块的日志的布隆过滤器。null当它的挂起块。
     * @var string
     */
    public $logsBloom;

    /**
     * DATA, 32 Bytes - 块的事务trie的根
     * @var string
     */
    public $transactionsRoot;

    /**
     * DATA，32 Bytes - 块的最终状态trie的根
     * @var string
     */
    public $stateRoot;

    /**
     * DATA，32字节 - 块的收据trie的根
     * @var string
     */
    public $receiptsRoot;

    /**
     * DATA，20字节 - 获得采矿奖励的受益人的地址。
     * @var string
     */
    public $miner;

    /**
     * 此块的难度的整数
     * @var integer
     */
    public $difficulty;

    /**
     * 直到此块的链的总难度的整数。
     * @var integer
     */
    public $totalDifficulty;

    /**
     * 该块的“额外数据”字段
     * @var string
     */
    public $extraData;

    /**
     * 整数此块的大小（以字节为单位）
     * @var integer
     */
    public $size;

    /**
     * 该区块允许的最大燃料
     * @var integer
     */
    public $gasLimit;

    /**
     * 此块中所有事务的总使用燃料
     * @var integer
     */
    public $gasUsed;

    /**
     * 整理块时的unix时间戳
     * @var integer
     */
    public $timestamp;

    /**
     * 事务对象的数组，或32字节事务哈希，具体取决于最后给定的参数
     * @var string[]|TransactionObject[]
     */
    public $transactions = [];

    /**
     * 叔块哈希数组
     * @var array
     */
    public $uncles;


    /**
     *
     * BlockObject constructor.
     * @param \stdClass $object
     * @param bool $fullTransaction
     */
    public function __construct(\stdClass $object, bool $fullTransaction = false)
    {
        $this->number               = hexdec($object->number);
        $this->hash                 = $object->hash;
        $this->parentHash           = $object->parentHash;
        $this->nonce                = $object->nonce;
        $this->sha3Uncles           = $object->sha3Uncles;
        $this->logsBloom            = $object->logsBloom;
        $this->transactionsRoot     = $object->transactionsRoot;
        $this->stateRoot            = $object->stateRoot;
        $this->miner                = $object->miner;
        $this->difficulty           = hexdec($object->difficulty);
        $this->totalDifficulty      = hexdec($object->totalDifficulty);
        $this->extraData            = $object->extraData;
        $this->size                 = hexdec($object->size);
        $this->gasLimit             = hexdec($object->gasLimit);
        $this->gasUsed              = hexdec($object->gasUsed);
        $this->timestamp            = hexdec($object->timestamp);
        if($fullTransaction){
            foreach ($object->transactions as $transaction){
                $this->transactions[] = new TransactionObject($transaction);
            }
        }else{
            $this->transactions     = $object->transactions;
        }
        $this->uncles               = $object->uncles;
    }
}