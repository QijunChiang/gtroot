<?php /* Smarty version Smarty-3.1.16, created on 2014-02-24 13:27:25
         compiled from "D:\xampp2\htdocs\gtweb\templates\user\cart\myOrderPay.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17956530b3aaddf8610-56081410%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f06ce3471749277ce4de792449e9da0992530132' => 
    array (
      0 => 'D:\\xampp2\\htdocs\\gtweb\\templates\\user\\cart\\myOrderPay.tpl',
      1 => 1392862784,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17956530b3aaddf8610-56081410',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'orderId' => 1,
    'itemCount' => 1,
    'totalPrice' => 1,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_530b3aadeaf311_24966346',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_530b3aadeaf311_24966346')) {function content_530b3aadeaf311_24966346($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>"好老师 www.kaopuu.com"), 0);?>

<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="banner_b"></div>
<div class="center">
	<div class="deal">
    	<div class="deal_t">购买仅需3步</div>
        <div class="deal_steap" style="background-position:bottom;"></div>
        <div class="table2">
          <div class="font_black">
          	
            订单编号：	<?php echo $_smarty_tpl->tpl_vars['orderId']->value;?>
<br/><br/>
            商品数量：	<?php echo $_smarty_tpl->tpl_vars['itemCount']->value;?>
<br/><br/>
            <font size="+0.5">应付总额：</font><font size="+1" color="#EA4F01">¥<?php echo $_smarty_tpl->tpl_vars['totalPrice']->value;?>
</font><br/><br/>
          </div>
          <div>付款方式：<input type='radio' checked='checked'><img src='<?php echo @constant('__SITE_PATH');?>
/includes/alipay/images/alipay.gif'/></div>
          <div class="deal_btn" style="margin-top:10px;">
          	<!--
          	<form method='get' action='<?php echo @constant('__SITE_PATH');?>
/user/myOrderPayResult.php'>
          		<input type='hidden' name='out_trade_no' value='<?php echo $_smarty_tpl->tpl_vars['orderId']->value;?>
'/>
          		<input type='hidden' name='trade_no' value='alipay-no-ddkfjdjfdjf'/>
          		<input type='hidden' name='trade_status' value='TRADE_SUCCESS'/>
          	-->
          	<form method='post' action='<?php echo @constant('__SITE_PATH');?>
/includes/alipay/alipayapi.php'>
          		<input type='hidden' name='WIDout_trade_no' value='<?php echo $_smarty_tpl->tpl_vars['orderId']->value;?>
'/>
          		<input type='hidden' name='WIDsubject' value='好老师课程购买'/>
          		<input type='hidden' name='WIDtotal_fee' value='<?php echo $_smarty_tpl->tpl_vars['totalPrice']->value;?>
'/>
          		<input type='hidden' name='WIDbody' value='好老师课程购买,数量：<?php echo $_smarty_tpl->tpl_vars['itemCount']->value;?>
'/>
          		<input type='hidden' name='WIDshow_url' value='<?php echo @constant('__SITE_DOMAIN');?>
<?php echo @constant('__SITE_PATH');?>
/user/myOrder.php'/>
          		<input type='hidden' name='notify_url' value='<?php echo @constant('__SITE_DOMAIN');?>
<?php echo @constant('__SITE_PATH');?>
/user/myOrderPayResult.php'/>
          		<input type='hidden' name='return_url' value='<?php echo @constant('__SITE_DOMAIN');?>
<?php echo @constant('__SITE_PATH');?>
/user/myOrderPayResult.php'/>
          		<input type="submit" class="green_btn" value="去付款"/>
          	</from>
          </div>
          <div class="clear"></div>
        </div>
  </div>
</div>
<!-- InstanceEndEditable --> 
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="<?php echo @constant('__SITE_PATH');?>
/js/usercart.js"></script>
<script type="text/javascript">
 $(function(){
    $.fn.usercart.options.rootPath='<?php echo @constant('__SITE_PATH');?>
';
 });
 function addToCart(courseId,num){
 	$.fn.usercart.addToCart(courseId,num);
 }
 $('input[id="orderSubmit"]').bind('click',	$.fn.usercart.submitOrder);
</script><?php }} ?>
