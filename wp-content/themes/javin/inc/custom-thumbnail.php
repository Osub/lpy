<?php
// 开启声明支持缩略图功能
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'medium', 150, 120, true );
// 获取文章特色图片
    //获取thumbnail特色图片
    function PostThumbURL_thumbnail() {
    global $post, $posts;
    $thumbnail = '';
    ob_start();the_post_thumbnail('thumbnail');$toparse=ob_get_contents();ob_end_clean();
    preg_match_all('/src=("[^"]*")/i', $toparse, $img_src); $thumbnail = str_replace("\"", "", $img_src[1][0]);
    return $thumbnail;
}    
    //获取medium特色图片
    function PostThumbURL_medium() {
    global $post, $posts;
    $thumbnail = '';
    ob_start();the_post_thumbnail('medium');$toparse=ob_get_contents();ob_end_clean();
    preg_match_all('/src=("[^"]*")/i', $toparse, $img_src); $thumbnail = str_replace("\"", "", $img_src[1][0]);
    return $thumbnail;
}
    //获取medium特色图片
    function PostThumbURL_full() {
    global $post, $posts;
    $thumbnail = '';
    ob_start();the_post_thumbnail('full');$toparse=ob_get_contents();ob_end_clean();
    preg_match_all('/src=("[^"]*")/i', $toparse, $img_src); $thumbnail = str_replace("\"", "", $img_src[1][0]);
    return $thumbnail;
}
//获取文章首张图片
    function catch_that_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $first_img = $matches [1] [0];
    if(empty($first_img)){ //设置默认图片，在文中没有图片是显示
        $first_img = get_bloginfo('template_url') . '/images/thumb_small.jpg';
    }
    return $first_img;
}
?>