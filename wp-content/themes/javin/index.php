<?php
/**
 * The template for displaying index.
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <div id="announcement_box"  class="ption_a">
        <div id="announcement">
        <ul style="margin-top: 0px;">
            <?php
                $loop = new WP_Query( array( 'post_type' => 'bulletin', 'posts_per_page' => 5 ) );
                while ( $loop->have_posts() ) : $loop->the_post();
            ?>
                <li><span class="mr10"><?php the_time('Y-n-j H:i') ?></span><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 70,"…"); ?></a></li>
                <?php endwhile; wp_reset_query(); ?>
        </ul>
        </div>
        <div class="announcement_remove"><a title="关闭" href="javascript:void(0)" onclick="$('#announcement_box').slideUp('slow');"><span id="announcement_close">×</span></a></div>
    </div>
    <?php if( javin_opt('javin_adall_top_button')!='' ) echo '<div class="ption_r content_top_ad_box"><div class="content_top_ad">'.javin_opt('javin_adall_top').'</div></div>'; ?>
    <?php if ( javin_opt('javin_slider_epen')!='' ) { ?>
        <?php
			if( javin_opt('javin_slider_num')!='' ) $sticky_post_number = javin_opt('javin_slider_num'); else $sticky_post_number = '5';
			$sticky = get_option('sticky_posts');
			rsort( $sticky );
			$sticky = array_slice( $sticky, 0, $sticky_post_number);
			query_posts( array( 'post__in' => $sticky, 'caller_get_posts' => 1 ) );
	    ?>
        <div id="slider-wrapper" class="index_post ption_r">
            <div id="slider" class="nivoSlider">
                  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                      <?php $banner_images_src_meta = get_post_meta(get_the_ID(),'banner-src',true); ?>
                      <?php if ( $banner_images_src_meta ) { ?>
                          <a href="<?php the_permalink(); ?>" target="_blank"><img src="<?php echo "$banner_images_src_meta"; ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" /></a>
                          <?php } else { ?>
                              <?php if (has_post_thumbnail()){?>
                                <a href="<?php the_permalink(); ?>" target="_blank">
                                 <img src="<?php echo PostThumbURL_full(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
                                </a>
                              <?php }else if (catch_that_image()) {?>
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <img src="<?php echo catch_that_image(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
                                </a>
                              <?php } ?>
                      <?php } ?>                  
                  <?php endwhile; else: ?> 
                <?php endif; wp_reset_query();?>
           </div>   
        </div>
    <?php } else { ?>
    <?php } ?>
    <?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$sticky = get_option('sticky_posts');
	$args = array(
	'ignore_sticky_posts' => 1,
	'paged' => $paged
	);
	query_posts($args);
	?>
    <?php get_template_part( 'loop-page' ); ?>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>
