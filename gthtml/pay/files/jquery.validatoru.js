/* author:cray@immomo.com */
//使用方法简述 by CrayLin
//提供三个属性
//request = true|false 表示是否需要 需要则是不可为空
//reg = [正则表达式]|[预设类型] 预设类型包括 chinese:中文 email:邮箱 int:整型 number:数字 httpurl:地址 password:密码
//url = [ajax异步请求地址]，请求时会将当前控件的name和value值作为参数拼接在url上传递，ajax端返回success字符串则表示验证成功，否则失败
//Demo
//<textarea name="" id="" class="textareainput" request="true" reg="email" tip="只能输入邮箱"></textarea>

//绑定方法 将某jquery对象内的所有输入控件绑定验证事件，前提是至少存在以上三个之一的属性的控件
//参数submit 表示整体表单的提交按钮，填写后，点击该按钮则验证整个区域
//参数callback 表示验证成功之后的回调
//$("#formTest").validatorU({submit:$("#submitTest"),callback:a});
//解除绑定方法
//$("#formTest").unvalidatorU();

// 绑定验证
// 与jquery对象进行绑定，会对旗下所有设定条件的对象进行绑定
// submit: 传入jquery对象则对其绑定表单提交判断
// callback: 所有表单项验证成功后回调方法
jQuery.fn.validatorU = function(options) {
    options = options || {};
	if(options.submit){
		validatorU.control.push({
			submit: $(options.submit),
			obj:this,
			callback: typeof options.callback == 'undefined' ? null : options.callback
		});
		$(options.submit).click(validatorU.checkForm);
	}
	var inputList = this.eq(0).find("[reg],[url]:not([reg]),[request]:not([reg]):not([url])");
	inputList.each(function(index,element){
		element = $(element);
		if(undefined == element.data("events") || undefined == element.data("events")["blur"])
		{
                        element.focus(function(){ $(this).addClass("focus"); });
                        element.blur(function(){ $(this).removeClass("focus"); });
			element.blur(validatorU.checkSingle);
			if(element.attr("request") != undefined && element.attr("request") == "true")
				element.after($("<em class=\"valiU\">[必填]"+ element.attr("tip") +"</em>"));
			else
				element.after($("<em class=\"valiU\">"+ element.attr("tip") +"</em>"));
		}
	});
	return validatorU;
	//if(options.autoCheck){
	//	this.eq(0).find("[reg],[url]:not([reg]),[request]:not([reg]):not([url])").blur();
	//}
};

