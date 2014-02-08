<?php
/**
 * CheckVersionAction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 检测应用版本更新
 *
 * <pre>
 * REST URL
 * 		index.php/app/check_version
 * METHOD
 * 		POST
 * STATS CODE
 * 		200: Ok
 * PARAMS
 * 		{}
 * </pre>
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: CheckVersionAction
 * @package com.server.controller.app
 * @since 0.1.0
 */
class CheckVersionAction extends BaseAction{
    /**
     * Action to run
     */
    public function run(){
        $higgsesApp=HiggsesApp::model()->getNewVersion();
        $this->addResponse("higgsesApp", $higgsesApp);
        $this->sendResponse();
    }
}
