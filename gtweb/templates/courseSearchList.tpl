<{include file="header.tpl"}>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="content">
  <div class="title">
    <h1>课程搜索结果</h1>
  </div>
  <div class="innerContent layout">
    <div class="lfColumn">
    <{if $teachCourseList|count ==0}>
    	<p align="middle">未搜索到关键词"<{$searchKey}>"相关课程</p>
    <{/if}>
    <{foreach from=$teachCourseList item=teachCourse}>
      <div class="group">
        <div class="title layout">
          <h2>
            <li>
            	<a title="查看课程详情" href="<{$smarty.const.__SITE_PATH}>/teacher.php?teacherId=<{$teachCourse->userId}>" target="_blank"><{$teachCourse->name}></a>
            	【<a title="查看老师详情" href="<{$smarty.const.__SITE_PATH}>/teacher.php?teacherId=<{$teachCourse->userId}>" target="_blank"><{$teachCourse->teachName}></a>】
            </li>
          </h2>
          <div class="price">
          	<em class="num">&yen;</em><font class="num"><{$teachCourse->price}></font>
        	<{if $teachCourse->unit=='1'}>
				/课
			<{elseif $teachCourse->unit=='2'}>
				/总价
			<{else}>
				/小时
			<{/if}>
          </div>
          <a onclick='addToCart("<{$teachCourse->courseId}>",1)' href="javascript:void(0);" class="btn">报名参加</a> </div>
        <div class="sub">
          <p>&nbsp;</p>
          <a href="javascript:void(0);" onClick="show_article(this)" class="ctrl show_ctrl">展开</a> </div>
        <div class="article">
          <div class="text">
            <p><{$teachCourse->remark}></p>
          </div>
          <div class="info">
            <div class="title" style="border-bottom:1px solid #e8e8e8;"> <span class="dot">·</span>
              <h3>课程信息</h3>
              <p>发布时间 : <{$teachCourse->signUpStartDate}></p>
            </div>
            <div class="inner">
              <ul class="week layout">
				<li class="<{if ($teachCourse->teachTime|strpos:'0') !== false}>cur<{/if}>">周一</li>
				<li class="<{if ($teachCourse->teachTime|strpos:'1') !== false}>cur<{/if}>">周二</li>
				<li class="<{if ($teachCourse->teachTime|strpos:'2') !== false}>cur<{/if}>">周三</li>
				<li class="<{if ($teachCourse->teachTime|strpos:'3') !== false}>cur<{/if}>">周四</li>
				<li class="<{if ($teachCourse->teachTime|strpos:'4') !== false}>cur<{/if}>">周五</li>
				<li class="<{if ($teachCourse->teachTime|strpos:'5') !== false}>cur<{/if}>">周六</li>
				<li class="<{if ($teachCourse->teachTime|strpos:'6') !== false}>cur<{/if}>">周日</li>
              </ul>
              <ul class="infoList">
                <li class="date layout"><span>上课日期 :</span>
                  <p>
                  <{$teachCourse->teachStartDate}> - <{$teachCourse->teachEndDate}>  
                  上课时间：<{$teachCourse->teachStartTime}> - <{$teachCourse->teachEndTime}></p>
                </li>
              </ul>
            </div>
            <div class="title" style="margin-top:10px; border-bottom:1px solid #e8e8e8;"> <span class="dot">·</span>
              <h3>所在地点</h3>
            </div>
            <div class="inner">
              <ul class="infoList" style="margin-top:-20px;clear:right;">
                <li class="address layout"><span>地&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;点 :</span>
                  <p><b> <{$teachCourse->location->info}></b></p>
                </li>
              </ul>
              <div>
                <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=31657cd57ab23627cbfb44476936628a"></script>
                <div id="allmap-<{$teachCourse->courseId}>" style='width:600px;height:200px;'></div>
                <script type="text/javascript">
					// 百度地图API功能
					var map = new BMap.Map("allmap-<{$teachCourse->courseId}>");
					var point = new BMap.Point(<{$teachCourse->location->y}>, <{$teachCourse->location->x}>);
					map.centerAndZoom(point, 16);
					var marker = new BMap.Marker(point); // 创建标注
					map.addOverlay(marker);              // 将标注添加到地图中
					marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
					var opts = {
					  width : 250,     // 信息窗口宽度
					  height: 70,     // 信息窗口高度
					  title : "地址：" , // 信息窗口标题
					  enableMessage:false,//设置允许信息窗发送短息
					  message:""
					}
					var infoWindow = new BMap.InfoWindow("<{$teachCourse->location->info}>", opts);  // 创建信息窗口对象
					map.openInfoWindow(infoWindow,point); //开启信息窗口
				</script>
              </div>
              <div class="ybm">
                <!--<p style="font-size:20px; font-weight:bold;">已报名人数 :<font class="num" style="font-size:20px;">0</font>人</p>-->
              </div>
            </div>
          </div>
        </div>
      </div>
	<{/foreach}>
    </div>
    <div class="clear"></div>
    <div class="page">
    	<{if $isShowPrevPageBtn}>
    		<a  id='goPrevPage' href="<{$smarty.const.__SITE_PATH}>/courseSearchList.php?searchKey=<{$searchKey}>&page=<{$page}>">上一页</a>
    	<{/if}>
    	<{if $isShowNextPageBtn}>
    		<a id='goNextPage' href="<{$smarty.const.__SITE_PATH}>/courseSearchList.php?searchKey=<{$searchKey}>&page=<{$page}>">下一页</a>
    	<{/if}>
  	</div>
  </div>
</div>
<!-- InstanceEndEditable --> 
<{include file="footer.tpl"}>
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/usercart.js"></script>
<script type="text/javascript">
	function show_article(e){
		var t=$(e)
		t.parent().siblings('.article').slideToggle(200,function(){
			if(t.text()=="展开"){
			t.text('收起');}else{
			t.text('展开');}
		});
		t.toggleClass('show_ctrl');
	}

 $(function(){
    $.fn.usercart.options.rootPath='<{$smarty.const.__SITE_PATH}>';
    videojs.options.flash.swf = "<{$smarty.const.__SITE_PATH}>/js/video-js/video-js.swf";
    
	$('.article:eq(0)').css('display','block');
	$('.ctrl:eq(0)').removeClass('show_ctrl').text('收起');
	$(".share_list_in").toggle(function(){
		$(this).parent().css("backgroundImage", "url(images/share_list2.gif)");
		$("#shareList").fadeIn(200);
	},function(){
		$(this).parent().css("backgroundImage", "url(images/share_list.gif)");
		$("#shareList").fadeOut(200);
	});
 });
 function addToCart(courseId,num){
 	//登录检查
 	$.ajax({
        url : "<{$smarty.const.__SITE_PATH}>/user/login.php?action=isLoginedCheck",
        type : 'GET',
        //data : '',
        //dataType : 'json',
        async : false,
        success : function(result){
            if(result && result == 0){
            	$.fn.usercart.addToCart(courseId,num);
            }else{
            	$('#login_btn').click();//打开登录窗口
            }
        },error:function(result){                
        }
    });
 }
</script>