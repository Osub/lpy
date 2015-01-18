<?php
/**
 * Includes of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.2
 * @date      2014.12.27
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014-2015, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<script src="<?php bloginfo('template_directory'); ?>/includes/js/slider.js"></script>
<script type="text/javascript">
$(function(){

	$('#flexslider').flexslider({
		animation: "slide",
		direction:"horizontal",
		easing:"swing",
		slideshowSpeed: 5000,
		animationDuration:600
	});
});
</script>
<?php 
	global $post;
	$orig_post = $post;
	$sliderposts = explode (',' , ot_get_option('homeslider'));
	$number = count($sliderposts);
	$stickys = get_option('sticky_posts');
	$stickys_num = count($stickys);
	$args= array('post_type' => 'post', 'post__in' => $sliderposts, 'post__not_in' => $stickys  );
	$featured_query = new wp_query( $args );
?>
<section id="home-featured" class="clr">
<!-- Slider -->
<div id="home-slider" class="container">
	<div id="flexslider" class="flexslider">
		<?php if( $featured_query->have_posts() ) : $i=0;?>
		<ul class="slides">
		<?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); $i++;?>
			<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>
			<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');?>
			<?php $img = $large_image_url[0];else:$img = catch_first_image(); endif; ?>
			<li>
				<a class="slider-img" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo tin_thumb_source($img,600,336,false); ?>" alt="<?php the_title(); ?>"/></a>
				<div class="slider-text">
					<div class="cl">
						<div class="slider-meta">
							<span class="num"><?php if($i<10) echo '0'.$i; else echo $i; ?></span>
							<span class="author"><?php the_author(); ?></span>
							<span class="date"><?php the_time('m-d'); ?></span>
						</div>
						<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
					</div>
					<span class="slider-excerpt"><?php $contents = get_the_excerpt(); $excerpt = wp_trim_words($contents,150,'...'); echo $excerpt; ?></span>
                	<span class="slider-readmore"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank">Read More »</a></span>
                </div>
            </li>
		<?php endwhile;?>
		</ul>
		<?php wp_reset_query(); else: echo '<p style="width:100%;position:absolute;top:50%;margin-top:-10px;text-align:center;font-weight:bold;color:#fff">'.__('请在后台设置中添加要显示幻灯的文章ID','tinection').'</p>'; endif;?>
	</div>
</div>
<!-- End Slider -->
</section>
<?php $post = $orig_post;  ?>