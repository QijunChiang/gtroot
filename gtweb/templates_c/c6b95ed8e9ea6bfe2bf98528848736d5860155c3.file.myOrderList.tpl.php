<?php /* Smarty version Smarty-3.1.16, created on 2014-02-25 09:51:30
         compiled from "D:\xampp2\htdocs\gtweb\templates\user\cart\myOrderList.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9571530c5992769776-92414399%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c6b95ed8e9ea6bfe2bf98528848736d5860155c3' => 
    array (
      0 => 'D:\\xampp2\\htdocs\\gtweb\\templates\\user\\cart\\myOrderList.tpl',
      1 => 1392878051,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9571530c5992769776-92414399',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'orderList' => 1,
    'order' => 1,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_530c59927e3574_69951825',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_530c59927e3574_69951825')) {function content_530c59927e3574_69951825($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../../header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>"好老师 www.kaopuu.com"), 0);?>

<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="banner_b"></div>
<div class="center">
	<div class="list_center">
		<?php echo $_smarty_tpl->getSubTemplate ("../my-left.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('menuId'=>'myOrder'), 0);?>

        <div class="user_r">
        	<?php echo $_smarty_tpl->getSubTemplate ("../my-top.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>'我的订单列表'), 0);?>

	        <div class="table1">
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
	            <tr>
	              <td height="30" align="center" valign="middle" class="table1_t">订单编号</td>
	              <td align="center" valign="middle" class="table1_t">总金额</td>
	              <td align="center" valign="middle" class="table1_t">支付状态</td>
	              <td align="center" valign="middle" class="table1_t">提交日期</td>
	              <td align="center" valign="middle" class="table1_t">操作</td>
	            </tr>
	          	<?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['orderList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value) {
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
	            <tr>
	              <td height="46" align="left" valign="middle" class="table1_g"><?php echo $_smarty_tpl->tpl_vars['order']->value->orderId;?>
<font color="#999999"></font></td>
	              <td align="center" valign="middle"><?php echo $_smarty_tpl->tpl_vars['order']->value->totalPrice;?>
</td>
	              <td width="106" align="center" valign="middle" class="quantity">
	              	<?php if ($_smarty_tpl->tpl_vars['order']->value->isPay==1) {?>
	              		已支付
	              	<?php } else { ?>
	              		未支付
	              	<?php }?>
	              </td>
	              <td align="center" valign="middle" class="fotnt_ora"><?php echo $_smarty_tpl->tpl_vars['order']->value->createTime;?>
</td>             
	              <td align="center" valign="middle" class="fotnt_ora"><a href=''><!--查看--> </a>
	              	<?php if ($_smarty_tpl->tpl_vars['order']->value->isPay==0) {?> <a href='<?php echo @constant('__SITE_PATH');?>
/user/myOrder.php?action=pay_order&orderId=<?php echo $_smarty_tpl->tpl_vars['order']->value->orderId;?>
'> 支付</a><?php }?>
	              </td>             
	            </tr>
				<?php } ?>
	          </table>
	        </div>
	   	  </div>
        <div class="clear"></div>
  	</div>
</div>
<!-- InstanceEndEditable --> 
<?php echo $_smarty_tpl->getSubTemplate ("../../footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>
