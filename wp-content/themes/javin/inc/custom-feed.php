<?php 
// 增加feed缩略图（已经去掉）、版权信息、邀请评论
function cwc_rss_post_thumbnail($content) {
	global $post;
	if(has_post_thumbnail($post->ID)) {
		$content = get_the_content();
		if (comments_open()) {
			$content .= '<P><strong>精彩评论功能已开放，<a href="'.get_permalink().'">点击发表评论</a></strong></p>';
		}
		$content.= "<blockquote>";
		$content.= '<div> 　» 转载保留版权：<a title="觉唯" href="http://www.jiawin.com">觉唯</a> » <a rel="bookmark" title="'.get_the_title().'" href="'.get_permalink().'">《'.get_the_title().'》</a></div>';
		$content.= '<div>　» 本文链接地址：<a rel="bookmark" title="'.get_the_title().'" href="'.get_permalink().'">'.get_permalink().'</a></div>';
	$content.= '<div> 　» 如果喜欢可以：<a title="觉唯" href="http://www.jiawin.com/feed/">点此订阅本站</a></div>';
		$content.= "</blockquote>";
	}
	return $content;
}
add_filter('the_excerpt_rss', 'cwc_rss_post_thumbnail');
add_filter('the_content_feed', 'cwc_rss_post_thumbnail');
?>