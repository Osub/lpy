<?php
/**
 * The template for displaying author.
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <?php
		if(isset($_GET['author_name'])) :
			$curauth = get_userdatabylogin($author_name);
	    else :
			$curauth = get_userdata(intval($author));
		endif;
	?>
	<div id="archive_title" class="author ption_r">
		<h1 class="fs24 f_w mb10">以下是“<?php echo $curauth->display_name; ?>”发布的文章（共 <?php the_author_posts(); ?> 篇）</h1>
	</div>
    <?php get_template_part( 'loop-page' ); ?>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>
