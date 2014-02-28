<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/login.css"/>
	<script src="../js/jquery-1.8.0.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/jquery.placeholder.js"></script>
	<script type="text/javascript" src="../js/user.js"></script>
</head>
<body>
	<div id="regin" class="reg_pannel">
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
						<input class="username text" name="txtUser" id="txtUser" type="text" tabindex="1" placeholder="手机号"/>
						<span style='color:red;'></span>
					</div>
					<div class="input_ui">
						<input class="password text" type="password" name="txtPwd" id="txtPwd" type="text" tabindex="2" placeholder="密码"/>
					</div>
					<div class="input_ui">
						<input class="password text" type="password" name="txtRePwd" id="txtRePwd" type="text" tabindex="2" placeholder="再次确认密码"/>
						<span style='color:red;'></span>
					</div>
					<!-- 
					<div class="input_ui">
						<input class="email text" type="text" name="txtEmail" id="txtEmail" type="text" tabindex="2" placeholder="电子邮箱"/>
						<span style='color:red;'></span>
					</div>	
					-->				
					<div class="input_ui min_input_ui checkCont">
						<input type="checkbox" class="check" name="chkLicence" tabindex="3" /><a href="#" target="_blank">我接受并同意《好老师用户服务条款》</a>
					</div>
					<div class="input_ui min_input_ui min_sumbit_ui">
						<input type="button" id="registerSumbit" class="submit margin_lf" value="注 册" tabindex="4" disabled='disabled'/>
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
	
	$("input[name='chkLicence']").bind('change', $.fn.user.changeChkLicence);
    $("input[name='txtRePwd']").bind('blur', $.fn.user.checkRePwd);
    $("input[name='txtPwd']").bind('blur', $.fn.user.checkRePwd);
    $("input[name='txtUser']").bind('blur', $.fn.user.checkRegMoible);
    $("input[name='txtEmail']").bind('blur', $.fn.user.checkRegEmail);
    $("#registerSumbit").bind('click', $.fn.user.register);
</script>