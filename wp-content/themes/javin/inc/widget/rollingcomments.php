<div class="box">
<h3 class="box_title ption_r mb20 f_l"><span>最新评论</span></h3>
<div class="box_content r_comments">
	<ul id="rcslider">
		<?php
			global $wpdb;
			$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url,comment_author_email, SUBSTRING(comment_content,1,20) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND comment_author != 'orz' AND post_password = '' AND user_id='0' ORDER BY comment_date_gmt DESC LIMIT 10";
			// comment_date_gmt DESC liMIT 10 中的 10 是指要显示的评论个数
            // SUBSTRING(comment_content,1,20) 中的 20 是指每条评论的中文文字个数
            // ('comment_author_email'), 32) 中的 32 是指头像的图片大小
			$comments = $wpdb->get_results($sql);
			$output = $pre_HTML;
			foreach ($comments as $comment) {$output .= "\n<li>".get_avatar( $comment, 32, '', $comment->comment_author )." <a href=\"" . get_permalink($comment->ID) ."#comment-" . $comment->comment_ID . "\" title=\"发表在： " .$comment->post_title . "\">" .strip_tags($comment->comment_author).":<br/>". strip_tags($comment->com_excerpt)."</a><br /></li>";}
			$output .= $post_HTML;
			echo $output;
		?>
        <div class="feat_bottom_line"></div>
	</ul>
</div>
</div>