<?php 
/**
 * The template for displaying header.
 * @package WordPress
 * @subpackage Javin
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <?php if (is_home()){
		$keywords = "webstar, 用户体验资源, 以用户为中心, 视觉资源, 交互资源, 前端开发, 用户研究, 博客, UED, Javin, HTML, CSS, javascript";
		$description = "webstar是一个分享资源博客，推崇以用户为中心的资源，专注于用户体验资源。webstar致力于互联网视觉资源、交互资源、前端开发、用户研究。";
		} elseif (is_single()){
			if ($post->post_excerpt) {
				$description = strip_tags($post->post_excerpt);
				} else {
					$str = csubstr(strip_tags($post->post_content),0,220);
					$str = trim($str);
                    $str = strip_tags($str,"");
                    $str = ereg_replace("\t","",$str);
                    $str = ereg_replace("\r\n","",$str);
                    $str = ereg_replace("\r","",$str);
                    $str = ereg_replace("\n","",$str);
                    $str = ereg_replace(" "," ",$str);
					$description = trim($str);
					}
					$keywords = "";
					$tags = wp_get_post_tags($post->ID);
					foreach ($tags as $tag ) {
						$keywords = $keywords . $tag->name . ", ";
						}
		}elseif (is_page('存档')){
				$description = "webstar的存档页面";
				$keywords = '存档, 归档';
		}elseif (is_page('读者墙')){
				$description = "webstar的读者墙页面";
				$keywords = '读者墙';
		}elseif (is_page('留言联系')){
				$description = "webstar的留言联系页面";
				$keywords = '留言, 联系';
		}elseif (is_page('关于webstar')){
				$description = "webstar的关于我们页面";
				$keywords = '关于webstar';
		}elseif (is_page('友情链接')){
				$description = "webstar的友情链接页面";
				$keywords = '友情链接';
	    }elseif (is_page('免责声明')){
				$description = "webstar的免责声明页面";
				$keywords = '免责声明, 版权';
		}elseif (is_page('网站地图')){
				$description = "webstar的网站地图页面";
				$keywords = '网站地图';
	    }elseif (is_page('投稿')){
				$description = "webstar的投稿页面";
				$keywords = '网站投稿';
		}elseif (is_category()){
                $description = strip_tags(trim(category_description()));
                $keywords = single_cat_title('',false);
        }elseif (is_tag()){
                $description = strip_tags(trim(tag_description()));
                $keywords = single_tag_title('',false);
		}elseif (is_search()){
                $description = "webstar的搜索结果页面";
                $keywords = "搜索";
		}elseif (is_author()){
                $description = "webstar的作者归档页面";
                $keywords = "作者";
		}elseif (is_archive()){
                $description = "webstar的文章归档页面";
                $keywords = "归档";
		}elseif (is_404()){
                $description = "Error 404 Not Found - 404错误页面";
                $keywords = "404";
                }
	?><meta name="keywords" content="<?=$keywords?>" />
    <meta name="description" content="<?=$description?>" />
    <meta name="author" content="Javin, www.jiawin.com">
    <title><?php wp_title( '-', true, 'right' ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php echo javin_opt('javin_rss'); ?>" />
    <?php wp_enqueue_script('jquery'); ?>
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
    <![endif]-->
	<?php wp_head(); ?>
    <!--[if gte IE 7]><!-->
	<?php switch ( javin_opt('javin_style_sheet') ) { 
	      case "红色":?><?php $javinstyle = "style"; ?>
	<?php break; ?>	
	<?php case "蓝色":?><?php $javinstyle = "style-blue"; ?>
	<?php break; ?>
    <?php default:?><?php $javinstyle = "style"; ?>
	<?php }?>
    <link href="<?php bloginfo('template_directory'); ?>/<?php echo $javinstyle ?>.css" rel="stylesheet" type="text/css" media="all" />
    <!--<![endif]-->
    <!--[if lt IE 7]>
    <link href="<?php bloginfo( 'template_url' ); ?>/ie.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <?php if ( is_page('投稿') ) { ?>
    <link href="<?php bloginfo( 'template_url' ); ?>/contribute.css" rel="stylesheet" type="text/css" media="all" />
    <script src="<?php bloginfo( 'template_url' ); ?>/js/contribute.js" type="text/javascript"></script>
    <script src="<?php bloginfo( 'template_url' ); ?>/xheditor/xheditor-1.2.1.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo( 'template_url' ); ?>/xheditor/xheditor_lang/zh-cn.js" type="text/javascript"></script>
    <?php } ?>  
</head>

<body>
<!--[if lt IE 7]>
<div id="tips-ie">
<p>欢迎来到<strong><a title="<?php bloginfo('name'); ?>" href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></strong></p>
<p>很抱歉，您正在使用的是一个古代的浏览器浏览网页，本站无法在IE6（包含IE6）以下版本的IE系列浏览器中正常访问。</p>
<p>您所看到的内容为本站的裸奔状态（纯文本，无样式无脚本）为了获得更好的浏览体验，请升级到更高级的浏览器或下载其他现代浏览器。</p>
</div>
<![endif]-->
<div class="left ption_f">
  <p class="left_line_y f_r"></p>
</div>
<div class="right ption_f">
  <p class="right_line_y f_l"></p>
</div>
<div class="header ption_r">
  <div class="progress ption_a">
    <p id="prog-p" class="ption_a">&nbsp;</p>
    <progress value="0" max="100" id="prog-bar" class="ption_a"></progress>
  </div>
  <div class="header_content mauto ption_r">
    <div class="title f_l">
      <h1><a class="t_shadow_3 fs14 f_w" href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('description'); ?>"><?php bloginfo('description'); ?></a></h1>
      <div class="sub_title pl10">
        <div class="mobile_nav_buttons"></div>
        <div class="clear"></div>
        <?php if ( ! dynamic_sidebar( 'Header' ) ) : ?><?php endif ?>
      </div>
    </div>
    <div class="connect ption_r f_r" id="connect">
      <ul>
        <li><a rel="external nofollow" id="connect_rss"  title="RSS订阅本站点内容" target="_blank" href="<?php echo javin_opt('javin_rss'); ?>">Rss订阅</a></li>
        <li><a rel="external nofollow" id="connect_email" title="邮件订阅本站内容" target="_blank" href="http://list.qq.com/cgi-bin/qf_invite?id=3acfbce8879b6c783c54dcd91f8f089d0d419a4afac82174">邮件订阅</a></li>
        <li><a id="connect_add" class="add_menu" href="javascript:void(0)">关注本站</a>
          <div class="clear"></div>
          <div class="add_menu_list" style="display: none; ">
            <p><span id="add_icon_xwei"></span><a rel="external nofollow" title="关注新浪微博" target="_blank" href="<?php echo javin_opt('javin_weibo'); ?>">关注新浪微博</a></p>
            <p><span id="add_icon_twei"></span><a rel="external nofollow" title="关注腾讯微博" target="_blank" href="<?php echo javin_opt('javin_qq_weibo'); ?>">关注腾讯微博</a></p>
          </div>
        </li>
        <li>
          <a id="load_icon" href="javascript:void(0)">登录</a>
          <div class="clear"></div>
          <div id="custom-loginform-box" class="ption_a t_shadow">
            <div id="custom-loginform">
              <?php
                if ( ! is_user_logged_in() ) { ?>
                  <?php
                    $args = array(
                        'redirect' => site_url(), 
                        'form_id' => 'loginform-custom',
                        'label_username' => __( '用户名' ),
                        'label_password' => __( '密码' ),
                        'label_remember' => __( '记住登录信息' ),
                        'label_log_in' => __( '登录网站' ),
                        'remember' => false,
                        'value_username' => NULL,
                    );
                    wp_login_form( $args );
                  ?>
                  <p class="loginform-custom-other text_c">
                      <a href="<?php echo wp_registration_url(); ?>" title="Register">注册</a> 或者 
                      <a href="<?php echo get_option('home'); ?>/wp-login.php?action=lostpassword">忘记密码？</a>
                  </p>
                <?php } else { ?>
                  <div id="author" class="text_c">
                  <div class="author-name-info">
                  <?php global $current_user; get_currentuserinfo(); ?>
                    <h4><a target="_blank" href="<?php echo $current_user->user_url ?>"><?php echo $current_user->user_login ?></a></h4>
                    <div class="author-avatar">
                     <?php echo get_avatar($current_user->user_email, 70, '', $current_user->user_login);?>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <p class="author-edit mt10"><?php if ( $user_level <= 1 ) : ?><a class="green-button" href="<?php bloginfo('url') ?>/wp-admin/post-new.php">发表文章</a><?php endif ?><a class="green-button" href="<?php bloginfo('url') ?>/wp-admin/profile.php">编辑资料</a></p>
                  <p class="author-edit"><a class="block-button" href="<?php bloginfo('url') ?>/wp-admin/">后台管理</a><a class="block-button" href="<?php echo wp_logout_url( home_url() ); ?>">退出登录</a></p>
                  </div>
              <?php } ?>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <?php get_template_part( 'header-nav' ); ?>
    <div class="logo ption_a"><a href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></div>
    <div class="jian ption_a"></div>
  </div>
</div>