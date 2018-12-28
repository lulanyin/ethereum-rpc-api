<?php
namespace ETHRpc;
class ApiBase
{

    /**
     * @var ETHRpc
     */
    public $rpc;

    private $id = 1;

    /**
     * 初始化
     * ApiBase constructor.
     * @param ETHRpc $rpc
     */
    public function __construct(ETHRpc $rpc)
    {
        $this->rpc = $rpc;
    }

    /**
     * 无方法时自动处理
     * @param $name
     * @param $arguments
     * @return \stdClass
     */
    public function __call(string $name, $arguments)
    {
        try{
            //反射获取是哪个类执行的
            $mr = new \ReflectionClass($this);
            $api = substr($mr->name, 11, -3);
            $api = strtolower($api);
            if(in_array($api, ["admin", "eth", "miner", "net", "personal", "web3", "txpool"])){
                $params = $arguments[0] ?? [];
                $params = is_string($params) ? [$params] : $params;
                return $this->curl_post($api, $name, $params, $arguments[1] ?? null);
            }
            return $this->responseNull();
        }catch (\ReflectionException $e){
            return $this->responseNull();
        }
    }

    /**
     * 常规处理
     * @param $method
     * @param array $params
     * @param int|null $id
     * @return \stdClass
     */
    public function call(string $method, $params = [], ?int $id = null)
    {
        try{
            $mr = new \ReflectionClass($this);
            $api = substr($mr->name, 11, -3);
            $api = strtolower($api);
            if(in_array($api, ["admin", "eth", "miner", "net", "personal", "web3", "txpool"])){
                $params = is_array($params) ? $params : [$params];
                return $this->curl_post($api, $method, $params, $id);
            }
            return $this->responseNull($id);
        }catch (\ReflectionException $e){
            return $this->responseNull($id);
        }
    }

    /**
     * 使用
     * @param $api
     * @param $method
     * @param array $params
     * @param null $id
     * @return \stdClass
     */
    public function curl_post(string $api, string $method, $params=[], $id=null)
    {
        if(null==$id)
        {
            $id = $this->id;
            $this->id++;
        }
        $url = "http".($this->rpc->ssl ? "s" : "")."://".$this->rpc->host.":".$this->rpc->port;
        $json = [
            "jsonrpc"   => "2.0",
            "method"    => "{$api}_{$method}",
            "params"    => $params ?? [],
            "id"        => $id
        ];
        $json = json_encode($json);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ));
        $result = curl_exec($ch);
        //执行结果应该是一个JSON
        if(!empty($result))
        {
            if($result_json = @json_decode($result))
            {
                $returnStdClass = new \stdClass();
                $returnStdClass->id = $result_json->id;
                if(isset($result_json->error))
                {
                    $returnStdClass->error = $result_json->error->code;
                    $returnStdClass->message = $result_json->error->message;
                    $returnStdClass->result = null;
                }
                elseif (isset($result_json->result))
                {
                    $returnStdClass->error = 0;
                    $returnStdClass->message = 'ok';
                    $returnStdClass->result = $result_json->result;
                }
                else {
                    $returnStdClass->error = 0;
                    $returnStdClass->message = 'ok';
                    $returnStdClass->result = null;
                }
                return $returnStdClass;
            }else{
                return $this->responseNull($id);
            }
        }else{
            return $this->responseNull($id);
        }
    }

    /**
     * 返回空的数据
     * @param int|null $id
     * @return \stdClass
     */
    public function responseNull(?int $id = null)
    {
        $std = new \stdClass();
        $std->id = $id;
        $std->error = 1;
        $std->message = 'null result';
        $std->result = null;
        return $std;
    }
}