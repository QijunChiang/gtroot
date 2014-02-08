<?php

/**
 * LoginForm class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 添加学生表单 规则验证
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SignIn
 * @package com.server.model.auth
 * @since 0.1.0
 */
class StuForm extends Form{

	protected $_validate = array(
		array('phone', 'require', '手机号码必须填写！'), //默认情况下用正则进行验证
		array('password', 'require', '密码必须填写'), // 是否唯一
		array('sex', 'require', '性别必须填写'), // 是否唯一
		array('name', 'require', '姓名必须填写'), // 是否唯一
		array('birthday', 'require', '生日必须填写'), // 是否唯一
		array('college', 'require', '所在学校必须填写'), // 是否唯一
		array('birthday', 'reg', '生日的格式有误','/^\d{4}-\d{2}-\d{2}$/'), // 时间 YYYY-MM-DD
		array('sex', 'number', '你所选择的性别有误'),
		array('password', 'length_between', '密码只能是6-20位之间！', 6,20), // 验证密码长度
	);

	protected $_validate_update = array(
		array('birthday', 'reg', '生日的格式有误','/^\d{4}-\d{2}-\d{2}$/'), // 时间 YYYY-MM-DD
		array('sex', 'number', '你所选择的性别有误'),
		array('password', 'length_between', '密码只能是6-20位之间！', 6,20), // 验证密码长度
	);
}
?>