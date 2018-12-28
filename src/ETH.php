<?php
namespace ETHRpc;

use stdClass;

class ETH{

    /**
     * @var [ETHRpc]
     */
    private static $rpc = [];

    public static function init(string $host="127.0.0.1", ?int $port=8545, bool $ssl=false, string $name="default") : ETHRpc {
        return self::$rpc[$name] = new ETHRpc($host, $port, $ssl);
    }

    /**
     * @param string $name
     * @return ETHRpc | null
     */
    public static function getRpc(string $name="default"){
        return self::$rpc[$name] ?? null;
    }

    /**
     * 执行接口
     * @param $api
     * @param $method
     * @param array $params
     * @param string $name
     * @return Api\EthApi|null|stdClass
     */
    public static function execute(string $api, string $method, ?array $params=null, string $name="default"){
        if($rpc = self::getRpc($name)){
            return $rpc->{$api}->{$method}($params);
        }
        return null;
    }

    /**
     * @param string|null $method
     * @param array $params
     * @param string $name
     * @return Api\EthApi|null|stdClass
     */
    public static function eth(?string $method=null, $params=null, $name="default"){
        if($rpc = self::getRpc($name)){
            if(null==$method){
                return $rpc->eth;
            }
            return $rpc->eth->{$method}($params);
        }
        return null;
    }

    /**
     * @param string|null $method
     * @param array $params
     * @param string $name
     * @return Api\AdminApi|null|stdClass
     */
    public static function admin(?string $method=null, $params=null, $name="default"){
        if($rpc = self::getRpc($name)){
            if(null==$method){
                return $rpc->admin;
            }
            return $rpc->admin->{$method}($params);
        }
        return null;
    }

    /**
     * @param string|null $method
     * @param array $params
     * @param string $name
     * @return Api\MinerApi|null|stdClass
     */
    public static function miner(?string $method=null, $params=null, $name="default"){
        if($rpc = self::getRpc($name)){
            if(null==$method){
                return $rpc->miner;
            }
            return $rpc->miner->{$method}($params);
        }
        return null;
    }

    /**
     * @param string|null $method
     * @param array $params
     * @param string $name
     * @return Api\NetApi|null|stdClass
     */
    public static function net(?string $method=null, $params=null, $name="default"){
        if($rpc = self::getRpc($name)){
            if(null==$method){
                return $rpc->net;
            }
            return $rpc->net->{$method}($params);
        }
        return null;
    }

    /**
     * @param string|null $method
     * @param array $params
     * @param string $name
     * @return Api\PersonalApi|null|stdClass
     */
    public static function personal(?string $method=null, $params=[], $name="default"){
        if($rpc = self::getRpc($name)){
            if(null==$method){
                return $rpc->personal;
            }
            return $rpc->personal->{$method}($params);
        }
        return null;
    }

    /**
     * @param string|null $method
     * @param array $params
     * @param string $name
     * @return Api\Web3Api|null|stdClass
     */
    public static function web3(?string $method=null, $params=null, $name="default"){
        if($rpc = self::getRpc($name)){
            if(null==$method){
                return $rpc->web3;
            }
            return $rpc->web3->{$method}($params);
        }
        return null;
    }
}