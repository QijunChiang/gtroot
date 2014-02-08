<?php
/**
 * CheckVersionAction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 检测应用版本更新
 * <pre>
 * 请求地址
 *    app/check_version
 * 请求方法
 *    POST
 * 状态码
 *    200: Ok
 * 错误码
 *    999：参数丢失。
 *    1000：API 系统出现错误。
 *    1001：缺省为保存时候验证出错所返回的信息。
 *    1002：插入数据出错。
 *    1006：你的sessionKey是无效或过期的。
 *    1033: 没有最新的版本。
 * 参数
 *    format ： xml/json 可选
 *    type：'1' 必选 类型,android：1
 *    versionCode: "11",必选 版本编号，每个版本，此号码必须增加
 * 返回
 *{
 *    "result": true,
 *    "data": {
 *       "id": "3",
 *       "packageLink": "upload/higgses/andorid/ssadad.apk",//包路径
 *       "downLink": "upload/higgses/andorid/ssadad.apk",//下载相对路径
 *       "type": "1",
 *       "versionCode": "3",
 *       "versionName": "1.0.3v",
 *       "createTime": "1989-01-01 01:01:01"
 *    }
 *}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: CheckVersionAction
 * @package com.server.controller.system.higgses
 * @since 0.1.0
 */
class CheckVersionAction extends BaseAction{
    /**
     * Action to run
     */
    public function run(){
		$versionCode = $this->getRequest("versionCode",true);
		$type = $this->getRequest("type",true);
		$data = array();
		$higgsesApp = HiggsesApp::model()->getNewVersion($versionCode,$type);
		if($higgsesApp){
			$data['id'] = $higgsesApp->id;
			$data['packageLink'] = $higgsesApp->packageLink;
			$data['downLink'] = $higgsesApp->downLink;
			$data['type'] = $higgsesApp->type;
			$data['versionCode'] = $higgsesApp->versionCode;
			$data['versionName'] = $higgsesApp->versionName;
			$data['createTime'] = $higgsesApp->createTime;
			$this->addResponse(Contents::RESULT,true);
			$this->addResponse(Contents::DATA,$data);
			$this->sendResponse();
		}else{
			throw new CHttpException(1033,Contents::getErrorByCode(1033));
		}
    }
}
