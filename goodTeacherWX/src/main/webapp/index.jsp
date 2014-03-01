<%@ page language="java" contentType="text/html; charset=utf-8"
    pageEncoding="utf-8"%>
<%@ taglib prefix="s" uri="/struts-tags"%>
<!DOCTYPE HTML>
<html lang="zh">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>小尚科技-好老师</title>
	<!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/wap.css">
	<script type="text/javascript">
	if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
	  var msViewportStyle = document.createElement("style")
	  msViewportStyle.appendChild(
		document.createTextNode(
		  "@-ms-viewport{width:auto!important}"
		)
	  )
	  document.getElementsByTagName("head")[0].appendChild(msViewportStyle)
	}
	</script>
</head>
<body>
<div class="container">
<input type="hidden" id="openId" value="<s:property value='openId'/>" />
<input type="hidden" id="userId" value="<s:property value='userId'/>" />
		<div class="row">
		  <%if(request.getAttribute("video")!=null && !"".equals(request.getAttribute("video"))){%>
			<video class="wap_video col-xs-12" src="http://www.kaopuu.com/gtapi/<s:property value='video'/>" type="video/mp4" controls="controls" poster="<s:property value='photo'/>" style="height:250px;">
				您的设备不支持播放该视频。
			</video>
		  <%}else{%>
            <img id="tLogo" class="wap_video col-xs-12" data-src="holder.js/100%x180" alt="100%x180" style="width:100%;height:250px; display: block;" src="<s:property value='photo'/>">
		  <%} %>
		</div>
		<div class="info">
			<div class="wap_rows row">
				<div class="avatar col-xs-2">
					<img class="img-responsive" src="<s:property value='teacherLogo'/>" alt="<s:property value='name'/>" style="height:50px;"/>
				</div>
				<div class="intro ffms row col-xs-10">
					<div class="name row">
						<label><s:property value='name'/></label>
					</div>
					<!-- <div class="row" style="margin-top:5px;">
						<small>&nbsp;距离:<s:property value='distance'/>km</small>
					</div>
					 <div class="school row">
						<label style="padding-left:5px;margin-right:5px;">学校：</label>
						<label class="fwnm cont"><s:property value='college'/></label>
					</div> -->
				</div>
			</div>
		    <div class="wap_rows row">
				<div class="address row">
				    <label class="tag fwnm"><s:property value='address'/></label>
				</div>
			</div>
			<div class="wap_rows row">
				<div class="skill" style="width:30px;float:left;">
					<label class="tag" style="height:35px;">&nbsp;</label>
				</div>
				<div class="fwnm" style="margin-top:3px;padding-left:30px;width:97%;"><s:property value="description" escape="false"/></div>
			</div>
		</div>
		<div class="class ffms">
			<section class="title">培训课程(<s:property value="%{dataList.size()}"/>)</section>
			<s:iterator value="dataList" id="array">
			<div class="container">
				<div class="row" style="border-bottom:1px solid #CCC;">
					<div class="class-name col-xs-7">
						<span><s:property value="name"/></span>
					</div>
					<div class="col-xs-5 class-price">
						&yen;<s:property value="price"/><small>/<s:property value="unit"/></small>
					</div>
				</div>
				<ul class="week">
					<s:property value="teachTime" escape="false"/>
				</ul>
				<div class="list">
					<div class="wap_rows row">
						<div class="date">
							<label class="tag fwnm"><s:property value="teachStartDate"/> - <s:property value="teachEndDate"/></label>
						</div>
					</div>
					<div class="wap_rows row">
						<div class="time">
							<label class="tag fwnm"><s:property value="teachStartTime"/> - <s:property value="teachEndTime"/></label>
						</div>
					</div>
					<div class="wap_rows row">
						<div class="address">
							<label class="tag fwnm"><s:property value="address"/></label>
						</div>
					</div>
					<div class="wap_rows row">
					    <div class="remark" style="width:30px;float:left;">
						  <label class="tag" style="height:35px;">&nbsp;</label>
					    </div>
						<div class="fwnm" style="margin-top:3px;padding-left:30px;"><s:property value="remark" escape="false"/></div>
					</div>
				</div>
				<a class="submit btn btn-lg btn-danger btn-block" cnm="<s:property value="name"/>" cid="<s:property value="courseId"/>">
				   <span class="glyphicon glyphicon-star"></span>免费预约试听课
				</a>
			</div>
			</s:iterator> 
		</div>
</div>
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
  var openId,userId;
  function handleBill(e){
	  e.stopPropagation();
      e.preventDefault();
	  var el=$(this);
	  var cid=el.attr('cid');
	  var cn=encodeURIComponent(el.attr('cnm'));
	  var un=encodeURIComponent('<s:property value="name"/>');
	  window.location.href='http://223.202.120.149/goodTeacherWX/course.jsp?cid='+cid+'&oid='+openId+'&uid='+userId+'&un='+un+'&cn='+cn;
  }
  $(document).ready(function(){
     $('[cnm]').each(function(){
        $(this).click(handleBill);
     });
     $('.class-name span').each(function(){
         var el=$(this);
         var h=el.outerHeight(true)+10;
         el.parent().css({height:h+'px'});
     });
     openId=$('#openId').val();
     userId=$('#userId').val();      
  });
</script>
</body>
</html>