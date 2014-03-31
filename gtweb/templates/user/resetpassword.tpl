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
			<p>寻找你身边的好老师-重置密码</p>
		</div>
		<div class="body_frame">
			<ul id="body_content" class="body_content">
				<li class="body member">
					<div class="input_ui">
						<input name="returnUrl" type="hidden" value="<{$returnUrl}>"/>
						<input class="username text" name="loginMobile" type="text" tabindex="1" placeholder="手机号"/>
						<p><a id="sendphonecode" href="javascript:void(0);">发送验证码</a></p>
					</div>
					<div class="input_ui">
						<input class="phoneCode text" type="text" name="phoneCode" tabindex="2" placeholder="手机验证码"/>
					</div>
					<div class="input_ui">
						<input class="password text" type="password" name="loginPwd" tabindex="3" placeholder="新密码"/>
					</div>
					<div class="input_ui min_input_ui min_sumbit_ui">
						<input type="button" id="sumbitBtn" style='width:100%;' class="submit margin_lf" value="提 交" tabindex="3"/>
					</div>
				</li>
			</ul>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	$.fn.user.options.rootPath='<{$smarty.const.__SITE_PATH}>';
	$.fn.user.options.loginedRedirectUrl='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>';
	
	$("#sendphonecode").bind('click', $.fn.user.get_reset_phone_code);
	$("#sumbitBtn").bind('click', $.fn.user.reset_password);
</script>