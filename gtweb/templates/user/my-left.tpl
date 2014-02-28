		<div class="user_l">
        	<div class="circle" style="width:122px; height:122px; background:url(<{$__GTAPI_BASE_URL|cat:$userProfile->photo}>); margin:0 auto; "></div>
            <div class="user_name">
            	<img src="<{$smarty.const.__SITE_PATH}>/images/sex.gif" width="11" height="11"> <{$userProfile->name}>
            </div>
            <!--<div class="user_time">上次登录: 2014-01-21</div>-->
            <div class="user_select">
            	<ul>
                	<li class="<{if $menuId=='myInfo'}>now<{/if}>">
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot1.gif" width="18" height="18"></em>
                        <span><a href="<{$smarty.const.__SITE_PATH}>/user/my.php">个人信息</a></span>
                    </li>
                    <li class="<{if $menuId=='myCourse'}>now<{/if}>">
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot2.gif" width="18" height="18"></em>
                        <span><a href="<{$smarty.const.__SITE_PATH}>/user/myCourse.php">我的课程</a></span>
                    </li>
                    <li class="<{if $menuId=='myOrder'}>now<{/if}>">
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot2.gif" width="18" height="18"></em>
                        <span><a href="<{$smarty.const.__SITE_PATH}>/user/myOrder.php">我的订单</a></span>
                    </li>
                    <li class="<{if $menuId=='modifypwd'}>now<{/if}>">
                    	<em><img src="<{$smarty.const.__SITE_PATH}>/images/user_dot3.gif" width="18" height="18"></em>
                        <span><a href="<{$smarty.const.__SITE_PATH}>/user/my.php?action=modifypwd">修改密码</a></span>
                    </li>
                </ul>
            </div>
        </div>
