<?php
// 自定义字段
function add_banner_meta_boxes() {
add_meta_box('banner-meta-box', '幻灯片图片设置', 'show_banner_meta_box', 'post', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_banner_meta_boxes');

function show_banner_meta_box($post) {
echo '<input type="hidden" name="banner_imgmeta_box_nonce" value="'. wp_create_nonce('banner-src-meta'). '" />';
echo '<input type="hidden" name="banner_meta_box_nonce" value="'. wp_create_nonce('banner-img-src-meta'). '" />';
?>
<table>
<tr valign="top">
<th scope="row"><label for="banner-awesome-img-field">图片地址(678x250)：</label></th>
<td><input type="text" size="130" name="banner-src" id="banner-awesome-img-field" value="<?php echo get_post_meta($post->ID, 'banner-src', true)?>" /> （文章置顶后该图将会显示在幻灯片）</td>
</tr>
</table>
<?php }

function save_banner_meta_box($post_id) {
if (!isset($_POST['banner_meta_box_nonce']) || !wp_verify_nonce($_POST['banner_imgmeta_box_nonce'], 'banner-src-meta')) {
return $post_id;
}
 
if ('post' == $_POST['post_type']) {
if (!current_user_can('edit_post', $post_id)) {
return $post_id;
}
} elseif (!current_user_can('edit_page', $post_id)) {
return $post_id;
}
 
if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
return $post_id;
}
 
if(isset($_POST['banner-src'])) {
update_post_meta($post_id, 'banner-src', $_POST['banner-src']);
} else {
delete_post_meta($post_id, 'banner-src');
}
}
add_action('save_post', 'save_banner_meta_box');
?>