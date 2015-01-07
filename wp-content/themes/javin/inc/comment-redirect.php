<?php
//评论链接重定向跳转
add_filter('get_comment_author_link', 'add_redirect_comment_link', 5);
add_filter('comment_text', 'add_redirect_comment_link', 99);
function add_redirect_comment_link($text = ''){
    $text=str_replace('href="', 'href="'.get_option('home').'/?r=', $text);
    $text=str_replace("href='", "href='".get_option('home')."/?r=", $text);
    return $text;
}
add_action('init', 'redirect_comment_link');
function redirect_comment_link(){
    $redirect = $_GET['r'];
    if($redirect){
        if(strpos($_SERVER['HTTP_REFERER'],get_option('home')) !== false){
            header("Location: $redirect");
            exit;
        }
        else {
            header("Location: http://www.jiawin.com/");
            exit;
        }
    }
}

?>