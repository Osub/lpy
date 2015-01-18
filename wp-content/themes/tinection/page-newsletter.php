<!DOCTYPE html>
<?php
/*
Template Name: Newsletter
*/
?>
<?php
/**
 * Main Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.0
 * @date      2014.12.11
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014-2015, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<?php 
$issue_meta = get_tin_meta('issue') ? get_tin_meta('issue') : '';
$issue_meta_array =explode(',',$issue_meta);
$length = count($issue_meta_array);
if(isset($_GET['special'])&&!empty($_GET['special'])){
	$special = $_GET['special'];
	$title = __('专刊速递(No.','tinection').$special.')';
	$content = newsletter_posts_special($special);	
}elseif(isset($_GET['issue'])&&!empty($_GET['issue'])){
	$issue = $_GET['issue'];
	$title = __('邮件周刊(No.','tinection').$issue.')';
	$content = tin_past_issues_selection();
	$content .= newsletter_posts_issue($issue);
}elseif(isset($_GET['action'])&&($_GET['action']=='unsubscribe')&&(!isset($_GET['nonce']))){
	$title = __('退订','tinection');
	$content = newsletter_unsubscribe_template();
}elseif(isset($_GET['action'])&&($_GET['action']=='unsubscribe')&&(isset($_GET['email']))&&(isset($_GET['nonce']))){
	$title = __('邮件订阅','tinection');
	$meta_key = 'unsubscribe_'.$_GET['email'];
	$nonce = get_tin_meta($meta_key) ? get_tin_meta($meta_key) : '';
	if($nonce==$_GET['nonce']){
		delete_tin_meta($meta_key);
		//删除用户邮箱
		$tin_dlusers = get_tin_meta('tin_dlusers');
		$tin_dlusers = tin_delete_string_specific_value(',',$tin_dlusers,$_GET['email']);
		update_tin_meta('tin_dlusers',$tin_dlusers);
		$tin_subscribers = get_tin_meta('tin_subscribers');
		$tin_subscribers = tin_delete_string_specific_value(',',$tin_subscribers,$_GET['email']);
		update_tin_meta('tin_subscribers',$tin_subscribers);
		$content = '<div style="min-height:800px;">'.__('退订成功!','tinection').'</div>';
	}else{$content = '<div style="min-height:800px;">'.__('退订失败，你可能已经退订，或请重新再试!','tinection').'</div>';}	
}elseif(isset($_GET['action'])&&($_GET['action']=='subscribe')){
	$title = __('邮件订阅','tinection');
	$content = newsletter_subscribe_template();
}else{
	$issue = $length;
	$title = __('邮件周刊(No.','tinection').$issue.')';
	$content = tin_past_issues_selection();
	$content .= newsletter_posts_issue($issue);
}
?>
<html>
<title><?php echo $title; ?> - <?php bloginfo('name'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- Main Wrap -->
<div id="main-wrap" style="padding-top:20px;padding-bottom:20px;">
	<?php echo $content; ?>
</div>
<!--/.Main Wrap -->
<!-- Bottom Banner -->
<?php $singlebottomad=ot_get_option('singlebottomad');if (!empty($singlebottomad)) {?>
<div id="singlebottom-banner">
	<?php echo $singlebottomad;?>
</div>
<?php }?>
<!-- /.Bottom Banner -->
<!-- Footer Nav Wrap -->
<div id="footer-nav-wrap">
	<div id="footer-nav" class="layout-wrap">
		<div id="footer-nav-left">
			<!-- Footer Nav -->
			<?php wp_nav_menu(array('theme_location'=>'footbar','container_id'=>'footermenu','menu_class'=>'footermenu','menu_id' => 'footer-nav-links', 'depth'=> '1','fallback_cb'=> '')); ?>

			<!-- /.Footer Nav -->

			<!-- Copyright -->
			<div id="footer-copyright">&copy;<?php echo date(' Y '); ?>
				<?php if(ot_get_option('copyright')) echo ot_get_option('copyright'); ?>&nbsp;|&nbsp;Theme by&nbsp;
				<a href="http://www.zhiyanblog.com/tinection.html"  target="_blank">Tinection</a>.&nbsp;|&nbsp;
				<?php if(ot_get_option('statisticcode')) echo ot_get_option('statisticcode'); ?>&nbsp;|&nbsp;
			<?php if(ot_get_option('beian')) echo '<a href="http://www.miitbeian.gov.cn/" target="_blank">'.ot_get_option('beian').'</a>'; ?>
			
			</div>
			<!-- /.Copyright -->
		</div>
		<div id="footer-nav-right">
			<?php get_template_part('includes/footer-user'); ?>
		</div>
	</div>
	
</div>
<div class="floatbtn">
<!-- Share -->
<span id="bdshare" class="bdshare_t"><a class="bds_more more-btn share-btn" href="#"><i class="fa fa-share-alt"></i></a></span>
<!-- /.Share -->
<!-- QR -->
<span id="qr"><i class="fa fa-qrcode"></i>
	<div id="floatbtn-qr">
		<div id="floatbtn-qr-msg"><?php _e('扫一扫二维码分享','tinection'); ?></div>
	</div>
</span>
<!-- /.QR -->
<!-- Simplified or Traditional -->
<span id="zh-cn-tw"><i><a id="StranLink"><?php _e('繁','tinection'); ?></a></i></span>
<!-- /.Simplified or Traditional -->
<!-- Back to Home -->
<?php if(!is_home()){ ?>
<span id="back-to-home">
		<a href="<?php echo get_settings('home'); ?>"><i class="fa fa-home"></i></a>
</span>
<?php } ?>
<!-- /.Back to Home -->
<!-- Scroll Top -->
<span id="back-to-top"><i class="fa fa-arrow-up"></i></span>
<!-- /.Scroll Top -->
</div>

<!-- /.Footer Nav Wrap -->
<script type="text/javascript">
/* <![CDATA[ */
var tin = {"ajax_url":"<?php echo admin_url( '/admin-ajax.php' ); ?>","tin_url":"<?php echo get_bloginfo('template_directory'); ?>","Tracker":<?php echo json_encode(tin_tracker_param()); ?>,"home":"<?php echo get_bloginfo('home'); ?>"};
/* ]]> */
</script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/lazyload.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/theme.js"></script>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style.css" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/includes/css/responsive.css"  />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/fonts/font-awesome/font-awesome.css"  media="all" />
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/zh-cn-tw.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/includes/js/pirobox.js"></script>
<script type="text/javascript">var defaultEncoding = 0; var translateDelay = 100; var cookieDomain = "<?php echo get_settings('home'); ?>";</script>
<!-- 百度分享 -->
<script type="text/javascript" id="bdshare_js" data="type=tools&mini=2"></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
		//在这里定义bds_config
		var bds_config = {'snsKey':{'tsina':'2884429244','tqq':'101166664'}};
		document.getElementById('bdshell_js').src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000);
</script>
</html>