<?php
/**
 * CutImageAction class file.
 *
 * @author Qijun Chiang <QijunChiang@gmail.com>
 */

/**
 * 修改文件的大小
 * @author Qijun Chiang <QijunChiang@gmail.com>
 * @version $Id: IndexAction
 * @package com.server.controller
 * @since 0.1.0
 */
class CutImageAction extends BaseAction {

	public function index() {
		$w = 300;$h=215;
		$result = array(
			Contents::RESULT => true,
			Contents::DATA => ''
		);
		$data = $this->getData();
		$fileName = $data["fileName"];//文件名
		$point_x = $data["x"];//起始点横坐标
		$point_y = $data["y"];//起始点纵坐标
		$width = $data["width"];//剪切的宽度
		$height = $data["height"];//剪切的高度
		$percent = $data["percent"];//图片被缩放的比例
		$realWidth = $data["realWidth"];//生成图片的宽,默认300
		$realHeight = $data["realHeight"];//生成图片的高,默认215
		$point_x = !is_numeric($point_x)? 0:$point_x;
		$point_y = !is_numeric($point_y)? 0:$point_y;
		$width = !is_numeric($width) ? $w:$width;
		$height = !is_numeric($height) ? $h : $height;
		$realWidth = !is_numeric($realWidth) ? $w : $realWidth;
		$realHeight = !is_numeric($realHeight) ? $h : $realHeight;
		$percent = !is_numeric($percent) ? 1 : $percent;
		try {
			require_cache(APP_PATH.'Common/phpthumb-latest/ThumbLib.inc.php');
			$filePath = './'.Contents::SWF_UPLOAD_DIR.'/'.$fileName;
			$thumb = PhpThumbFactory::create($filePath);
			$thumb->crop(
				(float)$point_x*$percent,
				(float)$point_y*$percent,
				(float)$width*$percent,
				(float)$height*$percent
			);
			//自适应的缩放。
			$thumb->adaptiveResize($realWidth, $realHeight);
			$thumb->save($filePath,'JPG');
		}catch(Exception $e) {
			echo $e;
			$result = array(
				Contents::RESULT => false,
				Contents::DATA => '图片不存在，剪切失败'
			);
		}
		echo CJSON::encode($result);
	}
}
?>