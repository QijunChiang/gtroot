<{include file="../../header.tpl"}>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="banner_b"></div>
<div class="center">
	<div class="list_center">
		<{include file="../my-left.tpl"  menuId='myCourse'}>
        <div class="user_r">
        	<{include file="../my-top.tpl" title='我的课程'}>
            <div class="table1">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="46" align="center" valign="middle" class="table1_t">课程名称</td>
                    <td align="center" valign="middle" class="table1_t">老师</td>
                    <td align="center" valign="middle" class="table1_t">价格</td>
                    <td align="center" valign="middle" class="table1_t">购买时间</td>
                    <td align="center" valign="middle" class="table1_t">操作</td>
                  </tr>
                  <{foreach from=$courseList item=course}>
                  <tr>
                    <td height="46" align="left" valign="middle" class="table1_g">
                    <a target='_blank' href='<{$smarty.const.__SITE_PATH}>/teacher.php?teacherId=<{$course->teachId}>'><{$course->name}></a>
                    </td>
                    <td align="center" valign="middle" class="table1_g">
                   	<a target='_blank' href='<{$smarty.const.__SITE_PATH}>/teacher.php?teacherId=<{$course->teachId}>'><{$course->teachName}></a>
                    </td>
                    <td align="center" valign="middle" class="fotnt_ora">￥<{$course->price}>
		        	<{if $course->unit=='1'}>
						/课
					<{elseif $course->unit=='2'}>
						/总价
					<{else}>
						/小时
					<{/if}>
                    </td>
                    <td align="center" valign="middle"><{$course->teachStartDate}></td>
                    <td align="center" valign="middle" class="table1_g">
                    	<a target='_blank' href='<{$smarty.const.__SITE_PATH}>/teacher.php?teacherId=<{$course->teachId}>'>详情</a>
                    </td>
                  </tr>
				  <{/foreach}>
                </table>
            </div>
	   	  </div>
        <div class="clear"></div>
  	</div>
</div>
<!-- InstanceEndEditable --> 
<{include file="../../footer.tpl"}>
