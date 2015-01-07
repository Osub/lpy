<?php

$themename = '觉唯主题';

$options = array (

	//基本设置
	array( "name" => "基本设置",
	       "type" => "section",
		   "desc" => "主题的基本设置，包括模块是否开启等"),
	
	array( "name" => "网站样式风格",
	       "type" => "tit"),	   
	array( "id" => "javin_style_sheet",
		   "type" => "select", 
		   "options" => array("红色", "蓝色"), 
		   "std" => "红色"),
	
	array( "name" => "幻灯片开启",
	       "type" => "tit"),	   
	array( "id" => "javin_slider_epen",
	       "type" => "checkbox"),
		   
    array( "name" => "幻灯片图片个数",
	       "type" => "tit"),	   
	array( "id" => "javin_slider_num",
	       "type" => "text",
		   "class" => "javin_text",
		   "std" => "5"),
		   
    array( "name" => "幻灯片切换效果",
	       "type" => "tit"),	   
	array( "id" => "javin_slider_effect",
		   "type" => "select", 
		   "options" => array("random", "fade", "fold", "sliceDownRight", "sliceDownLeft", "sliceUpRight", "sliceUpLeft", "sliceUpDown", "sliceUpDownLeft", "boxRandom","boxRain","boxRainReverse","boxRainGrow","boxRainGrowReverse"), 
		   "std" => "random"),
		   
    array( "name" => "幻灯片切换间隔（毫秒）",
	       "type" => "tit"),	   
	array( "id" => "javin_slider_pausetime",
	       "type" => "text",
		   "class" => "javin_text",
		   "std" => "3000"),
    
	array( "name" => "文章全文预览",
	       "type" => "tit"),	   
	array( "id" => "javin_post_allview_button",
	       "type" => "checkbox" ), 
		   
    array( "name" => "开启手动翻页",
	       "type" => "tit"),	   
	array( "id" => "javin_page_nav_button",
	       "type" => "checkbox" ),   
    
	array( "name" => "网站投稿地址",
	       "type" => "tit"), 
	array( "id" => "javin_contribute",
	       "type" => "text",
		   "class" => "javin_text",
		   "std" => "http://www.jiawin.com/contribute"),
	
	array( "name" => "RSS订阅地址",
	       "type" => "tit"), 
	array( "id" => "javin_rss",
	       "type" => "text",
		   "class" => "javin_text",
		   "std" => "http://www.jiawin.com/feed/"),

	array( "name" => "腾讯QQ",
	       "type" => "tit"),
	array( "id" => "javin_qq",
	       "type" => "text",
		   "class" => "javin_text",
		   "std" => "http://sighttp.qq.com/authd?IDKEY=efa0a34058fe927553ed27a6b55d34806e03f0889e67827b"),

	array( "name" => "腾讯微博",
	       "type" => "tit"),
	array( "id" => "javin_qq_weibo",
	       "type" => "text",
		   "class" => "javin_text",
		   "std" => "http://t.qq.com/jiawin-com"),

	array( "name" => "新浪微博",
	       "type" => "tit"),   
	array( "id" => "javin_weibo",
	       "type" => "text",
		   "class" => "javin_text",
		   "std" => "http://weibo.com/javinblog"),
	
	array( "name" => "流量统计代码",
	       "type" => "tit"),	   
	array( "id" => "javin_track_button",
	       "type" => "checkbox" ),   
	array( "id" => "javin_track",
	       "type" => "textarea",
		   "std" => "请输入谷歌统计、百度统计等统计代码"),
		   
    array( "name" => "关于我",
	       "type" => "tit"), 
	array( "id" => "javin_about",
	       "type" => "text",
		   "class" => "javin_text",
		   "std" => "推崇以用户为中心的设计理念，专注于用户体验设计，游走在视觉设计与前端开发之间。<br />——小小的一名视觉设计师"),

	array( "type" => "endtag"),

	//广告系统
	array( "name" => "广告系统",
	       "type" => "section",
		   "desc" => "站点的广告展示，包括自定义图片广告、Google广告、百度联盟、淘宝联盟等，将代码贴入即可"),	
	
	array( "type" => "table-start"),
		   
	array( "name" => "全站顶部",
	       "type" => "tit"),
	array( "id" => "javin_adall_top_button",
	       "type" => "checkbox" ),
	array( "id" => "javin_adall_top",
	        "type" => "textarea"),

	array( "name" => "全站底部",
	       "type" => "tit"),
	array( "id" => "javin_adall_bottom_button",
	       "type" => "checkbox" ),
	array( "id" => "javin_adall_bottom",
	       "type" => "textarea"),

	array( "name" => "文章页顶部",
	       "type" => "tit"),
	array( "id" => "javin_adpost_top_button",
	       "type" => "checkbox" ),
	array( "id" => "javin_adpost_top",
	       "type" => "textarea"),
		   
	array( "name" => "文章页底部",
	       "type" => "tit"),
	array( "id" => "javin_adpost_bottom_button",
	       "type" => "checkbox" ),
	array( "id" => "javin_adpost_bottom",
	       "type" => "textarea"),
	
	array( "type" => "table-end"),
		   
    array( "type" => "table-start"),

	array( "name" => "侧边栏小工具-01",
	       "type" => "tit"),
	array( "id" => "javin_adwidget_01_button",
	       "type" => "checkbox" ),
	array( "id" => "javin_adwidget_01",
	       "type" => "textarea",
		   "std" => "<a title='唯然 - 觉唯图片分享社区' target='_blank' href='http://www.jiawin.com/love/'><img alt='唯然 - 觉唯图片分享社区' src='http://www.jiawin.com/wp-content/uploads/recommend/weiran.jpg' height='110' width='279' /></a>"),
		   
    array( "name" => "侧边栏小工具-02",
	       "type" => "tit"),
	array( "id" => "javin_adwidget_02_button",
	       "type" => "checkbox" ),
	array( "id" => "javin_adwidget_02",
	       "type" => "textarea"),

    array( "name" => "侧边栏小工具-03",
	       "type" => "tit"),
	array( "id" => "javin_adwidget_03_button",
	       "type" => "checkbox" ),
	array( "id" => "javin_adwidget_03",
	       "type" => "textarea"),

    array( "name" => "侧边栏小工具-04",
	       "type" => "tit"),
	array( "id" => "javin_adwidget_04_button",
	       "type" => "checkbox" ),
	array( "id" => "javin_adwidget_04",
	       "type" => "textarea"),

    array( "name" => "侧边栏小工具-05",
	       "type" => "tit"),
	array( "id" => "javin_adwidget_05_button",
	       "type" => "checkbox" ),
	array( "id" => "javin_adwidget_05",
	       "type" => "textarea"),
	
	array( "name" => "侧边栏小工具-示例",
	       "type" => "tit"),
	array( "id" => "javin_adwidget_eg_button",
	       "type" => "checkbox" ),
	array( "id" => "javin_adwidget_eg",
	       "type" => "textarea",
		   "std" => "<a href='#' title='Advertise Here'>Advertise Here</a>"),
	
	array( "type" => "table-end"),

	array( "type" => "endtag"),

);

