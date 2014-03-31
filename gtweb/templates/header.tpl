<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>好老师 www.hlaoshi.com</title>
<link href="<{$smarty.const.__SITE_PATH}>/css/style.css" rel="stylesheet" type="text/css" />
<link href="<{$smarty.const.__SITE_PATH}>/css/css.css" rel="stylesheet" type="text/css" />
<link href="<{$smarty.const.__SITE_PATH}>/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" type="text/css" />
<!--IE6 PNG透明-->
<!--[if lte IE 6]>
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/DD_belatedPNG_0.0.8a.js"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('div, ul, img, li, input , a');
    </script>
<![endif]-->
<!--banner js-->
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/jquery.placeholder.js"></script>
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/jquery.showAlert.js"></script>
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript">
$(function(){
	$("#city").toggle(function(){
		$(this).css("backgroundImage", "url(images/dot2.gif)");
		$(".city_in").fadeIn(200);
		$(".nav_cate_sec").fadeOut(200);
	},function(){
		$(this).css("backgroundImage", "url(images/dot1.gif)");
		$(".city_in").fadeOut(200);
	});
	$(".nav_r .nav_cate").bind("mouseover",function(){
		$(".nav_cate_sec").fadeOut(200);
		var sec = $(this).next();
		sec.css("left",$(this).offset().left - 95)
		sec.fadeIn(200);
	});	
	$('body').click(function(){
		$(".nav_cate_sec").fadeOut(200);
		$("#city").css("backgroundImage", "url(images/dot1.gif)");
		$(".city_in").fadeOut(200);
		$("#shareList").fadeOut(200);	
		$(".user_info_b").fadeOut(200);			
	});
	//登录
	$('#login_btn').click(function(){
		if($('.login_pannel_frame').attr('src') == ''){
			$('.login_pannel_frame').attr('src','user/login.php?returnUrl='+window.location);	
		}
		$(this).showAlert({
			alertWidth:578,
			alertHeight:433,
			alertObject:$("#login"),
			opacity:.7//透明度
		});
	})
	//注册
	function showReg(e){
		if($('.reg_pannel_frame').attr('src') == ''){
			$('.reg_pannel_frame').attr('src','user/reg.php');	
		}
		$(e).showAlert({
			alertWidth:578,
			alertHeight:546,
			alertObject:$("#reg"),
			opacity:.7//透明度
		});			
	}
	$('#reg_btn').click(function(){
		showReg(this);
	})				
	$('#loginToReg').click(function(){
		showReg(this);
	})
    $('#search_submit').bind('click', function(){
    	var searchStr = $('#search_input').val();
    	if(searchStr != null && searchStr != ''){
    		window.location = '<{$smarty.const.__SITE_PATH}>/courseSearchList.php?searchKey='+searchStr;
    	}
    });
    
});
</script>
<!--个人中心列表-->
<script type="text/javascript">
$(function(){
	//导航栏用户信息下拉处理
	$(".user_info").toggle(
		function(){
			$(this).children(".user_info_b").stop(false,true).fadeIn(200);
		},
		function(){
			$(this).children(".user_info_b").stop(false,true).fadeOut(200);
	});
	$(".user_info_b ul li a").click(function(){ $(".user_info_b").stop(false,true).fadeOut(100);});
	//放大二维码
	$('#codeLink').click(function(){
		return false;
	});
	$('#dimBigCode .close').click(function(){
		$('#dimBigCode').fadeOut(200);
		return false;			
	});
});
</script>
</head>

<body>
<div class="top">
  <div class="top_in">
    <div class="logo"><a href="<{$smarty.const.__SITE_PATH}>/"><img src="<{$smarty.const.__SITE_PATH}>/images/logo.gif" width="150" height="39"  /></a></div>
    <div class="logo_r"></div>
    <div class="city"> <a id="city" href="#"><{$smarty.session.__CURR_CITY_NAME}></a>
      <div class="city_in">
        <div id="close" class="close"></div>
        <div class="city_list">
          <div class="city_l">热点城市</div>
          <div class="city_r"> 
          		<{foreach from=$citys item=item}>
          		<a href="<{$smarty.const.__SITE_PATH}>/teachers.php?cityId=<{$item->id}>"><{$item->name}></a> 
				<{/foreach}>
            <div class="clear"></div>
          </div>
          <div class="clear"></div>
        </div>
      </div>
    </div>
    <div class="tel400"><img src="<{$smarty.const.__SITE_PATH}>/images/400.jpg" style="width: 180px;"/></div>

    <div class="search">
      <div class="search_l" style="width: 260px;">
        <input id="search_input" type="text" class="text" placeholder="可输入老师姓名、课程名称、地址"/>
      </div>
      <!-- 输入您想学的课程 -->
      <div class="search_r">
        <input type="button" id='search_submit' value="搜索" />
      </div>
    </div>

    <{if $smarty.session.__IS_SIGN_IN_ANONYMOUS == 'YES'}>
    <div class="lr"  style="margin-top: 10px;"> 
    	<a id="login_btn" href="javascript:void(0);">登录</a><span>|</span>
    	<a id="reg_btn" href="javascript:void(0);">注册</a> 
    </div>
	<{else}>
    <div class="user_info"  style="margin-top: 20px;padding-right: 0px;margin-right: 0px;width: 160px;">
    	<a id="logined_downList">欢迎您，<font color="#ea5717"><{$smarty.session.__CURR_USER_INFO->name}></font></a>
        <div class="user_info_b" style="z-index:999999999;">
        	<ul>
            	<li><a onclick="window.location='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>/user/my.php';" href="">个人中心</a></li>
                <li><a onclick="window.location='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>/user/logout.php';" href="">注销</a></li>
            </ul>
        </div>
    </div>
    <{/if}>
  </div>
</div>
<!--导航-->
<div class="nav" style="box-shadow:none;">
  <div class="nav_in">
    <div class="nav_r">
      <ul>
		<{foreach from=$categorys item=category}>
	  	<li>
	  		<a class="nav_cate" href="<{$smarty.const.__SITE_PATH}>/teachers.php?categoryId=<{$category->id}>&categoryName=<{$category->name}>">&nbsp;<{$category->name}></a>
	  		<ul class="nav_cate_sec">
	  			<{foreach from=$category->childList item=secCategory}>
	  			<li><a href="<{$smarty.const.__SITE_PATH}>/teachers.php?categoryId=<{$secCategory->id}>&categoryName=<{$secCategory->name}>"><{$secCategory->name}></a></li>
	  			<{/foreach}>
	  	 	</ul>
	  	</li>
		<{/foreach}>
      </ul>
    </div>
   
  </div>
</div>

<!-- 登录-->
<div id="login" class="loginCont"> <a href="javascript:void(0);" class="close alertClose"></a>
  <iframe class="login_pannel_frame" name="" src="" width="578" height="433" scrolling="no" frameborder="0"></iframe>
  <div class="login_footer">
    <p><a id="loginToReg" href="javascripts:;" onClick="$('#login,#overlay').fadeOut(200);return false;" target="_blank">还没账号? 赶紧注册吧!</a></p>
  </div>
</div>

<!-- 注册-->
<div id="reg" class="regCont"> <a href="javascript:void(0);" class="close alertClose"></a>
  <iframe class="reg_pannel_frame" name="" src="" width="578" height="546" scrolling="no" frameborder="0"></iframe>
</div>
