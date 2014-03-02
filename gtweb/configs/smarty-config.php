<?php session_start(); ?>
<?php
 /**
 * Example Application
 * @package Example-application
 */
define('__SITE_ROOT', 'd:/Wnmp/html/gtroot/gtweb'); // 最后没有斜线D:/xampp2/htdocs/gtweb,d:/Wnmp/html/goodteacher/gtweb
define('__SITE_PATH', '/gtweb');
define('__SITE_DOMAIN', 'http://'.$_SERVER['HTTP_HOST']);//'http://www.kaopuu.com'
define('__UPLOAD_PATH', __SITE_ROOT.'/upload/'); // 上传文件路径

define('__GTAPI_BASE_URL', 'http://www.kaopuu.com/gtapi/'); //api接口BASE_URL,localhost:81
define('__CATEGORY_LIST', '__CATEGORY_LIST');
define('__CURR_CATEGORY_ID', '__CURR_CATEGORY_ID');
define('__CURR_CATEGORY_NAME', '__CURR_CATEGORY_NAME');
define('__CITY_LIST', '__CITY_LIST');
define('__CURR_CITY_ID', '__CURR_CITY_ID');
define('__CURR_CITY_NAME', '__CURR_CITY_NAME');
define('__CURR_USER_INFO', '__CURR_USER_INFO');
define('__CURR_USER_INFO_DETAIL', '__CURR_USER_INFO_DETAIL');
define('__IS_SIGN_IN_ANONYMOUS', '__IS_SIGN_IN_ANONYMOUS');//是否匿名登录,是true,否false
define('__IS_YES', 'YES');
define('__IS_NO', 'NO');
define('__USER_CART', '__USER_CART');

define('__DEFAULT_CITY_ID', '527dfbd92e343');
define('__DEFAULT_CATEGORY_ID', '');
define('__DEFAULT_CATEGORY_NAME', '所有分类');


require_once __SITE_ROOT.'/smarty-lib/Smarty.class.php';
require_once __SITE_ROOT.'/includes/functions.php';
require_once __SITE_ROOT.'/includes/gtapi.php';

$smarty = new Smarty;
$smarty->force_compile = true;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 30;
$smarty->template_dir = __SITE_ROOT . "/templates/";
$smarty->compile_dir = __SITE_ROOT . "/templates_c/";
$smarty->config_dir = __SITE_ROOT . "/configs/";
$smarty->cache_dir = __SITE_ROOT . "/cache/";
$smarty->left_delimiter = '<{';
$smarty->right_delimiter = '}>';

$smarty->assign("__GTAPI_BASE_URL", __GTAPI_BASE_URL);

require_once __SITE_ROOT.'/includes/baseIn.php';
?>