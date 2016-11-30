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
            <li class="title-s"><a>会员管理</a></li>
            <li><A href="<?php echo U('User/index');?>" target=main>会员列表</A></li>
            <li><A href="<?php echo U('User/add');?>" target=main>添加会员</A></li>
            <li><A href="<?php echo U('Fields/index', array('model'=>'user'));?>" target=main>字段管理</A></li>
        </ul>
        <ul>
            <li class="title-s"><a>会员组管理</a></li>
            <li><A href="<?php echo U('Group/index');?>" target=main>会员组列表</A></li>
            <li><A href="<?php echo U('Group/add');?>" target=main>添加会员组</A></li>
            <li><A href="<?php echo U('Fields/index', array('model'=>'user_group'));?>" target=main>字段管理</A></li>
        </ul>
    </div>
</body>
</html>