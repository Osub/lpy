<?php if ( is_single() ) { ?>
<div class="box">
  <h3 class="box_title ption_r mb20 f_l"><span>目录</span></h3>
  <div class="box_content" id="same_category">
    <ul>
      <?php
		global $post;
        $categories = get_the_category();
        foreach ($categories as $category) :
          $posts = get_posts('numberposts=100&category='. $category->term_id);
          foreach($posts as $post) :
          ?>
      <li> <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
        <?php the_title(); ?>
        </a> </li>
      <?php endforeach; ?>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php } else { ?>
<?php } ?>
