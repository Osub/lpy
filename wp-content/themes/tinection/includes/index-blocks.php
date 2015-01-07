<?php
/**
 * Main Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.0.9
 * @date      2014.12.09
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<div class="container">
	<section class="catlist clr">
		<div class="catlist-container-rand clr">
		<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; $uncat = ot_get_option('cmsundisplaycats');
			  query_posts(array('post__not_in'=>get_option('sticky_posts'),'category__not_in'=>$uncat,'paged' => $paged)); ?>
		<?php $i=4; while (have_posts()) : the_post(); $r = fmod($i,4)+1; $i++;?>
		<span class="col span_1_of_4 col-<?php echo $r;?>">
			<article class="home-blog-entry clr home-blog-entry-rand">
			<?php  if(!get_post_format()) { $format = 'standard'; } else { $format = get_post_format(); }?>
			<?php get_template_part('includes/thumbnail',esc_attr( $format )); ?>
			<div class="home-blog-entry-text contentcms-entry-text clr">
			<?php $category = get_the_category(); $catname1 = $category[0]->cat_name; $catnum1 = $category[0]->cat_ID; $catr = fmod($catnum1,4)+1;?>
			<?php switch ($catr) {
				case '4':
					$bgcolor = 'purple';
					break;
				case '3':
					$bgcolor = 'red';
					break;
				case '2':
					$bgcolor = 'blue';
					break;
				default:
					$bgcolor = 'green';
				break;
			}?>
			<div class="ribbon ribbon-<?php echo $bgcolor; ?>"><?php echo $catname1; ?>&nbsp;&nbsp;&nbsp;</div>
				<h3 style="margin-top:10px">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</h3>
				<p>
					<?php $contents = get_the_excerpt(); $excerpt = wp_trim_words($contents,120,ot_get_option('readmore')); echo $excerpt;?>
				</p>
				<div class="line"></div>
				<!-- Post meta -->
				<?php tin_post_meta(); ?>
				<!-- /.Post meta -->
			</div>
			</article>
		</span>
		<?php endwhile;?>	
		</div>
	</section>
	<!-- pagination -->
	<div class="clear">
	</div>
	<div class="pagination">
		<?php pagenavi(); ?>
	</div>
	<!-- /.pagination -->
</div>
<script type="text/javascript">
$('.site_loading').animate({'width':'75%'},50);
</script>