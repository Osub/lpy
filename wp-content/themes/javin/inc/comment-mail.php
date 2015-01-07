<?php
// 访客可选择是否有回复时邮件通知
function comment_mail_notify($comment_id) {
  $admin_notify = '1'; // admin 要不要收回复通知 ( '1'=要 ; '0'=不要 )
  $admin_email = get_bloginfo ('admin_email'); // $admin_email 可改为你指定的 e-mail.
  $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  global $wpdb;
  if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '')
    $wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
  if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1'))
    $wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
  $notify = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';
  $spam_confirmed = $comment->comment_approved;
  if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1') {
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); // e-mail 发出点, no-reply 可改为可用的 e-mail.
    $to = trim(get_comment($parent_id)->comment_author_email);
    $subject = '您在 [' . get_option("blogname") . '] 的留言有了回复';
    $message = '
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br />'
       . trim(get_comment($parent_id)->comment_content) . '</p>
      <p>' . trim($comment->comment_author) . ' 给您的回复:<br />'
       . trim($comment->comment_content) . '<br /></p>
      <p>请点击以下链接查看具体内容:<br /><a target="_blank" href="' . htmlspecialchars(get_comment_link($parent_id)) . '">' . htmlspecialchars(get_comment_link($parent_id)) . '</a></p>
      <p>感谢您对 <a target="_blank" href="http://www.jiawin.com">' . get_option('blogname') . '</a> 的关注</p>
	  <p><strong>如果你有任何疑问，请联系我。邮箱：jwzhong@foxmail.com</strong></p>
      <p>(此邮件由系统自动发送，请勿回复。)</p>
    </div>';
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
    //echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
  }
}
add_action('comment_post', 'comment_mail_notify');

// 投稿发布后发邮件通知投稿者
function contribute_notify($mypost) {
    $email = get_post_meta($mypost->ID, "contribute_authoremail", true);
 
    if( !empty($email) ) {
        // 以下是邮件标题
        $subject = '您在 '. get_option('blogname') .' 的投稿已发布';
        // 以下是邮件内容
        $message = '
        <p><strong>'. get_option('blogname') .'</strong> 提醒您: 您投递的文章 <strong>' . $mypost->post_title . '</strong> 已发布</p>
 
        <p>您可以点击以下链接查看具体内容:<br />
        <a href="' . get_permalink( $mypost->ID ) . '" target="_blank">点此查看完整內容</a></p>
        <p>感谢您对 <a href="http://www.jiawin.com">'. get_option('blogname') .'</a> 的关注和支持</p>
        <p><strong>该信件由系统自动发出, 请勿回复, 谢谢.</strong></p>';
 
        add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
        @wp_mail( $email, $subject, $message );
    }
}
 
add_action('draft_to_publish', 'contribute_notify', 6);

?>