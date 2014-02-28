<?php
 /**
 * Example Application
 * @package Example-application
 */
require_once './configs/smarty-config.php';

$smarty->assign("ios_download", "https://itunes.apple.com/us/app/hao-lao-shi/id717061931?ls=1&mt=8", true);
$smarty->assign("android_download", "http://www.kaopuu.com/gt/GoodTeacher.apk", true);

$smarty->display('index.tpl');
?>