<?php return array(
	//'配置项'=>'配置值'
	//项目设定
    'APP_STATUS' 			=> 'debug',									// 应用调试模式状态
	//SESSION设置
	'SESSION_AUTO_START' 	=> true,									// 是否自动开启Session
	//模板引擎设置
	'TMPL_CONTENT_TYPE'     => 'text/html',								// 默认模板输出类型
	'TMPL_TEMPLATE_SUFFIX'  => '.html',									// 默认模板文件后缀
	//URL设置
	'URL_CASE_INSENSITIVE'  => true,       								// 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL'=>2,           											// URL访问模式,可选参数0、1、2、3,代表四种模式
	'URL_HTML_SUFFIX'       => '.html',          						// URL伪静态后缀设置
	//错误设置
	'ERROR_MESSAGE'         => '您浏览的页面暂时发生了错误！请稍后再试～',	//错误显示信息,非调试模式有效
	'ERROR_PAGE'            => '',										// 错误定向页面
	'SHOW_ERROR_MSG'        => true,    								// 是否显示具体错误信息
	//分组配置
	'DEFAULT_GROUP'         => 'Auth',  								// 默认分组
	'DEFAULT_MODULE'        => 'Login', 						// 默认模块名称
	'DEFAULT_ACTION'        => 'login_view', 								// 默认操作名称
	'APP_GROUP_LIST'	=> 'Auth,Category,User,Video,Agency,Course,Upload,Range,Profile,Comment,Message,Notice,AppEdtion,Log,Index,Region,Place,Feedback',      						// 项目分组设定,多个组之间用逗号分隔,例如'Home,Admin'
	'URL_ROUTER_ON'         => true,        							// 是否开启URL路由
	//扩展配置
	'LOAD_EXT_CONFIG' => 'urlManager,contents',

	'APP_AUTOLOAD_PATH' => '@/Form/'
);
?>