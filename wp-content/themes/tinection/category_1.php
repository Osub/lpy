<?php
/**
 * Main Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.0.5
 * @date      2014.12.03
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
<div id="category-cms-wrap" class="container clr">
<div class="container-row">
<?php 
	$i=3;
	while (have_posts()) : the_post();
	$r = fmod($i,3)+1;
	$i++;
?>
<span class="col span_1_of_3 col-<?php echo $r;?>">
<article class="home-blog-entry clr">
	<span class="postlist-meta-cat"><?php the_category(' ', false); ?></span>
	<?php  if(!get_post_format()) { $format = 'standard'; } else { $format = get_post_format(); }?>
	<?php get_template_part('includes/contentcms',esc_attr( $format )); ?>
	</div>
	<div class="clear"></div>
	<div class="home-blog-entry-meta">
	<!-- Post meta -->
	<?php tin_post_meta('1'); ?>
	<!-- Post meta -->
	</div>
</article>
</span>
<?php if($r==3) echo"</div><div class='container-row'>";?>
<?php endwhile;?>
</div>
<!-- pagination -->
<div class="clear"></div>
<div class="pagination">
<?php pagenavi(); ?>
</div>
<!-- /.pagination -->
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