<?php
/**
 * Includes of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.2
 * @date      2014.12.27
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<script src="<?php bloginfo('template_directory'); ?>/includes/js/jquery.eislideshow.js"></script>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/includes/css/slider.css" />
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
	<div id="home-slider-left">
		<div id="ei-slider" class="ei-slider">
		<span class="slider-focus-recommend"><?php _e('焦点','tinection'); ?></span>
		<?php if( $featured_query->have_posts() ) : ?>
		<script>
			$(function() {
                $('#ei-slider').eislideshow({
					animation			: 'center',
					autoplay			: true,
					slideshow_interval	: 3000,
					titlesFactor		: 0
                });
            });
		</script>
		<ul class="ei-slider-large">
		<?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
			<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>
			<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');?>
			<?php $img = $large_image_url[0];else:$img = catch_first_image(); endif; ?>
			<li>
				<img src="<?php echo tin_thumb_source($img,700,325,false); ?>" alt="<?php the_title(); ?>"/>
                <div class="ei-title">
                    <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                </div>
            </li>
		<?php endwhile;?>
		</ul>
		<ul class="ei-slider-thumbs">
		<li class="ei-slider-element">Current</li>
		<?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
			<li></li>
		<?php endwhile;?>
		</ul>
		<?php wp_reset_query(); else: echo '<p style="width:100%;position:absolute;top:50%;margin-top:-10px;text-align:center;font-weight:bold;color:#fff">'.__('请在后台设置中添加要显示幻灯的文章ID','tinection').'</p>'; endif;?>
		</div>
	</div>
	<div id="home-slider-right">
	<span class="slider-focus-recommend"><?php _e('推荐','tinection'); ?></span>
<?php
	$arr = array('posts_per_page' => 6,'post__in' => $stickys,'ignore_sticky_posts' => 1); 
	$featured_list_query = new wp_query( $arr );
?>
<?php if( !empty($stickys)&&$featured_list_query->have_posts() ) : ?>
<?php $m=0;
	while ( $featured_list_query->have_posts() ) : $featured_list_query->the_post(); $m++; ?>
	<?php if($m==1) { ?>
		<article class="home-slider-right-list largelist">
			<h3 style="text-align:center;">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo cut_str(get_the_title($post),40); ?></a>
			</h3>
			<span><?php echo cut_str(get_the_excerpt(),140); ?></span>
		</article>
	<?php }else{ ?>
		<article class="home-slider-right-list smalllist">
			<h3>
				<i class="fa fa-angle-right"></i>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo cut_str(get_the_title($post),40); ?></a>
			</h3>
			<?php if(ot_get_option('slider_recommend_order')=='most_viewed'){ echo '<span>'.get_tin_traffic( 'single' , $post->ID ).'℃</span>';}else{echo '<span>'.$post->comment_count.__(' 条评论','tinection').'</span>';} ?>
		</article>
	<?php } ?>
	<?php wp_reset_query(); endwhile; ?>
	<?php if($m<6) { $limit = 6;?>
		<?php if(ot_get_option('slider_recommend_order')=='most_viewed'){ ?>
		<?php
		    $stickys_str = implode(',', $stickys);
			$table_name = $wpdb->prefix . 'tin_tracker'; 
			$most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (traffic+0) AS views FROM $wpdb->posts LEFT JOIN $table_name ON $table_name.pid = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_type = 'post' AND post_status = 'publish' AND post_password = '' ORDER BY views DESC LIMIT $limit"); 
			foreach ($most_viewed as $post) {
				$post_views = intval($post->views);
				$post_title = get_the_title($post);
				$post_title_short = cut_str(get_the_title($post),40);
				$post_url = get_permalink($post);
				if (in_array($post->ID,$stickys)||$m>=6) {echo '';} else{echo '<article class="home-slider-right-list smalllist"><h3><i class="fa fa-angle-right"></i><a href="'	.$post_url. '" title="' .$post_title.'">' .$post_title_short. '</a></h3><span>'.$post_views.'℃</span></article>';$m++;}			
			}
		?>
		<?php }else{ ?>
		<?php 
			$most_discussed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.* FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' AND post_password = '' ORDER BY comment_count DESC LIMIT 0 , $limit");
			foreach ($most_discussed as $post) {
				$post_comments = $post->comment_count;
				$post_title = get_the_title($post);
				$post_title_short = cut_str(get_the_title($post),40);
				$post_url = get_permalink($post);
				if (in_array($post->ID,$stickys)||$m>=6) {echo '';} else{
				echo '<article class="home-slider-right-list smalllist"><h3><i class="fa fa-angle-right"></i><a href="'	.$post_url. '" title="' .$post_title.'">' .$post_title_short. '</a></h3><span>'.$post_comments.__(' 条评论','tinection').'</span></article>';$m++;}
			}
		?>
		<?php } ?>
	<?php } ?>
<?php else: ;?>
	<?php if(ot_get_option('slider_recommend_order')=='most_viewed'){ $n=0;?>
		<?php
			$table_name = $wpdb->prefix . 'tin_tracker'; 
			$most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (traffic+0) AS views FROM $wpdb->posts LEFT JOIN $table_name ON $table_name.pid = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_type = 'post' AND post_status = 'publish' AND post_password = '' ORDER BY views DESC LIMIT 6"); 
			foreach ($most_viewed as $post) {
				$post_views = get_tin_traffic( 'single' , $post->ID );;
				$post_title = get_the_title($post);
				$post_title_short = cut_str(get_the_title($post),40);
				$post_url = get_permalink($post);
				$post_excerpt = wp_trim_words($post->post_content,120);
				$n++;
				if($n==1) {
					echo '<article class="home-slider-right-list largelist"><h3><a href="'	.$post_url. '" title="' .$post_title.'">' .$post_title. '</a></h3><span>'.$post_excerpt.'</span></article>';
				}else{
					echo '<article class="home-slider-right-list smalllist"><h3><i class="fa fa-angle-right"></i><a href="'	.$post_url. '" title="' .$post_title.'">' .$post_title_short. '</a></h3><span>'.$post_views.'℃</span></article>';
				}			
			}
		?>
	<?php }else{ $n=0; ?>
		<?php
			$most_discussed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.* FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY comment_count DESC LIMIT 0 , 6");
			foreach ($most_discussed as $post) {
				$post_comments = $post->comment_count;
				$post_title = get_the_title($post);
				$post_title_short = cut_str(get_the_title($post),40);
				$post_url = get_permalink($post);
				$post_excerpt = wp_trim_words($post->post_content,120);
				$n++;
				if($n==1) {
					echo '<article class="home-slider-right-list largelist"><h3><a href="'	.$post_url. '" title="' .$post_title.'">' .$post_title. '</a></h3><span>'.$post_excerpt.'</span></article>';
				}else{
					echo '<article class="home-slider-right-list smalllist"><h3><i class="fa fa-angle-right"></i><a href="'	.$post_url. '" title="' .$post_title.'">' .$post_title_short. '</a></h3><span>'.$post_comments.__(' 条评论','tinection').'</span></article>';
				}
			}
		?>
	<?php } ?>
<?php wp_reset_query(); endif;?>
	</div>
</div>
<!-- End Slider -->
</section>
<?php $post = $orig_post;  ?>