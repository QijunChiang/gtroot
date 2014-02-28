<?php /* Smarty version Smarty-3.1.16, created on 2014-02-28 03:22:58
         compiled from "D:\xampp2\htdocs\gtweb\templates\user\cart\myCart.tpl" */ ?>
<?php /*%%SmartyHeaderCode:690530ff3026de844-58069988%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'af1d288d1da456beb5f467094ad2528c9a65f52a' => 
    array (
      0 => 'D:\\xampp2\\htdocs\\gtweb\\templates\\user\\cart\\myCart.tpl',
      1 => 1393333560,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '690530ff3026de844-58069988',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'userCart' => 1,
    'cartItem' => 1,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_530ff302795549_45464625',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_530ff302795549_45464625')) {function content_530ff302795549_45464625($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="banner_b"></div>
<div class="center">
	<div class="deal">
    	<div class="deal_t">购买仅需3步</div>
        <div class="deal_steap"></div>
        <div class="table2">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="30" align="center" valign="middle" class="table2_t">课程名称</td>
              <td align="center" valign="middle" class="table2_t">老师</td>
              <td align="center" valign="middle" class="table2_t">数量</td>
              <td align="center" valign="middle" class="table2_t">价格</td>
            </tr>
          	<?php  $_smarty_tpl->tpl_vars['cartItem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cartItem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['userCart']->value->cartItems; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cartItem']->key => $_smarty_tpl->tpl_vars['cartItem']->value) {
$_smarty_tpl->tpl_vars['cartItem']->_loop = true;
?>
            <tr>
              <td height="46" align="left" valign="middle" class="table1_g"><a href="#"><?php echo $_smarty_tpl->tpl_vars['cartItem']->value->courseInfo->name;?>
<font color="#999999"></font></a></td>
              <td align="center" valign="middle"><?php echo $_smarty_tpl->tpl_vars['cartItem']->value->courseInfo->user->name;?>
</td>
              <td width="106" align="left" valign="middle" class="quantity">
              <div style="float:left">
              	<a onclick='addToCart("<?php echo $_smarty_tpl->tpl_vars['cartItem']->value->courseInfo->courseId;?>
",-1)' href="javascript:void(0);"">-</a>
                </div>
                <div style="float:left">
                	<input type="text" value="<?php echo $_smarty_tpl->tpl_vars['cartItem']->value->itemNum;?>
" readOnly='readOnly'>
                </div>
                <div style="float:left">
                <a onclick='addToCart("<?php echo $_smarty_tpl->tpl_vars['cartItem']->value->courseInfo->courseId;?>
",1)' href="javascript:void(0);">+</a>
                </div>
              </td>
              <td align="center" valign="middle" class="fotnt_ora">￥<?php echo $_smarty_tpl->tpl_vars['cartItem']->value->courseInfo->price;?>

				<?php if ($_smarty_tpl->tpl_vars['cartItem']->value->courseInfo->unit=='1') {?>
				/课
				<?php } elseif ($_smarty_tpl->tpl_vars['cartItem']->value->courseInfo->unit=='2') {?>
				/总价
				<?php } else { ?>
				/小时
				<?php }?> 
				</td>             
            </tr>
			<?php } ?>
            <tr>
              <td height="46" colspan="3" align="right" valign="middle" class="table2_all_deals">合计：</td>
              <td align="center" valign="middle" class="fotnt_ora" style="font-weight:bold; font-size:20px;">￥<?php echo $_smarty_tpl->tpl_vars['userCart']->value->totalPrice;?>
</td>
            </tr>
          </table>
          <div class="deal_btn">
          	<form method='post' action='<?php echo @constant('__SITE_PATH');?>
/user/myCart.php'>
          		<input type='hidden' name='action' value='submit_order'/>
            	<input type="submit" id='orderSubmit'class="green_btn" <?php if (count($_smarty_tpl->tpl_vars['userCart']->value->cartItems)<=0) {?>disabled=disabled<?php }?> value="提交订单"/>
            </form>
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
 //$('input[id="orderSubmit"]').bind('click',	$.fn.usercart.submitOrder);
</script><?php }} ?>
