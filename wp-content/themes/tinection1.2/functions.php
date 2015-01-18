<?php
/**
 * Main Function of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.2
 * @date      2014.12.28
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<?php
/* ------------------------------------------------------------------------- *
 *  Custom functions
/* ------------------------------------------------------------------------- */

/* File Security Check */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Sets the path to the parent theme directory. */
if ( !defined( 'THEME_DIR' ) ) {
	define( 'THEME_DIR', get_template_directory() );
}

/* Sets the path to the parent theme directory URI. */
if ( !defined( 'THEME_URI' ) ) {
	define( 'THEME_URI', get_template_directory_uri() );
}
	
/* ------------------------------------------------------------------------- *
 *  OptionTree framework integration: Use in theme mode
/* ------------------------------------------------------------------------- */	
	add_filter( 'ot_show_pages', '__return_false' );
	add_filter( 'ot_show_new_layout', '__return_false' );
	add_filter( 'ot_theme_mode', '__return_true' );
	add_filter( 'ot_show_settings_import', '__return_true' );
	add_filter( 'ot_show_settings_export', '__return_true' );
	add_filter( 'ot_show_docs', '__return_false' );
	load_template( get_template_directory() . '/option-tree/ot-loader.php' );
/* ------------------------------------------------------------------------- *
 *  Load theme files
/* ------------------------------------------------------------------------- */
function tin_theme_localized($local){
	if(ot_get_option('lan_en')=='on'){
		$local = 'en_US';
	}else{
		$local = 'zh_CN';
	}
	return $local;
}
add_filter('locale','tin_theme_localized');

if ( ! function_exists( 'tin_load' ) ) {
	function tin_load() {
		// Load theme options
		load_template( THEME_DIR . '/admin/theme-options.php' );		
		// Load custom widgets
		load_template( THEME_DIR . '/functions/widgets/tin-tabs.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-posts.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-posts-h.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-tagcloud.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-enhanced-text.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-readerwall.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-mailcontact.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-site.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-float-widget.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-bookmark.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-subscribe.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-aboutsite.php' );
		load_template( THEME_DIR . '/functions/widgets/tin-joinus.php' );
		// Load functions
		load_template( THEME_DIR . '/functions/open-social.php' );
		load_template( THEME_DIR . '/functions/message.php' );
		load_template( THEME_DIR . '/functions/credit.php' );
		load_template( THEME_DIR . '/functions/recent-user.php' );
		load_template( THEME_DIR . '/functions/tracker.php' );
		load_template( THEME_DIR . '/functions/user-page.php' );
		load_template( THEME_DIR . '/functions/meta.php' );
		load_template( THEME_DIR . '/functions/comment.php' );
		load_template( THEME_DIR . '/functions/shortcode.php' );
		load_template( THEME_DIR . '/functions/IP.php' );
		load_template( THEME_DIR . '/functions/mail.php' );
		load_template( THEME_DIR . '/functions/meta-box.php' );
		load_template( THEME_DIR . '/functions/newsletter.php' );
		load_template( THEME_DIR . '/functions/ua.php' );
		load_template( THEME_DIR . '/functions/download.php' );
		load_template( THEME_DIR . '/functions/mobile_detect.php' );
		load_template( THEME_DIR . '/functions/no_category_base.php' );
		if (is_admin()) {require_once( THEME_DIR . '/functions/class-tgm-plugin-activation.php' );}
		// Load language
		load_theme_textdomain('tinection',get_template_directory().'/languages');
		load_theme_textdomain( 'option-tree',get_template_directory().'/option-tree/languages');
		
		// 移除自动保存和修订版本 
		if(ot_get_option('wp_auto_save')=='on'){
			add_action('wp_print_scripts','tin_disable_autosave' );
			remove_action('post_updated','wp_save_post_revision' );
		}

		//建立Avatar上传文件夹
		tin_add_avatar_folder();
	}
}
add_action( 'after_setup_theme', 'tin_load' );	

/* ------------------------------------------------------------------------- *
 *  移除头部多余信息
/* ------------------------------------------------------------------------- */	
function wpbeginner_remove_version(){
	return;
}
add_filter('the_generator', 'wpbeginner_remove_version');//wordpress的版本号
remove_action('wp_head', 'feed_links', 2);//包含文章和评论的feed
remove_action('wp_head','index_rel_link');//当前文章的索引
remove_action('wp_head', 'feed_links_extra', 3);// 额外的feed,例如category, tag页
remove_action('wp_head', 'start_post_rel_link', 10, 0);// 开始篇 
remove_action('wp_head', 'parent_post_rel_link', 10, 0);// 父篇 
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // 上、下篇. 
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );//rel=pre
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );//rel=shortlink 
remove_action('wp_head', 'rel_canonical' );

/* ------------------------------------------------------------------------- *
 *  前台替换wordpress自带jquery
/* ------------------------------------------------------------------------- */	
if ( !is_admin() ){
	function add_scripts() { 
	wp_deregister_script( 'jquery' ); 
	//wp_deregister_script( 'jquery ui' );
	wp_register_script( 'jquery', get_template_directory_uri() . '/includes/js/jquery.min.js' ); 
	//wp_register_script( 'jquery ui', 'http://lib.sinaapp.com/js/jquery-ui/1.9.2/jquery-ui.min.js');
	wp_enqueue_script( 'jquery' ); 
	//wp_enqueue_script( 'jquery ui' );
	wp_register_script( 'tinection', get_template_directory_uri() .'/includes/js/theme.js' );
	} 
}
add_action('wp_enqueue_scripts', 'add_scripts');

/* -------------------------------------------------- *
 * WordPress 后台禁用Google Open Sans字体，加速网站
/* ------------------------------------------------- */
function tin_disable_open_sans( $translations, $text, $context, $domain ) {
  if ( 'Open Sans font: on or off' == $context && 'on' == $text ) {
    $translations = 'off';
  }
  return $translations;
}
add_filter( 'gettext_with_context', 'tin_disable_open_sans', 888, 4 );

/* 后台预览
/* --------- */
add_editor_style('/includes/css/editor-style.css');

/* 建立Avatar上传文件夹
/* ----------- */
function tin_add_avatar_folder() {
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/avatars';
    if (! is_dir($upload_dir)) {
       mkdir( $upload_dir, 0755 );
    }
}

/* 移除自动保存
/* -------------- */
function tin_disable_autosave() {
  wp_deregister_script('autosave');
}
//删除已经产生的修订版本请取消下一条语句注释并刷新网站任一页面，完成后可恢复注释
//$wpdb->query("DELETE FROM $wpdb->posts WHERE post_type = 'revision'");

/* 修改后台页脚文字
/* ------------ */
function left_admin_footer_text($text) {
$text = '<span id="footer-thankyou">感谢使用<a href=http://cn.wordpress.org/ >WordPress</a>进行创作，使用<a href="http://www.zhiyanblog.com/tinection.html">Tinection</a>主题定制网站样式</span>';
return $text;
}
add_filter('admin_footer_text','left_admin_footer_text');
	
/* 阻止站内文章Pingback
/* --------------------- */ 
function tin_noself_ping( &$links ) {
  $home = get_option( 'home' );
  foreach ( $links as $l => $link )
  if ( 0 === strpos( $link, $home ) )
  unset($links[$l]);
}
add_action('pre_ping','tin_noself_ping');

