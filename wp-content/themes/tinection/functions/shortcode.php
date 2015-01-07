<?php
/**
 * Functions of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.0
 * @date      2014.12.11
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/
 
/* Shortcode
/* ------------ */
// Toggle content
function toggle_content($atts, $content = null){ 
	extract(shortcode_atts(array('hide'=>'no','title'=>'','color'=>''),$atts));
	if($hide=='no'){
		return '<div class="toggle-wrap"><div class="toggle-click-btn '.$hide.'" style="color:'.$color.'">'.$title.'</div><div class="toggle-content">'.$content.'</div></div>';
	}else{
		return '<div class="toggle-wrap"><div class="toggle-click-btn '.$hide.'" style="color:'.$color.'">'.$title.'</div><div class="toggle-content" style="display:none;">'.$content.'</div></div>';
	}
}
add_shortcode('toggle', 'toggle_content');

// Button
function tin_button($atts, $content = null){
	extract(shortcode_atts(array('class'=>'default','size'=>'default','href'=>'','title'=>''),$atts));
	if(!empty($href)) {return '<a class="btnhref" href="'.$href.'" title="'.$title.'" target="_blank"><button type="button" class="btn btn-'.$class.' btn-'.$size.'">'.$content.'</button></a>';}
	else{return '<button type="button" class="btn btn-'.$class.' btn-'.$size.'">'.$content.'</button>';}
}
add_shortcode('button', 'tin_button');

// Call out
function tin_infoblock($atts, $content = null){
	extract(shortcode_atts(array('class'=>'info','title'=>''),$atts));
	return '<div class="bs-callout bs-callout-'.$class.'"><h4>'.$title.'</h4><p>'.$content.'</p></div>';
}
add_shortcode('callout', 'tin_infoblock');

// Info bg
function tin_infobg($atts, $content = null){
	extract(shortcode_atts(array('class'=>'info','closebtn'=>'no','bgcolor'=>'','color'=>''),$atts));
	if($closebtn=='yes')return '<div class="bg-'.$class.' contextual" style="background-color:'.$bgcolor.';color:'.$color.'"><span class="infobg-close"><i class="fa fa-close"></i></span>'.$content.'</div>';
	else return '<div class="bg-'.$class.' contextual" style="background-color:'.$bgcolor.';color:'.$color.'">'.$content.'</div>';
}
add_shortcode('infobg', 'tin_infobg');

// Login to visual
function tinl2v_shortcode( $atts, $content ){
	if( !is_null( $content ) && !is_user_logged_in() ) $content = '<div class="bg-lr2v contextual"><i class="fa fa-exclamation"></i>' . __(' 此处内容需要 <span class="login-pop-click">登录</span> 才可见','tinection') . '</div>';
	return $content;
}
add_shortcode( 'tinl2v', 'tinl2v_shortcode' );

// Review to visual
function tinr2v_shortcode( $atts, $content ){
	if( !is_null( $content ) ) :
	if(!is_user_logged_in()){
		$content = '<div class="bg-lr2v contextual"><i class="fa fa-comment"></i>' . __('此处内容需要登录并 <span class="login-pop-click">发表评论</span> 才可见','tinection') . '</div>';
	}else{
		global $post;
		$user_id = get_current_user_ID();
		if( $user_id != $post->post_author && !user_can($user_id,'edit_others_posts') ){
			$comments = get_comments( array('status' => 'approve', 'user_id' => get_current_user_ID(), 'post_id' => $post->ID, 'count' => true ) );
			if(!$comments) {
				$content = '<div class="bg-lr2v contextual"><i class="fa fa-comment"></i>' . __('此处内容需要登录并 <a href="#comments">发表评论</a> 才可见' ,'tinection'). '</div>';
			}
		}
	}
	endif;
	return $content;
}
add_shortcode( 'tinr2v', 'tinr2v_shortcode' );

?>