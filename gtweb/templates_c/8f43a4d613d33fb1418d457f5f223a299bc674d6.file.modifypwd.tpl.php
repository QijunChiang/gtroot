<?php /* Smarty version Smarty-3.1.16, created on 2014-02-20 01:38:02
         compiled from "d:\Wnmp\html\goodteacher\gtweb\templates\user\modifypwd.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9159835335305a2ca5e8d67-37631234%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8f43a4d613d33fb1418d457f5f223a299bc674d6' => 
    array (
      0 => 'd:\\Wnmp\\html\\goodteacher\\gtweb\\templates\\user\\modifypwd.tpl',
      1 => 1392878082,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9159835335305a2ca5e8d67-37631234',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5305a2ca6f2769_05541663',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5305a2ca6f2769_05541663')) {function content_5305a2ca6f2769_05541663($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>"好老师 www.kaopuu.com"), 0);?>

<!-- InstanceBeginEditable name="EditRegion3" -->
<script type="text/javascript" src="<?php echo @constant('__SITE_PATH');?>
/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo @constant('__SITE_PATH');?>
/js/user.js"></script>
<div class="banner_b"></div>
<div class="center">
	<div class="list_center">
		<?php echo $_smarty_tpl->getSubTemplate ("./my-left.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('menuId'=>'modifypwd'), 0);?>

        <div class="user_r">
        	<?php echo $_smarty_tpl->getSubTemplate ("./my-top.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>'修改密码'), 0);?>

        	<form method='post' name='updateUserPwd'>
            <div class="user_bg1">
            	<ul>
                	<li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot1.gif" width="18" height="18" /></em>
                        <i>旧密码：</i>
                        <span>
                        	<input type='password' name='oldPassword' class='inputText' style='float:left;'/>
                        	<div style='color:red; width:500px;'></div>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot7.gif" width="18" height="18" /></em>
                        <i>新密码 ：</i>
                        <span>
                        	<input type='password' name='newPassword' class='inputText'/>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot7.gif" width="18" height="18" /></em>
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
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript">
	$.fn.user.options.rootPath='<?php echo @constant('__SITE_PATH');?>
';
	$.fn.user.options.loginedRedirectUrl='<?php echo @constant('__SITE_DOMAIN');?>
<?php echo @constant('__SITE_PATH');?>
';
    $("input[name='oldPassword']").bind('blur', $.fn.user.checkModifyOldPwd);
    $("input[name='newPassword']").bind('blur', $.fn.user.checkModifyNewPwd);
    $("input[name='reNewPassword']").bind('blur', $.fn.user.checkModifyNewPwd);
    
	$("input[name='updateSubmit']").bind('click', $.fn.user.updateUserPwd);
</script><?php }} ?>
