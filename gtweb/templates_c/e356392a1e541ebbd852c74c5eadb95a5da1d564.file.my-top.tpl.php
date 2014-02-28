<?php /* Smarty version Smarty-3.1.16, created on 2014-02-20 02:59:18
         compiled from "d:\Wnmp\html\goodteacher\gtweb\templates\user\my-top.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4883373205305b5d63f8441-14921341%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e356392a1e541ebbd852c74c5eadb95a5da1d564' => 
    array (
      0 => 'd:\\Wnmp\\html\\goodteacher\\gtweb\\templates\\user\\my-top.tpl',
      1 => 1392606465,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4883373205305b5d63f8441-14921341',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5305b5d64139d6_24752166',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5305b5d64139d6_24752166')) {function content_5305b5d64139d6_24752166($_smarty_tpl) {?>        	<div class="user_title">
            	<span><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</span>
                <em><a href="my.php?action=edit">+编辑个人信息</a></em>
            </div>
<?php }} ?>
