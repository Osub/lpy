<!DOCTYPE html>
<?php
/**
 * Main Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.0.0
 * @date      2014.11.25
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>

<!--[if IE 6]>
<html class="ie6 ancient-ie old-ie no-js" lang="zh-CN">
<![endif]-->
<!--[if IE 7]>
<html class="ie7 ancient-ie old-ie no-js" lang="zh-CN">
<![endif]-->
<!--[if IE 8]>
<html class="ie8 old-ie no-js" lang="zh-CN">
<![endif]-->
<!--[if IE 9]>
<html class="ie9 old-ie9 no-js" lang="zh-CN">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html lang="zh-CN">
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!-- 引入页面描述和关键字模板 -->
<?php get_template_part( 'includes/seo');?>
<!-- 网站图标 -->
<?php if ( ot_get_option('favicon') ): ?>
<link rel="shortcut icon" href="<?php echo ot_get_option('favicon'); ?>" />
<link rel="icon" href="<?php echo ot_get_option('favicon'); ?>" />
<?php else: ?>
<link rel="shortcut icon" href="favicon.ico" />
<?php endif; ?>
<!-- 禁止浏览器初始缩放 -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<link rel="stylesheet" type="text/css" href="<?php echo THEME_URI.'/includes/css/error.css?ver='.time(); ?>"  />
<!-- Main Wrap -->
<div class="main">
    <div class="holmes-bear-wrap">&nbsp;</div>
    <div class="error-wrap">
        <div class="error-text-title-wrap clearfix"><div class="error-number">404，</div><div class="error-text-title"></div></div>
        <p class="error-text-content">您所查找的页面不存在</p>
        <ul class="handle-way-list">
            <li class="handle-way-item">1.您可以<a href="javascript:history.go(-1);">返回上一个页面</a></li>
            <li class="handle-way-item">2.您可以<a href="<?php bloginfo('home'); ?>">回到首页</a></li>
			<li class="handle-way-item">3.您可以尝试下方搜索</li>
        </ul>
    </div>
	<div class="error-search">
		<form method="get" class="searchform themeform" action="<?php echo home_url('/'); ?>">
			<input type="text" class="search" name="s" placeholder="Search.." maxlength="20" required="required" />
			<button name="submit" type="submit" id="submit">搜索</button>
		</form>
	</div>
</div>
<!--/.Main Wrap -->
<div class="footer">
<!-- Copyright -->
	<div id="footer-copyright">&copy;<?php echo date(' Y '); ?>
	<?php if(ot_get_option('copyright')) echo ot_get_option('copyright'); ?>&nbsp;|&nbsp;Theme by&nbsp;
		<a href="http://www.zhiyanblog.com/tinection.html"  target="_blank">Tinection</a>.
		<?php if(ot_get_option('statisticcode')) echo '&nbsp;|&nbsp;'.ot_get_option('statisticcode'); ?>
		<?php if(ot_get_option('beian')) echo '&nbsp;|&nbsp;<a href="http://www.miitbeian.gov.cn/" target="_blank">'.ot_get_option('beian').'</a>'; ?>
	<!--<?php echo get_num_queries();?> queries in <?php timer_stop(1); ?> seconds.-->
	</div>
<!-- /.Copyright -->
</div>
</body>
</html>