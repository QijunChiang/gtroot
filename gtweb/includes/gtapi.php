<?php
/**
 * 获取订单详细
 */
function get_user_order_detail($sessionKey,$orderId) {
	$data='sessionKey='.$sessionKey.'&orderId='.$orderId;
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_user_order_detail', $data,'GET');
	return json_decode($ret);
}
/**
 * 获取用户订单列表
 */
function get_user_order_list($sessionKey) {
	$data='sessionKey='.$sessionKey;
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_user_order_list', $data,'GET');
	return json_decode($ret);
}
/**
 * 更新订单支付成功
 */
function update_user_order_pay_succ($sessionKey, $orderId,$tradeNo) {
	$data='sessionKey='.$sessionKey.'&orderId='.$orderId.'&tradeNo='.$tradeNo;
	echo $data;
	$ret = curl_request(__GTAPI_BASE_URL.'app/update_user_order_pay_succ', $data,'POST');
	return json_decode($ret);
}
/**
 * 提交订单
 * TODO: 需要调整为调用线上接口
 * http://localhost/gtapi/app/get_course_info?courseId=520d8ff4de36c
 * {"result": true,"data": {orderId:"51d3ed1124848"}}
 */
function sumbit_user_order($sessionKey, $cartDataJson) {
	$data='sessionKey='.$sessionKey.'&cartDataJson='.$cartDataJson;
	$ret = curl_request(__GTAPI_BASE_URL.'app/sumbit_user_order', $data,'POST');
	return json_decode($ret);
}
/**
 * 获取课程详细信息
 * TODO: 需要调整为调用线上接口
 * http://localhost/gtapi/app/get_course_info?courseId=520d8ff4de36c
 */
function get_course_info($courseId) {
	$data='courseId='.$courseId;
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_course_info', $data,'GET');
	//$ret = '{"result":true,"data":{"courseId":"520d8ff4de36c","user":{"id":"520781926a9dd","name":"wondesssr"},"name":"\u5165\u5165\u95e8\u94a2\u7434","address":"\u56db\u5ddd\u7701\u6210\u90fd\u5e02\u6b66\u4faf\u533a\u6b66\u4faf\u5927\u9053\u53cc\u6960\u6bb5577\u53f7","remark":"\u65e9\u70b9\u4e0a\u8bfe","price":"150","unit":"0","teachTime":"0,1,6","signUpStartDate":"0000-00-00","signUpEndDate":"0000-00-00","teachStartDate":"0000-00-00","teachEndDate":"0000-00-00","teachStartTime":"00:00:00","teachEndTime":"00:00:00","location":{"x":"30.638664","y":"104.011993","info":"\u65e9\u70b9\u4e0a\u8bfe"}}}';
	//echo $ret;
	return json_decode($ret);
	
}
/**
 * 获得用户报名的课程列表 
 * 请求地址
	   app/get_teach_course_sign_up_list
	请求方法
	   GET
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1006：你的sessionKey是无效或过期的。
	参数
	   format ： xml/json 可选
	   sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
	   count:"30" 可选 一页的条数，默认20
	   page:'2' 可选 请求查询的页数 默认1
 */
function get_teach_course_sign_up_list($sessionKey, $count=30) {
	$data='sessionKey='.$sessionKey.'&count='.$count;
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_teach_course_sign_up_list', $data,'GET');
	//echo $ret;
	return json_decode($ret);
}
/**
 * 用户报名课程
 * 请求地址
	   app/add_teach_course_sign_up
	请求方法
	   POST
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1001：缺省为保存时候验证出错所返回的信息。
	   1002：插入数据出错。
	   1006：你的sessionKey是无效或过期的。
	   1031：不能根据teachCourseId找到对应的课程
	参数
	   format ： xml/json 可选
	   teachCourseId:'51d636282be46' 必选 被收藏的ID,需要收藏的ID。
	   sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 */
