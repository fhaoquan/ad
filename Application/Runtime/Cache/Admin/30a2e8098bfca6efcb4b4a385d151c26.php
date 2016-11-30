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
            <li class="title-s"><a>站点管理</a></li>
            <li><a href="<?php echo U('Site/index');?>" target=main>修改信息</a></li>
            <li><a href="<?php echo U('Site/deletecache');?>" target=main>清空缓存</a></li>
        </ul>
        <ul>
            <li class="title-s"><a>备份</a></li>
            <li><a href="<?php echo U('Backup/index');?>" target=main>数据库备份</a></li>
            <li><a href="<?php echo U('Backup/restore');?>" target=main>数据库恢复</a></li>
        </ul>
    </div>
</body>
</html>