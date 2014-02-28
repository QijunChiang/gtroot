<?php /* Smarty version Smarty-3.1.16, created on 2014-02-28 09:24:46
         compiled from "D:\xampp2\htdocs\gtweb\templates\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12727531047ce08de14-35967983%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aa90b95067dbae8a363a943c41e29d1d49628227' => 
    array (
      0 => 'D:\\xampp2\\htdocs\\gtweb\\templates\\index.tpl',
      1 => 1393340054,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12727531047ce08de14-35967983',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ios_download' => 1,
    'android_download' => 1,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_531047ce107c15_81591638',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_531047ce107c15_81591638')) {function content_531047ce107c15_81591638($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link href="<?php echo @constant('__SITE_PATH');?>
/css/jquery.jslides.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.jslides.js"></script>
<!-- InstanceBeginEditable name="EditRegion3" --> 
<!--banner-->
<div class="banner">
  <div id="full-screen-slider">
    <ul id="slides" class="slides">
      <li style="background:url('images/02.jpg') no-repeat center top"></li>
      <li style="background:url('images/01.jpg') no-repeat center top">
        <div class="banner_in">
          <div class="aio"><img src="images/all_in_one.png" width="391" height="115"></div>
          <div class="aio_b"> "好老师"应用软件,是中国领先的移动家教平台该平台录入了各行业优秀的教师资源,解决了用户找家教的难题。目前公司已经获得了天使投资。 </div>
          <div class="app_dl"> 
          	<a href="<?php echo $_smarty_tpl->tpl_vars['ios_download']->value;?>
"><img src="images/apple.png" width="161" height="48"></a>　　　 
          	<a href="<?php echo $_smarty_tpl->tpl_vars['android_download']->value;?>
"><img src="images/android.png" width="161" height="48"></a> 
          </div>
          <div id="eqc" class="ewm">
            <div id="dimBigCode" class="dimensionalBigCode"><a href="javascript:;" target="_self" class="close"></a><img src="./images/ewm2.png"  width="300" height="300"></div>
            <div class="ewm_tl"><a href="javascript:;" target="_self" onClick="$('#dimBigCode').fadeIn(200);return false;"><img src="images/ewm2.png" width="71" height="71"></a></div>
            <div class="ewm_tr">扫描左侧二维码下载</div>
            <div class="clear"></div>
            <div class="ewm_bl"><a id="codeLink" href="javascript:" target="_self" onClick="$('#dimBigCode').fadeIn(200);return false;">点击放大</a></div>
            <div class="ewm_br">或 直接访问 : 
            	<a href="<?php echo $_smarty_tpl->tpl_vars['ios_download']->value;?>
" style="color:#FFF;">iOS版本下载</a>&nbsp;&nbsp;&nbsp;&nbsp;
            	<a href="<?php echo $_smarty_tpl->tpl_vars['android_download']->value;?>
" style="color:#FFF;">Android版本下载</a>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript">


</script><?php }} ?>
