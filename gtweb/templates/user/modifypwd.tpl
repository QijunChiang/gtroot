<{include file="../header.tpl"}>
<!-- InstanceBeginEditable name="EditRegion3" -->
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/user.js"></script>
<div class="banner_b"></div>
<div class="center">
	<div class="list_center">
		<{include file="./my-left.tpl" menuId='modifypwd'}>
        <div class="user_r">
        	<{include file="./my-top.tpl" title='修改密码'}>
        	<form method='post' name='updateUserPwd'>
            <div class="user_bg1">
            	<ul>
                	<li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot1.gif" width="18" height="18" /></em>
                        <i>旧密码：</i>
                        <span>
                        	<input type='password' name='oldPassword' class='inputText' style='float:left;'/>
                        	<div style='color:red; width:500px;'></div>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot7.gif" width="18" height="18" /></em>
                        <i>新密码 ：</i>
                        <span>
                        	<input type='password' name='newPassword' class='inputText'/>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot7.gif" width="18" height="18" /></em>
                        <i>新密码：</i>
                        <span>
                        	<input type='password' name='reNewPassword' class='inputText' style='float:left;'/>
                        	<div style='color:red; width:500px;'></div>
                        </span>
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
    $("input[name='oldPassword']").bind('blur', $.fn.user.checkModifyOldPwd);
    $("input[name='newPassword']").bind('blur', $.fn.user.checkModifyNewPwd);
    $("input[name='reNewPassword']").bind('blur', $.fn.user.checkModifyNewPwd);
    
	$("input[name='updateSubmit']").bind('click', $.fn.user.updateUserPwd);
</script>