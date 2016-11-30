<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
    <link rel="stylesheet" type="text/css" href="/ad/Public/Admin/css/body.css" />
    <link rel="stylesheet" type="text/css" href="/ad/Public/Admin/icon/icon.css" />
    
    <script type="text/javascript" src="/ad/Public/jquery-1.7.2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/ad/Public/kindeditor/themes/default/default.css" />
    <script type="text/javascript" src="/ad/Public/kindeditor/kindeditor-min.js"></script>
    <script type="text/javascript" src="/ad/Public/kindeditor/lang/zh_CN.js"></script>
    <link rel="stylesheet" type="text/css" href="/ad/Public/uploadify/uploadify.css" />
    <script src="/ad/Public/uploadify/jquery.uploadify.js?ver=<?php echo rand(0,999999);?>" type="text/javascript"></script>
    <script type="text/javascript" src="/ad/Public/My97DatePicker/WdatePicker.js"></script>
    <script>
       $(function(){
                var url1="<?php echo U('Upload/upload_ke_json','',0);?>";
                var url2="<?php echo U('Upload/upload_ke_manager','',0);?>";
                var editor;
    			KindEditor.ready(function(K) {
    				editor = K.create('.elem1', {
                        uploadJson : url1,
                        fileManagerJson : url2,
                        allowFileManager : true
    				});
    			});
        }); 
     function checkboxx(x,_this){
            obj=$('#'+x);
            if($(_this).attr("checked")){
                obj.val(1);
            }else{
                obj.val(0);
            }
     }
    </script>
