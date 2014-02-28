<?php /* Smarty version Smarty-3.1.16, created on 2014-02-20 02:59:13
         compiled from "d:\Wnmp\html\goodteacher\gtweb\templates\user\cart\myCourseList.tpl" */ ?>
<?php /*%%SmartyHeaderCode:273507485305b5d1a4f7a9-72664839%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '09319f731a038697e8a28dd5b765fec331dd8e60' => 
    array (
      0 => 'd:\\Wnmp\\html\\goodteacher\\gtweb\\templates\\user\\cart\\myCourseList.tpl',
      1 => 1392878062,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '273507485305b5d1a4f7a9-72664839',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'courseList' => 1,
    'course' => 1,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5305b5d1b35f85_58341537',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5305b5d1b35f85_58341537')) {function content_5305b5d1b35f85_58341537($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../../header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>"好老师 www.kaopuu.com"), 0);?>

<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="banner_b"></div>
<div class="center">
	<div class="list_center">
		<?php echo $_smarty_tpl->getSubTemplate ("../my-left.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('menuId'=>'myCourse'), 0);?>

        <div class="user_r">
        	<?php echo $_smarty_tpl->getSubTemplate ("../my-top.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>'我的课程'), 0);?>

            <div class="table1">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="46" align="center" valign="middle" class="table1_t">课程名称</td>
                    <td align="center" valign="middle" class="table1_t">老师</td>
                    <td align="center" valign="middle" class="table1_t">价格</td>
                    <td align="center" valign="middle" class="table1_t">购买时间</td>
                    <td align="center" valign="middle" class="table1_t">操作</td>
                  </tr>
                  <?php  $_smarty_tpl->tpl_vars['course'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['course']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['courseList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['course']->key => $_smarty_tpl->tpl_vars['course']->value) {
$_smarty_tpl->tpl_vars['course']->_loop = true;
?>
                  <tr>
                    <td height="46" align="left" valign="middle" class="table1_g">
                    <a target='_blank' href='<?php echo @constant('__SITE_PATH');?>
/teacher.php?teacherId=<?php echo $_smarty_tpl->tpl_vars['course']->value->teachId;?>
'><?php echo $_smarty_tpl->tpl_vars['course']->value->name;?>
</a>
                    </td>
                    <td align="center" valign="middle"><?php echo $_smarty_tpl->tpl_vars['course']->value->teachName;?>
</td>
                    <td align="center" valign="middle" class="fotnt_ora">￥<?php echo $_smarty_tpl->tpl_vars['course']->value->price;?>

		        	<?php if ($_smarty_tpl->tpl_vars['course']->value->unit=='1') {?>
						/课
					<?php } elseif ($_smarty_tpl->tpl_vars['course']->value->unit=='2') {?>
						/总价
					<?php } else { ?>
						/小时
					<?php }?>
                    </td>
                    <td align="center" valign="middle"><?php echo $_smarty_tpl->tpl_vars['course']->value->teachStartDate;?>
</td>
                    <td align="center" valign="middle">
                    	<a target='_blank' href='<?php echo @constant('__SITE_PATH');?>
/teacher.php?teacherId=<?php echo $_smarty_tpl->tpl_vars['course']->value->teachId;?>
'>详情</a>
                    </td>
                  </tr>
				  <?php } ?>
                </table>
            </div>
	   	  </div>
        <div class="clear"></div>
  	</div>
</div>
<!-- InstanceEndEditable --> 
<?php echo $_smarty_tpl->getSubTemplate ("../../footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>
