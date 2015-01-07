<?php 
/**
 * The template for displaying Sidebar.
 * @package WordPress
 * @subpackage Javin
 */
?>
<div id="sidebar" class="f_l ption_r">
    <div class="right_top_bg ption_a"></div>
    <?php if ( ! dynamic_sidebar( 'Sidebar' )) : ?> 
      <div class="box">
      <h3 class="box_title ption_r mb20 f_l"><span>推荐文章<em></em></span></h3>
      <div class="box_content" id="feat">
        <ul>
        <?php
        $post_num = 5; // 设置调用条数
        $args = array(
        'post_password' => '',
        'post_status' => 'publish', // 只选公开的文章.
        'post__not_in' => array($post->ID),//排除当前文章
        'caller_get_posts' => 1, // 排除置頂文章.
        'orderby' => 'comment_count', // 依評論數排序.
        'posts_per_page' => $post_num
		);
        $query_posts = new WP_Query();
        $query_posts->query($args);
        while( $query_posts->have_posts() ) { $query_posts->the_post(); 
		?>
          <li style="border-top:0 none;">
          <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
          <small><?php the_time('Y.n.d') ?> , <?php comments_popup_link('0条评论', '1条评论', '%条评论', 'comments-link', '评论已关闭'); ?></small>
          </li>
          <div class="feat_bottom_line"></div>
         <?php } wp_reset_query();?>
        </ul>
      </div>
    </div>
    <?php endif; ?>
      <div id="float" class="box">
      <h3 class="box_title ption_r mb20 f_l"><span>功能</span></h3>
      <div class="box_content" id="log_register">
        <ul>
            <li><a target="_blank" title="我要投稿" href="<?php echo javin_opt('javin_contribute'); ?>">投稿</a></li>
            <?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo esc_attr(__('Syndicate this site using RSS 2.0')); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
			<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo esc_attr(__('The latest comments to all posts in RSS')); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
			<?php wp_meta(); ?>
        </ul>
      </div>
      <?php if( javin_opt('javin_adall_bottom_button')!='' ) echo '<div class="box_content_ad">'.javin_opt('javin_adall_bottom').'</div>'; ?>
      <!-- <div class="footer">
       <p class="footer_wline"><?php _e('Powered by'); ?> Wordpress. <a href="http://www.jiawin.com">Theme by <strong>Javin</strong></a>.</p>
       <p>Copyright &copy; 2012-<?php echo date("Y") ?> <a class="f_w" href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a></p>
       <p>All Rights Reserved.</p>
       <p>粤ICP备12345678号</p>
    </div> -->
</div>
  </div>
<div class="clear"></div>