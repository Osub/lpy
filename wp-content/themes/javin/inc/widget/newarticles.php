<?php if ( is_home() ) { ?>
<?php } else { ?>
<div class="box">
  <h3 class="box_title ption_r mb20 f_l"><span>最新文章</span></h3>
  <div class="box_content" id="new">
    <ul>
      <?php $post_query = new WP_Query('showposts=5');
while ($post_query->have_posts()) : $post_query->the_post();
$do_not_duplicate = $post->ID; ?>
      <li> <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
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
<?php } ?>