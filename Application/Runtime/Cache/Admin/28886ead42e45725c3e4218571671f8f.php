<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
    <link rel="stylesheet" type="text/css" href="/ad/Public/Admin/css/body.css" />
    <link rel="stylesheet" type="text/css" href="/ad/Public/icon/icon.css" />
</head>
<body>
    <div class="top">
        <div class="position"><span class="icon icon-31"></span>当前位置：数据库备份</div>
        <div class="top-title">
            <span class="icon icon-32"></span>
            <div class="top-tool">
                <a href="" class="button-a">数据库备份</a>
            </div>
        </div>
    </div>
    <div class="body"></div>
    <div style="text-align: left;width:50%;padding-left:10%;">
         <p style="font-size: 13px;">备份数据：</p>
         <a href="<?php echo U('Backup/handle');?>" class="button-a">开始备份</a>
         <br /><br />
         <span style="margin-left:0px;" class="remark">系统只提供数据库备份功能，无模板备份。备份文件在安装时使用。</span>
         
    </div>
</body>
</html>