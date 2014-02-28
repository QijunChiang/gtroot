<{include file="header.tpl"}>
<script src="<{$smarty.const.__SITE_PATH}>/js/video-js/video.js" type="text/javascript"></script>
<link href="<{$smarty.const.__SITE_PATH}>/js/video-js/video-js.css" rel="stylesheet" type="text/css"/>
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
</script>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="content">
  <div class="title">
    <h1><{$smarty.session.__CURR_CATEGORY_NAME}></h1>
	<div class="share_list">
	 <!--
      <div class="share_list_in"><a href="#">分享到</a></div>
      <div class="share_list_b">
        <ul id="shareList">
          <li><a href="#">腾讯微博</a></li>
          <li> <a href="#">新浪微博</a></li>
          <li><a href="#">微信</a></li>
          <li><a href="#">QQ</a></li>
          <li><a href="#">朋友圈</a></li>
          <li><a href="#">人人网</a></li>
        </ul>
      </div>
      -->
    </div>
  </div>
  <div>
	<!-- Begin VideoJS -->
	  <{if $teacher->introduction->video->url != ''}>
	  <div class="video-js-box" style="margin:20px auto">
	    <!-- Using the Video for Everybody Embed Code http://camendesign.com/code/video_for_everybody -->
	    <video id="example_video_1" class="video-js" width="100%" height="300" controls="controls" preload="auto" poster="<{$__GTAPI_BASE_URL}><{$teacher->introduction->video->image}>">
	      <source src="<{$__GTAPI_BASE_URL}><{$teacher->introduction->video->url}>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
	      <!--<source src="http://video-js.zencoder.com/oceans-clip.webm" type='video/webm; codecs="vp8, vorbis"' />-->
	      <!--<source src="http://video-js.zencoder.com/oceans-clip.ogv" type='video/ogg; codecs="theora, vorbis"' />-->
	      <!-- 如果浏览器不兼容HTML5则使用flash播放 -->
	      <object id="flash_fallback_1" class="vjs-flash-fallback" width="100%" height="300" type="application/x-shockwave-flash"
	        data="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf">
	        <param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf" />
	        <param name="allowfullscreen" value="true" />
	        <param name="flashvars" value='config={"playlist":["<{$__GTAPI_BASE_URL}><{$teacher->introduction->video->image}>", {"url": "<{$__GTAPI_BASE_URL}><{$teacher->introduction->video->url}>","autoPlay":false,"autoBuffering":true}]}' />
	        <!-- 视频图片. -->
	        <img src="<{$__GTAPI_BASE_URL}><{$teacher->introduction->video->image}>" width="640" height="264" alt="Poster Image" title="No video playback capabilities." />
	      </object>
	    </video>
	  </div>
	  <!-- End VideoJS -->
	  <{elseif $teacher->introduction->image|count > 1 && $teacher->introduction->image[1]->image}>
	  <div class="video_in"><img style='width:960px;height:360px;' src="<{$__GTAPI_BASE_URL}><{$teacher->introduction->image[1]->image}>" alt=""/></div>
	  <{else}>
	  <div class="video_in"><img style='width:960px;height:360px;' src="<{$smarty.const.__SITE_PATH}>/images/01.jpg" alt=""/></div>
	  <{/if}>
  </div>
  <div class="innerContent layout">
    <div class="lfColumn">
    <{if $teachCourseList|count ==0}>
    	<p align="middle"><img src='<{$smarty.const.__SITE_PATH}>/images/no-courses.png'/></p>
    <{/if}>
    <{foreach from=$teachCourseList item=teachCourse}>
      <div class="group">
        <div class="title layout">
          <h2>
            <li><{$teachCourse->name}></li>
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
      <div class="comment"> 
        <!-- 总分 -->
        <div class="points">
          <div class="cont layout"> <span class="head">分数</span>
            <div class="star star<{$teacher->star*10}>"></div>
            <span class="num"><{$teacher->star}></span> </div>
          <!--
          <ul class="point_list">
            <dl>
              <dt>5星</dt>
              <dd>
                <div class="point_column">
                  <div style="width:65%" class="point_red"></div>
                </div>
                <span class="number">(4)</span> </dd>
            </dl>
            <dl>
              <dt>4星</dt>
              <dd>
                <div class="point_column">
                  <div style="width:10%" class="point_red"></div>
                </div>
                <span class="number">(1)</span> </dd>
            </dl>
            <dl>
              <dt>3星</dt>
              <dd>
                <div class="point_column">
                  <div style="width:12%" class="point_red"></div>
                </div>
                <span class="number">(1)</span> </dd>
            </dl>
            <dl>
              <dt>2星</dt>
              <dd>
                <div class="point_column">
                  <div class="point_red"></div>
                </div>
                <span class="number">(0)</span> </dd>
            </dl>
            <dl>
              <dt>1星</dt>
              <dd>
                <div class="point_column">
                  <div class="point_red"></div>
                </div>
                <span class="number">(0)</span> </dd>
            </dl>
          </ul>
          -->
        </div>
        <!-- 总分 --> 
        <!-- 评论 -->
        <div class="reply">
        	<{foreach from=$teachCommentsList item=teachComments}>
	          <div class="item layout">
	            <div class="avatar"> 
	            	<{if $teachComments->user->photo != ''}>
						<img src="<{$__GTAPI_BASE_URL|cat:$teachComments->user->photo}>" width="40" height="40" alt=""/> 
					<{else}>
						<img src="images/user_default.jpg" width="40" height="40" alt=""/> 
					<{/if}>
	            </div>
	            <div class="contents">
	              <h3 class="username"><{$teachComments->user->name}></h3>
	              <div class="user_point layout">
	                <!--<div class="star star35"></div>-->
	                <span class="time"><{$teachComments->sendTime}></span> </div>
	              <p class="reply_text">
	              		<{$teachComments->body}>
	              </p>
	            </div>
	          </div>
			<{/foreach}>
		    <{if $teachCommentsList|count ==0}>
		    	<p align="middle"><img src='<{$smarty.const.__SITE_PATH}>/images/no-comments.png'/></p>
		    <{/if}>
          <!--<a class="add_reply" href="javascript:void(0);"><span>显示所有评论</span></a> -->
          </div>
        <!-- 评论 --> 
      </div>
    </div>
    <!--老师信息-->
    <div class="rgColumn">
      <div class="teachInfo">
        <div class="teacher_avatar"><img src="<{if $teacher->photo == ''}>images/teacher.jpg<{else}><{$__GTAPI_BASE_URL|cat:$teacher->photo}><{/if}>" width="120" height="120" alt="<{$teacher->name}>"/></div>
        <h2 class="teacher_name"><{$teacher->name}></h2>
      </div>
      <div class="teacherBio">
        <div class="title"> <span class="dot">·</span>
          <h3>教师简介</h3>
        </div>
        <div class="contents">
          <p><{$teacher->introduction->description}></p>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<!-- InstanceEndEditable --> 
<{include file="footer.tpl"}>
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/usercart.js"></script>
<script type="text/javascript">
 $(function(){
    $.fn.usercart.options.rootPath='<{$smarty.const.__SITE_PATH}>';
    videojs.options.flash.swf = "<{$smarty.const.__SITE_PATH}>/js/video-js/video-js.swf";
 });
 function addToCart(courseId,num){
 	$.fn.usercart.addToCart(courseId,num);
 }
</script>