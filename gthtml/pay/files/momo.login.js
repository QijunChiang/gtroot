	 //重载验证码
	 function fleshVerfiy2(obj,src){
         var timenow = new Date().getTime();
         $(".momo_captcha_pic").attr("src", "/login?action=captcha&w="+(timenow/1000));
//			try{
//				$(obj).attr('src',null);
//				$(obj).attr('src','/login?action=captcha');
//			}catch(e)
//			{}
	}
	function fleshVerify(obj,src){
		//重载验证码
		var timenow = new Date().getTime();
		//$('#verifyimg').attr('src','/login?action=verify&q='+(timenow/1000));
		try{
			if(undefined===src||null===src){
				obj.src='/login?action=verify&w='+(timenow/1000);
			}
			else{ 
				obj.src=null;
				obj.src=src;
			}
		}catch(e)
		{}
     }
	
	function checkEmpty()
	 {
		if($.trim($("#momo").val())!=''){
			$($("#momo").get(0).parentElement.parentElement).find(".logintip").hide();
		}else{return false;}
		if($.trim($("#phone").val())!=''){
			$("#phone").val('');
		}else {return false;}
		if($.trim($("#password").val())!=''){
			$($("#password").get(0).parentElement.parentElement).find(".logintip").hide();
      }else {return false;}
		if($.trim($("#password2").val())!=''){
			$("#password2").val('');
      }else{return false;}		
	   return true;
	 }