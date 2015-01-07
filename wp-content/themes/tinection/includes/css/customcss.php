<?php
/**
 * Customize Stylesheet of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.2
 * @date      2015.1.2
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
$browser_scroll_color = ot_get_option('browser_scroll_color','#00a67c');
//Body主字体颜色
$main_color = ot_get_option('main_body_color','#303030');
//Body主字体超链接颜色
$main_body_a_color = ot_get_option('main_body_a_color','#00a67c');
//Body主字体超链接鼠标悬停颜色
$main_body_a_hover_color = ot_get_option('main_body_a_hover_color','#d9534f');
//文章标题颜色
$title_a_color = ot_get_option('title_a_color','#00a67c');
//文章标题鼠标悬停颜色
$title_a_hover_color = ot_get_option('title_a_hover_color','#d9534f');
//Selection选取背景色
$selection_bg_color = ot_get_option('selection_bg_color','#72d0eb');
//Selection选取文字颜色
$selection_color = ot_get_option('selection_color','#fff');
//导航条背景色
$nav_bg_color = ot_get_option('nav_bg_color','#2e3639');
//菜单文字颜色
$menu_color = ot_get_option('menu_color','#fff');
//菜单悬停背景色
$menu_hover_bg_color = ot_get_option('menu_hover_bg_color','#00a67c');
//菜单悬停文字颜色
$menu_hover_color = ot_get_option('menu_hover_color','#fff');
//Logo字体颜色
$logo_color = ot_get_option('logo_color','#fff');

header('Content-Type: text/css; charset=UTF-8');
header('Expires: ' . gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT');
header("Cache-Control: public, max-age=$expires_offset");

?>
::-webkit-scrollbar-thumb{background-color:<?php echo $browser_scroll_color; ?> !important;}
::selection{background:<?php echo $selection_bg_color; ?>;color:<?php echo $selection_color; ?>}
body{color:<?php echo $main_body_color; ?>}
body a,.tin-tabs-nav li.active a,.bg-sitenews i.fa-volume-up,.tab-item-comment i{color:<?php echo $main_body_a_color; ?>}
.tin-tabs-nav li.active a{border-bottom-color:<?php echo $main_body_a_color; ?>}
#sidebar .widget > h3 span, .floatwidget h3 span, .multi-border-hl span{border-color: <?php echo $main_body_a_color; ?>}
.home-heading a{background: <?php echo $main_body_a_color; ?>}
body a:hover{color:<?php echo $main_body_a_hover_color; ?>}
article h3,article h3 a,.tab-item-title a,.cms-with-sidebar .col-right.catlist-style4 .col-small p:before{color:<?php echo $title_a_color; ?>}
article h3 a:hover,.tab-item-title a:hover{color:<?php echo $title_a_hover_color; ?>}
.header-wrap{background:<?php echo $nav_bg_color; ?>}
.logo-title a{color:<?php echo $logo_color; ?>}
.primary-navigation li > a,.primary-navigation li.current-menu-item a{color:<?php echo $menu_color; ?>}
.primary-navigation li a:hover,.user-tabs span:hover{background:<?php echo $menu_hover_bg_color; ?>}
.user-tabs span a,.user-tabs span i,.user-tabs span:hover{color:<?php echo $menu_hover_bg_color; ?>}
.primary-navigation li > a:hover,.user-tabs span:hover a,.user-tabs span:hover i{color:<?php echo $menu_hover_color; ?>}