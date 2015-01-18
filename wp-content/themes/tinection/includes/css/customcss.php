<?php
/**
 * Customize Stylesheet of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.3
 * @date      2015.1.7
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/
?>
<?php
error_reporting(0);
define( 'ABSPATH', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/' );
require_once(ABSPATH.'/wp-load.php');
//浏览器滚动条颜色
$browser_scroll_color = ot_get_option('browser_scroll_color','#33BBBA');
//Body主字体颜色
$main_body_color = ot_get_option('main_body_color','#666666');
//Body主字体超链接颜色
$main_body_a_color = ot_get_option('main_body_a_color','#428bca');
//Body主字体超链接鼠标悬停颜色
$main_body_a_hover_color = ot_get_option('main_body_a_hover_color','#60717e');
//文章标题颜色
$title_a_color = ot_get_option('title_a_color','#428bca');
//文章标题鼠标悬停颜色
$title_a_hover_color = ot_get_option('title_a_hover_color','#60717e');
//块标题底边色
$block_border_color = ot_get_option('block_border_color','#f85555');
//Selection选取背景色
$selection_bg_color = ot_get_option('selection_bg_color','#72d0eb');
//Selection选取文字颜色
$selection_color = ot_get_option('selection_color','#fff');
//导航条背景色
$nav_bg_color = ot_get_option('nav_bg_color','#fff');
//菜单文字颜色
$menu_color = ot_get_option('menu_color','#999999');
//菜单悬停背景色
$menu_hover_bg_color = ot_get_option('menu_hover_bg_color','#fff');
//菜单悬停文字颜色
$menu_hover_color = ot_get_option('menu_hover_color','#428bca');
//Logo字体颜色
$logo_color = ot_get_option('logo_color','#888');

header('Content-Type: text/css; charset=UTF-8');
header('Expires: ' . gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT');
header("Cache-Control: public, max-age=$expires_offset");

?>
::-webkit-scrollbar-thumb{background-color:<?php echo $browser_scroll_color; ?> !important;}
::selection{background:<?php echo $selection_bg_color; ?>;color:<?php echo $selection_color; ?>}
body{color:<?php echo $main_body_color; ?>}
body a,.bg-sitenews i.fa-volume-up,.tab-item-comment i{color:<?php echo $main_body_a_color; ?>}
.tin-tabs-nav li.active a{border-bottom-color:<?php echo $main_body_a_color; ?>}
.home-heading a{background: <?php echo $main_body_a_color; ?>}
body a:hover{color:<?php echo $main_body_a_hover_color; ?>}
article h3,article h3 a,.tab-item-title a,.cms-with-sidebar .col-right.catlist-style4 .col-small p:before{color:<?php echo $title_a_color; ?>}
article h3 a:hover,.tab-item-title a:hover{color:<?php echo $title_a_hover_color; ?>}
.header-wrap,.primary-navigation ul ul,.user-tabs{background-color:<?php echo $nav_bg_color; ?>}
.logo-title a{color:<?php echo $logo_color; ?>}
.primary-navigation li > a,.primary-navigation li.current-menu-item a,#focus-us,.user-tabs span a,.user-tabs span i{color:<?php echo $menu_color; ?>}
.primary-navigation li a:hover,.user-tabs span:hover,#focus-us:hover, #focus-us:focus,#focus-slide{background:<?php echo $menu_hover_bg_color; ?>}
.primary-navigation li > a:hover,.primary-navigation li:hover > a, .primary-navigation li.focus > a,.user-tabs span:hover a,.user-tabs span:hover i,#focus-us:hover, #focus-us:focus,.user-tabs span:hover,.user-tabs span:hover i,.login-yet-click a,.login-yet-click a:hover,.focus-content a:hover{color:<?php echo $menu_hover_color; ?>}
#sidebar .widget > h3 span, .floatwidget h3 span, .multi-border-hl span,.tin-tabs-nav li.active a,.cms-with-sidebar .heading-text, .heading-text-blog,.cms-with-sidebar .stickys .heading-text-cms.active{border-bottom-color:<?php echo $block_border_color; ?>}
.tin-tabs-nav li a:hover{color:<?php echo $block_border_color; ?>}