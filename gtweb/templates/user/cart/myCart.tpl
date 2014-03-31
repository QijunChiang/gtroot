<{include file="header.tpl"}>
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
          	<{foreach from=$userCart->cartItems item=cartItem}>
            <tr>
              <td height="46" align="left" valign="middle" class="table1_g"><a href="<{$smarty.const.__SITE_PATH}>/teacher.php?teacherId=<{$cartItem->courseInfo->user->id}>" target="_blank"><{$cartItem->courseInfo->name}><font color="#999999"></font></a></td>
              <td align="center" valign="middle"><{$cartItem->courseInfo->user->name}></td>
              <td width="106" align="left" valign="middle" class="quantity">
              <div style="float:left">
              	<a onclick='addToCart("<{$cartItem->courseInfo->courseId}>",-1)' href="javascript:void(0);"">-</a>
                </div>
                <div style="float:left">
                	<input type="text" value="<{$cartItem->itemNum}>" readOnly='readOnly'>
                </div>
                <div style="float:left">
                <a onclick='addToCart("<{$cartItem->courseInfo->courseId}>",1)' href="javascript:void(0);">+</a>
                </div>
              </td>
              <td align="center" valign="middle" class="fotnt_ora">￥<{$cartItem->courseInfo->price}>
				<{if $cartItem->courseInfo->unit=='1'}>
				/课
				<{elseif $cartItem->courseInfo->unit=='2'}>
				/总价
				<{else}>
				/小时
				<{/if}> 
				</td>             
            </tr>
			<{/foreach}>
            <tr>
              <td height="46" colspan="3" align="right" valign="middle" class="table2_all_deals">合计：</td>
              <td align="center" valign="middle" class="fotnt_ora" style="font-weight:bold; font-size:20px;">￥<{$userCart->totalPrice}></td>
            </tr>
          </table>
          <div class="deal_btn">
          	<form method='post' action='<{$smarty.const.__SITE_PATH}>/user/myCart.php'>
          		<input type='hidden' name='action' value='submit_order'/>
            	<input type="submit" id='orderSubmit'class="green_btn" <{if $userCart->cartItems|count <=0}>disabled=disabled<{/if}> value="提交订单"/>
            </form>
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
 //$('input[id="orderSubmit"]').bind('click',	$.fn.usercart.submitOrder);
</script>