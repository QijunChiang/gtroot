<{include file="../header.tpl" title="好老师 www.kaopuu.com"}>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="banner_b"></div>
<div class="center">
	<div class="list_center">
		<{include file="./my-left.tpl" menuId='myInfo'}>
        <div class="user_r">
        	<{include file="./my-top.tpl" title='个人资料'}>
            <div class="user_bg1">
            	<ul>
                	<li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot1.gif" width="18" height="18" /></em>
                        <i>姓名：</i>
                        <span><{$userProfile->name}></span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot4.gif" width="18" height="18" /></em>
                        <i>性别 ：</i>
                        <span><{if $userProfile->sex == '0'}>女<{else}>男<{/if}></span>
                        <div class="clear"></div>
                    </li>
                    <!-- 
                    <li>
                    	<em><img src="images/user_dot5.gif" width="18" height="18" /></em>
                        <i>邮箱：</i>
                        <span>jamneo@163.com</span>
                        <div class="clear"></div>
                    </li>
                    
                    <li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot6.gif" width="18" height="18" /></em>
                        <i>住址：</i>
                        <span><{$userProfile->location->info}></span>
                        <div class="clear"></div>
                    </li>
                    -->
                    <li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot7.gif" width="18" height="18" /></em>
                        <i>生日：</i>
                        <span><{$userProfile->birthday}></span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot8.gif" width="18" height="18" /></em>
                        <i>手机：</i>
                        <span><{$userAllSettins->phone}></span>
                        <div class="clear"></div>
                    </li>
                </ul>
            </div>
   	  </div>
        <div class="clear"></div>
  	</div>
</div>
<!-- InstanceEndEditable --> 
<{include file="footer.tpl"}>