function add_teach_course_sign_up($sessionKey, $teachCourseId) {
	$data='sessionKey='.$sessionKey.'&teachCourseId='.$teachCourseId;
	$ret = curl_request(__GTAPI_BASE_URL.'app/add_teach_course_sign_up', $data,'POST');
	return json_decode($ret);
}
/**
 * 请求地址
	   app/update_password
	请求方法
	   POST
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1001：缺省为保存时候验证出错所返回的信息。
	   1003：修改数据出错。
	   1006：你的sessionKey是无效或过期的。
	   1016：你的旧密码错误。
	参数
	   format ： xml/json 可选
	   sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
	   oldPassword: 'password' 必选 旧密码
	   newPassword: 'abc123' 必选 新密码
	返回
	{
	   "result": true,
	   "data": {}
	}
 */
function update_password($sessionKey, $oldPassword, $newPassword) {
	$data='sessionKey='.$sessionKey.'&oldPassword='.$oldPassword.'&newPassword='.$newPassword;
	$ret = curl_request(__GTAPI_BASE_URL.'app/update_password', $data,'POST');
	return json_decode($ret);
}
/**
 * 获得用户设置页的统计数据和设置信息
 * 请求地址
	   app/get_all_settings
	请求方法
	   GET
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1006：你的sessionKey是无效或过期的。
	参数
	   format ： xml/json 可选
	   sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
 */
function get_all_settings($sessionKey) {
	$data='sessionKey='.$sessionKey;
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_all_settings', $data,'GET');
	return json_decode($ret);
}
/**
 * 获得用户设置页的个人信息 
 * 请求地址
	   app/get_user_profile
	请求方法
	   GET
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1006：你的sessionKey是无效或过期的。
	参数
	   format ： xml/json 可选
	   sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
	返回
	{
	  "result": true,
	  "data": {
	      "id": "51d63c7019276", //用户编号
	      "roleId": "3",//用户角色：学生为2，老师为3，机构为1
	      "photo": "",//头像，base64位编码
	      "name": "张三",//名称
	      "sex": "0",//性别：0为女，1为男
	      "birthday": "2013-07-03 11:29:19",//出生日期
	      "college": "",//大学，毕业院校
	      "location": {//老师 特有
	          "x": "30.546438",//纬度
	          "y": "104.070536",//经度
	          "info": "中国四川省成都市武侯区天华一路81号"//标注地信息
	      },
	      "skill": "钢琴",//专业技能 老师 特有
	      "price": "90",//价格 老师 特有
	      "teachCategories":[//专长 老师 特有
	      	 {
	      		 "id": "123",
	      		 "name":"小提琴",//名称
	      		 "icon": "",//专长的 图片，base64位编码
	      	 }
	      ]
	      "v": [//V信息 老师 特有
	          {
	              "id": "23",//V编号
	              "name": "",//V名称
	              "icon": ""//小图标，base64位编码
	          }
	      ]
	  }
	}
 * @return data
 */
function get_user_profile_student($sessionKey) {
	$data='sessionKey='.$sessionKey;
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_user_profile', $data,'GET');
	return json_decode($ret);
}
/**
 * 更新个人信息
 * 请求地址
	   app/update_profile
	请求方法
	   POST
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1001：缺省为保存时候验证出错所返回的信息。
	   1003：修改数据出错。
	   1006：你的sessionKey是无效或过期的。
	   1008：位置坐标信息错误，不能自动获取到地址。
	参数
	   format ： xml/json 可选
	   sessionKey:'51d3ed1124848' 必选 密匙，需要获得sessionKey所登录的用户信息。
	   roleId:2 必选 注册的类型，老师还是学生，学生为2，老师为3
	   资料信息
	   photo: ''// 可选 头像
	   name:'王尼玛' 可选
	   sex:0 可选 0为女，1为男
	   birthday：'2013-07-03 21:53:00' 可选
	返回
	{
	   "result": true,
	   "data": {}
	}
 * @return data
 */
