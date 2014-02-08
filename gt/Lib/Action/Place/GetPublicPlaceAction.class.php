<?php

/**
 * LoginAction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 用户登录
 * <pre>
 * 请求地址
 *    auth/login
 * 请求方法
 *    POST
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: SignIn
 * @package com.server.controller.app.auth
 * @since 0.1.0
 */
class GetPublicPlaceAction extends BaseAction {
    /**
     * 获取行政区域列表
     */
    public function getRegionsList() {
        $this->sendRequest(Contents::GET, 'get_city_list');
        //输出结果json
        $this->echoResult();
    }
}
