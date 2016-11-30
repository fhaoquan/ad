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
		<?php if(empty($model)): ?><ul><li class="title-s"><a href="<?php echo U('Model/add');?>" target="main">请先<span style="color:#4679bd;">添加模型</span></a></li></ul>
		<?php else: ?>
        <?php if(is_array($model)): foreach($model as $key=>$v): ?><ul>
                <li class="title-s"><a><?php echo ($v["title"]); ?>管理</a></li>
                <li><a href="<?php echo U('Content/index',array('model'=>$v['model']));?>" target="main"><?php echo ($v["title"]); ?>列表</a></li>
                <li><a href="<?php echo U('Content/add',array('model'=>$v['model']));?>" target="main">添加<?php echo ($v["title"]); ?></a></li>
            </ul><?php endforeach; endif; endif; ?>
    </div>
</body>
</html>