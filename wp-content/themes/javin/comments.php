<?php
/**
 * The template for displaying comments.
 * @package WordPress
 * @subpackage Javin
 */
?>
<div id="comments">
  <?php if ( post_password_required() ) : ?>
  <p class="nopassword"><?php _e( '<h3 class="discussion_title fs24 f_w">请输入密码，查看精彩评论.</h3>' ); ?></p></div>
  <?php 
      return;
	endif;
  ?>
  <!-- 防止直接加载文件 -->
  <?php if(!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) : ?>
	    <?php die('Please do not load this page directly or we will hunt you down. Thanks and have a great day!'); ?>
  <?php endif; ?>
  <?php if ( have_comments() ) : ?>
    <h3 class="discussion_title fs24 f_w"><?php comments_popup_link('目前没有评论', '目前有一条精彩评论', '目前有 % 条精彩评论', 'comments-link', '评论已关闭'); ?></h3>
    <!-- 调用评论模版 -->
    <ol id="commentlist"><?php wp_list_comments( array( 'callback' => 'javin_comment' ) ); ?></ol>
    <!-- 检测调用评论翻页 -->
    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
    <div class="comment_pre_nex mb20">
      <?php paginate_comments_links('prev_text=<&next_text=>');?>
    </div>
    <?php endif; ?>
    <!-- 没有评论的状态 -->
    <?php else :
      _e('<h3 class="discussion_title fs24 f_w">目前没有评论. 你将成为第一个吃沙螃蟹的人!</h3>');
      if ( ! comments_open() ) : ?>
      <div id="respond"><h3 id="reply-title"><div class="circle_add"><div class="add">Comments</div></div><span class="discussion_title fs24 f_w"><?php _e('评论已关闭. 详情请联系博主'); ?></span></h3></div>
    <?php endif; ?>
  <?php endif; // end have_comments() ?>
  <!-- 引用留言部分 -->
  <?php if ( $comment_author != "" ) : ?>
     <?php $hideauthor = '<div class="mb20" id="welcome">欢迎 <strong> ' .$comment_author. '</strong> 回来！</div>'?>
  <?php endif; ?>
  <?php $comment_args = array( 'fields' => apply_filters( 'comment_form_default_fields', array(
    'author' => $hideauthor.'<div id="author_info"><ul>' . 
	            '<li class="comment-form-author"><div class="author_info_classify f_l">' .
                '<label for="author">' . __( 'Name' ) . '</label> ' .
                '</div><input id="author" name="author" type="text" value="' .
                esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . '  placeholder="觉唯" ' . ( $req ? ' required' : '' ) .' />' .
                '</li>',
    'email'  => '<li class="comment-form-email"><div class="author_info_classify f_l">
                <label for="email">' . __( 'Email' ) . '</label> ' .
                '</div><input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' placeholder="jwzhong#foxmail.com" ' . ( $req ? ' required' : '' ) .' />' . '<span class="form_hint">正确格式为： admin@jiawin.com</span>' .
		        '</li>',
    'url'    => '<li class="comment-form-url"><div class="author_info_classify f_l">
		         <label for="url">' . __( 'Website' ) . '</label>' .
                '</div><input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" placeholder="http://www.jiawin.com" pattern="(http|https)://.+" />' . '<span class="form_hint">正确格式为： http://www.jiawin.com</span>' .
				 '</li>' .
	             '</ul></div><div class="comment-form-comment ption_r">', ) ),
    'comment_field' => '<label class="comment_form_label author_info_classify f_l" for="comment">' . _x( 'Comment', 'noun' ) . '</label>' . '<textarea aria-required="true" rows="8" cols="100%" name="comment" id="comment" onkeydown="if(event.ctrlKey){if(event.keyCode==13){document.getElementById(\'submit\').click();return false}};" required ></textarea>' . '<div id="comment_from_tishi" class="ption_a"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" /><label for="comment_mail_notify">有人回复时邮件通知我</label><p>按 [ Ctrl+Enter ] 键直接提交</p></div></div>',
	'comment_notes_before' =>'<p class="comment-notes">' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . ( $req ? '<span class="required">*</span>标记为必填选项' : '' ) . '</p>',
    'comment_notes_after' => '',
	'title_reply'=> __( '<div class="circle_add"><div class="add">Comments</div></div><span class="discussion_title fs24 f_w">发表评论</span>' ),
	'title_reply_to' => '<div class="circle_add"><div class="add">Comments</div></div><span class="discussion_title fs24 f_w">' . __('Leave a Reply to %s') . '</span>',
	'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p><div class="comment-form-comment ption_r">',
  );
comment_form($comment_args); ?>
</div>