<?php 
/**
 * The template for displaying 404.
 * @package WordPress
 * @subpackage Javin
 */
get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="f_l t_shadow ption_r">
    <div class="left_top_404bg ption_a"></div>
    <div id="error404" class="ption_r">
            <h1 class="archive_title fs24 f_w"><?php _e('Error 404 Not Found'); ?></h1> 
			<p><?php _e('很抱歉，您要访问的页面不存在.'); ?></p>
            <p class="mt20"><a class="back_to_home" href="<?php bloginfo('url'); ?>/" title="返回首页">返回 <?php bloginfo('name'); ?> 首页 >></a></p>
	</div>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>
