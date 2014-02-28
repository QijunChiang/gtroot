<%@ page language="java" contentType="text/html; charset=utf-8"
    pageEncoding="utf-8"%>
<%    
response.setHeader("Cache-Control","no-cache"); //HTTP 1.1    
response.setHeader("Pragma","no-cache"); //HTTP 1.0    
response.setDateHeader ("Expires", 0); //prevents caching at the proxy server    
%>
<%
String lurl=(String)session.getAttribute("lastAc");
String oid=request.getParameter("oid");
String uid=request.getParameter("uid");
if(session.getAttribute("lastAc")!=null){
	 session.removeAttribute("lastAc");
	 response.sendRedirect(lurl);
}else{
   String url="/goodTeacherWX/teacherInfo?openId="+oid+"&userId="+uid;
   session.setAttribute("lastAc",url);
}
  String tn=request.getParameter("un");
  String cn=request.getParameter("cn");
  String cid=request.getParameter("cid");
  if(tn!=null) 
   	 tn=new String(tn.getBytes("ISO-8859-1"),"UTF-8");
  else
	 tn="";
  if(cn!=null) 
	  cn=new String(cn.getBytes("ISO-8859-1"),"UTF-8");
  else
	  cn="";
%>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<link rel="stylesheet" type="text/css" href="js/jquery.mobile-1.3.2.min.css"/>
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/jquery.mobile-1.3.2.min.js"></script>
<title>好老师</title>
</head>
<body>
<div data-role="page" style="font-size:200%">
    <div data-role="header">
      <h1>输入联系方式</h1>
    </div>
    <div data-role="content">
     <form id="signupForm" method="POST" action="http://223.202.120.149/goodTeacherWX/signup">
      <div data-role="fieldcontain">
        <label for="userName">联系人：</label>
        <input type="hidden" id="courseId" name="courseId" value="<%=cid%>" />   
        <input type="hidden" id="wxOpenId" name="wxOpenId" value="<%=oid%>" />
        <input type="hidden" id="teacherId" name="teacherId" value="<%=uid%>" />
        <input id="userName" name="userName" type="text" />
        <label for="phoneNo">手机号：</label>
        <input id="phoneNo" name="phoneNo" type="text" />
      </div>
      <button type="button" data-inline="true" value="提　交" onclick="submitUser();">提　交</button>
      <p><font id="errorMsg" color="red"></font></p>
     </form>
  </div>
 </div>
</body>
<script type="text/javascript">
function submitUser(){
	var un=$('#userName').val();
	var pn=$('#phoneNo').val();
	if(un==''||pn==''){
        $('#errorMsg').text("为了方便联系你，请输入联系信息");
		return false;
	}
	//$('#signupForm').submit();
	var data={userName:un,phoneNo:pn,
			teacherName:'<%=tn%>',
			courseName:'<%=cn%>',
			courseId:$('#courseId').val(),
			wxOpenId:$('#wxOpenId').val(),
			teacherId:$('#teacherId').val()};
	ajaxSubmit(data);
	return true;
}
function ajaxSubmit(data){
	$.ajax({
		type : 'POST',
		url : '/goodTeacherWX/signup',
		cache : false,
		contentType : "application/x-www-form-urlencoded;charset=UTF-8",
		dataType : "text",
		data : data,
		error : function(xqr){},
		success : function(text, status, jqXHR) {
			alert(text);
			window.location.reload();
			//window.location.href='/goodTeacherWX/teacherInfo?openId='+data.wxOpenId+'&userId='+data.teacherId;
		}
	});
}
</script>
</html>