</head>
<body>
<form action="<?php echo U('Site/siteHandle');?>" method='post'>
    <div class="top">
        <div class="position"><span class="icon icon-31"></span>当前位置：站点设置</div>
        <div class="top-title">
            <span class="icon icon-32"></span>
            <div class="top-tool">
                <input class="button-b" type="submit" value="提交保存"/>
            </div>
        </div>
    </div>
    <div class="body">
       <table class="list-tb tableTd"  border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td  colspan='2' style="padding-left:20px;background:#eee;">信息配置
                    <a href="<?php echo U('Fields/index',array('model'=>'site'));?>"  style="margin-left: 10px;text-decoration: none;color:#15afef;">字段管理</a>
                </td>
            </tr>
            <tr>
                <td class="textRight">网站名称：</td>
                <td align="left"><input name="name" style="width: 200px;" type="text" value="<?php echo ($site['name']); ?>"/><span class="remark">最大可输入50个字符,可留空</span></td>
            </tr>
            <tr>
                <td width="10%" class="textRight">网站地址：</td>
                <td><input name="domain" style="width: 200px;" type="text" value="<?php echo ($site['domain']); ?>"/><span class="remark">最大可输入50个字符,域名要以“http://”开头,可留空</span></td>
            </tr>
            <tr>
                <td class="textRight">关键字：</td>
                <td align="left"><input name="keywords" type="text" value="<?php echo ($site['keywords']); ?>"/><span class="remark">最大可输入150个字符,可留空</span></td>
            </tr>
            <tr>
                <td class="textRight">网站描述：</td>
                <td align="left"><textarea name="description"><?php echo ($site['description']); ?></textarea></td>
            </tr>
            
            <?php if(is_array($fields)): foreach($fields as $key=>$v): if($v["ftype"] == 1): ?><tr>
                            <td class="textRight"><?php echo ($v["title"]); ?>：</td>
                            <td> 
                                <input type="text" name="<?php echo ($v["name"]); ?>" value="<?php echo ($site[$v[name]]); ?>"/>
                            </td>
                        </tr><?php endif; ?>
                    <?php if($v["ftype"] == 2): ?><tr>
                            <td class="textRight"><?php echo ($v["title"]); ?>：</td>
                            <td>
                                <textarea class="elem1" style="width:700px;height:300px;" name="<?php echo ($v["name"]); ?>"><?php echo ($site[$v[name]]); ?></textarea>
                            </td>
                        </tr><?php endif; ?>
                    <?php if($v["ftype"] == 3): ?><tr>
                            <td class="textRight"><?php echo ($v["title"]); ?>：</td>
                            <td> 
                                <input type="text" name="<?php echo ($v["name"]); ?>" value="<?php echo ($site[$v[name]]); ?>"/>
                            </td>
                        </tr><?php endif; ?>
                    <?php if($v["ftype"] == 4): ?><tr>
                            <td class="textRight"><?php echo ($v["title"]); ?>：</td>
                            <td> 
                                <script>
                                    $(function() {
                                    	$("#<?php echo ($v["name"]); ?>_s").uploadify({
                                    		height        : 22,
                                    		swf           : '/ad/Public/uploadify/uploadify.swf',
                                    		uploader      : '<?php echo U("Upload/upload_tp");?>',
                                    		width         : 60,
                                            multi         :false, //ture多文件，false单文件
                                            queueID:'uploadify_ppt',
                                            buttonText:'',
                                            removeCompleted : true,
                                            'onUploadSuccess':function(file,data,response){
                                                    if(data=='error') 
                                                    {
                                                        alert('上传出错，请检查原因!(文件大小/文件类型)');
                                                    }
                                                    else
                                                    {
                                                        $('#<?php echo ($v["name"]); ?>').val(data);
                                                    }
                                            }
                                    	});
                                    });
                                </script>
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><input style="margin: 0px;margin-top:2px;" type="text" name="<?php echo ($v["name"]); ?>" id="<?php echo ($v["name"]); ?>" readOnly="true" value="<?php echo ($site[$v[name]]); ?>"/></td>
                                        <td style="padding-top: 3px;padding-left:3px;"><input type="file" name="<?php echo ($v["name"]); ?>_s" id="<?php echo ($v["name"]); ?>_s"/></td>
                                        <td><div id="uploadify_ppt" style="width:400px;margin-left:5px;"></div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr><?php endif; ?>
                    <?php if($v["ftype"] == 5): endif; ?>
                    <?php if($v["ftype"] == 6): ?><tr>
                            <td class="textRight"><?php echo ($v["title"]); ?>：</td>
                            <td> 
                                <input type="text" name="<?php echo ($v["name"]); ?>" readOnly="true"  class="Wdate" value="<?php echo ($site[$v[name]]); ?>" onclick="WdatePicker()"/>
                            </td>
                        </tr><?php endif; ?>
                    <?php if($v["ftype"] == 7): ?><tr>
                            <td class="textRight"><?php echo ($v["title"]); ?>：</td>
                            <td>
                                <input style="float: left;" type='checkbox' name='<?php echo ($v["name"]); ?>' value="1"/>
                            </td>
                        </tr><?php endif; ?>
                    <?php if($v["ftype"] == 8): ?><tr>
                            <td class="textRight"><?php echo ($v["title"]); ?>：</td>
                            <td>
                                    <textarea style="float:left;margin:4px 0px;" name="<?php echo ($v["name"]); ?>"><?php echo ($site[$v[name]]); ?></textarea>
                                    <div style="clear: both;"></div>
                            </td>
                        </tr><?php endif; endforeach; endif; ?>
        
            <tr>
                <td  colspan='2' style="padding-left:20px;background:#eee;">控制配置</td>
            </tr>
            
            <tr>
                <td class="textRight">URL模式：</td>
                <td align="left">
                    <select name="URL_MODEL">
                        <option <?php if($set['URL_MODEL']==0): ?>selected=""<?php endif; ?> value="0">模式一</option>
                        <option <?php if($set['URL_MODEL']==1): ?>selected=""<?php endif; ?> value="1">模式二</option>
                        <option <?php if($set['URL_MODEL']==2): ?>selected=""<?php endif; ?> value="2">模式三</option>
                    </select>
                    <span class="remark">访问时体现的url样式，具体参考帮助</span>
                </td>
            </tr>
            <tr>
                <td class="textRight">伪静态后缀：</td>
                <td align="left">
                    <input name="URL_HTML_SUFFIX" value="<?php echo ($set['URL_HTML_SUFFIX']); ?>"/>
                    <span class="remark">url模式为二、三时可用</span>
                </td>
            </tr>
            <tr>
                <td class="textRight">是否缓存：</td>
                <td align="left">
                    <select name="HTML_CACHE_ON">
                        <option <?php if($set['HTML_CACHE_ON']==0): ?>selected=""<?php endif; ?> value="0">关闭</option>
                        <option <?php if($set['HTML_CACHE_ON']==1): ?>selected=""<?php endif; ?> value="1">开启</option>
                    </select>
                </td>
            </tr>
       </table>
    </div>
</form>
</body>
</html>