<?php
/**
 * Template Name:友情链接
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="single f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <div class="post ption_r">
    <div class="circle">
      <div class="single_type"></div>
    </div>
    <h1 id="archive_title_reader" class="fs24 f_w ption_r">友情链接<em class="fs14">/Links (所有友链网站，排名不分先后)</em></h1>
    <div class="ption_r mt10" id="all_links">
    <ul><?php wp_list_bookmarks('orderby=rand&title_li=&categorize=0&show_images=1&show_name=1'); ?></ul>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php the_content(); ?>
        <?php endwhile; else: ?>
    <?php endif; ?>
    </div>
    </div>
    <div class="discussion ption_r">
      <div class="circle"><div class="bubble">Comments</div></div>
      <?php comments_template( '', true ); ?>
    </div>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>
