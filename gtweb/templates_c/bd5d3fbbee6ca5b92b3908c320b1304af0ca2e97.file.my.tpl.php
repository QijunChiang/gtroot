<?php /* Smarty version Smarty-3.1.16, created on 2014-02-25 09:51:14
         compiled from "D:\xampp2\htdocs\gtweb\templates\user\my.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11005530c59827cd5b8-56606382%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bd5d3fbbee6ca5b92b3908c320b1304af0ca2e97' => 
    array (
      0 => 'D:\\xampp2\\htdocs\\gtweb\\templates\\user\\my.tpl',
      1 => 1392878005,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11005530c59827cd5b8-56606382',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'userProfile' => 0,
    'userAllSettins' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_530c59828842b3_45898192',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_530c59828842b3_45898192')) {function content_530c59828842b3_45898192($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>"好老师 www.kaopuu.com"), 0);?>

<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="banner_b"></div>
<div class="center">
	<div class="list_center">
		<?php echo $_smarty_tpl->getSubTemplate ("./my-left.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('menuId'=>'myInfo'), 0);?>

        <div class="user_r">
        	<?php echo $_smarty_tpl->getSubTemplate ("./my-top.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>'个人资料'), 0);?>

            <div class="user_bg1">
            	<ul>
                	<li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot1.gif" width="18" height="18" /></em>
                        <i>姓名：</i>
                        <span><?php echo $_smarty_tpl->tpl_vars['userProfile']->value->name;?>
</span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot4.gif" width="18" height="18" /></em>
                        <i>性别 ：</i>
                        <span><?php if ($_smarty_tpl->tpl_vars['userProfile']->value->sex=='0') {?>女<?php } else { ?>男<?php }?></span>
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
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot6.gif" width="18" height="18" /></em>
                        <i>住址：</i>
                        <span><?php echo $_smarty_tpl->tpl_vars['userProfile']->value->location->info;?>
</span>
                        <div class="clear"></div>
                    </li>
                    -->
                    <li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot7.gif" width="18" height="18" /></em>
                        <i>生日：</i>
                        <span><?php echo $_smarty_tpl->tpl_vars['userProfile']->value->birthday;?>
</span>
                        <div class="clear"></div>
                    </li>
                    <li>
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot8.gif" width="18" height="18" /></em>
                        <i>手机：</i>
                        <span><?php echo $_smarty_tpl->tpl_vars['userAllSettins']->value->phone;?>
</span>
                        <div class="clear"></div>
                    </li>
                </ul>
            </div>
   	  </div>
        <div class="clear"></div>
  	</div>
</div>
<!-- InstanceEndEditable --> 
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
