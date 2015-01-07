<?php
//demos btn
function demoshortcode_function($atts, $text = null){
 
    extract(shortcode_atts(array(
    "href" => 'http://www.jiawin.com'
    ), $atts));
    return '<a class="demo" href="'. $href .'" target="_blank">
              '. $text .'
           </a>';
}
add_shortcode("demos", "demoshortcode_function");

//download btn
function downloadshortcode_function($atts, $text = null){
 
    extract(shortcode_atts(array(
    "href" => 'http://www.jiawin.com/love'
    ), $atts));
    return '<a class="download" href="'. $href .'" target="_blank">
              '. $text .'
           </a>';
}
add_shortcode("doloadbtn", "downloadshortcode_function");

//post class box
function postboxshortcode_function($atts, $text = null){
 
    extract(shortcode_atts(array(
    "class" => 'gray_box'
    ), $atts));
    return '<div class="'. $class .'">
              '. $text .'
           </div>';
}
add_shortcode("postbox", "postboxshortcode_function");

//title btn
function titlehortcode_function($atts, $text = null){
 
    extract(shortcode_atts(array(
    "class" => 'demo'
    ), $atts));
    return '<a class="'.$class.'" >
              '. $text .'
           </a>';
}
add_shortcode("title", "titlehortcode_function");


?>