<?php /* Smarty version Smarty-3.1.16, created on 2014-02-20 02:47:10
         compiled from "d:\Wnmp\html\goodteacher\gtweb\templates\teacher.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19365729425305b2feb8ac57-81096198%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '908c5241af6a23ad02bbcb05d10618f9bd5f0349' => 
    array (
      0 => 'd:\\Wnmp\\html\\goodteacher\\gtweb\\templates\\teacher.tpl',
      1 => 1392882418,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19365729425305b2feb8ac57-81096198',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'teacher' => 1,
    '__GTAPI_BASE_URL' => 1,
    'teachCourseList' => 1,
    'teachCourse' => 1,
    'teachCommentsList' => 1,
    'teachComments' => 1,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5305b2ff1cad10_66938137',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5305b2ff1cad10_66938137')) {function content_5305b2ff1cad10_66938137($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>"好老师 www.kaopuu.com"), 0);?>

<script src="<?php echo @constant('__SITE_PATH');?>
/js/video-js/video.js" type="text/javascript"></script>
<link href="<?php echo @constant('__SITE_PATH');?>
/js/video-js/video-js.css" rel="stylesheet" type="text/css"/>
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
    <h1><?php echo $_SESSION['__CURR_CATEGORY_NAME'];?>
</h1>
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
	  <?php if ($_smarty_tpl->tpl_vars['teacher']->value->introduction->video->url!='') {?>
	  <div class="video-js-box" style="margin:20px auto">
	    <!-- Using the Video for Everybody Embed Code http://camendesign.com/code/video_for_everybody -->
	    <video id="example_video_1" class="video-js" width="100%" height="300" controls="controls" preload="auto" poster="<?php echo $_smarty_tpl->tpl_vars['__GTAPI_BASE_URL']->value;?>
<?php echo $_smarty_tpl->tpl_vars['teacher']->value->introduction->video->image;?>
">
	      <source src="<?php echo $_smarty_tpl->tpl_vars['__GTAPI_BASE_URL']->value;?>
<?php echo $_smarty_tpl->tpl_vars['teacher']->value->introduction->video->url;?>
" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
	      <!--<source src="http://video-js.zencoder.com/oceans-clip.webm" type='video/webm; codecs="vp8, vorbis"' />-->
	      <!--<source src="http://video-js.zencoder.com/oceans-clip.ogv" type='video/ogg; codecs="theora, vorbis"' />-->
	      <!-- 如果浏览器不兼容HTML5则使用flash播放 -->
	      <object id="flash_fallback_1" class="vjs-flash-fallback" width="100%" height="300" type="application/x-shockwave-flash"
	        data="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf">
	        <param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf" />
	        <param name="allowfullscreen" value="true" />
	        <param name="flashvars" value='config={"playlist":["<?php echo $_smarty_tpl->tpl_vars['__GTAPI_BASE_URL']->value;?>
<?php echo $_smarty_tpl->tpl_vars['teacher']->value->introduction->video->image;?>
", {"url": "<?php echo $_smarty_tpl->tpl_vars['__GTAPI_BASE_URL']->value;?>
<?php echo $_smarty_tpl->tpl_vars['teacher']->value->introduction->video->url;?>
","autoPlay":false,"autoBuffering":true}]}' />
	        <!-- 视频图片. -->
	        <img src="<?php echo $_smarty_tpl->tpl_vars['__GTAPI_BASE_URL']->value;?>
<?php echo $_smarty_tpl->tpl_vars['teacher']->value->introduction->video->image;?>
" width="640" height="264" alt="Poster Image" title="No video playback capabilities." />
	      </object>
	    </video>
	  </div>
	  <!-- End VideoJS -->
	  <?php }?>
  </div>
  <div class="innerContent layout">
    <div class="lfColumn">
    <?php if (count($_smarty_tpl->tpl_vars['teachCourseList']->value)==0) {?>
    	<p align="middle"><img src='<?php echo @constant('__SITE_PATH');?>
/images/no-courses.png'/></p>
    <?php }?>
    <?php  $_smarty_tpl->tpl_vars['teachCourse'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['teachCourse']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['teachCourseList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['teachCourse']->key => $_smarty_tpl->tpl_vars['teachCourse']->value) {
$_smarty_tpl->tpl_vars['teachCourse']->_loop = true;
?>
      <div class="group">
        <div class="title layout">
          <h2>
            <li><?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->name;?>
</li>
          </h2>
          <div class="price">
          	<em class="num">&yen;</em><font class="num"><?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->price;?>
</font>
        	<?php if ($_smarty_tpl->tpl_vars['teachCourse']->value->unit=='1') {?>
				/课
			<?php } elseif ($_smarty_tpl->tpl_vars['teachCourse']->value->unit=='2') {?>
				/总价
			<?php } else { ?>
				/小时
			<?php }?>
          </div>
          <a onclick='addToCart("<?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->courseId;?>
",1)' href="javascript:void(0);" class="btn">报名参加</a> </div>
        <div class="sub">
          <p>&nbsp;</p>
          <a href="javascript:void(0);" onClick="show_article(this)" class="ctrl show_ctrl">展开</a> </div>
        <div class="article">
          <div class="text">
            <p><?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->remark;?>
</p>
          </div>
          <div class="info">
            <div class="title" style="border-bottom:1px solid #e8e8e8;"> <span class="dot">·</span>
              <h3>课程信息</h3>
              <p>发布时间 : <?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->signUpStartDate;?>
</p>
            </div>
            <div class="inner">
              <ul class="week layout">
				<li class="<?php if ((strpos($_smarty_tpl->tpl_vars['teachCourse']->value->teachTime,'0'))!==false) {?>cur<?php }?>">周一</li>
				<li class="<?php if ((strpos($_smarty_tpl->tpl_vars['teachCourse']->value->teachTime,'1'))!==false) {?>cur<?php }?>">周二</li>
				<li class="<?php if ((strpos($_smarty_tpl->tpl_vars['teachCourse']->value->teachTime,'2'))!==false) {?>cur<?php }?>">周三</li>
				<li class="<?php if ((strpos($_smarty_tpl->tpl_vars['teachCourse']->value->teachTime,'3'))!==false) {?>cur<?php }?>">周四</li>
				<li class="<?php if ((strpos($_smarty_tpl->tpl_vars['teachCourse']->value->teachTime,'4'))!==false) {?>cur<?php }?>">周五</li>
				<li class="<?php if ((strpos($_smarty_tpl->tpl_vars['teachCourse']->value->teachTime,'5'))!==false) {?>cur<?php }?>">周六</li>
				<li class="<?php if ((strpos($_smarty_tpl->tpl_vars['teachCourse']->value->teachTime,'6'))!==false) {?>cur<?php }?>">周日</li>
              </ul>
              <ul class="infoList">
                <li class="date layout"><span>上课日期 :</span>
                  <p>
                  <?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->teachStartDate;?>
 - <?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->teachEndDate;?>
  
                  上课时间：<?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->teachStartTime;?>
 - <?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->teachEndTime;?>
</p>
                </li>
              </ul>
            </div>
            <div class="title" style="margin-top:10px; border-bottom:1px solid #e8e8e8;"> <span class="dot">·</span>
              <h3>所在地点</h3>
            </div>
            <div class="inner">
              <ul class="infoList" style="margin-top:-20px;clear:right;">
                <li class="address layout"><span>地&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;点 :</span>
                  <p><b> <?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->location->info;?>
</b></p>
                </li>
              </ul>
              <div>
                <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=31657cd57ab23627cbfb44476936628a"></script>
                <div id="allmap-<?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->courseId;?>
" style='width:600px;height:200px;'></div>
                <script type="text/javascript">
					// 百度地图API功能
					var map = new BMap.Map("allmap-<?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->courseId;?>
");
					var point = new BMap.Point(<?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->location->y;?>
, <?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->location->x;?>
);
					map.centerAndZoom(point, 16);
					var marker = new BMap.Marker(point); // 创建标注
					map.addOverlay(marker);              // 将标注添加到地图中
					marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
					var opts = {
					  width : 200,     // 信息窗口宽度
					  height: 60,     // 信息窗口高度
					  title : "<?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->name;?>
(<?php echo $_smarty_tpl->tpl_vars['teacher']->value->name;?>
)" , // 信息窗口标题
					  enableMessage:false,//设置允许信息窗发送短息
					  message:""
					}
					var infoWindow = new BMap.InfoWindow("地址：<?php echo $_smarty_tpl->tpl_vars['teachCourse']->value->location->info;?>
", opts);  // 创建信息窗口对象
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
	<?php } ?>
      <div class="comment"> 
        <!-- 总分 -->
        <div class="points">
          <div class="cont layout"> <span class="head">分数</span>
            <div class="star star10"></div>
            <span class="num"><?php echo $_smarty_tpl->tpl_vars['teacher']->value->star;?>
</span> </div>
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
        	<?php  $_smarty_tpl->tpl_vars['teachComments'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['teachComments']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['teachCommentsList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['teachComments']->key => $_smarty_tpl->tpl_vars['teachComments']->value) {
$_smarty_tpl->tpl_vars['teachComments']->_loop = true;
?>
	          <div class="item layout">
	            <div class="avatar"> 
	            	<?php if ($_smarty_tpl->tpl_vars['teachComments']->value->user->photo!='') {?>
						<img src="<?php echo ($_smarty_tpl->tpl_vars['__GTAPI_BASE_URL']->value).($_smarty_tpl->tpl_vars['teachComments']->value->user->photo);?>
" width="40" height="40" alt=""/> 
					<?php } else { ?>
						<img src="images/user_default.jpg" width="40" height="40" alt=""/> 
					<?php }?>
	            </div>
	            <div class="contents">
	              <h3 class="username"><?php echo $_smarty_tpl->tpl_vars['teachComments']->value->user->name;?>
</h3>
	              <div class="user_point layout">
	                <!--<div class="star star35"></div>-->
	                <span class="time"><?php echo $_smarty_tpl->tpl_vars['teachComments']->value->sendTime;?>
</span> </div>
	              <p class="reply_text">
	              		<?php echo $_smarty_tpl->tpl_vars['teachComments']->value->body;?>

	              </p>
	            </div>
	          </div>
			<?php } ?>
		    <?php if (count($_smarty_tpl->tpl_vars['teachCommentsList']->value)==0) {?>
		    	<p align="middle"><img src='<?php echo @constant('__SITE_PATH');?>
/images/no-comments.png'/></p>
		    <?php }?>
          <!--<a class="add_reply" href="javascript:void(0);"><span>显示所有评论</span></a> -->
          </div>
        <!-- 评论 --> 
      </div>
    </div>
    <!--老师信息-->
    <div class="rgColumn">
      <div class="teachInfo">
        <div class="teacher_avatar"><img src="<?php if ($_smarty_tpl->tpl_vars['teacher']->value->photo=='') {?>images/teacher.jpg<?php } else { ?><?php echo ($_smarty_tpl->tpl_vars['__GTAPI_BASE_URL']->value).($_smarty_tpl->tpl_vars['teacher']->value->photo);?>
<?php }?>" width="120" height="120" alt="<?php echo $_smarty_tpl->tpl_vars['teacher']->value->name;?>
"/></div>
        <h2 class="teacher_name"><?php echo $_smarty_tpl->tpl_vars['teacher']->value->name;?>
</h2>
      </div>
      <div class="teacherBio">
        <div class="title"> <span class="dot">·</span>
          <h3>教师简介</h3>
        </div>
        <div class="contents">
          <p><?php echo $_smarty_tpl->tpl_vars['teacher']->value->introduction->description;?>
</p>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<!-- InstanceEndEditable --> 
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="<?php echo @constant('__SITE_PATH');?>
/js/usercart.js"></script>
<script type="text/javascript">
 $(function(){
    $.fn.usercart.options.rootPath='<?php echo @constant('__SITE_PATH');?>
';
    videojs.options.flash.swf = "<?php echo @constant('__SITE_PATH');?>
/js/video-js/video-js.swf";
 });
 function addToCart(courseId,num){
 	$.fn.usercart.addToCart(courseId,num);
 }
</script><?php }} ?>