function update_profile_student($sessionKey,$photo='',$name='',$sex=0,$birthday='') {
	$data='roleId=2&sessionKey='.$sessionKey.'&name='.$name.'&sex='.$sex.'&birthday='.$birthday;
	if(!empty($photo)){
		$data = $data.'&photo=@'.$photo;
	}
	$ret = curl_request(__GTAPI_BASE_URL.'app/update_profile', $data,'POST');
	return json_decode($ret);
}
/**
 * 创建账号
 * 请求地址
	   app/phone_is_exist
	请求方法
	   GET
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1001：缺省为保存时候验证出错所返回的信息。
	   1002：插入数据出错。
	   1009：手机号码已经被注册。
	   1010：你的手机号码已被管理员冻结，请联系管理员。
	参数
	   format ： xml/json 可选
	   phone ："13333333333" 必选 手机号码
	返回
	{
	   "result": true,
	   "data": []
	}
 * @return data
 */
function phone_is_exist($phone) {
	$data='phone='.$phone;
	$ret = curl_request(__GTAPI_BASE_URL.'app/phone_is_exist', $data,'GET');
	return $ret;
}
/**
 * 创建账号
 * 请求地址
	   app/create_account
	请求方法
	   POST
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1001：缺省为保存时候验证出错所返回的信息。
	   1002：插入数据出错。
	   1007：你的验证码错误。
	   1008：位置坐标信息错误，不能自动获取到地址。
	   1009：手机号码已经被注册。
	   1010：你的手机号码已被管理员冻结，请联系管理员。
	参数
	   format ： xml/json 可选
	   //创建成功后登录使用
	   deviceId ：'123243sdss434343' 必选 设备唯一ID（不能唯一的情况下，在没被卸载的情况下，设备唯一ID）
	   type ：'iphone' 可选 使用的设备( iphone,android)
	   账号创建的基本信息
	   phone: '13333333333' 必选 手机号码
	   phoneCode: '562891' 必选 手机验证码
	   password:'password' 必选 密码
	   roleId:2 必选 注册的类型，老师还是学生，学生为2，老师为3
	
	   老师学生共有的资料信息
	   photo: ''// 可选 文件
	   name:'王尼玛' 必选
	   sex:0 可选 0为女，1为男
	   birthday：'2013-07-03 21:53:00' 可选
	   college：'某某学院' 可选
 * @return data
 */
function create_account_student($phone, $password) {
	$data='deviceId=web&roleId=2&phone='.$phone.'&password='.$password.'&name='.$phone;
	$ret = curl_request(__GTAPI_BASE_URL.'app/create_account', $data,'POST');
	return $ret;
}
/**
 * 获得老师、机构或者课程视频的评论列表 
 * 请求地址
	   app/get_comments_list
	请求方法
	   GET
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
	参数
	   format ： xml/json 可选
	   commentsId:'51d636282be46' 必选 被评论的Id（老师、学生、机构、课程视频）。
	   count:"30" 可选 一页的条数，默认20
	   page:'2' 可选 请求查询的页数 默认1
	   sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
 * @return data
 */
function get_comments_list($commentsId, $sessionKey='',$count='10',$page='1') {
	$data='commentsId='.$commentsId.'&sessionKey='.$sessionKey.'&count='.$count.'&page='.$page;
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_comments_list', $data,'GET');
	//echo $ret;
	$ret = json_decode($ret);
	if($ret->result){
		return $ret->data;
	}
	return null;
}
/**
 * 获得老师或机构所有的课程
 * 请求地址
   app/get_teach_course_list
	请求方法
	   GET
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	参数
	   format ： xml/json 可选
	   userId:'51d636282be46' 必选 用户Id（老师、学生、机构），需要查看的用户ID。
	   sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
	   count:"30" 可选 一页的条数，默认20
	   page:'2' 可选 请求查询的页数 默认1
 * @return data
 */
function get_teach_course_list($userId, $sessionKey,$count='10',$page='1') {
	$data='userId='.$userId.'&sessionKey='.$sessionKey.'&count='.$count.'&page='.$page;
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_teach_course_list', $data,'GET');
	//echo $ret;
	$ret = json_decode($ret);
	if($ret->result){
		return $ret->data;
	}
	return null;
}

