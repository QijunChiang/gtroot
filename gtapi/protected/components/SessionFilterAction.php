<?php
/**
 * SessionFilterAction class file.
 */

/**
 * 用户session的检测
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SessionFilterAction $
 * @package com.server.components
 * @since 0.1.0
 */
class SessionFilterAction extends BaseAction
{
	public $userSession;
	/* (non-PHPdoc)
	 * @see CAction::__construct()
	 */
	public function __construct($controller, $id) {
		// TODO: Auto-generated method stub
		parent::__construct($controller,$id);
		$this->checkSessionKey();
	}

	/**
	 * check session is invalid.
	 */
	protected function checkSessionKey(){
		$sessionKey = $this->getRequest(Contents::KEY);
		if(Tools::isEmpty($sessionKey)){
			throw new CHttpException(1006,Contents::getErrorByCode(1006));
		}
		$this->userSession = UserSession::model()->getSessionByKey($sessionKey);
		if(!$this->userSession){
			throw new CHttpException(1006,Contents::getErrorByCode(1006));
		}
	}

}