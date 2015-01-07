<?php
/**
 * Includes of Tinection WordPress Theme
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
<article class="archive clr">
<h3>
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?><?php $thelayout = the_layout();if(is_sticky()&&($thelayout == 'blog')) echo" <span style='color:red;'>".__('[置顶]','tinection')."</span>" ; ?></a>
</h3>
<?php if( $post->post_status=='publish' ){ ?>		
<div class="postlist-meta">
		<div class="postlist-meta-author"><i class="fa fa-user"></i>&nbsp;&nbsp;<?php the_author();?></div>
		<div class="postlist-meta-time"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?php echo date(__('Y年m月j日','tinection'),get_the_time('U'));?></div>
		<div class="postlist-meta-views"><i class="fa fa-eye"></i>&nbsp;&nbsp;<?php echo get_tin_traffic( 'single' , get_the_ID() ); ?></div>
		<div class="postlist-meta-category"><i class="fa fa-folder"></i>&nbsp;&nbsp;<?php the_category(' '); ?></div>
		<div class="postlist-meta-comments"><?php if ( comments_open() ): ?><i class="fa fa-comments"></i>&nbsp;&nbsp;<a href="<?php comments_link(); ?>"><?php comments_number( '0', '1', '%' ); ?></a><?php  endif; ?></div>
		<?php get_template_part('includes/like_collect_meta'); ?>
</div>
<?php } ?>
<p class="archive-excerpt">
<?php the_excerpt();?>
</p>
<?php if( $post->post_status!='publish' ){ 
	$meta_output = '<div class="entry-meta">';
		if( $post->post_status==='pending' ) $meta_output .= sprintf(__('正在等待审核，你可以 <a href="%1$s">预览</a> 或 <a href="%2$s">重新编辑</a> 。','tinection'), get_permalink(), get_edit_post_link() );
		if( $post->post_status==='draft' ) $meta_output .= sprintf(__('这是一篇草稿，你可以 <a href="%1$s">预览</a> 或 <a href="%2$s">继续编辑</a> 。','tinection'), get_permalink(), get_edit_post_link() );
		$meta_output .= '</div>';
		echo $meta_output;
} ?>
</article>