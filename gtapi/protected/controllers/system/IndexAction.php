<?php
/**
 * ErrorAction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * rest转发action的测试
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: ErrorAction $
 * @package com.server.controller.system
 * @since 0.1.0
 */
class IndexAction extends BaseAction {

	public $request_method;
	/**
	 * Action to run
	 */
	public function run() {
		$method = $_SERVER['REQUEST_METHOD'];
		$parameters = $this->getRequestArray();
		if($method =='POST' && !empty($_FILES)){
			foreach ($_FILES as $key=>$value){
                            $file  = $value["tmp_name"];
                            if(!empty($file)){
                                $path = dirname($file) . '/' . $value["name"];
                                $path = iconv("UTF-8", "gb2312", $path);
                                move_uploaded_file($file, $path);
                                $parameters[$key] = "@" . $path;
                            }
			}
		}
		$url = $this->getRequest('server_url',true);
		unset($parameters['server_url']);
		$rest = RestClient::call($method,$url,$parameters);
		$this->setResponse($rest->getResponse());
		$this->sendResponse();
	}

}
