<?php
/**
 * Template Name:公告页面
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <h1 id="archive_title" class="fs24 f_w ption_r"><?php the_title(); ?></h1>
    <?php
	  $loop = new WP_Query( array( 'post_type' => 'bulletin' ) );
      while ( $loop->have_posts() ) : $loop->the_post();
    ?>
    <div class="post ption_r" id="bulletin-<?php the_ID(); ?>">
      <div class="circle">
        <div class="type_bulletin"><span><?php the_time('j') ?><small><?php $u_time = get_the_time('U');/*获取日志文章发表时间的时间戳*/ echo date("M",$u_time); ?></small></span></div>
      </div>
      <h1 class="bulletin_title f_w"><?php the_content(); ?></h1>
      <div class="meta">
        <p class="meta_info">
          <span class="mr10"><?php _e('时间: '); the_time('Y-n-j H:i') ?></span>
          <span class="mr10">标题：<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
          <span class="mr10"><?php echo getPostViews(get_the_ID()); ?></span>
          <span class="mr10"><?php comments_popup_link('0', '1', '%', 'comments-link', '关闭'); ?>条评论</span>
          <span class="mr10"><a href="<?php the_permalink(); ?>#respond" title="<?php the_title(); ?>">点击评论>></a></span>
        </p>
        <div class="clear"></div>
      </div>
    </div>
    <?php endwhile; wp_reset_query(); ?>
	<?php if( javin_opt('javin_page_nav_button')!='' ) { ?>
	   <?php if(function_exists('javin_pagenavi')) { ?>
       <?php javin_pagenavi(); ?>   
       <?php } else { ?>      
       <div class="page-nav" style="text-align:center;"><p><?php posts_nav_link('&#8734;','&laquo;&laquo; Previous Posts','Older Posts &raquo;&raquo;'); ?></p></div>
       <?php } ?>
    <?php } else { ?>
	   <nav id="page_nav"><?php next_posts_link(__('MORE')) ?></nav>
    <?php } ?>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>