function mytheme_add_admin() {
	global $themename, $options;
	if ( $_GET['page'] == basename(__FILE__) ) {
		if ( 'save' == $_REQUEST['action'] ) {
			foreach ($options as $value) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
			}
			foreach ($options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); }
				else { delete_option( $value['id'] ); } 
			}
			header("Location: admin.php?page=theme-settings.php&saved=true");
			die;
		}
		else if( 'reset' == $_REQUEST['action'] ) {
			foreach ($options as $value) {delete_option( $value['id'] ); }
			header("Location: admin.php?page=theme-settings.php&reset=true");
			die;
		}
	}
	add_menu_page($themename."设置", $themename."设置", 'edit_themes', basename(__FILE__), 'mytheme_admin');
}

function mytheme_admin() {
	global $themename, $options;
	$i=0;
?>

<div class="wrap javin_wrap">
	<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/inc/styles/theme_settings.css"/>
	<h2><?php echo $themename; ?>设置<span class="javin_port_btn"></span></h2>
    <p class="javin_themedesc">当前版本：觉唯 1.3 &nbsp;&nbsp; 作者：<a href="http://www.jiawin.com/" target="_blank">Javin</a>&nbsp;&nbsp;&nbsp;更新：<a href="http://www.jiawin.com/javin-theme-sale/" target="_blank">查看版本以及常见问题</a></p>
    <?php
	if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p>'.$themename.'修改已保存</p></div>';
	if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p>'.$themename.'已恢复设置</p></div>';
	?>
    <div id="icon-themes" class="icon32"><br></div>
    <h2 class="nav-tab-wrapper"><a class="nav-tab nav-tab-active">基本设置</a><a class="nav-tab">广告系统</a></h2>
	<div id="">
    <form method="post">
		<?php foreach ($options as $value) { switch ( $value['type'] ) { case "": ?>
            
            <?php break; case "section": $i++; ?>
			<div class="javin_box" id="javin_box_<?php echo $i; ?>">
		    <div class="javin_nav_title" id="nav-menu-header"><input style="margin-top:6px;" class="button-primary" name="save<?php echo $i; ?>" type="submit" value="保存设置" /><?php echo $value['desc']; ?></div>
            <table class="form-table"><tbody>
        
			<?php break; case "tit": ?>
            <tr>
			<th><?php echo $value['name']; ?>：</th>
			
			<?php break; case 'text': ?>
            <td>
			<?php if ( $value['desc'] != "") { ?><label class="javin_the_desc"><?php echo $value['desc']; ?></label><?php } ?><input class="<?php echo $value['class']; ?>" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" size="42" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])  ); } else { echo $value['std']; } ?>" />
            </td>
			
            <?php break; case "checkbox": ?>
            <td>
			<?php if(get_settings($value['id']) != ""){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
			<p><label class="javin_checkbox"><input type="checkbox" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" <?php echo $checked; ?> />开启</label></p>
            
			<?php break; case 'textarea': ?>
			<textarea class="javin_textarea" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="60" rows="5"><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?></textarea>
            <div class="javin_ads_view"></div>
            </td>
			
			<?php break; case 'select': ?>
            <td>
			<?php if ( $value['desc'] != "") { ?><span class="javin_the_desc" id="<?php echo $value['id']; ?>_desc"><?php echo $value['desc']; ?></span><?php } ?><select class="javin_select" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
				<?php foreach ($value['options'] as $option) { ?>
				<option <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected" class="javin_select_opt"'; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
            </td>
            
            <?php break; case "table-start": ?>
			<td valign="top"><table><tbody>
            
            <?php break; case "table-end": ?>
			</tbody></table></td>
            				
			<?php break; case "endtag": ?>
			</tr></tbody></table>
			<div style="border-top-color:#dfdfdf;" class="javin_nav_footer" id="nav-menu-footer"><input style="margin-top:6px;" class="button-primary" name="save<?php echo $i; ?>" type="submit" value="保存设置" /></div>
			</div>
			
		<?php break; }} ?>
				
		<input type="hidden" name="action" value="save" />
		<div class="javin_popup javin_export">
			<h3><input style="margin:3px 5px;" class="button-primary" type="button" value="关闭" /><?php echo $themename; ?>设置-导出：</h3>
			<h4>妥善保管好您导出的数据，否则您就要一条条的添加！</h4>
			<p style="padding:0; margin:0;"><textarea onmouseover="this.focus();this.select();" disabled="true" name="" id="" cols="30" rows="10"></textarea></p>
		</div>
		<div class="javin_popup javin_import">
			<h3><input style="margin:3px 5px;" class="button-primary" type="button" value="立即导入" /><?php echo $themename; ?>设置-导入：</h3>
			<h4>贴入您之前保存的导出数据，点击"立即导入"，确定导入成功后再保存！</h4>
			<p style="padding:0; margin:0;"><textarea onmouseover="this.focus();this.select();" name="" id="" cols="30" rows="10"></textarea></p>
		</div>
	</form>
    <p>支持这个主题，欢迎捐助我：</p>
    <p><a href='http://me.alipay.com/jiawin'> <img src='<?php bloginfo( 'template_url' ); ?>/images/pay_donated.png' /> </a></p>
    <p><?php echo $themename; ?> Design by <a href="http://www.jiawin.com/">www.jiawin.com</a> | <a href="http://www.jiawin.com">觉唯版权所有!</a> 请尊重设计师的劳动，请在主题下方保留版权信息。 </p>
    </div>
<script src="<?php bloginfo('template_url') ?>/js/jquery.js"></script>
<script src="<?php bloginfo('template_url') ?>/inc/js/theme_settings.js"></script>
</div>
<?php } ?>
<?php add_action('admin_menu', 'mytheme_add_admin');?>