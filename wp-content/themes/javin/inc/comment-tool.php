<?php
// 评论框添加简单文本编辑器和弹出表情框
function wp_smilies() {
    global $wpsmiliestrans;
    if ( !get_option('use_smilies') or (empty($wpsmiliestrans))) return;
    $smilies = array_unique($wpsmiliestrans);
    $link='';
    foreach ($smilies as $key => $smile) {
		$file = get_bloginfo('wpurl').'/wp-includes/images/smilies/'.$smile;
        $value = " ".$key." ";
        $img = "<img src=\"{$file}\" alt=\"{$smile}\" />";
        $imglink = htmlspecialchars($img);
        $link .= "<span><a href=\"#commentform\" title=\"{$smile}\" onclick=\"document.getElementById('comment').value += '{$value}'\">{$img}</a></span>";
    }
    echo '<div class="editor_tools clearfix">
    <span><a href="javascript:SIMPALED.Editor.strong()" title="粗体" class="et_strong">粗体</a></span>
    <span><a href="javascript:SIMPALED.Editor.em()" title="斜体" class="et_em">斜体</a></span>
    <span><a href="javascript:SIMPALED.Editor.underline()" title="下划线" class="et_underline">下划线</a></span>
    <span><a href="javascript:SIMPALED.Editor.del()" title="删除线" class="et_del">删除线</a></span>
    <span><a href="javascript:SIMPALED.Editor.ahref()" title="链接" class="et_ahref">链接</a></span>
    <span><a href="javascript:SIMPALED.Editor.img()" title="图片" class="et_img">图片</a></span>
    <span><a href="javascript:SIMPALED.Editor.code()" title="代码" class="et_code">代码</a></span>
    <span><a href="javascript:SIMPALED.Editor.quote()" title="引用" class="et_quote">引用</a></span>
    <span><a href="javascript:SIMPALED.Editor.private()" title="隐藏" class="et_private">隐藏</a></span>
    <span><a href="javascript:SIMPALED.Editor.smilies()" title="表情" class="et_smilies">表情</a></span>
    <div id="smilies-container"><div class="wp_smilies">'.$link.'</div></div></div>';
    }
    if (is_user_logged_in()) {
       add_filter('comment_form_logged_in_after', 'wp_smilies');
    }
    else {
        add_filter( 'comment_form_after_fields', 'wp_smilies');
    }
function private_content($atts, $content = null)
{ if (current_user_can('create_users'))
return '' . $content . ''; return '***隐藏内容仅管理员可见***'; }
add_shortcode('private', 'private_content');
add_filter('comment_text', 'do_shortcode'); /* 添加隐藏短代码*/
?>