/**
	请求地址
	   app/get_user_info
	请求方法
	   GET
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
	   1012：不能根据UserId找到用户。
	   1013：当前用户的角色无效，非老师、学生或机构。
	参数
	   format ： xml/json 可选
	   userId:'51d636282be46' 必选 用户Id（老师、学生、机构），需要查看的用户ID。
	   sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
 * @return data
 */
function get_user_info($userId, $sessionKey='') {
	$data='userId='.$userId.'&sessionKey='.$sessionKey;
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_user_info', $data,'GET');
	//echo $ret;
	$ret = json_decode($ret);
	if($ret->result){
		return $ret->data;
	}
	return null;
}

/**
 * 手机用户登录
 * @return data
 */
function app_sign_in($loginMobile, $password) {
	$data='phone='.$loginMobile.'&password='.$password.'&deviceId='.$loginMobile;
	$ret = curl_request(__GTAPI_BASE_URL.'app/sign_in', $data,'POST');
	$ret = json_decode($ret);
	return $ret;
}
/**
 * 根据经纬度获得附近机构或老师列表信息
 * @return data
 */
function get_course_list($sessionKey, $cityId) {
	$data="sessionKey=".$sessionKey."&id=".$cityId;
	$ret = curl_request(__GTAPI_BASE_URL.'web/get_course_list', $data,'GET');
	$ret = json_decode($ret);
	if($ret->result){
		return $ret->data;
	}
	return null;
}
/**
 * 获得热点城市列表 
 */
function get_city_list() {
	$data="";
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_city_list', $data,'GET');
	return json_decode($ret);
}
/**
 * 获得热点城市下区域和商区的列表 
 */
function get_city_children_list($id) {
	$data="id=".$id;
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_city_children_list', $data,'GET');
	return json_decode($ret);
}
/**
 * 获得附近机构或老师列表信息
 * 请求地址
	   app/get_user_list
	请求方法
	   GET
	状态码
	   200: Ok
	错误码
	   1000：API 系统出现错误。
	   1006：你的sessionKey是无效或过期的，仅在传入了sessionKey的情况下才会出现。
	参数
	   format ： xml/json 可选
	   sessionKey:'51d3ed1124848' 可选 密匙，需要获得sessionKey所登录的用户信息。
	   categoryIds: '1,2,3' 可选 分类ID，搜索项，传入时根据分类进行筛选，
	        登录用户，不传入时，会根据关注分类查询，非登录用户不传入查询所有。
	   name:'1,3' 可选 老师或机构名称，搜索项
	   order:'1' 可选 查询排序方式，距离：0，评分：1，收藏数：2，评论数：3,默认根据距离排序
	   locationX:'30.549299' 必选 用户的纬度
	   locationY:'104.069734' 必选 用户的经度
	   roleId:'1' 可选 机构或老师,老师为3，机构为1
	   count:"30" 可选 一页的条数，默认20
	   page:'2' 可选 请求查询的页数 默认1
	   isMap:'0' 选填 不传递1地图查询，0查询附近的人
	   mile:'200' 可选 查询距离
	   cityId:'' 可选 查询区域
 * @return data
 */
function get_user_list($data) {
	//$data="locationX=".$locationX."&locationY=".$locationY."&roleId=".$roleId."&categoryIds=".$categoryIds."&name=".$name."&order=".$order;
	//var_dump($data);
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_user_list', $data,'GET');
	$ret = json_decode($ret);
	if($ret->result){
		return $ret->data;
	}
	return null;
}
/**
 * 用户匿名登录
 * @return obj
 */
function sign_in_anonymous() {
	$ret = curl_request(__GTAPI_BASE_URL.'app/sign_in_anonymous', 'deviceId=web','POST');
	$ret = json_decode($ret);
	if($ret->result){
		return $ret->data;
	}
	return null;
}
/**
 * 获取所有分类列表
 * @param string $sessionKey 可选，当前登录用户sessionKey,
 * @return obj
 */
function get_category_list($sessionKey='') {
	$ret = curl_request(__GTAPI_BASE_URL.'app/get_category_list?sessionKey='.$sessionKey);
	return json_decode($ret);
}
?>