<?php
/**
 * The template for displaying Search.
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <h1 id="archive_title" class="fs24 f_w ption_r">
        <?php /* Search Count */ $allsearch = &new WP_Query("s=$s&showposts=-1"); $key = wp_specialchars($s, 1); $count = $allsearch->post_count; _e('');  _e('共搜到 '); echo $count . ' ';_e(' 条关于 "<span>');echo $key; _e('</span>" '); _e('的结果'); wp_reset_query(); ?>
	</h1>
    <?php get_template_part( 'loop-page' ); ?>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>