/*  Theme setup
/* ------------------ */
if ( ! function_exists( 'tin_setup' ) ) {

	function tin_setup() {	
		// 开启自动feed地址
		add_theme_support( 'automatic-feed-links' );
		
		// 开启缩略图
		add_theme_support( 'post-thumbnails' );
		
		// 增加文章形式
		add_theme_support( 'post-formats', array( 'audio', 'aside', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
		
		// 图片上传时形成的缩略图尺寸
		add_image_size( 'thumbnail', 225, 150, true );
		add_image_size( 'medium', 375, 250, true );
		add_image_size( 'large', 750, 500, true );

		// 菜单区域
		register_nav_menus( array(
			'topbar' => '顶部菜单',
			'footbar' => '底部菜单',
			'pagebar' => '页面合并菜单',
		) );		
	}
	
}
add_action( 'after_setup_theme', 'tin_setup' );

/* 搜索结果排除所有页面
/* --------------------- */
function search_filter_page($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}
add_filter('pre_get_posts','search_filter_page');

/*  摘要长度
/* ---------- */
if ( ! function_exists( 'tin_excerpt_length' ) ) {

	function tin_excerpt_length( $length ) {
		return ot_get_option('excerpt-length',$length);
	}
	
}
add_filter( 'excerpt_length', 'tin_excerpt_length', 999 );

/* 摘要去除短代码
/* ----------------- */
function tin_excerpt_delete_shortcode($excerpt){
	$r = "'\[button(.*?)+\](.*?)\[\/button]|\[toggle(.*?)+\](.*?)\[\/toggle]|\[callout(.*?)+\](.*?)\[\/callout]|\[infobg(.*?)+\](.*?)\[\/infobg]|\[tinl2v(.*?)+\](.*?)\[\/tinl2v]|\[tinr2v(.*?)+\](.*?)\[\/tinr2v]|\<pre(.*?)+\>(.*?)\<\/pre>|\[php(.*?)+\](.*?)\[\/php]|\[PHP(.*?)+\](.*?)\[\/PHP]'";
	return preg_replace($r, '', $excerpt);
}
add_filter( 'the_excerpt', 'tin_excerpt_delete_shortcode', 999 );

/* 替换摘要后more字样
/* -------------------- */
function new_excerpt_more($more) {
       // global $post;
	// return '<a class="more-link" style="text-decoration:none;" href="'. get_permalink($post->ID) . '"> <i class="fa fa-share"></i></a>';
	$readmore=ot_get_option('readmore');
       return $readmore;
}
add_filter('excerpt_more', 'new_excerpt_more');

/* 去除正文P标签包裹 */
//remove_filter( 'the_content', 'wpautop' );

/* 去除摘要P标签包裹 */
remove_filter( 'the_excerpt', 'wpautop' );

/* 改变正文P标签包裹优先级 */
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 12);

/* 在文本小工具不自动添加P标签 */
add_filter( 'widget_text', 'shortcode_unautop' );
/* 在文本小工具也执行短代码 */
add_filter( 'widget_text', 'do_shortcode' );

/* 登录用户浏览站点时不显示工具栏 */
add_filter('show_admin_bar', '__return_false');

/* 添加链接功能 */
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/* 后台编辑器强化
/* --------------- */
function add_more_buttons($buttons){  
	$buttons[] = 'fontsizeselect';  
	$buttons[] = 'styleselect';  
	$buttons[] = 'fontselect';  
	$buttons[] = 'hr';  
	$buttons[] = 'sub';  
	$buttons[] = 'sup';  
	$buttons[] = 'cleanup';  
	$buttons[] = 'image';  
	$buttons[] = 'code';  
	$buttons[] = 'media';  
	$buttons[] = 'backcolor';  
	$buttons[] = 'visualaid';  
	return $buttons;  
}  
add_filter("mce_buttons_3", "add_more_buttons");

/* 后台编辑器文本模式添加短代码快捷输入按钮
/* ------------------------------------------ */
function my_quicktags() {
    wp_enqueue_script('my_quicktags',get_stylesheet_directory_uri().'/includes/js/my_quicktags.js',array('quicktags'));
}
add_action('admin_print_scripts', 'my_quicktags');
	
/*  增加用户资料字段
/* ------------------ */
function tin_add_contact_fields($contactmethods){
	$contactmethods['tin_qq'] = 'QQ';
	$contactmethods['tin_qq_weibo'] = __('腾讯微博','tinection');
	$contactmethods['tin_sina_weibo'] = __('新浪微博','tinection');
	$contactmethods['tin_weixin'] = __('微信','tinection');
	$contactmethods['tin_twitter'] = __('Twitter','tinection');
	$contactmethods['tin_googleplus'] = 'Google+';
	$contactmethods['tin_donate'] = __('赞助链接','tinection');
	return $contactmethods;
}
add_filter('user_contactmethods', 'tin_add_contact_fields');

/*  无缩略图时抓取第一张图片或输出随机图片
/* ------------------------------------ */
 function catch_first_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];
  if(empty($first_img)){
		$categories = get_the_category();
		foreach ($categories as $cat){
			$catid = $cat->cat_ID;
			break;
		}		
		$random = mt_rand(1, 40);
		$first_img = get_bloginfo ( 'stylesheet_directory' );
		$catidstext = ot_get_option('catgorydefaultimg');
		if(!empty($catidstext)){
			$catids = explode (',' , $catidstext);
			$numbers = count($catids);
			$unmatchs = 0;
			foreach ($catids as $catidsarray){
				if ($catidsarray[0]==$catid){
					$first_img .= '/images/cat-'.$catidsarray[0].'.jpg';
					break;
				}else{$unmatchs++; continue; }		
			}
			if ($unmatchs == $numbers){
				$first_img .= '/images/random/'.$random.'.jpg';
			}
		}
		else{$first_img .= '/images/random/'.$random.'.jpg';}
  }
  return $first_img;
 }

/* 缩略图采用类型
/* ----------------- */
function tin_thumb_source($src,$w=375,$h=250,$customize=true){
	$timthumb = ot_get_option('timthumb');
	$cloudimgsuffix = ot_get_option('cloudimgsuffix');
	if($timthumb==='on'){
		$img = get_bloginfo('template_url').'/functions/timthumb.php?src='.$src.'&q=100&w='.$w.'&h='.$h.'&zc=1';
	}else{
		if(empty($cloudimgsuffix)||$customize==false) $cloudimgsuffix = '?imageView2/1/w/'.$w.'/h/'.$h.'/q/100';
		$img = $src.$cloudimgsuffix;
	}
	return $img;
}

/*  注册通用边栏
/* -------------- */
if ( ! function_exists( 'tin_custom_sidebars' ) ) {

	function tin_custom_sidebars() {
		if ( !ot_get_option('sidebar-areas') =='' ) {
			
			$sidebars = ot_get_option('sidebar-areas', array());
			
			if ( !empty( $sidebars ) ) {
				foreach( $sidebars as $sidebar ) {
					if ( isset($sidebar['title']) && !empty($sidebar['title']) && isset($sidebar['id']) && !empty($sidebar['id']) && ($sidebar['id'] !='sidebar-') ) {
						register_sidebar(array('name' => ''.$sidebar['title'].'','id' => ''.strtolower($sidebar['id']).'','before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>'));
					}
				}
			}
		}
	}
	
}
add_action( 'widgets_init', 'tin_custom_sidebars' );

/*  动态primary边栏
/* ------------------ */
if ( ! function_exists( 'tin_sidebar_primary' ) ) {

	function tin_sidebar_primary() {
		// Default sidebar
		$sidebar = 'primary';

		// Set sidebar based on page
		if ( is_home() && ot_get_option('s1-home') ) $sidebar = ot_get_option('s1-home');
		if ( is_single() && ot_get_option('s1-single') ) $sidebar = ot_get_option('s1-single');
		if ( is_archive() && ot_get_option('s1-archive') ) $sidebar = ot_get_option('s1-archive');
		if ( is_category() && ot_get_option('s1-archive-category') ) $sidebar = ot_get_option('s1-archive-category');
		if ( is_search() && ot_get_option('s1-search') ) $sidebar = ot_get_option('s1-search');
		if ( is_404() && ot_get_option('s1-404') ) $sidebar = ot_get_option('s1-404');
		if ( is_page() && ot_get_option('s1-page') ) $sidebar = ot_get_option('s1-page');

		// Check for page/post specific sidebar
		if ( is_page() || is_single() ) {
			// Reset post data
			wp_reset_postdata();
			global $post;
			// Get meta
			$meta = get_post_meta($post->ID,'tin_sidebar_primary',true);
			if ( $meta ) { $sidebar = $meta; }
		}

		// Return sidebar
		return $sidebar;
	}
	
}

