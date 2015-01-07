<?php
// 评论表单重构
 if ( ! function_exists( 'javin_comment' ) ) :
 function javin_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
    global $commentcount,$wpdb, $post;
    if(!$commentcount) { //初始化楼层计数器
		$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type = '' AND comment_approved = '1' AND !comment_parent");
		$cnt = count($comments);//获取主评论总数量
		$page = get_query_var('cpage');//获取当前评论列表页码
		$cpp=get_option('comments_per_page');//获取每页评论显示数量
		if (ceil($cnt / $cpp) == 1 || ($page > 1 && $page  == ceil($cnt / $cpp))) {
			$commentcount = $cnt + 1;//如果评论只有1页或者是最后一页，初始值为主评论总数
		} else {
			$commentcount = $cpp * $page + 1;
		}
    }
	
	switch ( $comment->comment_type ) :
		case '' :
	?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
  <div id="comment-<?php comment_ID(); ?>" class="ption_r">
    <?php echo get_avatar( $comment, 75, '', $comment->comment_author ); ?>
    <?php if($comment->user_id == 1) { ?>
    <p class="author_role ption_a">管理员</p>
    <?php } else { ?>
    <?php } ?>
    <div class="author_comment fl ption_r">
      <div class="comment-meta"> <span id="commentauthor-<?php comment_ID() ?>" class="author fs18 f_w mr10">
        <?php comment_author_link(); ?>
        </span> <span>发表于：
        <?php comment_date(); ?>
        <em class="ml10">
        <?php comment_time('H:i:s'); ?>
        </em></span> <span class="ml10">
        <?php edit_comment_link('编辑评论', '', ''); ?>
        </span> </div>
      <div class="clear"></div>
      <div id="commentbody-<?php comment_ID() ?>" class="comment-text">
        <?php comment_text(); ?>
      </div>
      <div class="reply">
        <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        <a href="javascript:void(0);" onclick="quote('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'commentbody-<?php comment_ID() ?>', 'comment');">引用</a> </div>
      <div class="floor ption_a fs20 f_w"> <a href="#comment-<?php comment_ID() ?>">
        <?php
 if(!$parent_id = $comment->comment_parent){
   switch ($commentcount){
     case 2 :echo "#1";--$commentcount;break;
     case 3 :echo "#2";--$commentcount;break;
     case 4 :echo "#3";--$commentcount;break;
     default:printf('#%1$s', --$commentcount);
   }
 }
 ?>
        </a> </div>
    </div>
    <?php if ( $comment->comment_approved == '0' ) : ?>
    <p class="waiting-for-approval">
      <?php _e( '评论正在审核中……' ); ?>
    </p>
    <?php endif; ?>
  </div>
  <!-- #comment-##  -->
  <?php
		break;
		case 'pingback'  :
		case 'trackback' :
	?>
<li class="post pingback">
  <p>
    <?php _e( 'Pingback:' ); ?>
    <?php comment_author_link(); ?>
    <?php edit_comment_link( __( '(Edit)' ), ' ' ); ?>
  </p>
  <?php
			break;
	endswitch;
}
endif;

//密码保护文章输入框
function my_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<form class="protected-post-form clear" action="' . get_option( 'siteurl' ) . '/wp-login.php?action=postpass" method="post">
    ' . __( "这是一篇受密码保护的文章。您需要提供访问密码：" ) . '
    <label class="f_l" for="' . $label . '">' . __( "Password:" ) . ' </label><input class="protected_post_text f_l" name="post_password" id="' . $label . '" type="password" size="20" /><input class="protected_post_btn f_l" type="submit" name="Submit" value="' . esc_attr__( "Submit" ) . '" />
    </form>
    ';
    return $o;
}
add_filter( 'the_password_form', 'my_password_form' );
?>
