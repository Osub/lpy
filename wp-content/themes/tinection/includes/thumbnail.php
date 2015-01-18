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
<?php if ( has_post_thumbnail()&&!is_singular() ) { ?>
	<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');?>
	<?php $imgsrc = $large_image_url[0]; $imgtype = substr($imgsrc,strrpos($imgsrc,'.')); ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="fancyimg home-blog-entry-thumb">
			<div class="thumb-img">
			<?php if($imgtype!='.gif'){ ?>
			<img src="<?php echo tin_thumb_source($imgsrc); ?>" alt="<?php the_title(); ?>">
			<span><?php the_article_icon();?></span>
			<?php } else { ?>
			<img src="<?php echo($large_image_url[0]);?>" alt="<?php the_title(); ?>" class="thumb-gif">
			<span><?php the_article_icon();?></span>			
			<?php } ?>
			</div>
		</a>
<?php }?>
	<?php if ( !has_post_thumbnail()&&!is_singular() ) {  ?>
	<?php $imgsrc = catch_first_image(); $imgtype = substr($imgsrc,strrpos($imgsrc,'.')); ?>
		<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="fancyimg home-blog-entry-thumb">
			<div class="thumb-img">
			<?php if($imgtype!='.gif'){ ?>
			<img src="<?php echo tin_thumb_source($imgsrc); ?>" alt="<?php the_title(); ?>">
			<span><?php the_article_icon();?></span>
			<?php } else { ?>
			<img src="<?php echo(catch_first_image());?>" alt="<?php the_title(); ?>" class="thumb-gif">
			<span><?php the_article_icon();?></span>			
			<?php } ?>
			</div>
		</a>
<?php }?>