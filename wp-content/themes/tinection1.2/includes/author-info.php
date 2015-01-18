<?php
/**
 * Includes of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.0
 * @date      2014.12.16
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<div class="single-author clr">
	<div class="img"><?php echo tin_get_avatar( get_the_author_ID() , '100' , tin_get_avatar_type(get_the_author_ID()) ); ?></div>
	<div class="single-author-info">
		<div class="word">
			<div class="wordname"><?php _e('关于','tinection');the_author_posts_link(); ?></div>
			<div class="authordes"><?php the_author_description(); ?></div>
			<div class="authorsocial">
			<?php
				$author_info = get_userdata(get_the_author_ID());
				$sinawb = $author_info->tin_sina_weibo;
				$qqwb	= $author_info->tin_qq_weibo;
				$weixin = $author_info->tin_weixin;;
				$qq	 	= $author_info->tin_qq;
				$twitter	 	= $author_info->tin_twitter;
				$googleplus	 	= $author_info->tin_googleplus;
				$donate = $author_info->tin_donate;
				$author_home = $author_info->user_url;
			?>
			<?php if(!empty($author_home)){ ?>
			<span class="social-icon-wrap"><a class="as-img as-home" href="<?php echo $author_home; ?>" title="<?php _e('作者主页','tinection'); ?>"><i class="fa fa-home"></i><?php _e('作者主页','tinection'); ?></a></span>
			<?php } ?>
			<?php if(!empty($donate)){ ?>
			<span class="social-icon-wrap"><a class="as-img as-donate" href="<?php echo $donate; ?>" title="<?php _e('赞助作者','tinection'); ?>"><i class="fa fa-coffee"></i><?php _e('赞助作者','tinection'); ?></a></span>
			<?php } ?>
			<?php if(!empty($sinawb)){ ?>
			<span class="social-icon-wrap"><a class="as-img as-sinawb" href="http://weibo.com/<?php echo $sinawb; ?>" title="<?php _e('微博','tinection'); ?>"><i class="fa fa-weibo"></i></a></span>
			<?php } ?>
			<?php if(!empty($qqwb)){ ?>
			<span class="social-icon-wrap"><a class="as-img as-qqwb" href="http://t.qq.com/<?php echo $qqwb; ?>" title="<?php _e('腾讯微博','tinection'); ?>"><i class="fa fa-tencent-weibo"></i></a></span>
			<?php } ?>
			<?php if(!empty($twitter)){ ?>
			<span class="social-icon-wrap"><a class="as-img as-twitter" href="https://twitter.com/<?php echo $twitter; ?>" title="Twitter"><i class="fa fa-twitter"></i></a></span>
			<?php } ?>
			<?php if(!empty($googleplus)){ ?>
			<span class="social-icon-wrap"><a class="as-img as-googleplus" href="<?php echo $googleplus; ?>" title="Google+"><i class="fa fa-google-plus"></i></a></span>
			<?php } ?>
			<?php if(!empty($weixin)){ ?>
			<span class="social-icon-wrap"><a class="as-img as-weixin" href="#" id="as-weixin-a" title="<?php _e('微信','tinection'); ?>"><i class="fa fa-weixin"></i>
				<div id="as-weixin-qr"><img src="<?php echo $weixin; ?>" title="<?php _e('微信扫描二维码加我为好友并交谈','tinection'); ?>" /></div>
			</a></span>
					
			<?php } ?>
			<?php if(!empty($qq)){ ?>
			<span class="social-icon-wrap"><a class="as-img as-qq" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $qq; ?>&site=qq&menu=yes" title="<?php _e('QQ交谈','tinection'); ?>"><i class="fa fa-qq"></i></a></span>
			<?php } ?>
			<span class="social-icon-wrap"><a class="as-img as-email" href="mailto:<?php the_author_email(); ?>" title="<?php _e('给我写信','tinection'); ?>"><i class="fa fa-envelope"></i></a></span>					
			</div>
			</div>
	</div>
</div>
<div class="clear"></div>