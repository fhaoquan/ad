<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Admin Login</title>
		<link rel="stylesheet" type="text/css" href="/ad/Public/Admin/css/login.css" />
	</head>
<body>
	<div id="container">
		<h1>Admin Login</h1>
		<div id="box">
    <form method="post" action="<?php echo U('login');?>">
			<p class="main">
				<label>用户: </label>
				<input name="username" value="" /> 
				<label>密码: </label>
				<input type="password" name="password" value="">
			</p>
			<p class="space">
				<input type="submit" value="登陆" class="login" />
			</p>
			</form>
		</div>
	</div>
</body>
</html>