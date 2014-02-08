<?php

/**
 * LoginForm class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 登录表单 规则验证
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SignIn
 * @package com.server.model.auth
 * @since 0.1.0
 */
class LoginForm extends Form{

	protected $_validate = array(
		array('username', 'require', '账号必须填写！'), //默认情况下用正则进行验证
		array('password', 'require', '密码必须填写'), // 在新增的时候验证name字段是否唯一
		array('username', 'length_between', '账号只能是6-20位之间！', 6,20), // 验证账号长度
		array('password', 'length_between', '密码只能是6-20位之间！', 6,20), // 验证密码长度
	);
}
?>