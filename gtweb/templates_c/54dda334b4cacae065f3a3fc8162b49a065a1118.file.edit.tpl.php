<?php /* Smarty version Smarty-3.1.16, created on 2014-02-20 15:40:04
         compiled from "D:\xampp2\htdocs\gtweb\templates\user\edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:22426530613c46c6830-79375394%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '54dda334b4cacae065f3a3fc8162b49a065a1118' => 
    array (
      0 => 'D:\\xampp2\\htdocs\\gtweb\\templates\\user\\edit.tpl',
      1 => 1392878504,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '22426530613c46c6830-79375394',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'userAllSettins' => 0,
    'userProfile' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_530613c47ba432_44492962',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_530613c47ba432_44492962')) {function content_530613c47ba432_44492962($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>"好老师 www.kaopuu.com"), 0);?>

<!-- InstanceBeginEditable name="EditRegion3" -->
<script type="text/javascript" src="<?php echo @constant('__SITE_PATH');?>
/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo @constant('__SITE_PATH');?>
/js/user.js"></script>
<div class="banner_b"></div>
<div class="center">
	<div class="list_center">
		<?php echo $_smarty_tpl->getSubTemplate ("./my-left.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('menuId'=>'myInfo'), 0);?>

        <div class="user_r">
        	<?php echo $_smarty_tpl->getSubTemplate ("./my-top.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>'个人资料'), 0);?>

        	<form method='post' name='updateUserProfile' enctype="multipart/form-data">
            <div class="user_bg1">
            	<ul>
                    <li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot8.gif" width="18" height="18" /></em>
                        <i>手机：</i>
                        <span><?php echo $_smarty_tpl->tpl_vars['userAllSettins']->value->phone;?>
</span>
                        <div class="clear"></div>
                    </li>
                	<li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot1.gif" width="18" height="18" /></em>
                        <i>姓名：</i>
                        <span><input type='text' name='name' class='inputText' value='<?php echo $_smarty_tpl->tpl_vars['userProfile']->value->name;?>
'/></span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot4.gif" width="18" height="18" /></em>
                        <i>性别 ：</i>
                        <span>
                        	<input type='radio' name='sex' value='0' <?php if ($_smarty_tpl->tpl_vars['userProfile']->value->sex=='0') {?>checked='checked'<?php } else { ?><?php }?>/>女 
                        	<input type='radio' name='sex' value='1' <?php if ($_smarty_tpl->tpl_vars['userProfile']->value->sex=='1') {?>checked='checked'<?php } else { ?><?php }?>/>男
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot7.gif" width="18" height="18" /></em>
                        <i>头像：</i>
                        <span><input type='file' name='photo' class='inputText'/></span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot7.gif" width="18" height="18" /></em>
                        <i>生日：</i>
                        <span><input onClick="WdatePicker()" readonly=readonly type='text' name='birthday' class='inputText' value='<?php echo $_smarty_tpl->tpl_vars['userProfile']->value->birthday;?>
'/></span>
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
	
	$("input[name='updateSubmit']").bind('click', $.fn.user.updateUserProfile);
</script><?php }} ?>
