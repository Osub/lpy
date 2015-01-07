<?php 
/**
 * The template for displaying archive.
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <h1 id="archive_title" class="fs24 f_w ption_r">
		<?php if ( is_day() ) : ?>
							<?php printf( __( '存档: %s' ), '<span>' . get_the_date() . '</span>' ); ?>
						<?php elseif ( is_month() ) : ?>
							<?php printf( __( '存档: %s' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format' ) ) . '</span>' ); ?>
						<?php elseif ( is_year() ) : ?>
							<?php printf( __( '存档: %s' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format' ) ) . '</span>' ); ?>
						<?php else : ?>
							<?php _e( 'Blog Archives' ); ?>
						<?php endif; ?>
	</h1>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="post ption_r" id="post-<?php the_ID(); ?>">
      <div class="circle">
        <div 
        <?php if ( is_category() ) { ?>
         <?php
          if (is_category('HTML/CSS'))
            {echo 'class="type_css"';}
          if (is_category('JavaScript'))
            {echo 'class="type_js"';}
		  if (is_category('前端设计'))
            {echo 'class="type_desi"';}
		  if (is_category('资源技巧'))
            {echo 'class="type_cou"';}
		  if (is_category('觉唯图库'))
            {echo 'class="type_tu"';}
		  else
		    {echo 'class="type"';}
          ?>
         <?php } else { ?>
          <?php
          if (in_category('HTML/CSS'))
            {echo 'class="type_css"';}
          if (in_category('JavaScript'))
            {echo 'class="type_js"';}
		  if (in_category('前端设计'))
            {echo 'class="type_desi"';}
		  if (in_category('资源技巧'))
            {echo 'class="type_cou"';}
		  if (in_category('觉唯图库'))
            {echo 'class="type_tu"';}
		  else
		    {echo 'class="type"';}
          ?>
         <?php } ?>
        ><span><?php the_time('j') ?><small><?php $u_time = get_the_time('U');/*获取日志文章发表时间的时间戳*/ echo date("M",$u_time); ?></small></span></div>
      </div>
      <h1 class="post_title fs24 f_w"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
      <div class="meta">
        <p class="meta_info">
          <span class="mr10"><?php _e('时间: '); the_time('Y-n-j') ?></span>
          <span class="mr10"><?php comments_popup_link('0', '1', '%', 'comments-link', '关闭'); ?>条评论</span>
          <span class="mr10"><?php echo getPostViews(get_the_ID()); ?></span>
          <span class="mr10"><?php if (the_tags('<span>TAGS:</span> ', ', ', ' ')); ?></span>
        </p>
        <div class="clear"></div>
      </div>
    </div>
    <?php endwhile; else: ?>
        
	<?php endif; ?>
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
