<?php
/**
 * Template Name:网站地图
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <div class="archives_list ption_r">
      <div class="circle"><div class="sitemap_sort"></div></div>
      <h3 class="archives_list_title fs24 f_w">页面</h3>
      <ul><?php wp_list_pages("title_li=" ); ?></ul>
      <div class="clear"></div>
    </div>
    <div class="archives_list ption_r">
      <div class="circle"><div class="sitemap_sort"></div></div>
      <h3 class="archives_list_title fs24 f_w">目录</h3>
      <ul><?php
$args=array('orderby' => 'name','order' => 'ASC');
$categories=get_categories($args);
  foreach($categories as $category) { 
    echo '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a></li>';
  }
?></ul>
      <div class="clear"></div>
    </div>
    <div class="archives_list ption_r">
      <div class="circle"><div class="sitemap_sort"></div></div>
      <h3 class="archives_list_title fs24 f_w">文章</h3>
      <ul><?php $archive_query = new WP_Query('showposts=1000&cat=-8');
                while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
                    <li>
                        <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a>
                        - Comments(<?php comments_number('0', '1', '%'); ?>)
                    </li>
                <?php endwhile; wp_reset_query(); ?>
            </ul>
      <div class="clear"></div>
    </div>
    <div class="archives_list ption_r">
      <div class="circle"><div class="sitemap_sort"></div></div>
      <h3 class="archives_list_title fs24 f_w">文章归档</h3>
      <ul>
                <?php wp_get_archives('type=monthly&show_post_count=true'); ?>
            </ul>
      <div class="clear"></div>
    </div>
    <div class="archives_list ption_r">
      <div class="circle"><div class="sitemap_sort"></div></div>
      <h3 class="archives_list_title fs24 f_w">Feeds</h3>
        <ul>
          <li><a rel="external nofollow" title="使用在线阅读器直接订阅" href="<?php echo javin_opt('javin_rss'); ?>">阅读器订阅</a></li>
          <li><a title="Full content" href="feed:<?php bloginfo('rss2_url'); ?>">Main RSS</a></li>
          <li><a title="Comment Feed" href="feed:<?php bloginfo('comments_rss2_url'); ?>">Comment Feed</a></li>
        </ul>
      <div class="clear"></div>
    </div>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>
