<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/ad/Public/Admin/css/head.css" type="text/css" rel="stylesheet">
<link href="/ad/Public/Admin/icon/icon.css" type="text/css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="logo"><img src="/ad/Public/Admin/images/login.png"/></div>
        <ul class="nav_top">
            <li><a href="<?php echo U('Index/menu');?>" target="menu"><span class="icon icon-1"></span>&nbsp;内容管理</a></li>
            <li><a href="<?php echo U('Index/menu1');?>" target="menu"><span class="icon icon-2"></span>&nbsp;分类管理</a> </li>
            <li><a href="<?php echo U('Index/menu2');?>" target="menu"><span class="icon icon-3"></span>&nbsp;模型管理</a></li>
            <li><a href="<?php echo U('Index/menu3');?>" target="menu"><span class="icon icon-7"></span>&nbsp;会员管理</a></li>
            <li><a href="<?php echo U('Index/menu4');?>" target="menu"><span class="icon icon-7"></span>&nbsp;系统管理</a></li>
        </ul>
        <ul class="nav_top_right">
            <li><a href="<?php echo U('Index/main');?>" target="main"><span class="icon icon-4"></span>&nbsp;后台首页</a></li>
            <li><a href="/ad/" target="_blank"><span class="icon icon-4"></span>&nbsp;前台首页</A></li>
            <li><a href="<?php echo U('Rbac/modifyUser',array('uid'=>session('uid')));?>" target=main><span class="icon icon-5"></span>&nbsp;修改口令</A></li>
            <li><a href="<?php echo U('Login/logout');?>" target=_top><span class="icon icon-6"></span>&nbsp;退出</a></li>
        </ul>
    </div>
</body>
</html>