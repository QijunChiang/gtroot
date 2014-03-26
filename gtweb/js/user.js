$.fn.user = {
    options : {
        rootPath : '/gtweb',
        loginedRedirectUrl : 'http://localhost/gtweb'
    },
    changeChkLicence : function(){
    	if($(this).attr('checked')){
    		$('#registerSumbit').attr('disabled',false);
    	}else{
    		$('#registerSumbit').attr('disabled',true);
    	}
    },
    checkRePwd : function(){
    	var txtPwd = $("input[name='txtPwd']").val();
    	var txtRePwd = $("input[name='txtRePwd']").val();
    	if(txtPwd != txtRePwd){
    		$("input[name='txtRePwd']").next().next().text("两次密码不一至");
    		return false;
    	}else{
    		$("input[name='txtRePwd']").next().next().text("");
    	}
    	return true;
    },
    checkRegMoible : function(){
    	var txtUser = $("input[name='txtUser']").val();
    	var ret = true;
    	if($.fn.user.isMobileNum(txtUser)){
    		$.ajax({
                url : $.fn.user.options.rootPath+"/user/reg.php",
                type : 'GET',
                data : 'phone='+txtUser+'&action=phone_is_exist&t='+(new Date()).getTime(),
                dataType : 'json',
                async : false,
                success : function(result){
                    if(!result.result){
                    	if(result.data.error_code == 1009){
                    		$("input[name='txtUser']").next().text('手机号码已经被注册');
                    	}else if(result.data.error_code == 1010){
                    		$("input[name='txtUser']").next().text('你的手机号码已被管理员冻结，请联系管理员');
                    	}else{
                    		$("input[name='txtUser']").next().text(result.data.error);
                    	}
                    	ret = false;
                    }else{
                    	$("input[name='txtUser']").next().text("");
                    }
                },error:function(result){                
                }
            });
    	}else{
    		$("input[name='txtUser']").next().text("请输入合法的手机号");
    		ret = false;
    	}
    	return ret;
    },
    checkRegEmail : function(){
    	var txtEmail = $("input[name='txtEmail']").val();
    	if($.fn.user.isEmail(txtEmail)){
    		$("input[name='txtEmail']").next().text("");
    	}else{
    		$("input[name='txtEmail']").next().text("请输入合法的EMAIL");
    	   return false;
    	}
    	return true;
    	
    },
    register : function(){
    	if(!$.fn.user.checkRePwd() || !$.fn.user.checkRegMoible()){//!$.fn.user.checkRegEmail()
    		return;
    	}
		var phone = $("input[name='txtUser']").val();
		var password = $("input[name='txtPwd']").val();
		//var email = $("input[name='txtEmail']").val();
        $.ajax({
            url : $.fn.user.options.rootPath+"/user/reg.php",
            type : 'POST',
            data : 'action=create_account_student&phone='+phone+'&password='+password+'&t='+(new Date()).getTime(),
            dataType : 'json',
            success : function(result){
                if(!result.result){
                	if(result.data.error_code == 1009){
                		alert('手机号码已经被注册');
                	}else if(result.data.error_code == 1010){
                		alert('你的手机号码已被管理员冻结，请联系管理员');
                	}else{
                		alert(result.data.error);
                	}
                }else{
                	alert('恭喜注册成功,请使用注册帐号和密码登录系统');
                	window.location = $.fn.user.options.rootPath+'/user/login.php';
                }
            },error:function(result){}
        });
    },
    login : function() {
        var loginMobile = $("input[name='loginMobile']").val();
        var loginPwd = $("input[name='loginPwd']").val();
        var returnUrl = $("input[name='returnUrl']").val();
        if(! $.fn.user.checkLogin(loginMobile,loginPwd)){
            return false;
        }
        $.ajax({
            url : $.fn.user.options.rootPath+"/user/login.php",
            type : 'POST',
            data : 'loginMobile='+loginMobile+'&loginPwd='+loginPwd+'&returnUrl='+returnUrl+'&t='+(new Date()).getTime(),
            dataType : 'json',
            success : function(result){
                if(result.error_code==200){
                	if(result.returnUrl){
                		top.window.location = result.returnUrl;
                	}else{
                		top.window.location = $.fn.user.options.loginedRedirectUrl;
                	}
                }else{
                    alert(result.error);
                }
            },
            error:function(result){                
            }
        });
    },
    checkLogin : function(loginMobile, loginPwd) {
        var ret = true;
        var msg = "";
        if ($.fn.user.isMobileNum(loginMobile)) {
            ret = true;
        } else {
            msg = '非法手机号';
            ret = false;
        }
        if(loginPwd == null || $.trim(loginPwd) == ''){
            msg += (!msg ? "" : ",") + '密码为空';
            ret = false;
        }
        if(!ret){
            alert(msg);
        }
        return ret;
    },
    updateUserProfile : function(){
    	var form = $("form[name='updateUserProfile']");
    	form.attr('action', $.fn.user.options.rootPath+'/user/my.php?action=updateUserProfile');
    	form.attr('method', 'POST');
    	form.submit();
    },
    updateUserPwd : function(){
    	if(!$.fn.user.checkModifyOldPwd() || !$.fn.user.checkModifyNewPwd()){
    		return;
    	}
    	var form = $("form[name='updateUserPwd']");
    	form.attr('action', $.fn.user.options.rootPath+'/user/my.php?action=updateUserPwd');
    	form.attr('method', 'POST');
    	form.submit();
    },
    checkModifyNewPwd : function(){
    	var checkPass = true;
    	var txtPwd = $("input[name='newPassword']").val();
    	var txtRePwd = $("input[name='reNewPassword']").val();
    	if(txtPwd != txtRePwd){
    		$("input[name='reNewPassword']").next().text("两次新密码不一至");
    		checkPass=false;
    	}else{
    		$("input[name='reNewPassword']").next().text("");
    	}
    	return checkPass;
    },
    checkModifyOldPwd : function(){
    	var checkPass = true;
    	var oldPassword = $("input[name='oldPassword']").val();
    	if(oldPassword== null || oldPassword == ''){
    		$("input[name='oldPassword']").next().text("请输入旧密码");
    		checkPass=false;
    	}else{
    		$("input[name='oldPassword']").next().text("");
    	}
    	return checkPass;
    },
    isMobileNum : function(mobile){
    	var regu = /^[1][0-9][0-9]{9}$/;
        var re = new RegExp(regu);
        if (re.test(mobile)) {
        	return true;
        }
        return false;
    },
    isEmail : function (str) { 
        var myReg = /^[-_A-Za-z0-9]+@([_A-Za-z0-9]+\.)+[A-Za-z0-9]{2,3}$/; 
        if (myReg.test(str)) return true; 
        return false; 
    }, 
    get_reset_phone_code : function() {
        var loginMobile = $("input[name='loginMobile']").val();
        if(! $.fn.user.isMobileNum(loginMobile)){
        	alert('请输入合法手机号');
            return false;
        }
        $.ajax({
            url : $.fn.user.options.rootPath+"/user/resetPassword.php",
            type : 'GET',
            data : 'action=get_reset_phone_code&loginMobile='+loginMobile+'&t='+(new Date()).getTime(),
            dataType : 'json',
            success : function(result){
            	if(result.result){
            		overSecond = result.data.overSecond;
            		iIntervalID = window.setInterval($.fn.user.showPhoneCodeWaitSecond, 1000)
            		alert("验证码已发送到手机,请尽快使用");
            	}else{
            		alert(result.data.error);
            	}
            },
            error:function(result){                
            }
        });
    },
    showPhoneCodeWaitSecond : function(){//验证码有效期
    	if(overSecond<0){
    		$("#sendphonecode").text("发送验证码");
    		window.clearInterval(iIntervalID);
    	}else{
    		$("#sendphonecode").text("等待时间："+overSecond);
    	}
    	--overSecond;
    }, 
    reset_password : function() {
        var loginMobile = $("input[name='loginMobile']").val();
        var loginPwd = $("input[name='loginPwd']").val();
        var phoneCode = $("input[name='phoneCode']").val();
        if(!phoneCode || phoneCode == '手机验证码'){
        	alert("请输入验证码");return false;
        }
        if(! $.fn.user.checkLogin(loginMobile,loginPwd)){
            return false;
        }
        $.ajax({
            url : $.fn.user.options.rootPath+"/user/resetPassword.php",
            type : 'POST',
            data : 'action=reset_password&loginMobile='+loginMobile+'&loginPwd='+loginPwd+'&phoneCode='+phoneCode+'&t='+(new Date()).getTime(),
            dataType : 'json',
            success : function(result){
            	if(result.result){
            		alert("重置密码成功,请重新登录");
            		window.location = $.fn.user.options.rootPath+'/user/login.php';
            	}else{
            		alert(result.data.error);
            	}
            },
            error:function(result){                
            }
        });
    }
};