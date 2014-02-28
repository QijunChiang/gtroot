<{include file="header.tpl"}>
<!-- InstanceBeginEditable name="EditRegion3" -->
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/teachers.js"></script>
<!--老师列表-->
<div class="banner_b"></div>
<div class="center">
<div class="list_center">

<!--回头部-->
<div  class="fixed-bottom"><a href="#"><img src="images/to_top.png" width="82" height="82"></a></div>

    	<div class="list_t">
        	<div class="title1"><{$smarty.session.__CURR_CATEGORY_NAME}></div>
            <div class="title2"></div>
            <div class="select2">
            	<a>
            	<{if $currOrder==0}>按距离排序<{/if}>
            	<{if $currOrder==1}>按评分排序<{/if}>
            	<{if $currOrder==2}>按收藏数排序<{/if}>
            	<{if $currOrder==3}>按评论数排序<{/if}>
            	</a>
                <div class="select2_b">
                	<ul>
                    	<li class="distance"><a href="#" order='0' class='<{if $currOrder==0}>rn2<{/if}>'>按距离排序</a></li>
                        <li class="distance"><a href="#" order='1' class='<{if $currOrder==1}>rn2<{/if}>'>按评分排序</a></li>
                        <li class="distance"><a href="#" order='2' class='<{if $currOrder==2}>rn2<{/if}>'>按收藏数排序</a></li>
                        <li class="distance"><a href="#" order='3' class='<{if $currOrder==3}>rn2<{/if}>'>按评论数排序</a></li>
                    </ul>
                </div>
            </div>
          <div class="select2_l"><img src="images/area2.gif" width="17" height="17"></div>
            <div class="ge2"></div>
            <div class="select2">
            	<a id='currCountyName'>所有地区</a>
                <div class="select2_b">
                	<ul>
                		<{assign var="currCountyName" value="所有地区"}>
                    	<li class="county"><a href="#" county=''>所有地区</a></li>
                    	<{foreach from=$countyList item=county}>
						<li class="county"><a href="#" county='<{$county->id}>' class='<{if $county->id == $currCounty}>rn<{/if}>'><{$county->name}></a></li>
						<{/foreach}>
                    </ul>
                </div>
            </div>
            <div class="select2_l"><img src="images/area.gif" width="17" height="17"></div>
        </div>
        
        <!--老师列表-->
        <{foreach from=$teachers item=teacher}>
        <div class="teacher">
            <div class="photo">
				<div class="photo1">
	                <a href="teacher.php?teacherId=<{$teacher->userId}>" target="_blank">
	                <{if $teacher->introduction_image != ''}>
	                	<img src="<{$__GTAPI_BASE_URL|cat:$teacher->introduction_image}>" width="200" height="150">
	                <{else}>
	                	<img src="<{$smarty.const.__SITE_PATH}>/images/teacher.jpg" width="200" height="150">
	                <{/if}>
	                </a>
	                <span class="circle" style="position:absolute; display:inline-block; background:url(<{if $teacher->photo == ''}>images/teacher.jpg<{else}><{$__GTAPI_BASE_URL|cat:$teacher->photo}><{/if}>) no-repeat center center; width: 71px; height: 71px; left: 183px; top: -14px; border:2px solid #fff;"></span>
          		</div>
            	<div class="photo_b">
					<em>
					<{if $teacher->introduction_video != ''}>
					<a href="teacher.php?teacherId=<{$teacher->userId}>" target="_blank"><img src="images/media.gif" width="25" height="25"></a>
					<{else}>
					<a><img src="images/media2.gif" width="25" height="25"></a>
					<{/if}>
					</em>
            		<span><a href="teacher.php?teacherId=<{$teacher->userId}>" target="_blank"><{$teacher->shortName}></a></span>
            	</div>
			</div>
			<{if $teacher->firstCourse == null}>
            <div class="photo_l">
            	<div class="teacher_t">
                	<span><a href="teacher.php?teacherId=<{$teacher->userId}>" target="_blank">暂未开设课程</a></span>
                    <em><font class="redfont"></font></em>
                </div>
                <div class="detail">
                	暂未开设课程
                </div>
                <a href="teacher.php?teacherId=<{$teacher->userId}>" target="_blank"  class="detail_btn"><input type="image" src="images/detail.gif"></a>
                <div class="clear"></div>
                <div class="pf1">分数</div>
                <div class='pf2 points'>
					<div class="star star<{$teacher->star *10}>"></div>
                </div>
                <!--<div class="pf2" style="background-position:-<{$teacher->star*130}>px top;"></div>-->
                <div class="pf3"><{$teacher->star}></div>
                <div class="pf_r">发布时间 : </div>
          	</div>
			<{else}>
            <div class="photo_l">
            	<div class="teacher_t">
                	<span><a href="teacher.php?teacherId=<{$teacher->userId}>"><{$teacher->firstCourse->name}></a></span>
                    <em><font class="redfont">￥<{$teacher->firstCourse->price}></font>
                    	<{if $teacher->firstCourse->unit=='1'}>
							/课
						<{elseif $teacher->firstCourse->unit=='2'}>
							/总价
						<{else}>
							/小时
						<{/if}>
                    </em>
                </div>
                <div class="detail">
                	<{$teacher->firstCourse->remark}>
                </div>
                <a href="teacher.php?teacherId=<{$teacher->userId}>" target="_blank" class="detail_btn"><img src='images/detail.gif'/></a>
                <div class="clear"></div>
                <div class="pf1">分数</div>
                <div class='pf2 points'>
					<div class="star star<{$teacher->star *10}>"></div>
                </div>
                <!--<div class="pf2" style="background-position:-<{$teacher->star*130}>px top;"></div>-->
                <div class="pf3"><{$teacher->star}></div>
                <div class="pf_r">发布时间 : <{$teacher->firstCourse->signUpStartDate}></div>
          	</div>
			<{/if}>
            <div class="clear"></div>
		</div>
		<{/foreach}>
        <!--页码-->
        <div class="page">
        	<input type='hidden' name='currPage' value='<{$page}>'/>
        	<input type='hidden' name='currOrder' value='<{$currOrder}>'/>
        	<input type='hidden' name='currCounty' value='<{$currCounty}>'/>
        	<{if $isShowPrevPageBtn}>
        		<a  id='goPrevPage' href="javascript:void(0);">上一页</a>
        	<{/if}>
        	<{if $isShowNextPageBtn}>
        		<a id='goNextPage' href="javascript:void(0);">下一页</a>
        	<{/if}>
      </div>
      
      	<!--产看更多-->
        <div class="see_more" style="display:none"></div>
  </div>
</div>

<!-- InstanceEndEditable --> 
<{include file="footer.tpl"}>
<script type="text/javascript">
	$.fn.teachers.options.rootPath='<{$smarty.const.__SITE_PATH}>';
	$.fn.teachers.options.loginedRedirectUrl='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>';
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
</script>