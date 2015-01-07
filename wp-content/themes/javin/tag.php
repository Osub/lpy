<?php
/**
 * The template for displaying Tag.
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <h1 id="archive_title" class="fs24 f_w ption_r">
		<?php printf( __( '关于 "%s" 的页面' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?>
	</h1>
    <?php get_template_part( 'loop-page' ); ?>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>