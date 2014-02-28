<{include file="../header.tpl"}>
<!-- InstanceBeginEditable name="EditRegion3" -->
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/user.js"></script>
<div class="banner_b"></div>
<div class="center">
	<div class="list_center">
		<{include file="./my-left.tpl" menuId='myInfo'}>
        <div class="user_r">
        	<{include file="./my-top.tpl" title='个人资料'}>
        	<form method='post' name='updateUserProfile' enctype="multipart/form-data">
            <div class="user_bg1">
            	<ul>
                    <li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot8.gif" width="18" height="18" /></em>
                        <i>手机：</i>
                        <span><{$userAllSettins->phone}></span>
                        <div class="clear"></div>
                    </li>
                	<li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot1.gif" width="18" height="18" /></em>
                        <i>姓名：</i>
                        <span><input type='text' name='name' class='inputText' value='<{$userProfile->name}>'/></span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot4.gif" width="18" height="18" /></em>
                        <i>性别 ：</i>
                        <span>
                        	<input type='radio' name='sex' value='0' <{if $userProfile->sex == '0'}>checked='checked'<{else}><{/if}>/>女 
                        	<input type='radio' name='sex' value='1' <{if $userProfile->sex == '1'}>checked='checked'<{else}><{/if}>/>男
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot7.gif" width="18" height="18" /></em>
                        <i>头像：</i>
                        <span><input type='file' name='photo' class='inputText'/></span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot7.gif" width="18" height="18" /></em>
                        <i>生日：</i>
                        <span><input onClick="WdatePicker()" readonly=readonly type='text' name='birthday' class='inputText' value='<{$userProfile->birthday}>'/></span>
                        <div class="clear"></div>
                    </li>
                    <li>
                     	<i></i>
                        <span><input type='button' name='updateSubmit' value='更新'/></span>
                        <div class="clear"></div>
                    </li>
                </ul>
            </div>
            </form>
   	  </div>
        <div class="clear"></div>
  	</div>
</div>
<!-- InstanceEndEditable --> 
<{include file="footer.tpl"}>
<script type="text/javascript">
	$.fn.user.options.rootPath='<{$smarty.const.__SITE_PATH}>';
	$.fn.user.options.loginedRedirectUrl='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>';
	
	$("input[name='updateSubmit']").bind('click', $.fn.user.updateUserProfile);
</script>