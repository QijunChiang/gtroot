<?php /* Smarty version Smarty-3.1.16, created on 2014-02-25 09:51:45
         compiled from "D:\xampp2\htdocs\gtweb\templates\user\my-left.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2761530c59a1430912-28740776%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3ee84a8189d634bba2c7fdb0ee628df911aa5261' => 
    array (
      0 => 'D:\\xampp2\\htdocs\\gtweb\\templates\\user\\my-left.tpl',
      1 => 1392878106,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2761530c59a1430912-28740776',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '__GTAPI_BASE_URL' => 0,
    'userProfile' => 0,
    'menuId' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_530c59a14aa719_46740580',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_530c59a14aa719_46740580')) {function content_530c59a14aa719_46740580($_smarty_tpl) {?>		<div class="user_l">
        	<div class="circle" style="width:122px; height:122px; background:url(<?php echo ($_smarty_tpl->tpl_vars['__GTAPI_BASE_URL']->value).($_smarty_tpl->tpl_vars['userProfile']->value->photo);?>
); margin:0 auto; "></div>
            <div class="user_name">
            	<img src="<?php echo @constant('__SITE_PATH');?>
/images/sex.gif" width="11" height="11"> <?php echo $_smarty_tpl->tpl_vars['userProfile']->value->name;?>

            </div>
            <!--<div class="user_time">上次登录: 2014-01-21</div>-->
            <div class="user_select">
            	<ul>
                	<li class="<?php if ($_smarty_tpl->tpl_vars['menuId']->value=='myInfo') {?>now<?php }?>">
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot1.gif" width="18" height="18"></em>
                        <span><a href="<?php echo @constant('__SITE_PATH');?>
/user/my.php">个人信息</a></span>
                    </li>
                    <li class="<?php if ($_smarty_tpl->tpl_vars['menuId']->value=='myCourse') {?>now<?php }?>">
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot2.gif" width="18" height="18"></em>
                        <span><a href="<?php echo @constant('__SITE_PATH');?>
/user/myCourse.php">我的课程</a></span>
                    </li>
                    <li class="<?php if ($_smarty_tpl->tpl_vars['menuId']->value=='myOrder') {?>now<?php }?>">
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot2.gif" width="18" height="18"></em>
                        <span><a href="<?php echo @constant('__SITE_PATH');?>
/user/myOrder.php">我的订单</a></span>
                    </li>
                    <li class="<?php if ($_smarty_tpl->tpl_vars['menuId']->value=='modifypwd') {?>now<?php }?>">
                    	<em><img src="<?php echo @constant('__SITE_PATH');?>
/images/user_dot3.gif" width="18" height="18"></em>
                        <span><a href="<?php echo @constant('__SITE_PATH');?>
/user/my.php?action=modifypwd">修改密码</a></span>
                    </li>
                </ul>
            </div>
        </div>
<?php }} ?>
