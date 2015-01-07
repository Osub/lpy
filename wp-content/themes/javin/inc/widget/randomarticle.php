<div class="box">
  <h3 class="box_title ption_r mb20 f_l"><span>随机文章</span></h3>
  <div class="box_content" id="feat">
    <ul>
      <?php
          global $post;
          $postid = $post->ID;
          $args = array( 'orderby' => 'rand', 'post__not_in' => array($post->ID), 'showposts' => 5);
          $query_posts = new WP_Query();
          $query_posts->query($args);
          ?>
      <?php while ($query_posts->have_posts()) : $query_posts->the_post(); ?>
      <li> <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
        <?php the_title(); ?>
        </a> <small>
        <?php the_time('Y.n.d') ?>
        ,
        <?php comments_popup_link('0条评论', '1条评论', '%条评论', 'comments-link', '评论已关闭'); ?>
        </small> </li>
      <?php endwhile; wp_reset_query(); ?>
    </ul>
  </div>
</div>
