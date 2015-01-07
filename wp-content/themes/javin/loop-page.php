<?php 
/**
 * The template for displaying loop-page.
 * @package WordPress
 * @subpackage Javin
 */
?>
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
      <h1 class="post_title fs24 f_w"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php if( is_sticky() ) echo '[推荐]&nbsp;'; the_title(); ?></a></h1>
      <div class="meta">
        <div class="thumb f_l">
          <?php if (has_post_thumbnail()){?>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
          <img width="126" height="126" src="<?php echo PostThumbURL_thumbnail(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
          </a>
         <?php }else if (catch_that_image()) {?>
         <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
         <img width="126" height="126" src="<?php echo catch_that_image(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
         </a>
         <?php } ?>
        </div>
        <p class="meta_info">
          <span class="mr10"><?php _e('时间: '); the_time('Y-n-j') ?></span>
          <span class="mr10"><?php _e('分类: '); the_category(', ') ?></span>
          <span class="mr10">
		  <?php $contribute_author = get_post_meta(get_the_ID(),'contribute_authorname',true); ?>
          <?php if ( $contribute_author ) { ?>
              <?php echo "投稿者： $contribute_author"; ?>
          <?php } else { ?>
              <?php _e('作者: '); ?><?php printf( __( ' %s ' ), get_the_author() ); ?>
          <?php } ?>
          </span>
          <span class="mr10"><?php echo getPostViews(get_the_ID()); ?></span>
        </p>
        <p class="meta_tag"><?php if (the_tags('<span>TAGS:</span> ', ', ', ' ')); ?></p>
        <div class="meta_content">
		   <?php if(has_excerpt()) the_excerpt();  
           else  
               echo mb_strimwidth(strip_tags($post->post_content),0,240,'……'); ?>
        </div>
        <?php if( javin_opt('javin_post_allview_button')!='' ) { ?> 
        <div class="meta_content_all mt10" style="display:none;">
		   <?php the_content(); ?>
        </div>
        <div class="mt20"><a class="more_link" href="<?php the_permalink() ?>"><em>Read more &gt;&gt;</em></a><a class="preview_post" >预览全文</a></div>
        <?php } else { ?>
        <p><a class="more_link" href="<?php the_permalink() ?>"><em>Read more &gt;&gt;</em></a></p>
        <?php } ?>
        <div class="clear"></div>
      </div>
      <div class="comments ption_a fs20"><em><?php comments_popup_link('0', '1', '%', 'comments-link', '禁'); ?></em></div>
    </div>
    <?php endwhile; else: ?>
      <div id="post-0" class="post ption_r error404 not-found" style="background-image:none;">
            <p class="f_l mr20 not-found-img">not found images</p>
            <h1 class="entry-title f_w"><?php _e('Not Found'); ?></h1>
			<p class="mb20"><?php _e('非常抱歉，没有找到相关的内容。你可以尝试搜索找到相关的内容。'); ?></p>
			<?php get_search_form(); ?>
            <div class="clear"></div>
	  </div>
	<?php endif; wp_reset_query();?>
    <?php if( javin_opt('javin_page_nav_button')!='' ) { ?>
	   <?php if(function_exists('javin_pagenavi')) { ?>
       <?php javin_pagenavi(); ?>   
       <?php } else { ?>      
       <div class="page-nav" style="text-align:center;"><p><?php posts_nav_link('&#8734;','&laquo;&laquo; Previous Posts','Older Posts &raquo;&raquo;'); ?></p></div>
       <?php } ?>
    <?php } else { ?>
	   <nav id="page_nav"><?php next_posts_link(__('MORE')) ?></nav>
    <?php } ?>