<?php 
// creates a nicely title
function javin_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;
	$title .= get_bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'javin' ), max( $paged, $page ) );
	return $title;
}
add_filter( 'wp_title', 'javin_wp_title', 10, 2 );


////php没有mb_string扩展，解决首页摘要显示问题 （如主机不支持则去掉注释开启）
//  function mb_strimwidth($str ,$start , $width ,$trimmarker ){
// 	$output = preg_replace('/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$start.'}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$width.'}).*/s','\1',$str);  
//    return $output.$trimmarker; 
//    }
	
// 自定义后台登陆图标
function custom_login_logo() {
	echo '<style type="text/css">
	h1 a { background-image: url('.get_bloginfo('template_directory').'/images/custom-login-logo.png) !important; }
	</style>';
}
add_action('login_head', 'custom_login_logo');

// 自定义后台登陆链接
function my_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

// 自定义后台登陆链接title
function my_login_logo_url_title() {
    return '觉唯设计';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

// removes detailed login error information for security（移除安全登录的详细错误信息）
	add_filter('login_errors',create_function('$a', "return null;"));
	
//设置默认头像
add_filter( 'avatar_defaults', 'custom_gravatar' );
function custom_gravatar($avatar_defaults) {
	$myavatar = get_bloginfo('template_url') . '/images/avatar.jpg';
	$avatar_defaults[$myavatar] = "觉唯";
	return $avatar_defaults;
}
// 分页导航
function javin_pagenavi() {
  global $wp_query, $wp_rewrite;
  $pages = '';
  $max = $wp_query->max_num_pages;
  if (!$current = get_query_var('paged')) $current = 1;
  $args['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
  $args['total'] = $max;
  $args['current'] = $current;

  $total = 1;
  $args['mid_size'] = 3;
  $args['end_size'] = 1;
  $args['prev_text'] = '上一页';
  $args['next_text'] = '下一页';

  if ($max > 1) echo '<div class="page-nav ption_r" style="text-align:center;">';
  if ($total == 1 && $max > 1) $pages = '<span class="pages mr20">第' . $current . '页，共' . $max . '页</span>';
   echo $pages . paginate_links($args);
  if ($max > 1) echo '</div>';
}
//后台开启链接管理菜单
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//禁掉多余的默认小工具
function my_unregister_widgets() {   
    unregister_widget( 'WP_Widget_Archives' );   
    unregister_widget( 'WP_Widget_Calendar' );   
    unregister_widget( 'WP_Widget_Categories' );   
    unregister_widget( 'WP_Widget_Links' );   
    unregister_widget( 'WP_Widget_Meta' );   
    unregister_widget( 'WP_Widget_Pages' );   
    unregister_widget( 'WP_Widget_Recent_Comments' );   
    unregister_widget( 'WP_Widget_Recent_Posts' );   
    unregister_widget( 'WP_Widget_RSS' );   
    unregister_widget( 'WP_Widget_Search' );    
    unregister_widget( 'WP_Widget_Text' );   
}  
add_action( 'widgets_init', 'my_unregister_widgets' );  

//增加作者链接nofollow
add_filter('the_author_posts_link','cis_nofollow_the_author_posts_link');
function cis_nofollow_the_author_posts_link ($link) {
return str_replace('<a href=','<a rel="nofollow" href=', $link);
}

// 简化header信息
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

// 禁止全英文评论的垃圾评论
function scp_comment_post( $incoming_comment ) {
    $pattern = '/[一-龥]/u';
    if(!preg_match($pattern, $incoming_comment['comment_content'])) {
        wp_die( "You should type some Chinese word (like \"你好\") in your comment to pass the spam-check, thanks for your patience! 您的评论中必须包含汉字!" );
    }
    return( $incoming_comment );
}
add_filter('preprocess_comment', 'scp_comment_post');

// no_self_ping
function no_self_ping( &$links ) {
	$home = get_option( 'home' );
	foreach ( $links as $l => $link )
	if ( 0 === strpos( $link, $home ) )
	unset($links[$l]);
	}
add_action( 'pre_ping', 'no_self_ping' );

// 关闭保存文章修改历史记录
remove_action('pre_post_update', 'wp_save_post_revision');

// 引入自定义文件
    include("inc/custom-widget.php");
	include("inc/custom-thumbnail.php");
	include("inc/notice-type.php");
	include("inc/custom-feed.php");
	include("inc/comment-tool.php");
	include("inc/comment-form.php");
	include("inc/comment-mail.php");
	include("inc/comment-redirect.php");
	include("inc/tag-internet.php");
	include("inc/tag-cloud.php");
	include("inc/meta-utf.php");
	include("inc/browsing-times.php");
	include("inc/short-code.php");
	include("inc/custom-meta-box.php");
	
// 后台引入自定义文件
if ( is_admin() ) require_once( TEMPLATEPATH . '/inc/theme-settings.php' );

function javin_opt($e){
    return stripslashes(get_option($e));
}
	
// enables wigitized sidebars（开启wigitized侧边栏）
	if ( function_exists('register_sidebar') )

    // Sidebar Widget（边栏小工具）
	// Location: the sidebar
	register_sidebar(array('name'=>'Sidebar',
		'before_widget' => '<div class="box" id="%2$s">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="box_title ption_r mb20 f_l"><span>',
		'after_title' => '</span></h3><div class="box_content">',
	));
	// Header Widget（头部小工具）
	// Location: right after the navigation
	register_sidebar(array('name'=>'Header',
		'before_widget' => '<ul>',
		'after_widget' => '</ul>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	// Footer Widget（尾部小工具）
	// Location: at the top of the footer, above the copyright
	register_sidebar(array('name'=>'Footer',
		'before_widget' => '<div class="widget-area widget-footer"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	// custom menu support（开启自定义菜单）
	add_theme_support( 'menus' );
	if ( function_exists( 'register_nav_menus' ) ) {
	  	register_nav_menus(
	  		array(
	  		  'header-menu' => '头部导航'
	  		)
	  	);
	}
	
?>