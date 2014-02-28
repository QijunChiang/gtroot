<{include file="../../header.tpl"}>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="banner_b"></div>
<div class="center">
	<div class="list_center">
		<{include file="../my-left.tpl"}>
        <div class="user_r">
        	<{include file="../my-top.tpl" title='我的订单列表'  menuId='myOrder'}>
	        <div class="table1">
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
	            <tr>
	              <td height="30" align="center" valign="middle" class="table1_t">订单编号</td>
	              <td align="center" valign="middle" class="table1_t">总金额</td>
	              <td align="center" valign="middle" class="table1_t">支付状态</td>
	              <td align="center" valign="middle" class="table1_t">提交日期</td>
	              <td align="center" valign="middle" class="table1_t">操作</td>
	            </tr>
	          	<{foreach from=$orderList item=order}>
	            <tr>
	              <td height="46" align="left" valign="middle" class="table1_g"><a href="#"><{$order->orderId}><font color="#999999"></font></a></td>
	              <td align="center" valign="middle"><{$order->totalPrice}></td>
	              <td width="106" align="center" valign="middle" class="quantity">
	              	<{if $order->isPay == 1}>
	              		已支付
	              	<{else}>
	              		未支付
	              	<{/if}>
	              </td>
	              <td align="center" valign="middle" class="fotnt_ora"><{$order->createTime}></td>             
	              <td align="center" valign="middle" class="fotnt_ora"><a href=''>查看 </a>
	              	<{if $order->isPay == 0}> /<a href='<{$smarty.const.__SITE_PATH}>/user/myCart.php?action=pay_order&orderId=<{$order->orderId}>'> 支付</a><{/if}>
	              </td>             
	            </tr>
				<{/foreach}>
	          </table>
	        </div>
	   	  </div>
        <div class="clear"></div>
  	</div>
</div>
<!-- InstanceEndEditable --> 
<{include file="../../footer.tpl"}>
