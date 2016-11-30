<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
    <link rel="stylesheet" type="text/css" href="/ad/Public/Admin/css/body.css" />
    <link rel="stylesheet" type="text/css" href="/ad/Public/Admin/icon/icon.css" />
</head>
<body>
    <div class="top">
        <div class="position"><span class="icon icon-31"></span>当前位置：后台首页</div>
        <div class="top-title">
            <span class="icon icon-32"></span>
            <div class="top-tool">
                <a href="javascript:void(0);" class="button-a">基本信息</a>
            </div>
        </div>
    </div>
    <div class="body">
        <table class="list-tb" border="0" cellpadding="0" cellspacing="4">
            <tr>
                <th width="30%">服务器信息</th>
                <th width="30%">个人信息</th>
                <th width="40$">其他信息</th>
            </tr>
            <tr>
                <td>操作系统：<?php echo PHP_OS;?></td>
                <td>用户账号：<?php echo session('un');?></td>
                <td>模型(<a href="<?php echo U('Model/index');?>">更多</a>)：
				<?php if(empty($model)): ?>无
				<?php else: ?>
					<?php if(is_array($model)): foreach($model as $key=>$vo): ?><span style="display: inline-block;background:#ec6941;margin-left:5px;height:15px;font-size:11px;padding:2px 5px;color:#fff;"><?php echo ($vo["title"]); ?></span><?php endforeach; endif; endif; ?>
                </td>
            </tr>
            <tr>
                <td>服务器名称：<?php echo ($_SERVER['SERVER_NAME']); ?></td>
                <td>用户姓名：<span style="color: red;"><?php echo session('rn');?></span></td>
                <td>前台会员共有<span style="color: green;"> <?php echo ((isset($qthy) && ($qthy !== ""))?($qthy):"0"); ?> </span>个</td>
            </tr>
            <tr>
                <td>PHP版本：<?php echo phpversion();?></td>
                <td>上次登陆IP：<?php echo session('li');?></td>
                <td>后台会员共有<span style="color: green;"> <?php echo ((isset($hthy) && ($hthy !== ""))?($hthy):"0"); ?> </span>个</td>
            </tr>
            <tr>
                <td>CMS版本：<span style="color: green;">HeavonCMS <?php echo C('M_VER');?></span></td>
                <td>账号过期时间：30分钟</td>
                <td>全站有<span style="color: green;"> <?php echo ((isset($discuss) && ($discuss !== ""))?($discuss):"0"); ?> </span>条评论</td>
            </tr>
            <tr>
                <td>网站根目录：<?php echo ($_SERVER['DOCUMENT_ROOT']); ?></td>
                <td>上次登陆日期：<?php echo session('lt');?></td>
                <td>有<span style="color: green;"> <?php echo ((isset($advertisement) && ($advertisement !== ""))?($advertisement):"0"); ?> </span>个广告位和<span style="color: green;"> <?php echo ((isset($advertext) && ($advertext !== ""))?($advertext):"0"); ?> </span>个广告</td>
            </tr>
            <tr>
                <td>网站端口：<?php echo ($_SERVER['SERVER_PORT']); ?></td>
                <td></td>
            </tr>
            
            <tr>
                <td>数据库类型：MYSQL</td>
                <td></td>
            </tr>
            <tr>
                <td>数据库地址：<?php echo C('DB_HOST');?></td>
                <td></td>
            </tr>
            <tr>
                <td>数据库名称：<?php echo C('DB_NAME');?></td>
                <td></td>
            </tr>
            <tr>
                <td>数据库用户名：<?php echo C('DB_USER');?></td>
                <td></td>
            </tr>
        </table>
    </div>
</body>
</html>