<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/ad/Public/Admin/css/menu.css" type="text/css" rel="stylesheet">
</head>
<body>
    <div class="left_nav">
        <div class="photo">
            <img src="/ad/Public/Admin/images/define_user.png"/>
            <div>
                <p>用户名:<?php echo session('un');?></p>
                <p>数据库:<?php echo C('DB_NAME');?></p>
            </div>
        </div>
        <ul>
            <li class="title-s"><a>分类管理</a></li>
            <li><A href="<?php echo U('Type/index');?>" target="main">分类列表</A></li>
            <li><A href="<?php echo U('Type/add');?>" target="main">添加分类</A></li>
        </ul>
    </div>
</body>
</html>