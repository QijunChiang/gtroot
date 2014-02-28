<{include file="header.tpl"}>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="banner_b"></div>
<div class="center">
	<div class="deal">
    	<div class="finished">
        	<em><img src="<{$smarty.const.__SITE_PATH}>/images/right.gif" width="99" height="68"></em>
            <span>恭喜，支付成功！</span>
      </div>
        <div class="finished_b"></div>
      <div class="result"><!--<font color="#00b386">商务英语突击小班 (48课时)</font>已有50人报名，报名成功。--></div>
        <div class="success">
          <input type="button" class="green_btn2" value="查看订单详细" 
          	onclick="javascript:window.location='<{$smarty.const.__SITE_PATH}>/user/myOrder.php?action=orderDetail&orderId=<{$orderId}>'">
          <div class="success_in2">您还可以点击 
	          <b><a href='<{$smarty.const.__SITE_PATH}>/user/my.php'>【个人中心】</a></b> 、
	          <b><a href='<{$smarty.const.__SITE_PATH}>/user/myCourse.php'>【我的课程】</a></b> 、
	          <b><a href='<{$smarty.const.__SITE_PATH}>/user/myOrder.php'>【我的订单】</a></b> 查看详情！
          </div>
      </div>
      <div class="success_b"></div>
  </div>
</div>
<!-- InstanceEndEditable --> 
<{include file="footer.tpl"}>