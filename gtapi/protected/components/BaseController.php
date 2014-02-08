<?php
/**
 * BaseController class file.
 */

/**
 * This BaseController Extends CController. this controller have a filter to save interface Log.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: BaseController $
 * @package com.server.components
 * @since 0.1.0
 */
class BaseController extends CController
{

	/* (non-PHPdoc)
	 * @see CController::filters()
	 */
	public function filters() {
		// TODO: Auto-generated method stub
		return array(
				'accessControl', // perform access control for CRUD operations
				'saveInterfaceLog',
		);
	}


	/**
	 * 添加事务支持，所有方法均含有事务
	 * save interface log.
	 * @param $filterChain
	 */
	public function FilterSaveInterfaceLog($filterChain) {
			$log = new InterfaceLog();
			$log->id = uniqid();
			$log->interface = Yii::app()->request->url;
			$log->parameter = CJSON::encode(BaseAction::getData());
			$log->isError = Contents::F;
			$log->runTime = date(Contents::DATETIME);
			try {
				//开启事务
				$transaction = Yii::app()->db->beginTransaction();
				try {
					$filterChain->run();
					//提交执行
					$transaction->commit();
				} catch (Exception $ea) {
					//出错，回滚数据
					$transaction->rollback();
					throw $ea;
				}
				$log->info = "success";
				$this->saveLog($log);
			}catch (CHttpException $e1) {
				$log->info = $e1->getMessage();
				$this->saveLog($log);
				throw $e1;//custom error
			}catch (Exception $e) {
				$log->isError = Contents::T;
				$log->info = $e->getMessage();
				$this->saveLog($log);
				throw new CHttpException(1000,Contents::getErrorByCode(1000)); //system error
			}
	}

	/**
	 * 写入接口调用日志
	 * @param InterfaceLog $log
	 */
	private function saveLog(InterfaceLog $log){
		//开启事务
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$log->save();
			//提交执行
			$transaction->commit();
		} catch (Exception $ea) {
			//出错，回滚数据
			$transaction->rollback();
		}
	}

}