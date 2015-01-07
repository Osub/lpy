<div class="box">
  <h3 class="box_title ption_r mb20 f_l"><span>热门文章</span></h3>
  <div class="box_content" id="hot">
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
      <li> <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
        <?php the_title(); ?>
        </a> <small>
        <?php the_time('Y.n.d') ?>
        ,
        <?php comments_popup_link('0条评论', '1条评论', '%条评论', 'comments-link', '评论已关闭'); ?>
        </small> </li>
      <?php } wp_reset_query();?>
    </ul>
  </div>
</div>
