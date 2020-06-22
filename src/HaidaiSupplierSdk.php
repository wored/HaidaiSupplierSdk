<?php

namespace Wored\HaidaiSupplierSdk;


use Hanson\Foundation\Foundation;

/***
 * Class HaiXiaoSdk
 * @package \Wored\HaidaiSupplierSdk
 *
 * @property \Wored\HaidaiSupplierSdk\Api $api
 */
class HaidaiSupplierSdk extends Foundation
{
    protected $providers = [
        ServiceProvider::class
    ];

    public function __construct($config)
    {
        $config['debug'] = $config['debug'] ?? false;
        parent::__construct($config);
    }

    /**
     * 设置登录方法返回账号相关参数
     * @param array $params
     */
    public function setLoginResult(array $params)
    {
        $this->api->setLoginResult($params);
    }

    /**
     * 账号登录，用于获取账号相关参数
     * @return mixed
     * @throws \Exception
     */
    public function login()
    {
        return $this->api->login();
    }

    /**
     * 验证签名是否正确
     * @param array $params
     * @return bool
     */
    public function verifySign(array $params)
    {
        $paramSign = $params['topSign'];
        $sign = $this->api->sign($params);
        if ($paramSign == $sign) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 发货
     * @param $orderSn订单编号
     * @param $dlyCodes物流公司（例：shentong）,多个以逗号分隔
     * @param $shipNos运单号，多个以逗号分隔
     * @return mixed
     * @throws \Exception
     */
    public function delivery($orderSn, $dlyCodes, $shipNos)
    {
        $method = '/api/v2/order/shipping';
        $params = [
            'accountId' => $this->api->loginData['accountId'],
            'memberId'  => $this->api->loginData['memberId'],
            'token'     => $this->api->loginData['token'],
            'orderSn'   => $orderSn,
            'dlyCodes'  => $dlyCodes,
            'shipNos'   => $shipNos,
        ];
        $response = $this->api->request($method, $params);
        return $response;
    }

    /**
     * 退单接口
     * @param $orderSns订单编号
     * @param $returnReason退单原因code
    退单原因列表（中文原因，字符要求完全一致）：
    1	身份证重复
    2	地址异常：地址中不能出现关于超市、母婴店、药店等类似的字眼
    3	地址超过购买限额
    4	联系方式有误
    5	报关失败
    6	收件人信息重复
    7	单证审核不通过
    8	身份证海关封号
    10	身份证号码错误
    20	身份证号码与姓名不对应
    30	身份验证失败
    40	地址不详
    50	地址重复
    60	联系方式超过购买限额
    70	身份信息超过购买限额
    80	订单商品缺货
    90	物流配送无法联系到收货人
    95	其它
    96	未上传身份证照片
     * @return mixed
     * @throws \Exception
     */
    public function refund($orderSns, $returnReason)
    {
        $method = '/api/v2/order/batchAddExceptionReply';
        $params = [
            'accountId'    => $this->api->loginData['accountId'],
            'memberId'     => $this->api->loginData['memberId'],
            'token'        => $this->api->loginData['token'],
            'orderSns'     => $orderSns,
            'returnReason' => $returnReason,
        ];
        $response = $this->api->request($method, $params);
        return $response;
    }
}