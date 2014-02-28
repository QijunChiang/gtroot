<?php /* Smarty version Smarty-3.1.16, created on 2014-02-20 02:46:17
         compiled from "d:\Wnmp\html\goodteacher\gtweb\templates\teachers.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3621615665305b2c92b7902-87583799%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '83a225e13442b56b572f4426139e3ebd4b68db35' => 
    array (
      0 => 'd:\\Wnmp\\html\\goodteacher\\gtweb\\templates\\teachers.tpl',
      1 => 1392606465,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3621615665305b2c92b7902-87583799',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'currOrder' => 1,
    'countyList' => 1,
    'county' => 1,
    'currCounty' => 1,
    'teachers' => 1,
    'teacher' => 1,
    '__GTAPI_BASE_URL' => 1,
    'page' => 1,
    'isShowPrevPageBtn' => 1,
    'isShowNextPageBtn' => 1,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5305b2c9622a66_77897570',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5305b2c9622a66_77897570')) {function content_5305b2c9622a66_77897570($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>"好老师 www.kaopuu.com"), 0);?>

<!-- InstanceBeginEditable name="EditRegion3" -->
<script type="text/javascript" src="<?php echo @constant('__SITE_PATH');?>
/js/teachers.js"></script>
<!--老师列表-->
<div class="banner_b"></div>
<div class="center">
<div class="list_center">

<!--回头部-->
<div  class="fixed-bottom"><a href="#"><img src="images/to_top.png" width="82" height="82"></a></div>

    	<div class="list_t">
        	<div class="title1"><?php echo $_SESSION['__CURR_CATEGORY_NAME'];?>
</div>
            <div class="title2"></div>
            <div class="select2">
            	<a>
            	<?php if ($_smarty_tpl->tpl_vars['currOrder']->value==0) {?>按距离排序<?php }?>
            	<?php if ($_smarty_tpl->tpl_vars['currOrder']->value==1) {?>按评分排序<?php }?>
            	<?php if ($_smarty_tpl->tpl_vars['currOrder']->value==2) {?>按收藏数排序<?php }?>
            	<?php if ($_smarty_tpl->tpl_vars['currOrder']->value==3) {?>按评论数排序<?php }?>
            	</a>
                <div class="select2_b">
                	<ul>
                    	<li class="distance"><a href="#" order='0' class='<?php if ($_smarty_tpl->tpl_vars['currOrder']->value==0) {?>rn2<?php }?>'>按距离排序</a></li>
                        <li class="distance"><a href="#" order='1' class='<?php if ($_smarty_tpl->tpl_vars['currOrder']->value==1) {?>rn2<?php }?>'>按评分排序</a></li>
                        <li class="distance"><a href="#" order='2' class='<?php if ($_smarty_tpl->tpl_vars['currOrder']->value==2) {?>rn2<?php }?>'>按收藏数排序</a></li>
                        <li class="distance"><a href="#" order='3' class='<?php if ($_smarty_tpl->tpl_vars['currOrder']->value==3) {?>rn2<?php }?>'>按评论数排序</a></li>
                    </ul>
                </div>
            </div>
          <div class="select2_l"><img src="images/area2.gif" width="17" height="17"></div>
            <div class="ge2"></div>
            <div class="select2">
            	<a id='currCountyName'>所有地区</a>
                <div class="select2_b">
                	<ul>
                		<?php $_smarty_tpl->tpl_vars["currCountyName"] = new Smarty_variable("所有地区", null, 0);?>
                    	<li class="county"><a href="#" county=''>所有地区</a></li>
                    	<?php  $_smarty_tpl->tpl_vars['county'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['county']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['countyList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['county']->key => $_smarty_tpl->tpl_vars['county']->value) {
$_smarty_tpl->tpl_vars['county']->_loop = true;
?>
						<li class="county"><a href="#" county='<?php echo $_smarty_tpl->tpl_vars['county']->value->id;?>
' class='<?php if ($_smarty_tpl->tpl_vars['county']->value->id==$_smarty_tpl->tpl_vars['currCounty']->value) {?>rn<?php }?>'><?php echo $_smarty_tpl->tpl_vars['county']->value->name;?>
</a></li>
						<?php } ?>
                    </ul>
                </div>
            </div>
            <div class="select2_l"><img src="images/area.gif" width="17" height="17"></div>
        </div>
        
        <!--老师列表-->
        <?php  $_smarty_tpl->tpl_vars['teacher'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['teacher']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['teachers']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['teacher']->key => $_smarty_tpl->tpl_vars['teacher']->value) {
$_smarty_tpl->tpl_vars['teacher']->_loop = true;
?>
        <div class="teacher">
            <div class="photo">
				<div class="photo1">
	                <a href="teacher.php?teacherId=<?php echo $_smarty_tpl->tpl_vars['teacher']->value->userId;?>
" target="_blank">
	                	<img src="<?php if ($_smarty_tpl->tpl_vars['teacher']->value->photo=='') {?>images/teacher.jpg<?php } else { ?><?php echo ($_smarty_tpl->tpl_vars['__GTAPI_BASE_URL']->value).($_smarty_tpl->tpl_vars['teacher']->value->photo);?>
<?php }?>" width="220" height="160">
	                </a>
	                <span class="circle" style="position:absolute; display:inline-block; background:url(<?php if ($_smarty_tpl->tpl_vars['teacher']->value->photo=='') {?>images/teacher.jpg<?php } else { ?><?php echo ($_smarty_tpl->tpl_vars['__GTAPI_BASE_URL']->value).($_smarty_tpl->tpl_vars['teacher']->value->photo);?>
<?php }?>) no-repeat center center; width: 71px; height: 71px; left: 183px; top: -14px; border:2px solid #fff;"></span>
          		</div>
            	<div class="photo_b">
					<em>
					<?php if ($_smarty_tpl->tpl_vars['teacher']->value->introduction_video!='') {?>
					<a href="teacher.php?teacherId=<?php echo $_smarty_tpl->tpl_vars['teacher']->value->userId;?>
" target="_blank"><img src="images/media.gif" width="25" height="25"></a>
					<?php } else { ?>
					<a><img src="images/media2.gif" width="25" height="25"></a>
					<?php }?>
					</em>
            		<span><a href="teacher.php?teacherId=<?php echo $_smarty_tpl->tpl_vars['teacher']->value->userId;?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['teacher']->value->shortName;?>
</a></span>
            	</div>
			</div>
			<?php if ($_smarty_tpl->tpl_vars['teacher']->value->firstCourse==null) {?>
            <div class="photo_l">
            	<div class="teacher_t">
                	<span><a href="teacher.php?teacherId=<?php echo $_smarty_tpl->tpl_vars['teacher']->value->userId;?>
" target="_blank">暂未开设课程</a></span>
                    <em><font class="redfont">￥xxx</font>/xx</em>
                </div>
                <div class="detail">
                	暂未开设课程
                </div>
                <a href="teacher.php?teacherId=<?php echo $_smarty_tpl->tpl_vars['teacher']->value->userId;?>
" target="_blank"  class="detail_btn"><input type="image" src="images/detail.gif"></a>
                <div class="clear"></div>
                <div class="pf1">分数</div>
                <div class="pf2" style="background-position:-<?php echo $_smarty_tpl->tpl_vars['teacher']->value->star*130;?>
px top;"></div>
                <div class="pf3"><?php echo $_smarty_tpl->tpl_vars['teacher']->value->star;?>
</div>
                <div class="pf_r">发布时间 : yyyy-mm-dd</div>
          	</div>
			<?php } else { ?>
            <div class="photo_l">
            	<div class="teacher_t">
                	<span><a href="teacher.php?teacherId=<?php echo $_smarty_tpl->tpl_vars['teacher']->value->userId;?>
"><?php echo $_smarty_tpl->tpl_vars['teacher']->value->firstCourse->name;?>
</a></span>
                    <em><font class="redfont">￥<?php echo $_smarty_tpl->tpl_vars['teacher']->value->firstCourse->price;?>
</font>
                    	<?php if ($_smarty_tpl->tpl_vars['teacher']->value->firstCourse->unit=='1') {?>
							/课
						<?php } elseif ($_smarty_tpl->tpl_vars['teacher']->value->firstCourse->unit=='2') {?>
							/总价
						<?php } else { ?>
							/小时
						<?php }?>
                    </em>
                </div>
                <div class="detail">
                	<?php echo $_smarty_tpl->tpl_vars['teacher']->value->firstCourse->remark;?>

                </div>
                <a href="teacher.php?teacherId=<?php echo $_smarty_tpl->tpl_vars['teacher']->value->userId;?>
" target="_blank" class="detail_btn"><img src='images/detail.gif'/></a>
                <div class="clear"></div>
                <div class="pf1">分数</div>
                <div class="pf2" style="background-position:-<?php echo $_smarty_tpl->tpl_vars['teacher']->value->star*130;?>
px top;"></div>
                <div class="pf3"><?php echo $_smarty_tpl->tpl_vars['teacher']->value->star;?>
</div>
                <div class="pf_r">发布时间 : <?php echo $_smarty_tpl->tpl_vars['teacher']->value->firstCourse->signUpStartDate;?>
</div>
          	</div>
			<?php }?>
            <div class="clear"></div>
		</div>
		<?php } ?>
        <!--页码-->
        <div class="page">
        	<input type='hidden' name='currPage' value='<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
'/>
        	<input type='hidden' name='currOrder' value='<?php echo $_smarty_tpl->tpl_vars['currOrder']->value;?>
'/>
        	<input type='hidden' name='currCounty' value='<?php echo $_smarty_tpl->tpl_vars['currCounty']->value;?>
'/>
        	<?php if ($_smarty_tpl->tpl_vars['isShowPrevPageBtn']->value) {?>
        		<a  id='goPrevPage' href="javascript:void(0);">上一页</a>
        	<?php }?>
        	<?php if ($_smarty_tpl->tpl_vars['isShowNextPageBtn']->value) {?>
        		<a id='goNextPage' href="javascript:void(0);">下一页</a>
        	<?php }?>
      </div>
      
      	<!--产看更多-->
        <div class="see_more" style="display:none"></div>
  </div>
</div>

<!-- InstanceEndEditable --> 
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript">
	$.fn.teachers.options.rootPath='<?php echo @constant('__SITE_PATH');?>
';
	$.fn.teachers.options.loginedRedirectUrl='<?php echo @constant('__SITE_DOMAIN');?>
<?php echo @constant('__SITE_PATH');?>
';
	$('#currCountyName').html($(".select2_b ul li a.rn").html());
	$('#goPrevPage').bind('click', $.fn.teachers.goPrevPage);
	$('#goNextPage').bind('click', $.fn.teachers.goNextPage);
	$('#goNextPage').bind('click', $.fn.teachers.goNextPage);
	$(".select2_b .distance a").bind('click',$.fn.teachers.teachersOrder);
	$(".select2_b .county a").bind('click',$.fn.teachers.teachersCounty);
	
	$(".select2_b ul li a").click(function(){ 
		$(this).parent().parent().parent().prev(".select2 a").html($(this).html());
		$(".select2_b").stop(false,true).fadeOut(200);
		$(this).addClass("rn");
		$(this).parent().prevAll().children().removeClass("rn");
		$(this).parent().nextAll().children().removeClass("rn");
	});
	
	$(".select2_b ul li.distance a").click(function(){ 
		$(this).addClass("rn2");
		$(this).parent().prevAll().children().removeClass("rn2");
		$(this).parent().nextAll().children().removeClass("rn2");
	});

	$(".select2 a").toggle(function(){
		$(this).next(".select2_b").stop(false,true).fadeIn(200);
	},function(){
		$(this).next(".select2_b").stop(false,true).fadeOut(200);
	});
	$('body').click(function(){
		$(".select2_b").stop(false,true).fadeOut(200);
	});
</script><?php }} ?>
