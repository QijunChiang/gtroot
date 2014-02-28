<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<{$smarty.const.__SITE_PATH}>/css/login.css"/>
	<script src="<{$smarty.const.__SITE_PATH}>/js/jquery-1.8.0.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/jquery.placeholder.js"></script>
	<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/user.js"></script>
</head>
<body>
	<div id="login" class="login_pannel">
		<div class="header">
			<div class="switch" id="switch">
				<img src="../images/logo.gif"/>
			</div>
			<p>寻找你身边的好老师</p>
		</div>
		<div class="body_frame">
			<ul id="body_content" class="body_content">
				<li class="body member">
					<div class="input_ui">
						<input class="username text" name="loginMobile" type="text" tabindex="1" placeholder="手机号"/>
					</div>
					<div class="input_ui">
						<input class="password text" type="password" name="loginPwd" tabindex="2" placeholder="密码"/>
						<p><a href="#" target="_target">忘记密码？</a></p>
					</div>
					<!-- <div class="input_ui min_input_ui checkCont">
						<input type="checkbox" class="check" checked="checked" name="" tabindex="3" />两周内免登录
					</div> -->
					<div class="input_ui min_input_ui min_sumbit_ui">
						<!--<a class="submit" id="registerSumbit" tabindex="4">注 册</a>
						<a class="submit margin_rg" id="loginSumbit" tabindex="5"/>登 录</a>
						-->
						<input type="button" id="loginSumbit" style='width:100%;' class="submit margin_lf" value="登 录" tabindex="3"/>
						
					</div>
<!-- 					<div class="footer">
						<p><a href="#" target="_blank">还没账号? 赶紧注册吧!</a></p>
					</div> -->
				</li>
			</ul>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	$.fn.user.options.rootPath='<{$smarty.const.__SITE_PATH}>';
	$.fn.user.options.loginedRedirectUrl='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>';
	
	$("#loginSumbit").bind('click', $.fn.user.login);
</script>