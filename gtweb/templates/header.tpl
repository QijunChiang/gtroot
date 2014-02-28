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
    $(".nav_list_b ul li").mouseover(function(){
    	//$(this).css("background", "#f5f5f5"),function(){$(this).children().css("display", "none")}
    });
	$(".nav_list_b ul li").mouseover(function(){
		$(this).children(".nav_list_b ul li ul").css("display", "block")
	});
	$(".nav_list_b ul li").mouseout(function(){
		$(this).css("background", "#fff")
	});
	$(".nav_list_b ul li").mouseout(function(){
		$(this).children(".nav_list_b ul li ul").css("display", "none");
	});
	
	$("#study li ul li").mouseover(function(){
		$(this).css("background", "#e0e0e0");
	});
	$("#study li ul li").mouseout(function(){
		$(this).css("background", "#f5f5f5");
	});
	
	$(".nav_list_in").toggle(function(){
		$(this).parent().css("backgroundImage", "url(images/nav_list.gif)");
		$("#study").fadeIn(200);
		$(".city_in").fadeOut(200);
	},function(){
		$(this).parent().css("backgroundImage", "url(images/nav_list2.gif)");
		$("#study").fadeOut(200);
	});
	$(".nav_list_in").bind('mouseover',function(){
		$(this).parent().css("backgroundImage", "url(images/nav_list.gif)");
		$("#study").fadeIn(200);
		$(".city_in").fadeOut(200);
	});
	
	$("#city").toggle(function(){
		$(this).css("backgroundImage", "url(images/dot2.gif)");
		$(".city_in").fadeIn(200);
		$("#study").fadeOut(200);
	},function(){
		$(this).css("backgroundImage", "url(images/dot1.gif)");
		$(".city_in").fadeOut(200);
	});
		
	$('body').click(function(){
		$(".nav_list").css("backgroundImage", "url(images/nav_list2.gif)");
		$("#city").css("backgroundImage", "url(images/dot1.gif)");
		$("#study").fadeOut(200);
		$(".city_in").fadeOut(200);
		$("#shareList").fadeOut(200);	
		$(".user_info_b").fadeOut(200);			
	});
	//登录
	$('#login_btn').click(function(){
		if($('.login_pannel_frame').attr('src') == ''){
			$('.login_pannel_frame').attr('src','user/login.php');	
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
	//搜索下拉框
    $("#search_input").bind("keydown", keyDown).autocomplete($.extend(options, {
        minLength: 1,
        source: function(request, response) {
            // delegate back to autocomplete, but extract the last term
            response($.ui.autocomplete.filter(
			    availableTags, extractLast(request.term)));
        }
    }));
	
	/* multi value autocomplete */
    // 按逗号分隔多个值
    function split(val) {
        return val.split(/,\s*/);
    }
    // 提取输入的最后一个值
    function extractLast(term) {
        return split(term).pop();
    }			
    // 按Tab键时，取消为输入框设置value
    function keyDown(event) {
        if (event.keyCode === $.ui.keyCode.TAB &&
				$(this).data("autocomplete").menu.active) {
            event.preventDefault();
        }
    }
    var options = {
        // 获得焦点
        focus: function() {
            // prevent value inserted on focus
            return false;
        },
        // 从autocomplete弹出菜单选择一个值时，加到输入框最后，并以逗号分隔
        select: function(event, ui) {
            var terms = split(this.value);
            // remove the current input
            terms.pop();
            // add the selected item
            terms.push(ui.item.value);
            // add placeholder to get the comma-and-space at the end
            terms.push("");
            this.value = terms.join(", ");
            return false;
        }
    };			
    // 本地字符串数组
    var availableTags = [
	    "C#",
	    "C++",
	    "Java",
	    "JavaScript",
	    "ASP",
        "ASP.NET",
	    "JSP",
	    "PHP",
	    "Python",
	    "Ruby",
		"语文",
		"数学",
		"英语",
		"物理"
    ];	
    $('#search_submit').bind('click', function(){
    	var searchStr = $('#search_input').val();
    	if(searchStr != null && searchStr != ''){
    		window.location = '<{$smarty.const.__SITE_PATH}>/teachers.php?name='+searchStr;
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
    <div class="logo"><img src="<{$smarty.const.__SITE_PATH}>/images/logo.gif" width="150" height="39"  /></div>
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
    <div class="search">
      <div class="search_l">
        <input id="search_input" type="text" class="text" placeholder="输入老师姓名"/>
      </div>
      <!-- 输入您想学的课程 -->
      <div class="search_r">
        <input type="button" id='search_submit' value="搜索" />
      </div>
    </div>
  </div>
</div>
<!--导航-->
<div class="nav" style="box-shadow:none;">
  <div class="nav_in">
    <div class="nav_list">
      <div class="nav_list_in"><a href="#"><{$smarty.session.__CURR_CATEGORY_NAME}></a></div>
      <div class="nav_list_b">
        <ul id="study">
        	<li><a style="background:url(images/list_all.png)" href="<{$smarty.const.__SITE_PATH}>/teachers.php?categoryId=all">所有分类</a></li>
		  	<{foreach from=$categorys item=category}>
		  	<li>
		  		<!--background:url(<{$__GTAPI_BASE_URL|cat:$category->icon}>)-->
		  		<a style="" href="<{$smarty.const.__SITE_PATH}>/teachers.php?categoryId=<{$category->id}>&categoryName=<{$category->name}>"><{$category->name}></a>
		  		<ul>
		  			<{foreach from=$category->childList item=secCategory}>
		  			<li><a href="<{$smarty.const.__SITE_PATH}>/teachers.php?categoryId=<{$secCategory->id}>&categoryName=<{$secCategory->name}>"><{$secCategory->name}></a></li>
		  			<{/foreach}>
		  	 	</ul>
		  	</li>
		  	<{/foreach}>
        </ul>
      </div>
    </div>
    <div class="nav_r">
      <ul>
        <li> <a  style="background:url(images/nav_home.png) left center no-repeat;" onMouseOver="this.style.background='url(images/nav_home2.png) left center no-repeat'"  onMouseOut="this.style.background='url(images/nav_home.png) left center no-repeat'" href="<{$smarty.const.__SITE_PATH}>/">首页</a> </li>
        <li> <a  style="background:url(images/nav_about.png) left center no-repeat;" onMouseOver="this.style.background='url(images/nav_about2.png) left center no-repeat'"  onMouseOut="this.style.background='url(images/nav_about.png) left center no-repeat'" href="<{$smarty.const.__SITE_PATH}>/aboutUs.php">关于我们</a> </li>
        
      </ul>
    </div>
    <{if $smarty.session.__IS_SIGN_IN_ANONYMOUS == 'YES'}>
    <div class="lr"> 
    	<a id="login_btn" href="javascript:void(0);">登录</a><span>|</span>
    	<a id="reg_btn" href="javascript:void(0);">注册</a> 
    </div>
	<{else}>
    <div class="user_info">
    	<a id="logined_downList">欢迎您，<font color="#ea5717"><{$smarty.session.__CURR_USER_INFO->name}></font></a>
        <div class="user_info_b">
        	<ul>
            	<li><a onclick="window.location='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>/user/my.php';" href="">个人中心</a></li>
                <li><a onclick="window.location='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>/user/logout.php';" href="">注销</a></li>
            </ul>
        </div>
    </div>
	<{/if}>
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
