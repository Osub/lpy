<?php
/*
template name: Gallery
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
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!-- 引入页面描述和关键字模板 -->
<title>Gallery - <?php bloginfo('name'); ?></title>
<meta name="description" content="<?php bloginfo('name'); ?><?php _e('文章缩略图相册','tinection'); ?>">
<meta name="keywords" content="<?php _e('Gallery,相册,缩略图,','tinection'); ?><?php bloginfo('name'); ?>">
<!-- 网站图标 -->
<?php if ( ot_get_option('favicon') ): ?>
<link rel="shortcut icon" href="<?php echo ot_get_option('favicon'); ?>" />
<link rel="icon" href="<?php echo ot_get_option('favicon'); ?>" />
<!-- <link rel="shortcut icon" href="favicon.ico" /> -->
<?php endif; ?>
<link rel="shortcut icon" href="favicon.ico" />
<!-- 禁止浏览器初始缩放 -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!-- 引入主题样式表 -->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style.css"  />
<!-- 引入主题响应式样式表-->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/includes/css/responsive.css"  />
<!-- 引入相册样式表-->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/includes/css/sgallery.css"  />
<!-- 引入字体样式表-->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/fonts/font-awesome/font-awesome.css"  media="all" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!-- 引入jquery -->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/jquery.min.js"></script>
<!-- IE Fix for HTML5 Tags -->
  <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/includes/js/html5.js"></script>
  <![endif]-->
<?php wp_head(); ?>

</head>
<body style="background:#111;color:#fff;">
<h1 style="margin: 40px; font: 32px Microsoft Yahei; text-align: center;color:#fff;"><?php bloginfo('name'); ?> - Gallery</h1>
<?php wp_reset_query(); ?>
<?php $query1 = new WP_Query('meta_key=_thumbnail_id&showposts=-1&posts_per_page=-1&ignore_sticky_posts=1');
      $postnum = $query1->post_count;
      wp_reset_postdata();

?>
<div id="gallery-container">
	<ul class="items--small">
<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $arr = array('meta_key' => '_thumbnail_id',
                'showposts' => 12,        // 显示5个特色图像
                'posts_per_page' => 2,   // 显示5个特色图像
                'paged' => $paged,
                'orderby' => 'date',     // 按发布时间先后顺序获取特色图像，可选：'title'、'rand'、'comment_count'等
                'ignore_sticky_posts' => 1,
                'order' => 'DESC');
    $slideshow = new WP_Query($arr);
    if ($slideshow->have_posts()) {
        $postCount = 0;
        while ($slideshow->have_posts()) {
            $slideshow->the_post();
?>
<?php $timthumb_src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()),'large'); ?>
		<li class="item"><a href="#"><img src="<?php bloginfo('template_url');?><?php echo '/functions/timthumb.php?src=';?><?php echo $timthumb_src[0]; ?><?php echo '&q=90&w=240&h=160&zc=1';?>" alt="" title="<?php the_title(); ?>" /></a></li>
<?php } } ?>
	</ul>

<?php if(empty($paged))$paged = 1;
    $prev = $paged - 1;   
    $next = $paged + 1;   
    $range = 2; // only edit this if you want to show more page-links
    $showitems = ($range * 2)+1; 
    $pages = ceil($postnum/$arr['showposts']); 
    if(1 != $pages){
        echo "<div class='gpagination'>";   
        echo ($paged > 2 && $paged+$range+1 > $pages && $showitems < $pages)? "<a href='".get_pagenum_link(1)."'>".__('最前','tinection')."</a>":"";   
        echo ($paged > 1 && $showitems < $pages)? "<a href='".get_pagenum_link($prev)."'>".__('上一页','tinection')."</a>":"";
            for ($i=1; $i <= $pages; $i++){   
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){   
                    echo ($paged == $i)? "<span class='gcurrent'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='ginactive' >".$i."</a>";   
                }   
            }   
        echo ($paged < $pages && $showitems < $pages) ? "<a href='".get_pagenum_link($next)."'>".__('下一页','tinection')."</a>" :"";   
        echo ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) ? "<a href='".get_pagenum_link($pages)."'>".__('最后','tinection')."</a>":"";   
        echo "</div>\n";   
    }   
?>
	<ul class="items--big">
<?php wp_reset_postdata(); ?>
<?php 
	$slideshow = new WP_Query($arr);
	if ($slideshow->have_posts()) {
        $postCount = 0;
        while ($slideshow->have_posts()) {
            $slideshow->the_post();
?>
<?php $timthumb_src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()),'large'); ?>
		<li class="item--big">
			<figure><img src="<?php echo $timthumb_src[0]; ?>" title="<?php the_title(); ?>" alt="" />
				<a href="<?php the_permalink(); ?>"  title="<?php the_title(); ?>" target="_blank"><figcaption class="img-caption"><?php the_title(); ?></figcaption></a>
			</figure>
		</li>
<?php } } ?>
	</ul>
	<div class="controls">
		<span class="control icon-arrow-left" data-direction="previous"></span>
		<span class="control icon-arrow-right" data-direction="next"></span>
		<span class="grid icon-grid"></span>
		<span class="fs-toggle icon-fullscreen"></span>
	</div>
</div>

<?php
    wp_reset_postdata();
?>
<p class="vad">
	<a href="<?php bloginfo('home'); ?>"  target="_blank" title="返回首页"><?php _e('返回 ','tinection'); ?><?php bloginfo('name'); ?></a>
	<a href="<?php bloginfo('home'); ?>/archives" target="_blank" title="<?php _e('文章归档','tinection'); ?>"><?php _e('文章归档','tinection'); ?></a>
</p>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/sgallery-plugins.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/sgallery.js"></script>
<script>
$(function(){
	$('#gallery-container').sGallery({
		fullScreenEnabled: true
	});
});
</script>
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
<?php get_template_part('includes/loginbox'); ?>
<?php get_template_part('includes/floatbutton'); ?>

<!-- /.Footer Nav Wrap -->
<!-- 引入主题js -->
<script type="text/javascript">
/* <![CDATA[ */
var tin = {"ajax_url":"<?php echo admin_url( '/admin-ajax.php' ); ?>","tin_url":"<?php echo get_bloginfo('template_directory'); ?>","Tracker":<?php echo json_encode(tin_tracker_param()); ?>,"home":"<?php echo get_bloginfo('home'); ?>"};
/* ]]> */
</script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/theme.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/lazyload.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/zh-cn-tw.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/includes/js/pirobox.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/lazyload.js"></script>
<script type="text/javascript">var defaultEncoding = 0; var translateDelay = 100; var cookieDomain = "<?php echo get_settings('home'); ?>";</script>
<!-- 百度分享 -->
<script type="text/javascript" id="bdshare_js" data="type=tools&mini=2"></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
		//在这里定义bds_config
		var bds_config = {'snsKey':{'tsina':'2884429244','tqq':'101166664'}};
		document.getElementById('bdshell_js').src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000);
</script>
<!-- 引入用户自定义代码 -->
<?php if(ot_get_option('footercode')) echo ot_get_option('footercode'); ?>
<?php wp_footer(); ?>
<!-- /.Footer -->
</body>
</html>