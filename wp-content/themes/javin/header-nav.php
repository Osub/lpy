<div class="navigation f_l fs16 f_w t_shadow ption_r mt10">
  <div id="menu" class="f_l">
    <?php
	if(function_exists('wp_nav_menu')) {
	wp_nav_menu(array('theme_location'=>'header-menu','menu_id'=>'nav','container'=>'ul'));
	}
	?>
  </div>
  <div class="search f_r pr20">
    <?php get_search_form(); ?>
  </div>
</div>
