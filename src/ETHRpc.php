<?php
namespace ETHRpc;

use ETHRpc\Api\AdminApi;
use ETHRpc\Api\EthApi;
use ETHRpc\Api\MinerApi;
use ETHRpc\Api\NetApi;
use ETHRpc\Api\PersonalApi;
use ETHRpc\Api\Web3Api;

class ETHRpc{

    /**
     * RPC接口地址（可代理）
     * @var string
     */
    public $host = "127.0.0.1";

    /**
     * RPC端口（可代理）
     * @var int
     */
    public $port = 8545;

    /**
     * 是否是https
     * @var bool
     */
    public $ssl = false;

    /**
     * 用于自增，命令执行后自增，除非自定义
     * @var int
     */
    private $id = 1;

    /**
     * @var adminApi
     */
    public $admin;

    /**
     * @var EthApi
     */
    public $eth;

    /**
     * @var minerApi
     */
    public $miner;

    /**
     * @var NetApi
     */
    public $net;

    /**
     * @var personalApi
     */
    public $personal;

    /**
     * @var web3Api
     */
    public $web3;


    public function __construct(string $host = "127.0.0.1", ?int $port = 8545, bool $ssl=false)
    {
        //配置
        $this->host         = $host;
        $this->port         = $port;
        $this->ssl          = $ssl;
        //各方法接口处理类
        $this->admin        = new AdminApi($this);
        $this->eth          = new EthApi($this);
        $this->miner        = new MinerApi($this);
        $this->net          = new NetApi($this);
        $this->personal     = new PersonalApi($this);
        $this->web3         = new Web3Api($this);
    }

    /**
     * 使用方法设置RPC接口地址（可代理）
     * @param string $host
     */
    public function host(string $host = "127.0.0.1"){
        $this->host = $host;
    }

    /**
     * 使用方法设置RPC端口（可代理）
     * @param int $port
     */
    public function port(?int $port = 8545){
        $this->port = $port;
    }

    /**
     * 使用方法设置是否是https
     * @param bool $ssl
     */
    public function ssl(bool $ssl = false){
        $this->ssl = $ssl;
    }

    public function admin(string $method, $params=[], $id = null){
        if(null==$id){
            $id = $this->id;
            $this->id++;
        }
        return $this->admin->{$method}($params, $id);
    }

    public function eth(string $method, $params=[], $id = null){
        if(null==$id){
            $id = $this->id;
            $this->id++;
        }
        return $this->eth->{$method}($params, $id);
    }

    public function miner(string $method, $params=[], $id = null){
        if(null==$id){
            $id = $this->id;
            $this->id++;
        }
        return $this->miner->{$method}($params, $id);
    }

    public function __call(string $name, $arguments)
    {
        // TODO: Implement __call() method.
        $std = new \stdClass();
        $std->id        = 0;
        $std->error     = 1;
        $std->message   = "RPC->{$name} not exists";
        $std->result    = null;
        $std->arguments = $arguments;
        return $std;
    }
}