<?php
/**
 * Includes of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.0
 * @date      2014.12.11
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014-2015, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<div id="login-box-mobile">
<?php if (!(current_user_can('level_0'))) { ?>
	<div class="login-box-mobile-form">
	<form action="<?php echo get_option('home'); ?>/wp-login.php" method="post">
		<div class="login-box-mobile-name">
		<input type="text" name="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="18" placeholder="<?php _e('请输入用户名','tinection'); ?>" />
		</div>
		<div class="login-box-mobile-pass">
		<input type="password" name="pwd" size="18" placeholder="<?php _e('密码','tinection'); ?>"/>
		</div>
		<div class="login-box-mobile-submit">	
		<?php if(ot_get_option('tin_open_qq')=='on') { ?>
            <a class="btn-qq btns" href="<?php echo home_url('/?connect=qq&action=login&redirect='.urlencode(tin_get_redirect_uri())) ?>">
            </a>
			<?php } ?>
			<?php if(ot_get_option('tin_open_weibo')=='on') { ?>
            <a class="btn-weibo btns" href="<?php echo home_url('/?connect=weibo&action=login&redirect='.urlencode(tin_get_redirect_uri())) ?>">
            </a>
		<?php } ?>
		<input type="submit" name="submit" value="<?php _e('登录','tinection'); ?>" class="button" />
		</div>
		<div class="login-box-mobile-func">
		<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
		<div class="login-box-mobile-register">
		<a href="<?php echo get_option('home'); ?>/wp-login.php?action=lostpassword" style="color:#e25442;"><?php _e('忘记密码 ?','tinection'); ?></a><a href="<?php bloginfo('url'); ?>/wp-login.php?action=register"><?php _e('  现在注册','tinection'); ?></a>
		</div>
		</div>
	</form>
	</div>
<?php } else { ?>
	<div class="login-yet-mobile">
<?php global $current_user; get_currentuserinfo();?>
		<div class="login-yet-mobile-avatar">
			<?php echo tin_get_avatar( $current_user->ID , '60' , tin_get_avatar_type($current_user->ID) ); ?>
		</div>
		<div class="login-yet-mobile-manageinfo">
		<a href="<?php bloginfo('url'); ?>" class="title"><?php bloginfo('name');?></a>
		<a href="<?php echo tin_get_user_url('profile'); ?>" class="name">@&nbsp;<?php echo $current_user->display_name;?></a>
		<?php $unread = intval(get_tin_message($current_user->ID, 'count', "msg_type='unread' OR msg_type='unrepm'")); if($unread>0) { ?><a href="<?php echo tin_get_user_url('message'); ?>" title="<?php _e('新消息','tinection'); ?>" class="new-message-notify"></a><?php } ?>
		</div>
		<div class="clr"></div>
	</div>
<?php }?>
</div>