<{include file="header.tpl"}>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="banner_b"></div>
<div class="center">
	<div class="deal">
    	<div class="deal_t">购买仅需3步</div>
        <div class="deal_steap" style="background-position:bottom;"></div>
        <div class="table2">
          <div class="font_black">
          	
            订单编号：	<{$orderId}><br/><br/>
            商品数量：	<{$itemCount}><br/><br/>
            <font size="+0.5">应付总额：</font><font size="+1" color="#EA4F01">¥<{$totalPrice}></font><br/><br/>
          </div>
          <div>付款方式：<input type='radio' checked='checked'><img src='<{$smarty.const.__SITE_PATH}>/includes/alipay/images/alipay.gif'/></div>
          <div class="deal_btn" style="margin-top:10px;">
          	<!--
          	<form method='get' action='<{$smarty.const.__SITE_PATH}>/user/myOrderPayResult.php'>
          		<input type='hidden' name='out_trade_no' value='<{$orderId}>'/>
          		<input type='hidden' name='trade_no' value='alipay-no-ddkfjdjfdjf'/>
          		<input type='hidden' name='trade_status' value='TRADE_SUCCESS'/>
          	-->
          	<form method='post' action='<{$smarty.const.__SITE_PATH}>/includes/alipay/alipayapi.php'>
          		<input type='hidden' name='WIDout_trade_no' value='<{$orderId}>'/>
          		<input type='hidden' name='WIDsubject' value='好老师课程购买'/>
          		<input type='hidden' name='WIDtotal_fee' value='<{$totalPrice}>'/>
          		<input type='hidden' name='WIDbody' value='好老师课程购买,数量：<{$itemCount}>'/>
          		<input type='hidden' name='WIDshow_url' value='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>/user/myOrder.php'/>
          		<input type='hidden' name='notify_url' value='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>/user/myOrderPayResult.php'/>
          		<input type='hidden' name='return_url' value='<{$smarty.const.__SITE_DOMAIN}><{$smarty.const.__SITE_PATH}>/user/myOrderPayResult.php'/>
          		<input type="submit" class="green_btn" value="去付款"/>
          	</from>
          </div>
          <div class="clear"></div>
        </div>
  </div>
</div>
<!-- InstanceEndEditable --> 
<{include file="footer.tpl"}>
<script type="text/javascript" src="<{$smarty.const.__SITE_PATH}>/js/usercart.js"></script>
<script type="text/javascript">
 $(function(){
    $.fn.usercart.options.rootPath='<{$smarty.const.__SITE_PATH}>';
 });
 function addToCart(courseId,num){
 	$.fn.usercart.addToCart(courseId,num);
 }
 $('input[id="orderSubmit"]').bind('click',	$.fn.usercart.submitOrder);
</script>