jQuery.fn.validatorU_Checker = function() {
};
 
 
// 解除绑定
// submit: 传入jquery对象则对其解除
jQuery.fn.unvalidatorU = function(options) {
    options = options || {};
	if(options.submit){
		$(options.submit).unbind("click",validatorU.checkForm);
	}
	this.eq(0).find("[reg],[url]:not([reg]),[request]:not([reg]):not([url])").unbind("blur",validatorU.checkSingle);
};
// 操作集
var validatorU = {
	//判空提示
	tipsInputEmpty : "不可为空",
	tipsSelectEmpty : "必选一项",
	tipsUrlError : "异步验证失败",
	//控制器，应对页面中多个部分绑定验证
	control : [],
	//对表单验证管用，判断过程是否顺利
	returnValue : true,
	//表单验证,绑定在初始化的提交按钮上
	checkForm : function(){
		validatorU.returnValue = true;
		for(var i=0;i<validatorU.control.length;i++){
			if(validatorU.control[i].submit[0] == this){
				validatorU.control[i].obj.find("[reg],[url]:not([reg]),[request]:not([reg]):not([url])").each(function(index,element){
					if($(element).attr("request") != undefined){
						validatorU.returnValue = validatorU.returnValue && validatorU.request($(element));
					}
					else if($(element).attr("reg") != undefined&&$(element).val().length>0){
						validatorU.returnValue = validatorU.returnValue && validatorU.check($(element));
					}
					else if($(element).attr("url") != undefined&&$(element).val().length>0){
						validatorU.returnValue = validatorU.returnValue && validatorU.ajax($(element));
					}
				});
				//执行回调
				if(validatorU.returnValue && validatorU.control[i].callback){
					validatorU.returnValue = validatorU.control[i].callback();
				}
			}
		}
		return validatorU.returnValue;
	},
	checkSingle : function(){
		if($(this).attr("request") != undefined){
			return validatorU.request($(this));
		}
		if($(this).attr("reg") != undefined&&$(this).val().length>0){
			return validatorU.check($(this));
		}
		if($(this).attr("url") != undefined&&$(this).val().length>0){
			return validatorU.ajax($(this));
		}
	},
	//验证是否为空
	request : function(obj){
		if(obj.attr("request") == "true"){
			if(obj.find("option").length > 0){
				if($(obj).val() == "" || $(obj).val() == 0){
					obj.addClass("valiU_TextError");
					obj.next("em").attr("class","valiU_Error").show().html(validatorU.tipsSelectEmpty);
					return false;
				}
			}else {
				obj.val($.trim(obj.val()));
				if(obj.val() == "") {
					obj.addClass("valiU_TextError");
					obj.next("em").attr("class","valiU_Error").show().html(validatorU.tipsInputEmpty);
					return false;
				}
			}
		}
		obj.removeClass("valiU_TextError");
		obj.next("em").attr("class","valiU_Correct").html("");
		if(obj.val().length > 0 && obj.attr("reg") != undefined){
			return validatorU.check(obj);
		} else if (obj.attr("url") != undefined){
			return validatorU.ajax(obj);
		}else {
			obj.removeClass("valiU_TextError");
			obj.next("em").attr("class","valiU_Correct").show().html("&radic;");
			return true;
		}
	},
	//验证过程 单个空间异步判断
	//读取数据框属性name的值和value的组作为参数提交 url?name=value
	//返回值success代表正确，其他代表错误
	ajax : function(obj){
		var url_str = obj.attr("url");
		/*
		if(url_str.indexOf("?") != -1){
			url_str = url_str+"&"+obj.attr("name")+"="+obj.attr("value");
		}else{
			url_str = url_str+"?"+obj.attr("name")+"="+obj.attr("value");
		}
		*/
		//与原值相等时不做ajax
		if(obj.val() == obj.attr("orivalue")){
			obj.removeClass("valiU_TextError");
			obj.next("em").attr("class","valiU_Correct").html("&radic;");
			return true;
		}
	    var dataValue = obj.attr("name")+"="+obj.attr("value");

		var feed_back = $.ajax({type: "post",url:url_str,cache: false, async: false,data:dataValue,dataType:"txt"}).responseText;
		if( undefined == typeof(feed_back)){
			obj.addClass("valiU_TextError");
			obj.next("em").attr("class","valiU_Error").show().html(validatorU.tipsUrlError);
			return false;
		}
		feed_back = feed_back.replace(/(^\s*)|(\s*$)/g, "");
		if(feed_back == 'success'||feed_back=='0'){
			
			obj.removeClass("valiU_TextError");
			obj.next("em").attr("class","valiU_Correct").html("&radic;");
			return true;
		}else{
			obj.addClass("valiU_TextError");
			obj.next("em").attr("class","valiU_Error").show().html(feed_back);
			return false;
		}
			/*
		$.ajax({type: "get",url: url_str,cache: false, async: false, success: function(feed_back){
			
			if( undefined = typeof(feed_back)){
				obj.addClass("valiU_TextError");
				obj.next("em").attr("class","valiU_Error").show().html(validatorU.tipsUrlError);
				return false;
			}
			feed_back = feed_back.replace(/(^\s*)|(\s*$)/g, "");
			if(feed_back == 'success'||feed_back=='0'){
				
				obj.removeClass("valiU_TextError");
				obj.next("em").attr("class","valiU_Correct").html("&radic;");
				return false;
			}else{
				alert(2);
				obj.addClass("valiU_TextError");
				obj.next("em").attr("class","valiU_Error").show().html(feed_back);
				return false;
			}
		},complete : function(){
		}
		});
			*/
	},
	//验证过程 单个控件判断
	check : function(obj){
		var reg = new RegExp(validatorU.mapRegex(obj.attr("reg")));
		var objValue = obj.attr("value");
		if(!reg.test(objValue)){
			obj.addClass("valiU_TextError");
			obj.next("em").attr("class","valiU_Error").html(obj.attr("tip")).show();
			return false;
		}else{
			if(obj.attr("url") == undefined){
				obj.removeClass("valiU_TextError");
				obj.next("em").attr("class","valiU_Correct").html("&radic;");
				return true;
			}else{
				return validatorU.ajax(obj);
			}
		}
	},
	//正则映射
	mapRegex : function(str){
		switch(str){
			case "chinese": return /^[\u4e00-\u9fa5]+$/;
			case "email": return /^\w+([-+.]\w+)*@+[_A-Za-z0-9]{1,20}([.]+[A-Za-z0-9]{2,6}){1,2}$/;
			case "int": return /^\d+$/;
			case "number": return /^[\d.-]+$/;
			case "httpurl": return /^http:\/\/.+$/gi;
			case "password": return /^[\w!@$%^&()-+#.]+$/;
			default : return str;
		}
	}
};