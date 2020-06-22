<h1 align="center"> 海带供应链供应商接口</h1>

## 安装

```shell
$ composer require wored/haidaisupplier-sdk -vvv
```

## 使用
```php
<?php
use \Wored\HaidaiSupplierSdk\HaidaiSupplierSdk;

$config = [
    'appkey'    => '******',
    'appSecret' => '******',
    'username'  => '******',
    'memberId'  => '******',
    'password'  => '******',
    'rootUrl'   => 'http://******.com',
    'debug'     => true,
];
// 海带sdk
$haidai = new HaidaiSupplierSdk($config);
$loginResult = $haidai->login();//账号登录
if (isset($loginResult['result']) and $loginResult['result'] == 1) {//登录成功
    $loginData = $loginResult['data'];//登录数据
    $haidai->setLoginResult($loginData);
} else {
    throw new Exception('登录失败');
}
$haidai->delivery('test','yuantong','YT2055358076810');//发货
$haidai->refund('test','test');//退单接口
```
## License

MIT