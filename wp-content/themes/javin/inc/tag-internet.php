<?php
// 文章关键词tag互联
$match_num_from = 1;  //一个关键字少于多少不替换
$match_num_to = 1;  //一个关键字最多替换
add_filter('the_content','tag_link',1);
function tag_sort($a, $b){
if ( $a->name == $b->name ) return 0;
return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
}
function tag_link($content){
global $match_num_from,$match_num_to;
$posttags = get_the_tags();
if ($posttags) {
usort($posttags, "tag_sort");
foreach($posttags as $tag) {
$link = get_tag_link($tag->term_id);
$keyword = $tag->name;
$cleankeyword = stripslashes($keyword);
$url = "<a href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('View all posts in %s'))."\"";
$url .= ' target="_blank"';
$url .= ">".addcslashes($cleankeyword, '$')."</a>";
$limit = rand($match_num_from,$match_num_to);
$content = preg_replace( '|(<a[^>]+>)(.*)('.$ex_word.')(.*)(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
$content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
$cleankeyword = preg_quote($cleankeyword,'\'');
$regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
$content = preg_replace($regEx,$url,$content,$limit);
$content = str_replace( '%&&&&&%', stripslashes($ex_word), $content);
}
}
return $content;
}
?>