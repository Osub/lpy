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
<?php get_header(); ?>
<?php get_template_part( 'includes/breadcrumbs');?>
<!-- Header Banner -->
<?php $headerad=ot_get_option('headerad');if (!empty($headerad)) {?>
<div id="header-banner">
	<div class="container">
		<?php echo ot_get_option('headerad');?>
	</div>
</div>
<?php }?>
<!-- /.Header Banner -->
<!-- Main Wrap -->
<div id="main-wrap">
<div class="container two-col-container" style="margin-top:10px;">
<div id="main-wrap-left">
<?php if(have_posts()) : while (have_posts()) : the_post();?>
<article class="home-blog-entry col span_1 clr">
	<span class="postlist-meta-cat"><?php the_category(' ', false); ?></span>
	<?php  if(!get_post_format()) { $format = 'standard'; } else { $format = get_post_format(); }?>
	<?php get_template_part('content',esc_attr( $format )); ?>
	<div class="clear"></div>
	<!-- Post meta -->
	<?php tin_post_meta(); ?>
	<!-- /.Post meta -->
</article>
<?php endwhile;?>
<?php else : ?>
	<p><?php _e('这里好像什么文章都没有!~','tinection'); ?></p>
<?php endif;?>
<!-- pagination -->
<div class="clear">
</div>
<div class="pagination">
<?php pagenavi(); ?>
</div>
<!-- /.pagination -->
</div>
<?php get_sidebar();?>
</div>
<div class="clear">
</div>
</div>
<!--/.Main Wrap -->
<!-- Bottom Banner -->
<?php $bottomad=ot_get_option('bottomad');if (!empty($bottomad)) {?>
<div id="bottom-banner">
	<div class="container">
		<?php echo ot_get_option('bottomad');?>
	</div>
</div>
<?php }else{?>
<div style="height:50px;"></div>
<?php }?>
<!-- /.Bottom Banner -->
<?php if(ot_get_option('footer-widgets-singlerow') == 'on'){?>
<div id="footer-widgets-singlerow">
	<div class="container">
	<?php dynamic_sidebar( 'footer-row'); ?>
	</div>
</div>
<?php }?>
<?php get_footer(); ?>