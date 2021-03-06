<?php
/**
 * Main Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.0
 * @date      2014.12.11
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com> & Dmeng <http://www.dmeng.net/>
 * @copyright Copyright (c) 2014-2015, Zhiyan & Dmeng
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

// 启动主题时清理固定链接缓存
function tin_rewrite_flush_rules(){
	global $pagenow,$wp_rewrite;   
	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){
		$wp_rewrite->flush_rules();
	}
}
add_action( 'load-themes.php', 'tin_rewrite_flush_rules' ); 

// 资料卡URL
function tin_get_user_url( $type='', $user_id=0 ){
	$user_id = intval($user_id);
	if( $user_id==0 ){
		$user_id = get_current_user_id();
	}
	$url = add_query_arg( 'tab', $type, get_author_posts_url($user_id) );
	return $url;
}

//~ 用户页资料页拒绝搜索引擎索引
function tin_author_tab_no_robots(){
	if( is_author() && isset($_GET['tab']) ) wp_no_robots();
}
add_action('wp_head', 'tin_author_tab_no_robots');

//~ 更改编辑个人资料链接
function tin_profile_page( $url ) {
    return is_admin() ? $url : tin_get_user_url('profile');
}
add_filter( 'edit_profile_url', 'tin_profile_page' );

//~ 拒绝普通用户访问后台
function tin_redirect_wp_admin(){
	if( is_admin() && is_user_logged_in() && !current_user_can('edit_users') && ( !defined('DOING_AJAX') || !DOING_AJAX )  ){
		wp_redirect( tin_get_user_url('profile') );
		exit;
	}
}
add_action( 'init', 'tin_redirect_wp_admin' );

//~ 普通用户编辑链接改为前台
function tin_edit_post_link($url, $post_id){
	if( !current_user_can('edit_users') ){
		$url = add_query_arg(array('action'=>'edit', 'id'=>$post_id), tin_get_user_url('post'));
	}
	return $url;
}
add_filter('get_edit_post_link', 'tin_edit_post_link', 10, 2);

//~ 登陆页LOGO
function tin_login_logo(){
	$custom_login_logo = ot_get_option('custom-login-logo');
	$default_login_logo = get_bloginfo('template_directory').'/images/wordpress-logo.png';
	if( !empty($custom_login_logo) ){
		$css = sprintf('background-image:url(%1$s);-webkit-background-size:85px 85px;background-size:85px 85px;width:85px;height:85px;', $custom_login_logo);
	}else{
		$css = sprintf('background-image:url(%1$s);-webkit-background-size:85px 85px;background-size:85px 85px;width:85px;height:85px;', $default_login_logo);;
	}
	?>
    <style type="text/css">
        body.login div#login h1 a{
			<?php echo $css;?>
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'tin_login_logo' );

function tin_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'tin_login_logo_url' );

function tin_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'tin_login_logo_url_title' );

//~ 在后台用户列表中显示昵称
function tin_display_name_column( $columns ) {
	$columns['tin_display_name'] = '显示名称';
	unset($columns['name']);
	return $columns;
}
add_filter( 'manage_users_columns', 'tin_display_name_column' );
 
function tin_display_name_column_callback( $value, $column_name, $user_id ) {

	if( 'tin_display_name' == $column_name ){
		$user = get_user_by( 'id', $user_id );
		$value = ( $user->display_name ) ? $user->display_name : '';
	}

	return $value;
}
add_action( 'manage_users_custom_column', 'tin_display_name_column_callback', 10, 3 );

//~ 侧边栏用户中心小工具
function tin_user_profile_widget(){

	$current_user = wp_get_current_user();
	
	$li_output = '';
	
	$li_output .= '<li>'.tin_get_avatar( $current_user->ID , '34' , tin_get_avatar_type($current_user->ID), false ) .
		sprintf(__('Logged in as <a href="%1$s">%2$s</a>.','tinection'), get_edit_profile_url($current_user->ID), $current_user->display_name) . 
		'<a href="'.wp_logout_url(tin_get_current_page_url()).'" title="'.esc_attr__('Log out of this account').'">' .
		__('Log out &raquo;') . 
		'</a></li>';

	if(!filter_var($current_user->user_email, FILTER_VALIDATE_EMAIL)){
		
		$li_output .= '<li><a href="'.tin_get_user_url('profile').'#pass">'.__('【重要】请添加正确的邮箱以保证账户安全','tinection').'</a></li>';
		
	}

	$shorcut_links[] = array(
		'title' => __('个人主页','tinection'),
		'url' => get_author_posts_url($current_user->ID)
	);
	
	if( current_user_can( 'manage_options' ) ) {
		$shorcut_links[] = array(
			'title' => __('管理后台','tinection'),
			'url' => admin_url()
		);
	}
	
	$can_post_cat = json_decode(ot_get_option('tin_can_post_cat'));
	if( count($can_post_cat) ) {
		$shorcut_links[] = array(
			'title' => __('文章投稿','tinection'),
			'url' => add_query_arg('action','new',tin_get_user_url('post'))
		);
	}
	
	$shorcut_html = '<li class="active">';
	foreach( $shorcut_links as $shorcut ){
		 $shorcut_html .= '<a href="'.$shorcut['url'].'">'.$shorcut['title'].' &raquo;</a>';
	}
	 $shorcut_html .= '</li>';

	$credit = intval(get_user_meta( $current_user->ID, 'tin_credit', true ));
	$credit_void = intval(get_user_meta( $current_user->ID, 'tin_credit_void', true ));
	$unread_count = intval(get_tin_message($current_user->ID, 'count', "( msg_type='unread' OR msg_type='unrepm' )"));
	
	$info_array = array(
		array(
			'title' => __('文章','tinection'),
			'url' => tin_get_user_url('post'),
			'count' => count_user_posts($current_user->ID)
		),
		array(
			'title' => __('评论','tinection'),
			'url' => tin_get_user_url('comment'),
			'count' => get_comments( array('status' => '1', 'user_id'=>$current_user->ID, 'count' => true) )
		),
		array(
			'title' => __('收藏','tinection'),
			'url' => tin_get_user_url('like'),
			'count' => intval(get_tin_user_vote($current_user->ID, true, '', 'up'))
		),
		array(
			'title' => __('积分','tinection'),
			'url' => tin_get_user_url('credit'),
			'count' => ($credit+$credit_void)
		)
	);
	
	
	if($unread_count){
		$info_array[] = array(
				'title' => __('未读','tinection'),
				'url' => tin_get_user_url('message'),
				'count' => $unread_count
			);
	}
	
	$info_html = '<li>';
	
	foreach( $info_array as $info ){
		$info_html .= $info['title'].'<a href="'.$info['url'].'">'.$info['count'].'</a>';
	}
	
	$info_html .= '</li>';
	
	$friend_html = '
	<li>
		<div class="input-group">
			<span class="input-group-addon">'.__('本页推广链接','tinection').'</span>
			<input class="tin_aff_url" type="text" class="form-control" value="'.add_query_arg('fid',$current_user->ID,tin_canonical_url()).'">
		</div>
	</li>
	';

	return $li_output.$shorcut_html.$info_html.$friend_html;;

}