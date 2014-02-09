<%@ page language="java" contentType="text/html; charset=utf-8"
    pageEncoding="utf-8"%>
<%@ taglib prefix="s" uri="/struts-tags"%>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap-theme.min.css"/>
<link rel="stylesheet" type="text/css" href="js/webPS.css"/>
<script type="text/javascript" src="js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<title>好老师</title>
</head>
<body>
  <div class="jumbotron webps_background">
    <img data-src="holder.js/100%x180" alt="100%x180" style="height: 650px; width: 100%; display: block;" src="<s:property value='photo'/>">
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center"><h1><s:property value='name'/></h1></div>
    </div>
    <div class="row">
      <div class="col-md-12 text-center"><p style="font-size:400%">你和此老师相距：<b><s:property value='distance'/></b>米</p></div>
    </div>
    <div class="row">
      <div class="col-md-12 text-center"><p style="font-size:400%">专业技能：<s:property value='skill'/></p></div>
    </div>
  </div>
  <div class="jumbotron webps_background">
  <div class="container">
    <h1 class="title">好老师</h1>
    <p style="font-size:200%">海量好老师聚合平台，帮你找到你想要的好老师</p>
    <p style="font-size:200%">想要获得好老师帮助，请下载手机APP:</p>
    <button type="button" class="btn btn-primary btn-lg btn-block"><h1>IOS 下载</h1></button><br>
    <button type="button" class="btn btn-primary btn-lg btn-block" onclick="javascript:window.location.href='http://www.kaopuu.com/gt/GoodTeacher.apk'"><h1>Android 下载</h1></button>
  </div>
</div>

</body>
</html>