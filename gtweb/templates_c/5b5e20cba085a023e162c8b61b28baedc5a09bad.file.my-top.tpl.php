<?php /* Smarty version Smarty-3.1.16, created on 2014-02-25 09:51:45
         compiled from "D:\xampp2\htdocs\gtweb\templates\user\my-top.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1486530c59a14aa712-92804520%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5b5e20cba085a023e162c8b61b28baedc5a09bad' => 
    array (
      0 => 'D:\\xampp2\\htdocs\\gtweb\\templates\\user\\my-top.tpl',
      1 => 1392606465,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1486530c59a14aa712-92804520',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_530c59a14aa717_37676883',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_530c59a14aa717_37676883')) {function content_530c59a14aa717_37676883($_smarty_tpl) {?>        	<div class="user_title">
            	<span><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</span>
                <em><a href="my.php?action=edit">+编辑个人信息</a></em>
            </div>
<?php }} ?>
