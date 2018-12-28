<?php
namespace ETHRpc\Objects;

class WeiObject{

    /**
     * 十六进制数字
     * @var string
     */
    public $hexNumber;

    /**
     * 十进制数字
     * @var float|int
     */
    public $number;

    /**
     * 对象初始化
     * WeiObject constructor.
     * @param string $string
     */
    public function __construct(?string $string)
    {
        $this->hexNumber = $string;
        $this->number = $string ? hexdec($string) : 0;
    }

    /**
     * 转成最小单位
     * @return float|int
     */
    public function toWei(){
        return $this->number;
    }

    /**
     * @return float|int
     */
    public function toKWei(){
        return $this->transfer(3);
    }

    /**
     * @return float|int
     */
    public function toMWei(){
        return $this->transfer(6);
    }

    /**
     * @return float|int
     */
    public function toGWei(){
        return $this->transfer(9);
    }

    /**
     * @return float|int
     */
    public function toMicroEther(){
        return $this->transfer(12);
    }
    /**
     * @return float|int
     */
    public function toMilliEther(){
        return $this->transfer(15);
    }

    /**
     * 转换成以太坊单位
     * @return float|int
     */
    public function toEther(){
        return $this->transfer();
    }

    /**
     * 单位转换
     * @param int $exp
     * @return float|int
     */
    private function transfer($exp = 18){
        return $this->number/pow(10, $exp);
    }
}