/*  注册页脚边栏
/* --------------- */
if ( ! function_exists( 'tin_sidebars' ) ) {
	
	function tin_sidebars() {
		register_sidebar(array( 'name' => 'Primary','id' => 'primary','description' => __("默认边栏区，请在后台设置选择各页面的边栏",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>'));
		register_sidebar(array( 'name' => 'Float','id' => 'float','description' => __("浮动边栏，容纳一定小工具，随鼠标滚动超出可视区域后将浮动重新显示",'tinection'), 'before_widget' => '<div id="%1$s" class="%2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>'));
		if ( ot_get_option('footer-widgets') >= '1' ) { register_sidebar(array( 'name' => 'Footer 1','id' => 'footer-1', 'description' => __("底部多列边栏1",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>')); }
		if ( ot_get_option('footer-widgets') >= '2' ) { register_sidebar(array( 'name' => 'Footer 2','id' => 'footer-2', 'description' => __("底部多列边栏2",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>')); }
		if ( ot_get_option('footer-widgets') >= '3' ) { register_sidebar(array( 'name' => 'Footer 3','id' => 'footer-3', 'description' => __("底部多列边栏3",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>')); }
		if ( ot_get_option('footer-widgets') >= '4' ) { register_sidebar(array( 'name' => 'Footer 4','id' => 'footer-4', 'description' => __("底部多列边栏4",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>')); }
		if ( ot_get_option('footer-widgets-singlerow') == 'on' ) { register_sidebar(array( 'name' => 'Footer row','id' => 'footer-row', 'description' => __("底部通栏",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>')); }
	}
	
}
add_action( 'widgets_init', 'tin_sidebars' );

/* 字符串剪切
/* ------------------ */
function cut_str($src_str,$cut_length){
	$return_str='';
	$i=0;
	$n=0;
	$str_length=strlen($src_str);
	while (($n<$cut_length) &&($i<=$str_length)){
		$tmp_str=substr($src_str,$i,1);
		$ascnum=ord($tmp_str);
		if ($ascnum>=224){
			$return_str=$return_str.substr($src_str,$i,3);
			$i=$i+3;
			$n=$n+2;
		}
		elseif ($ascnum>=192){
			$return_str=$return_str.substr($src_str,$i,2);
			$i=$i+2;
			$n=$n+2;
		}
		elseif ($ascnum>=65 &&$ascnum<=90){
			$return_str=$return_str.substr($src_str,$i,1);
			$i=$i+1;
			$n=$n+2;
		}
		else {
			$return_str=$return_str.substr($src_str,$i,1);
			$i=$i+1;
			$n=$n+1;
		}
	}
	if ($i<$str_length){
		$return_str = $return_str .'...';
	}
	if (get_post_status() == 'private'){
		$return_str = $return_str .'（private）';
	}
	return $return_str;
}

function utf8Substr($str, $from, $len){
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
          '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
          '$1',$str);
}

/* 首页分页导航 
/* -------------*/  
function pagenavi( $before = '', $after = '', $p = 2 ) {
    if ( is_singular() ) return;
    global $wp_query, $paged;
    $max_page = $wp_query->max_num_pages;
    if ( $max_page == 1 )
        return;
    if ( empty( $paged ) )
        $paged = 1;
    	// $before = "<span class='pg-item'><a href='".esc_html( get_pagenum_link( $i ) )."'>{$i}</a></span>";
    echo $before;
    if ( $paged > 1)
        p_link( $paged - 1, '上一页', '<span class="pg-item pg-nav-item pg-prev">' ,'上一页' );
    if ( $paged > $p + 1 )
        p_link( 1, '首页','<span class="pg-item">',1 );
    for( $i = $paged - $p; $i <= $paged + $p; $i++ ) {
        if ( $i > 0 && $i <= $max_page )
            $i == $paged ? print "<span class='pg-item pg-item-current'><span class='current'>{$i}</span></span>" : p_link( $i,'', '<span class="pg-item">',$i);
    }
    if ( $paged < $max_page - $p ) p_link( $max_page, __('末页','tinection'),'<span class="pg-item"> ... </span><span class="pg-item">',$max_page );
    if ( $paged < $max_page ) p_link( $paged + 1,__('下一页','tinection'), '<span class="pg-item pg-nav-item pg-next">' ,__('下一页','tinection'));
    echo $after;
}

function p_link( $i, $title = '', $linktype = '' , $prevnext='') {
    if ( $title == '' ) $title = __("浏览第{$i}页",'tinection');
    if ( $linktype == '' ) { $linktext = $i; } else { $linktext = $linktype; }
    echo "{$linktext}<a href='", esc_html( get_pagenum_link( $i ) ), "' title='{$title}' class='navbutton'>{$prevnext}</a></span>";
}

/* 个人信息页页码分页导航
/* ----------------------- */
function tin_paginate($wp_query=''){
	if(empty($wp_query)) global $wp_query;
	$pages = $wp_query->max_num_pages;
	if ( $pages >= 2 ):
		$big = 999999999;
		$paginate = paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $pages,
			'type' => 'array'
		) );
		echo '<div class="pagination">';
		foreach ($paginate as $value) {
			echo '<span class="pg-item">'.$value.'</span>';
		}
		echo '</div>';
	endif;
}

/* 个人信息页页码带下拉分页导航
/* ------------------------------ */
function tin_pager($current, $max){
	$paged = intval($current);
	$pages = intval($max);
	if($pages<2) return '';
	$pager = '<div class="pagination">';
		$pager .= '<div class="btn-group">';
			if($paged>1) $pager .= '<a class="btn btn-default" style="float:left;padding:6px 12px;" href="' . add_query_arg('page',$paged-1) . '">'.__('上一页','tinection').'</a>';
			if($paged<$pages) $pager .= '<a class="btn btn-default" style="float:left;padding:6px 12px;" href="' . add_query_arg('page',$paged+1) . '">'.__('下一页','tinection').'</a>';
		if ($pages>2 ){
			$pager .= '<div class="btn-group pull-right"><select class="form-control pull-right" onchange="document.location.href=this.options[this.selectedIndex].value;">';
				for( $i=1; $i<=$pages; $i++ ){
					$class = $paged==$i ? 'selected="selected"' : '';
					$pager .= sprintf('<option %s value="%s">%s</option>', $class, add_query_arg('page',$i), sprintf(__('第 %s 页','tinection'), $i));
				}
			$pager .= '</select></div>';
		}
	$pager .= '</div></div>';
	return $pager;
}

/* 首页布局
/* ----------- */
function the_layout(){
	$layout = 'blog';
	if(isset($_GET['layout'])){
		$layout = $_GET['layout'];
	}elseif(ot_get_option('layout')=='cms'){
		$layout = 'cms';
	}elseif(ot_get_option('layout')=='blocks'){
		$layout = 'blocks';
	}else{
		$layout = 'blog';
	}
	return $layout;
}

/* 简单相关文章(用于邮件)
/* ------------------------------ */
function tin_mail_relatedpost($postid){
	$tags = wp_get_post_tags($postid);
	$tagIDs = array();
	if ($tags) {$tagcount = count($tags);for ($i = 0;$i <$tagcount;$i++) {$tagIDs[$i] = $tags[$i]->term_id;}
			$args=array(
				'tag__in'=>$tagIDs,
				'post__not_in'=>array($postid),
				'showposts'=>5,
				'orderby'=>'rand',
				'caller_get_posts'=>1
			);
	$my_query = new WP_Query($args);
		if( $my_query->have_posts() ) {$related = '<h3 style="padding-left:10px;margin:10px 0 5px;font-size:16px;font-weight:bold;border-left:3px solid #1cbdc5;line-height:25px;height:25px;">'.__('相关文章','tinection').'</h3><ul>'; 
		while ($my_query->have_posts()) : $my_query->the_post(); $related .= '<li style="list-style:circle;font-size:13px;"><a style="color:#1cbdc5;font-size:13px;font-family:微软雅黑;" href="'; $related .= get_permalink(); $related .= '" rel="bookmark" title="'; $related .= get_the_title($post->ID);$related .= '">'; $related .= get_the_title($post->ID); $related .= ' ( '; $related .= get_comments_number($post->ID); $related .= ' ) '; $related .= '</a></li>';endwhile; $related .= '</ul>';}}
	return $related;
}

/* 加密文章
/* --------- */
function password_hint( $c ){
	global $post, $user_ID, $user_identity;
	if ( empty($post->post_password) )
		return $c;
	if ( isset($_COOKIE['wp-postpass_'.COOKIEHASH]) && stripslashes($_COOKIE['wp-postpass_'.COOKIEHASH]) == $post->post_password )
		return $c;
	if($hint = get_post_meta($post->ID, 'password_hint', true)){
		$url = get_option('siteurl').'/wp-pass.php';
		if($hint)
		$hint = __('密码提示：','tinection').$hint;
		else
		$hint = __("请输入您的密码",'tinection');
			if($user_ID)
			$hint .= sprintf(__('欢迎进入，您的密码是：','tinection'), $user_identity, $post->post_password);
			$out = <<<END
			<form method="post" action="$url">
			<p>__('这篇文章是受保护的文章，请输入密码继续阅读：','tinection')</p>
			<div>
			<label>$hint<br/>
			<input type="password" name="post_password"/></label>
			<input type="submit" value="__('输入密码','tinection')" name="Submit"/>
			</div>
			</form>
END;
	return $out;
	}else{
		return $c;
	}
}
add_filter('the_content', 'password_hint');

/* 页面meta信息条
/* --------------- */
function tin_post_meta($special=0){
	$thelayout = the_layout(); 
	if(is_singular()){ ?>
	<div id="single-meta">
		<span class="single-meta-author"><i class="fa fa-user">&nbsp;</i><?php the_author_posts_link();?></span>
		<span class="single-meta-edit"><?php edit_post_link(__(' 编辑 ','tinection')); ?></span>
		<span class="single-meta-time"><i class="fa fa-calendar">&nbsp;</i><?php echo timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ) ?></span>
		<span class="single-meta-category"><i class="fa fa-folder">&nbsp;</i><?php the_category(' ',''); ?></span>
		<span class="single-meta-comments"><?php if ( comments_open() ): ?>|&nbsp;<i class="fa fa-comments"></i>&nbsp;<a href="#" class="commentbtn"><?php comments_number( __('抢沙发','tinection'), __('1 条评论','tinection'), __('% 条评论','tinection') ); ?></a><?php else:?>|&nbsp;<i class="fa fa-comments"></i><?php _e(' 评论关闭','tinection'); ?><?php endif; ?></span>
		<span class="single-meta-views"><i class="fa fa-eye"></i>&nbsp;<?php echo get_tin_traffic( 'single' , get_the_ID() ); ?>&nbsp;</span>
	</div>
	<?php }elseif(is_search() || (is_home() && $thelayout == 'blog') || (is_category() && $special==0) || is_tag() || is_date() ){ ?>
	<div class="home-blog-entry-meta">
		<div class="postlist-meta">
			<div class="postlist-meta-time" style="font-style:italic;color:#b5b5b5;">&nbsp;<?php echo timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ) ?></div>
			<div class="postlist-meta-tags"><?php the_tags('',', ',''); ?></div>
			<div class="postlist-meta-views">/&nbsp;&nbsp;<?php echo get_tin_traffic( 'single' , get_the_ID() ); ?>&nbsp;℃&nbsp;/&nbsp;</div>
			<div class="postlist-meta-comments"><?php if ( comments_open() ): ?><i class="fa fa-comments"></i>&nbsp;<a href="<?php comments_link(); ?>"><?php comments_number( __('0<span> 条评论</span>','tinection'), __('1<span> 条评论</span>','tinection'), __('%<span> 条评论</span>','tinection') ); ?></a><?php  endif; ?></div>
			<?php get_template_part('includes/like_collect_meta'); ?>
		</div>
	</div>
	<?php }elseif((is_home()) || (is_category() && $special==1)){ ?>
	<div class="postlist-meta">
		<div class="postlist-meta-time"><i class="fa fa-clock-o"></i>&nbsp;<?php echo date('Y-m-j',get_the_time('U'));?></div>
		<div class="postlist-meta-views" style="float:right;"><i class="fa fa-eye"></i>&nbsp;<?php echo get_tin_traffic( 'single' , get_the_ID() ); ?></div>
		<?php get_template_part('includes/like_collect_meta'); ?>
	</div>
	<?php }else{ ?>
	<div id="single-meta">
		<span class="single-meta-author"><i class="fa fa-user">&nbsp;</i><?php the_author_posts_link();?></span>
		<span class="single-meta-edit"><?php edit_post_link(__(' 编辑 ','tinection')); ?></span>
		<span class="single-meta-time"><i class="fa fa-calendar">&nbsp;</i><?php echo timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ) ?></span>
		<span class="single-meta-comments"><?php if ( comments_open() ): ?>|&nbsp;<i class="fa fa-comments"></i>&nbsp;<a href="<?php comments_link(); ?>"><?php comments_number( __('0 条评论','tinection'), __('1 条评论','tinection'), __('% 条评论','tinection') ); ?></a><?php else:?>|&nbsp;<i class="fa fa-comments"></i><?php _e(' 评论关闭','tinection'); ?><?php endif; ?></span>
		<span class="single-meta-views"><i class="fa fa-eye"></i>&nbsp;<?php echo get_tin_traffic( 'single' , get_the_ID() ); ?>&nbsp;</span>
	</div>
	<?php }
}

/* 时间显示方式xx以前
/* -------------------- */
function time_ago( $type = 'commennt', $day = 7 ) {
  $d = $type == 'post' ? 'get_post_time' : 'get_comment_time';
  if (time() - $d('U') > 60*60*24*$day) return;
  echo human_time_diff($d('U'), strtotime(current_time('mysql', 0))), '前';
}

function timeago( $ptime ) {
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return '刚刚';
    $interval = array (
        12 * 30 * 24 * 60 * 60  =>  '年前 ('.date('Y-m-d', $ptime).')',
        30 * 24 * 60 * 60       =>  '个月前 ('.date('m-d', $ptime).')',
        7 * 24 * 60 * 60        =>  '周前 ('.date('m-d', $ptime).')',
        24 * 60 * 60            =>  '天前',
        60 * 60                 =>  '小时前',
        60                      =>  '分钟前',
        1                       =>  '秒前'
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}

/* 文章目录
/* ----------- */
function content_index($content) {
	if(is_single()){
    $matches = array();
    $ul_li = '';
    $r = "/<h2>([^<]+)<\/h2>/im";
	$dlinks = get_post_meta(get_the_ID(),'tin_dload',true);
	$demos = get_post_meta(get_the_ID(),'tin_demo',true);
    if(preg_match_all($r, $content, $matches)) {
        foreach($matches[1] as $num => $title) {
            $content = str_replace($matches[0][$num], '<h2 id="title-'.$num.'">'.$title.'</h2>', $content);
            $ul_li .= '<li><a href="#title-'.$num.'" title="'.$title.'">'.$title."</a></li>\n";
        }
        if(!empty($dlinks) && !empty($demos)){
        	$ul_li .= '<li><a href="#title-last" title="'.__('演示与下载','tinection').'">'.__('演示与下载','tinection').'</a></li>';
        }
        if(!empty($dlinks) && empty($demos)){
        	$ul_li .= '<li><a href="#title-last" title="'.__('相关下载','tinection').'">'.__('相关下载','tinection').'</a></li>';
        }
        if(empty($dlinks) && !empty($demos)){
        	$ul_li .= '<li><a href="#title-last" title="'.__('相关演示','tinection').'">'.__('相关演示','tinection').'</a></li>';
        }

        $content = "\n<div id=\"content-index-wrap\"><div id=\"content-index\">
        		<span id=\"content-index-control\" class=\"open\">[".__('收起','tinection')."]</span>
                <b>文章目录</b>
                <ul id=\"index-ul\">\n" . $ul_li . "</ul>
            </div></div>\n" . $content;
    }
}
    return $content;
}
add_filter( "the_content", "content_index", 13 );

/* 文章图片添加 pirobox 类，用于图片暗箱
/* -------------------------------- */
function pirobox_gall_replace ($content){
	global $post;
	$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
	$replacement = '<a$1href=$2$3.$4$5 class="pirobox_gall"$6>$7</a>';
	$content = preg_replace($pattern, $replacement, $content);
	// $pattern2 = "/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)\>/i";
	// $replacement2 = '<a href=$2$3.$4$5 class="pirobox_gall"><img$1src=$2$3.$4$5 $6></a>';
	// $content = preg_replace($pattern2, $replacement2, $content);
	return $content;
}
add_filter('the_content', 'pirobox_gall_replace');

/* WordPress文字标签关键词自动内链
/* --------------------------------- */
$match_num_from = 1;		//一篇文章中同一個標籤少於幾次不自動鏈接
$match_num_to = 4;		//一篇文章中同一個標籤最多自動鏈接幾次
function tag_sort($a, $b){
	if ( $a->name == $b->name ) return 0;
	return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
}
function tag_link($content){
	global $match_num_from,$match_num_to;
		$posttags = get_the_tags();
		if ($posttags) {
			usort($posttags, "tag_sort");
			foreach($posttags as $tag) {
				$link = get_tag_link($tag->term_id);
				$keyword = $tag->name;
				$cleankeyword = stripslashes($keyword);
				$url = "<a href=\"$link\" class=\"tooltip-trigger tin\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('查看更多关于 %s 的文章'))."\"";
				$url .= ' target="_blank"';
				$url .= ">".addcslashes($cleankeyword, '$')."</a>";
				$limit = rand($match_num_from,$match_num_to);
				$content = preg_replace( '|(<a[^>]+>)(.*)<pre.*?>('.$ex_word.')(.*)<\/pre>(</a[^>]*>)|U'.$case, '$1$2$4$5', $content);
				$content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2$4$5', $content);
				$cleankeyword = preg_quote($cleankeyword,'\'');
				$regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
				$content = preg_replace($regEx,$url,$content,$limit);
				$content = str_replace( '', stripslashes($ex_word), $content);
			}
		}
	return $content;
}
add_filter('the_content','tag_link',1);

/* 高亮显示搜索关键词
/* ------------------- */
function search_word_replace($buffer){
	if(is_search()){
		$arr=explode(' ',get_search_query());
		foreach($arr as $v){
			if($v)$buffer=preg_replace('/('.$v.')/i',"<span style='color:#f00;font-weight:bold'>$1</span>",$buffer);
		}
	}
	return $buffer;
}

/* 输出文章版权信息
/* ------------------ */
function tin_post_copyright($post_id){
	
	$post_id = (int)$post_id;
	if(!$post_id) return;
		$cc = get_post_meta( $post_id, 'tin_copyright_content', true );
		$cc = empty($cc) ? ot_get_option('tin_copyright_content_default') : $cc;
		$cc = stripcslashes(htmlspecialchars_decode($cc));
		if($cc){ ?>
		<div class="single-copyright">
			<i class="fa fa-bullhorn">&nbsp;</i>
			<?php 
				$cc = str_replace(array( '{name}', '{url}', '{title}', '{link}'), array(get_bloginfo('name'), home_url('/'), get_the_title($post_id), get_permalink($post_id)), $cc);
				echo $cc;
				?>
		</div>
		<?php }
}

/* 作者类别
/* ----------------- */
function the_user_level(){
	$user_id=get_post($id)->post_author;   
if(user_can($user_id,'install_plugins')){_e('管理员','tinection');}   
elseif(user_can($user_id,'edit_others_posts')){_e('编辑','tinection');}elseif(user_can($user_id,'publish_posts')){_e('作者','tinection');}elseif(user_can($user_id,'delete_posts')){_e('投稿者','tinection');}elseif(user_can($user_id,'read')){_e('订阅者','tinection');}  
}

/* 文章类型适配图标
/* ----------------- */
function the_article_icon(){
	if ( is_sticky() ){echo '<i class="fa fa-star"></i>';}
	elseif ( has_post_format('audio') ){echo '<i class="fa fa-headphones"></i>';}
	elseif ( has_post_format('aside') ){echo '<i class="fa fa-pencil"></i>';}
	elseif ( has_post_format('chat') ){echo '<i class="fa fa-comments-o"></i>';}
	elseif ( has_post_format('gallery') ){echo '<i class="fa fa-picture-o"></i>';}
	elseif ( has_post_format('image') ){echo '<i class="fa fa-camera"></i>';}
	elseif ( has_post_format('link') ){echo '<i class="fa fa-link"></i>';}
	elseif ( has_post_format('quote') ){echo '<i class="fa fa-quote-left"></i>';}
	elseif ( has_post_format('status') ){echo '<i class="fa fa-bullhorn"></i>';}
	elseif ( has_post_format('video') ){echo '<i class="fa fa-video-camera"></i>';}
	else{echo'<i class="fa fa-plus"></i>';}
}

/* 点击评分 
/* -------------- */
function tin_refresh_rate(){
	$sid = $_POST['sid'];
	$pid = $_POST['pid'];
	$rating = get_post_meta($pid,'tin_rating',true);
	$rating_array = explode(',',$rating);
	$rateone = $rating_array[0];
	$ratetwo = $rating_array[1];
	$ratethree = $rating_array[2];
	$ratefour = $rating_array[3];
	$ratefive = $rating_array[4];
	$rateaverage = get_post_meta($pid,'tin_rating_average',true);
	empty($rateone)?$rateone=0:$rateone=$rateone;
	empty($ratetwo)?$ratetwo=0:$ratetwo=$ratetwo;
	empty($ratethree)?$ratethree=0:$ratethree=$ratethree;
	empty($ratefour)?$ratefour=0:$ratefour=$ratefour;
	empty($ratefive)?$ratefive=0:$ratefive=$ratefive;
	empty($rateaverage)?$rateaverage=0:$rateaverage=$rateaverage;
	$ratetimes = $rateone + $ratetwo + $ratethree + $ratefour + $ratefive;
	$ratetimes++;
	$ratescore = $rateone*1 + $ratetwo*2 + $ratethree*3 + $ratefour*4 + $ratefive*5;
	switch($sid){
	case 'starone':
		$rated = $rateone;
		$rated++;
		$ra = ($ratescore+1)/$ratetimes;
		$ratearr = array($rated,$ratetwo,$ratethree,$ratefour,$ratefive);
		$ratestr = implode(',',$ratearr);
		update_post_meta($pid,'tin_rating',$ratestr);
		update_post_meta($pid,'tin_rating_average',$ra);
		break;
	case 'startwo':
		$rated = $ratetwo;
		$rated++;
		$ra = ($ratescore+2)/$ratetimes;
		$ratearr = array($rateone,$rated,$ratethree,$ratefour,$ratefive);
		$ratestr = implode(',',$ratearr);
		update_post_meta($pid,'tin_rating',$ratestr);
		update_post_meta($pid,'tin_rating_average',$ra);
		break;
	case 'starthree':
		$rated = $ratethree;
		$rated++;
		$ra = ($ratescore+3)/$ratetimes;
		$ratearr = array($rateone,$ratetwo,$rated,$ratefour,$ratefive);
		$ratestr = implode(',',$ratearr);
		update_post_meta($pid,'tin_rating',$ratestr);
		update_post_meta($pid,'tin_rating_average',$ra);
		break;
	case 'starfour':
		$rated = $ratefour;
		$rated++;
		$ra = ($ratescore+4)/$ratetimes;
		$ratearr = array($rateone,$ratetwo,$ratethree,$rated,$ratefive);
		$ratestr = implode(',',$ratearr);
		update_post_meta($pid,'tin_rating',$ratestr);
		update_post_meta($pid,'tin_rating_average',$ra);
		break;
	case 'starfive':
		$rated = $ratefive;
		$rated++;
		$ra = ($ratescore+5)/$ratetimes;
		$ratearr = array($rateone,$ratetwo,$ratethree,$ratefour,$rated);
		$ratestr = implode(',',$ratearr);
		update_post_meta($pid,'tin_rating',$ratestr);
		update_post_meta($pid,'tin_rating_average',$ra);
		break;
	default:
		return;
	}
}
add_action( 'wp_ajax_nopriv_rating', 'tin_refresh_rate' );
add_action( 'wp_ajax_rating', 'tin_refresh_rate' );

/* 点击喜欢
/* -------------- */
function tin_like_article(){
	$pid = $_POST['pid'];
	$likes = get_post_meta($pid,'tin_post_likes',true);
	$likes++;
	update_post_meta($pid,'tin_post_likes',$likes);
}
add_action( 'wp_ajax_nopriv_like', 'tin_like_article' );
add_action( 'wp_ajax_like', 'tin_like_article' );

/* 点击收藏或取消收藏
/* ----------- */
function tin_collect(){
	$pid = $_POST['pid'];
	$uid = $_POST['uid'];
	$action = $_POST['act'];
	if($action!='remove'){
		$collect = get_user_meta($uid,'tin_collect',true);
		if(!empty($collect)){
			$collect .= ','.$pid;
			update_user_meta($uid,'tin_collect',$collect);
		}else{
			$collect = $pid;
			update_user_meta($uid,'tin_collect',$collect);		
		}
		$collects = get_post_meta($pid,'tin_post_collects',true);
		$collects++;
		update_post_meta($pid,'tin_post_collects',$collects);
	}else{
		$collect = get_user_meta($uid,'tin_collect',true);
		$collect = tin_delete_string_specific_value(',',$collect,$pid);
		update_user_meta($uid,'tin_collect',$collect);
		$collects = get_post_meta($pid,'tin_post_collects',true);
		$collects--;
		update_post_meta($pid,'tin_post_collects',$collects);
	}
}
add_action( 'wp_ajax_nopriv_collect', 'tin_collect' );
add_action( 'wp_ajax_collect', 'tin_collect' );

/* 文章归档
/* ------------ */
function PostCount(){
	$num_posts = wp_count_posts( 'post');
	return number_format_i18n( $num_posts->publish );
}

function tin_archives_list() {
     if( !$output = get_option('tin_archives_list') ){
         $output = '<div id="archives"><p>[<a id="al_expand_collapse" href="#">'.__('全部展开/收缩','tinection').'</a>] <em>('.__('注: 点击月份可以展开','tinection').')</em></p>';
         $the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1' ); //update: 加上忽略置顶文章
         $year=0; $mon=0; $i=0; $j=0;
         while ( $the_query->have_posts() ) : $the_query->the_post();
             $year_tmp = get_the_time('Y');
             $mon_tmp = get_the_time('m');
             $y=$year; $m=$mon;
             if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
             if ($year != $year_tmp && $year > 0) $output .= '</ul>';
             if ($year != $year_tmp) {
                 $year = $year_tmp;
                 $output .= '<h3 class="al_year">'. $year .__(' 年','tinection').'</h3><ul class="al_mon_list">'; //输出年份
             }
             if ($mon != $mon_tmp) {
                 $mon = $mon_tmp;
                 $output .= '<li><span class="al_mon">'. $mon .__(' 月','tinection').'</span><ul class="al_post_list">'; //输出月份
             }
             $output .= '<li>'. get_the_time(__('d日: ','tinection')) .'<a href="'. get_permalink() .'">'. get_the_title() .'</a> <em>('. get_comments_number('0', '1', '%') .__('条评论','tinection').')</em></li>'; //输出文章日期和标题
         endwhile;
         wp_reset_postdata();
         $output .= '</ul></li></ul></div>';
         update_option('tin_archives_list', $output);
     }
     echo $output;
 }
function clear_tin_cache(){
    update_option('tin_archives_list', ''); 
}
add_action('save_post', 'clear_tin_cache'); // 新发表文章/修改文章时

/* 邮件期刊页面期刊号下拉列表
/* --------------------------- */
function tin_past_issues_selection(){
	$issue_meta = get_tin_meta('issue') ? get_tin_meta('issue') : '';
	$issue_meta_array =explode(',',$issue_meta);
	$length = count($issue_meta_array);
	$current_issue = isset($_GET['issue']) ? $_GET['issue']:$length;
	for( $i=1; $i<=$length; $i++ ){
		$class = $current_issue==$i ? 'selected="selected"' : '';
		$options .= sprintf('<option %s value="%s">%s</option>', $class, get_bloginfo('home').'/newsletter?issue='.$i, sprintf(__('第 %s 期','tinection'), $i));
	}
	$html = '<!-- Past Issues Selection --><div style="max-width:800px;height:50px;margin:0 auto;padding:5px 0;"><div class="btn-group pull-right"><select class="form-control pull-right" onchange="document.location.href=this.options[this.selectedIndex].value;">'.$options.'</select></div><div class="btn-group pull-right" style="line-height:34px;padding-right:10px;color:#00B5FC;font-weight:bold;">'.__('选择期刊','tinection').'</div></div><!-- /. Past Issues Selection -->';
	return $html;
}

/* 必备与推荐插件安装提醒
/* ----------------------- */
function tin_register_required_plugins() {
	$plugins = array(
		// crayon-syntax-highlighter
		array(
			'name' => 'crayon-syntax-highlighter',
			'slug' => 'crayon-syntax-highlighter',
			'source' => '/crayon-syntax-highlighter.zip',
			'required' => true,
			'version' => '2.6.8',
			'force_activation' => false,
			'force_deactivation' => false
		),
		// woo-cmp-flash-video-player
		array(
			'name' => 'woo-cmp-flash-video-player',
			'slug' => 'woo-cmp-flash-video-player',
			'source' => '/woo-cmp-flash-video-player.zip',
			'required' => false,
			'version' => '1.0.0',
			'force_activation' => false,
			'force_deactivation' => false
		),
		// netease-music
		array(
			'name' => '网易云音乐插件',
			'slug' => 'netease-music',
			'source' => '/netease-music.zip',
			'required' => false,
			'version' => '1.4.0',
			'force_activation' => false,
			'force_deactivation' => false
		),

	);
	$config = array(
		'domain'       		=> 'tinection',         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> THEME_DIR .'/includes/plugins',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
		'page_title'                       			=> __( '安装必要的插件', 'tinection' ),
		'menu_title'                       			=> __( '安装插件', 'tinection' ),
		'installing'                       			=> __( '正在安装插件: %s', 'tinection' ), // %1$s = plugin name
		'oops'                             			=> __( '插件API出现了一些问题', 'tinection' ),
		'notice_can_install_required'     			=> _n_noop( '本主题需要以下插件: %1$s.', '本主题需要以下插件: %1$s.' ), // %1$s = plugin name(s)
		'notice_can_install_recommended'			=> _n_noop( '本主题推荐以下插件: %1$s.', '本主题推荐以下插件: %1$s.' ), // %1$s = plugin name(s)
		'notice_cannot_install'  					=> _n_noop( '抱歉，你没有正确的许可去安装 %s 插件. ', '抱歉，你没有正确的许可去安装 %s 插件.' ),
		'notice_can_activate_required'    			=> _n_noop( '下列必须插件当前未激活: %1$s.', '下列必须插件当前未激活: %1$s.' ), 
		'notice_can_activate_recommended'			=> _n_noop( '下列推荐插件当前未激活: %1$s.', '下列推荐插件当前未激活: %1$s.' ), 
		'notice_cannot_activate' 					=> _n_noop( '抱歉，你没有正确的许可去激活 %s 插件.', '抱歉，你没有正确的许可去激活 %s 插件.' ),
		'notice_ask_to_update' 						=> _n_noop( '下列插件需要更新至最新版本以提高兼容性: %1$s.', '下列插件需要更新至最新版本以提高兼容性: %1$s.' ), // %1$s = plugin name(s)
		'notice_cannot_update' 						=> _n_noop( '抱歉，你没有正确的许可去更新 %s 插件.', '抱歉，你没有正确的许可去更新 %s 插件.' ),
		'install_link' 					  			=> _n_noop( '开始安装插件', '开始安装插件' ),
		'activate_link' 				  			=> _n_noop( '激活已安装插件', '激活已安装插件' ),
		'return'                           			=> __( '返回必须插件安装器', 'tinection' ),
		'plugin_activated'                 			=> __( '插件成功激活', 'tinection' ),
		'complete' 									=> __( '所有插件已安装并激活成功 %s', 'tinection' ), // %1$s = dashboard link
		'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);
	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'tin_register_required_plugins' );

/* 自定义头像
/* --------------- */
function fb_addgravatar( $avatar_defaults ) {
$myavatar = get_bloginfo('template_directory') . '/images/gravatar.png';
  $avatar_defaults[$myavatar] = __('自定义头像','tinection');
  return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'fb_addgravatar' );

/* 头像缓存
/* --------- */
function my_avatar($avatar) {
	if(ot_get_option('localavatar') == 'on'){
	    $tmp = strpos($avatar, 'http');      
        $len = strpos($avatar, "'", $tmp) - $tmp;      
    	$g = substr($avatar, $tmp, $len);           
    	$tmp = strpos($g, 'avatar/') + 7;      
   		$len2 = strpos($g, "?", $tmp) - $tmp;      
    	$f = substr($g, $tmp, $len2);      
	    $w = get_bloginfo('wpurl');      
	    $e = ABSPATH .'avatar/'. $f .'.jpg';      
	    $t = 604900;       
		    if ( !is_file($e) || (time() - filemtime($e)) > $t ) {      
		        copy(htmlspecialchars_decode($g), $e);      
    		} else{      
		        $avatar = strtr($avatar, array($g => $w.'/avatar/'.$f.'.jpg'));      
    		}      
    		if( filesize($e) < 500 ){      
		        copy($w.'/avatar/default.jpg', $e);      
    		}      
	    return $avatar;
	}
}
//add_filter('get_avatar', 'my_avatar');
//改用多说gravatar服务器
function mytheme_get_avatar($avatar) {
	if(ot_get_option('duoshuoavatar') == 'on'){
		$avatar = str_replace(array("www.gravatar.com","0.gravatar.com","1.gravatar.com","2.gravatar.com"),"gravatar.duoshuo.com",$avatar);
		return $avatar;
	}else{return $avatar;}
}
add_filter( 'get_avatar', 'mytheme_get_avatar', 10, 3 );

/* 获取头像
/* ------------ */
function tin_get_avatar( $id , $size='40' , $type=''){
	if($type==='qq'){
		$O = array(
			'ID'=>ot_get_option('tin_open_qq_id'),
			'KEY'=>ot_get_option('tin_open_qq_key')
		);
		$U = array(
			'ID'=>get_user_meta( $id, 'tin_qq_openid', true ),
			'TOKEN'=>get_user_meta( $id, 'tin_qq_access_token', true )
		);	
		if( $O['ID'] && $O['KEY'] && $U['ID'] && $U['TOKEN'] ){
			$avatar_url = 'http://q.qlogo.cn/qqapp/'.$O['ID'].'/'.$U['ID'].'/100';
		}	
	}else if($type==='weibo'){
		$O = array(
			'KEY'=>ot_get_option('tin_open_weibo_key'),
			'SECRET'=>ot_get_option('tin_open_weibo_secret')
		);
		$U = array(
			'ID'=>get_user_meta( $id, 'tin_weibo_openid', true ),
			'TOKEN'=>get_user_meta( $id, 'tin_weibo_access_token', true )
		);
		if( $O['KEY'] && $O['SECRET'] && $U['ID'] && $U['TOKEN'] ){
			$avatar_url = 'http://tp3.sinaimg.cn/'.$U['ID'].'/180/1.jpg';
		}
	}else if($type==='customize'){
		$avatar_url = get_bloginfo('home').'/wp-content/uploads/avatars/'.get_user_meta($id,'tin_customize_avatar',true);
	}else{
		preg_match("/src='(.*?)'/i", get_avatar( $id, $size ), $matches);
		$avatar_url = $matches[1];
	}
	return '<img src="'.$avatar_url.'" class="avatar" width="'.$size.'" height="'.$size.'" />';
}

/* 获取头像类型
/* --------------- */
function tin_get_avatar_type($user_id){
	$id = (int)$user_id;
	if($id===0) return;
	$avatar = get_user_meta($id,'tin_avatar',true);
	$customize = get_user_meta($id,'tin_customize_avatar',true);
	if( $avatar=='qq' && tin_is_open_qq($id) ) return 'qq';
	if( $avatar=='weibo' && tin_is_open_weibo($id) ) return 'weibo';
	if( $customize && !empty($customize) ) return 'customize';
	return 'default';
}

/* 头像上传裁剪
/* -------------- */
function resize( $ori ){
    if( preg_match('/^http:\/\/[a-zA-Z0-9]+/', $ori ) ){
        return $ori;
    }
    $info = getImageInfo( AVATARS_PATH . $ori );
    if( $info ){
        //上传图片后切割的最大宽度和高度
        $dst_width = 100;
        $dst_height = 100;
        $scrimg = AVATARS_PATH . $ori;
        if( $info['type']=='jpg' || $info['type']=='jpeg' ){
            $im = imagecreatefromjpeg( $scrimg );
        }
        if( $info['type']=='gif' ){
            $im = imagecreatefromgif( $scrimg );
        }
        if( $info['type']=='png' ){
            $im = imagecreatefrompng( $scrimg );
        }
        if( $info['type']=='bmp' ){
            $im = imagecreatefromwbmp( $scrimg );
        }
        if( $info['width']<=$dst_width && $info['height']<=$height ){
            return;
        } else {
            if( $info['width'] > $info['height'] ){
                $height = intval($info['height']);
                $width = $height;
                $x = ($info['width']-$width)/2;
                $y = 0;
            } else {
                $width = intval($info['width']);
                $height = $width;
                $x = 0;
                $y = ($info['height']-$height)/2;
            }

        }
        $newimg = imagecreatetruecolor( $width, $height );
        imagecopy($newimg,$im,0,0,$x,$y,$info['width'],$info['height']);
        $scale = $dst_width/$width;
        $target = imagecreatetruecolor($dst_width, $dst_height);
        $final_w = intval($width*$scale);
        $final_h = intval($height*$scale);
        imagecopyresampled( $target, $newimg, 0, 0, 0, 0, $final_w, $final_h, $width, $height );
        imagejpeg( $target, AVATARS_PATH . $ori );
        imagedestroy( $im );
        imagedestroy( $newimg );
        imagedestroy( $target );
    }
    return;
}

function getImageInfo( $img ){
    $imageInfo = getimagesize($img);
    if( $imageInfo!== false) {
        $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
        $info = array(
                "width"     =>$imageInfo[0],
                "height"    =>$imageInfo[1],
                "type"      =>$imageType,
                "mime"      =>$imageInfo['mime'],
        );
        return $info;
    }else {
        return false;
    }
}

/* 生成随机文件名函数
/* ------------------- */       
function randomname($length=10){   
    $hash = '';   
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';   
    $max = strlen($chars) - 1;   
    mt_srand((double)microtime() * 1000000);   
    for($i = 0; $i < $length; $i++){   
        $hash .= $chars[mt_rand(0, $max)];   
    }   
    return $hash;   
} 

/* 通过AJAX获取并保存到cookie是防止页面进行缓存加速后nonce不能及时更新
/* -------------------------------------------------------------------- */
function tin_create_nonce_callback(){

	echo wp_create_nonce( 'check-nonce' );

   die();
}
add_action( 'wp_ajax_tin_create_nonce', 'tin_create_nonce_callback' );
add_action( 'wp_ajax_nopriv_tin_create_nonce', 'tin_create_nonce_callback' );

/* 获取登录状态
/* ------------- */
function tin_get_login_status(){
	if(is_user_logged_in()){
		$msg = 1;
	}else{
		$msg = 0;
	}
	$arr = array('status'=>$msg);
	$json = json_encode($arr);
	echo $json;
	exit;
}
add_action( 'wp_ajax_checklogin', 'tin_get_login_status' );
add_action( 'wp_ajax_nopriv_checklogin', 'tin_get_login_status' );

/* 获取当前页面url
/* ---------------- */
function tin_get_current_page_url(){
	$ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port  = $_SERVER['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    return $protocol . '://' . $host . $port . $_SERVER['REQUEST_URI'];
}

//判断移动终端
function tin_is_mobile() {
    $detect = new Mobile_Detect;
	return $detect->isMobile();
}

/* PHP POST or GET提交
/* ------------------------------- */
function tin_get_url($url, $post='', $method='GET'){
	$content = is_array($post) ? http_build_query($post) : $post;
	$content_length = strlen($content);
	$options = array(
            'http' => array(
                'method' => $method,
                'header' =>
                "Content-type: application/x-www-form-urlencoded\r\n" .
                "Content-length: $content_length\r\n",
                'content' => $content
                //'timeout' => 60
            )
        );
	return file_get_contents($url, false, stream_context_create($options));
}

/* 获取GET方法http响应状态代码
/* ------------------------------ */
function tin_get_http_response_code($theURL) {
	@$headers = get_headers($theURL);
	return substr($headers[0], 9, 3);
}

/* Curl POST
/* ---------- */
function tin_curl_post($url,$data){
	$post_data = http_build_query($data);
	$post_url= $url;
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_URL, $post_url );
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$return = curl_exec($ch);
	if (curl_errno($ch)) {      
       return '';      
    }
	curl_close($ch);
	return $return;
}

/* Socket POST
/* ------------- */
function tin_socket_post($url, $data, $referer=''){
		if( ! is_array($data))	return;
		$data = http_build_query($data);
		$url = parse_url($url);
		if ( ! isset($url['scheme']) || $url['scheme'] != 'http'){die('Error: Only HTTP request are supported !');}
		$host = $url['host'];
		$path = isset($url['path']) ? $url['path'] : '/';
		$fp = fsockopen($host, 80, $errno, $errstr, 30);
		if ($fp){
			$length = strlen($data);
			$POST = <<<HEADER
POST {$path} HTTP/1.1
Accept: text/plain, text/html
Referer: {$referer}
Accept-Language: zh-CN,zh;q=0.8
Content-Type: application/x-www-form-urlencoded 
Cookie: token=value; pub_cookietime=2592000; pub_sauth1=value; pub_sauth2=value
User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.56 Safari/537.17
Host: {$host}
Content-Length: {$length}
Pragma: no-cache
Cache-Control: no-cache
Connection: close\r\n
{$data}
HEADER;
		fwrite($fp, $POST);
		$result = '';
		while(!feof($fp)){$result .= fread($fp, 512);}
		}else{
			return array(
					'status' => 'error',
					'error' => "$errstr ($errno)"
					);
		}
		fclose($fp);
		$result = explode("\r\n\r\n", $result, 2);
		return array(
				'status' => 'ok',
				'header' => isset($result[0]) ? $result[0] : '',
				'content' => isset($result[1]) ? $result[1] : ''
				);
}

/* 启动主题时清理检查任务
/* ------------------------- */
function tin_clear_version_check(){
	global $pagenow;   
	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){
		wp_clear_scheduled_hook( 'tin_check_version_daily_event' );
	}
}
add_action( 'load-themes.php', 'tin_clear_version_check' ); 

/* 每天00:00检查主题版本
/* ------------------------ */
function tin_check_version_setup_schedule() {
	if ( ! wp_next_scheduled( 'tin_check_version_daily_event' ) ) {
		wp_schedule_event( '1193875200', 'daily', 'tin_check_version_daily_event');
	}
}
add_action( 'wp', 'tin_check_version_setup_schedule' );
	
/* 检查主题版本回调函数
/* ---------------------- */
function tin_check_version_do_this_daily() {
	if(tin_get_http_response_code('http://cdn.zhiyanblog.com/tinection/version.json')=='200'){
		$check = 0;
		$tinVersion = wp_get_theme()->get( 'Version' );
		$version = json_decode(tin_get_url('http://cdn.zhiyanblog.com/tinection/version.json'),true);
		if ( $version["version"] != $tinVersion && !empty($version["version"]) ) $check = $version["version"];
		update_option('tin_theme_upgrade',$check);
	}
}
add_action( 'tin_check_version_daily_event', 'tin_check_version_do_this_daily' );
	
/* 新版本提示
/* ------------- */
function tin_update_alert_callback(){
	$tin_upgrade = get_option('tin_theme_upgrade',0);
	$theme = wp_get_theme();
	if($tin_upgrade){
		echo '<div class="updated fade"><p>'.sprintf(__('Tinection主题已更新至<a color="red">%1$s</a>(当前%2$s)，请访问<a href="http://www.zhiyanblog.com/tinection-theme-download.html" target="_blank">知言博客Tinection专页</a>查看！','tinection'),$tin_upgrade,$theme->get('Version')).'</p></div>';
	}
}
add_action( 'admin_notices', 'tin_update_alert_callback' );

function tin_new_friend(){
	global $pagenow;
	if(tin_get_http_response_code('http://cdn.zhiyanblog.com/tinection/version.json')=='200'):
	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){	
		$url = get_bloginfo('url');
		$name = get_bloginfo('name');
		$email = get_bloginfo('admin_email');	
		$theme = wp_get_theme();
		$ip = $_SERVER['REMOTE_ADDR'];
		$ipaddr = convertip($ip);
		$data = array(
				'url'=>$url,
				'name'=>$name,
				'email'=>$email,
				'version'=>( $theme->get('Version') ),
				'ip'=>$ip,
				'ipaddr'=>$ipaddr
		);
		tin_socket_post('http://cdn.zhiyanblog.com/tinection/r.php',$data);	
	}
	endif;
}
add_action( 'load-themes.php', 'tin_new_friend' );

/* Cron定时任务添加周循环选项
/* --------------------- */ 
function cron_add_weekly( $schedules )
{
	// Adds once weekly to the existing schedules.
	$schedules['weekly'] = array(
		'interval' => 604800, // 1周 = 60秒 * 60分钟 * 24小时 * 7天
		'display' => __('每周一次','tinection')
	);
	return $schedules;
}
add_filter('cron_schedules', 'cron_add_weekly');

/* 获取CMS布局分类的调用模板
/* -------------------------- */
function tin_get_cms_cat_template($cat_ID,$sidebar=1){
	if($sidebar==1){
		$tp[] = ot_get_option('cms_catlist_template_bar_0') ? ot_get_option('cms_catlist_template_bar_0') : array();
		$tp[] = ot_get_option('cms_catlist_template_bar_1') ? ot_get_option('cms_catlist_template_bar_1') : array();
		$tp[] = ot_get_option('cms_catlist_template_bar_2') ? ot_get_option('cms_catlist_template_bar_2') : array();
		$tp[] = ot_get_option('cms_catlist_template_bar_3') ? ot_get_option('cms_catlist_template_bar_3') : array();
		$tp[] = ot_get_option('cms_catlist_template_bar_4') ? ot_get_option('cms_catlist_template_bar_4') : array();
		$tp[] = ot_get_option('cms_catlist_template_bar_5') ? ot_get_option('cms_catlist_template_bar_5') : array();
		$tp[] = ot_get_option('cms_catlist_template_bar_6') ? ot_get_option('cms_catlist_template_bar_6') : array();
		$tp_pre = 'catlist_bar_';
	}else{
		$tp[] = ot_get_option('cms_catlist_template_1') ? ot_get_option('cms_catlist_template_1') : array();
		$tp[] = ot_get_option('cms_catlist_template_2') ? ot_get_option('cms_catlist_template_2') : array();
		$tp[] = ot_get_option('cms_catlist_template_3') ? ot_get_option('cms_catlist_template_3') : array();
		$tp[] = ot_get_option('cms_catlist_template_4') ? ot_get_option('cms_catlist_template_4') : array();
		$tp[] = ot_get_option('cms_catlist_template_5') ? ot_get_option('cms_catlist_template_5') : array();
		$tp[] = ot_get_option('cms_catlist_template_6') ? ot_get_option('cms_catlist_template_6') : array();
		$tp_pre = 'catlist_';
	}
	$tp_id = -1;
	for ($i=0; $i <= 6; $i++) {
		if(in_array($cat_ID, $tp[$i])){
			$tp_id = $i;
			break;
		}
	}
	if($tp_id==-1){
		$tp_id=rand(0,6);
	}
	$tp_name = $tp_pre.$tp_id;
	return $tp_name;
}
//编辑器中增加字体
function custum_fontfamily($initArray){
   $initArray['font_formats'] = "微软雅黑='微软雅黑';宋体='宋体';黑体='黑体';仿宋='仿宋';";
   return $initArray;
}
add_filter('tiny_mce_before_init', 'custum_fontfamily');

function conference_change_mce_options($initArray) {
//@see http://wiki.moxiecode.com/index.php/TinyMCE:Control_reference
$initArray['theme_advanced_blockformats'] = 'p,address,pre,code,h3,h4,h5,h6';
$initArray['theme_advanced_disable'] = 'forecolor';
if ( isset($initArray['theme_advanced_fonts'])){
    $initArray['theme_advanced_fonts'] = '宋体=宋体;黑体=黑体;仿宋=仿宋;楷体=楷体;隶书=隶书;幼圆=幼圆;'.$initArray['theme_advanced_fonts'];
}else {
    $initArray['theme_advanced_fonts'] = '宋体=宋体;黑体=黑体;仿宋=仿宋;楷体=楷体;隶书=隶书;幼圆=幼圆;Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
}
   return $initArray;
}
add_filter('tiny_mce_before_init', 'conference_change_mce_options',999);


?>