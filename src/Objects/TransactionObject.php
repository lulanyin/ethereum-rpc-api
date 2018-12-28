<?php
namespace ETHRpc\Objects;

class TransactionObject{

    /**
     * DATA, 32 Bytes - hash of the block where this transaction was in. null when its pending.
     * @var string
     */
    public $blockHash;

    /**
     * QUANTITY - block number where this transaction was in. null when its pending.
     * @var string
     */
    public $blockNumber;

    /**
     * DATA, 20 Bytes - address of the sender.
     * @var string
     */
    public $from;

    /**
     * QUANTITY - gas provided by the sender.
     * @var WeiObject
     */
    public $gas;

    /**
     * QUANTITY - gas price provided by the sender in Wei.
     * @var float|int
     */
    public $gasPrice;

    /**
     * DATA, 32 Bytes - hash of the transaction.
     * @var string
     */
    public $hash;

    /**
     * DATA - the data send along with the transaction.
     * @var string
     */
    public $input;

    /**
     * QUANTITY - the number of transactions made by the sender prior to this one.
     * @var string
     */
    public $nonce;

    /**
     * DATA, 20 Bytes - address of the receiver. null when its a contract creation transaction.
     * @var string
     */
    public $to;

    /**
     * QUANTITY - integer of the transaction's index position in the block. null when its pending.
     * @var string
     */
    public $transactionIndex;

    /**
     * QUANTITY - value transferred in Wei.
     * @var WeiObject
     */
    public $value;

    /**
     * QUANTITY - ECDSA recovery id
     * @var string
     */
    public $v;

    /**
     * DATA, 32 Bytes - ECDSA signature r
     * @var string
     */
    public $r;

    /**
     * DATA, 32 Bytes - ECDSA signature s
     * @var
     */
    public $s;

    public function __construct(\stdClass $object)
    {
        $this->blockHash            = $object->blockHash;
        $this->blockNumber          = hexdec($object->blockNumber);
        $this->from                 = $object->from;
        $this->gas                  = hexdec($object->gas);
        $this->gasPrice             = new WeiObject($object->gasPrice);
        $this->hash                 = $object->hash;
        $this->input                = $object->input;
        $this->nonce                = $object->nonce;
        $this->to                   = $object->to;
        $this->transactionIndex     = hexdec($object->transactionIndex);
        $this->value                = new WeiObject($object->value);
        $this->v                    = $object->v;
        $this->r                    = $object->r;
        $this->s                    = $object->s;
    }
}