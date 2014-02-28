<?php /* Smarty version Smarty-3.1.16, created on 2014-02-20 02:59:18
         compiled from "d:\Wnmp\html\goodteacher\gtweb\templates\user\my-left.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15534627285305b5d61a6751-19939715%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2403e30106efc69da301b815f58bcc174021cc9e' => 
    array (
      0 => 'd:\\Wnmp\\html\\goodteacher\\gtweb\\templates\\user\\my-left.tpl',
      1 => 1392878106,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15534627285305b5d61a6751-19939715',
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
  'unifunc' => 'content_5305b5d6309f63_89583494',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5305b5d6309f63_89583494')) {function content_5305b5d6309f63_89583494($_smarty_tpl) {?>		<div class="user_l">
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
