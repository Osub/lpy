<?php
/**
 * Main Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.0.8
 * @date      2014.12.07
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
	<div class="container two-col-container">
		<div id="main-wrap-left">
		<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; $uncat = ot_get_option('cmsundisplaycats');query_posts(array('post__not_in'=>get_option('sticky_posts'),'category__not_in'=>$uncat,'paged' => $paged)); ?>
		<?php $i=0;if(have_posts()):while (have_posts()) : the_post();$i++;?>
			<article class="home-blog-entry col span_1 clr">
				<span class="postlist-meta-cat"><?php the_category(' ', false); ?></span>
				<?php  if(!get_post_format()) { $format = 'standard'; } else { $format = get_post_format(); }?>
				<?php get_template_part('content',esc_attr( $format )); ?>
				<div class="clear"></div>
				<!-- Post meta -->
				<?php tin_post_meta(); ?>
				<!-- /.Post meta -->
			</article>
			<?php if($i==2){ ?>
			<?php if(!tin_is_mobile()){ ?>
			<div id="loopad" class="container">
			<?php echo ot_get_option('cmswithsidebar_loop_ad'); ?>
			</div>
			<?php }else{ ?>
			<div id="loopad" class="mobile-ad">
			<?php echo ot_get_option('singlead1_mobile'); ?>
			</div>
			<?php }?>
			<?php }?>
			<?php endwhile;?>
			<!-- pagination -->
			<div class="clear"></div>
			<div class="pagination">
			<?php pagenavi(); ?>
			</div>
			<!-- /.pagination -->
			<?php endif;?>
		</div>
	<?php get_sidebar();?>
	</div>
	<div class="clear"></div>