<?php

namespace Wored\HaidaiSupplierSdk;

use Hanson\Foundation\AbstractAPI;
use Hanson\Foundation\Log;

class Api extends AbstractAPI
{
    public $config;
    public $timestamp;
    public $loginData;

    /**
     * Api constructor.
     * @param $appkey
     * @param $appsecret
     * @param $sid
     * @param $baseUrl
     */
    public function __construct(HaidaiSupplierSdk $haidaiSupplierSdk)
    {
        $this->config = $haidaiSupplierSdk->getConfig();
    }

    /*
     * 设置登录方法返回账号相关参数
     */
    public function setLoginResult(array $params)
    {
        $this->loginData = $params;
    }

    /**
     * api请求方法
     * @param $method域名后链接
     * @param $params账后相关参数以外请求参数
     * @return mixed
     * @throws \Exception
     */
    public function request($method, $params)
    {
        if ($method != '/ssoapi/v2/login/login' and empty($this->loginData)) {
            throw new \Exception('账号参数必须，请使用setLoginResult()方法设置');
        }
        $request = [
            'appkey'    => $this->config['appkey'],
            'timestamp' => $this->microtime(),
        ];
        $request = array_merge($request, $params);
        $request['topSign'] = $this->sign($request);
        $url = $this->config['rootUrl'] . $method . '?' . http_build_query($request);
        $http = $this->getHttp();
        $response = call_user_func_array([$http, 'POST'], [$url]);
        return json_decode(strval($response->getBody()), true);
    }

    /**
     * 账号登录，用于获取账号相关参数
     * @return mixed
     * @throws \Exception
     */
    public function login()
    {
        $method = '/ssoapi/v2/login/login';
        $params = [
            'username' => $this->config['username'],
            'password' => md5($this->config['password']),
        ];
        $response = $this->request($method, $params);
        return $response;
    }

    /**
     * 生成签名
     * @param array $params请求的所有参数
     * @return string
     */
    public function sign(array $params)
    {
        unset($params['topSign']);
        ksort($params, SORT_STRING);//参数按键排序
        $str = $this->config['appSecret'] . http_build_query($params) . $this->config['appSecret'];//两端加字符
        return strtoupper(sha1($str));//加密生成签名
    }

    /**
     * 生成毫秒
     * @return string
     */
    private function microtime()
    {
        list($t1, $t2) = explode(' ', microtime());
        return $t2 . ceil(($t1 * 1000));
    }
}