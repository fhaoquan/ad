<?php
return array(
    //七牛上传配置
    'UPLOAD_SITEIMG_QINIU' => array(
        'maxSize' => 5 * 1024 * 1024,//文件大小
        'rootPath' => './',
        'saveName' => array('uniqid', ''),
        'driver' => 'Qiniu',
        'driverConfig' => array(
            'accessKey' => 'GWdNy10cRt4M0WesoLjQdBiCPkQF7C-dMR7VPz96',
            'secretKey' => 'fJq9cTrJ-5Ah2oWaHC5-hnuPL1iBYWA1KcC4AbYk',
            'domain' => 'on2ilbqnc.bkt.clouddn.com',
            'bucket' => 'advisement',
        ),
    ),
    // 加载扩展配置文件
    'LOAD_EXT_CONFIG' => 'db',
    // 加载自定义标签
    'TAGLIB_BUILD_IN' => 'cx,html,loop',

    //支付宝配置参数
    'alipay_config' => array(
        'partner' => '2088912549766922',   //这里是你在成功申请支付宝接口后获取到的PID；
        'key' => '6uj2he2y0shb6q9seaakayma11p8vlc3',//这里是你在成功申请支付宝接口后获取到的Key
        'sign_type' => strtoupper('MD5'),
        'input_charset' => strtolower('utf-8'),
        'cacert' => getcwd() . '\\cacert.pem',
        'transport' => 'http',
    ),
    //以上配置项，是从接口包中alipay.config.php 文件中复制过来，进行配置；
    'alipay' => array(
        //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
        'seller_email' => 'lolocat1109@qq.com',
        //这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
        'notify_url' => 'http://www.ui100day.com/Pay/notifyurl',
        //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
        'return_url' => 'http://www.ui100day.com/Pay/returnurl',
        //支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
        'successpage' => 'User/vip?payed=1',
        //支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
        'errorpage' => 'User/vip?payed=0',
    ),
    //微信支付配置参数
    'wxpay_config' => array(
        'appid' => 'wx714261e7a480f525',
        'mchid' => '1330262901',
        'key' => '7901c98f234946277191beaee455bfa5',
        'appsecret' => 'f11301145c3090a3c5f2ccc2707fea4e',
        'js_api_call_url' => WEB_HOST . '/index.php/Home/WxJsAPI/jsApiCall',
        'sslcert_path' => WEB_HOST . '/ThinkPHP/Library/Vendor/Wxpay/cert/apiclient_cert.pem',
        'sslkey_path' => WEB_HOST . '/ThinkPHP/Library/Vendor/Wxpay/cert/apiclient_key.pem',
        'notify_url' => WEB_HOST . '/index.php/Home/WxJsAPI/notify',
        'curl_timeout' => 30
    ),
    // 配置邮件发送服务器
    'MAIL_HOST' => 'smtp.qq.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' => TRUE, //启用smtp认证
    'MAIL_USERNAME' => 'ui100days@uigreat.com',//你的邮箱名
    'MAIL_FROM' => 'ui100days@uigreat.com',//发件人地址
    'MAIL_FROMNAME' => 'UI100day',//发件人姓名
    'MAIL_PASSWORD' => '494178361Meng',//邮箱密码
    'MAIL_CHARSET' => 'utf-8',//设置邮件编码
    'MAIL_ISHTML' => TRUE, // 是否HTML格式邮件

);