<?php
namespace ETHRpc\Api;
use ETHRpc\ApiBase;

class PersonalApi extends ApiBase
{

    /**
     * 创建新账户
     * @param string $password
     * @param int|null $id
     * @return string|null
     */
    public function newAccount($password, ?int $id = null) : ?string
    {
        $password = is_array($password) ? $password[0] : $password;
        $result = $this->call('newAccount', $password, $id);
        return $result->result ?? null;
    }

    /**
     * 解锁账户（只要密码正确，解锁都会成功）
     * @param string $account
     * @param string $password
     * @param int|null $id
     * @return bool
     */
    public function unlockAccount(string $account, string $password, ?int $id = null) : bool
    {
        $result = $this->call("unlockAccount", [$account, $password], $id);
        return $result->error==0 && $result->result==1;
    }

    /**
     * 锁定账户（只要连接成功，地址正确，都会锁定成功）
     * @param string $account
     * @param int|null $id
     * @return bool
     */
    public function lockAccount(string $account, ?int $id = null)
    {
        $account = is_array($account) ? $account[0] : $account;
        $result = $this->call('lockAccount', $account, $id);
        return $result->error==0 && $result->result==1;
    }

}