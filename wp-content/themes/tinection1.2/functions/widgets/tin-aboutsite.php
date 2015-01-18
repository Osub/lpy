<?php
/*
	Tinection Widget
	
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	
	Copyright: (c) 2014 知言博客 - http://www.zhiyanblog.com
	
		@package Tinection
		@version 1.1.0
*/
class tinaboutsite extends WP_Widget {
/*  Widget
/* ------------------------------------ */
	function __construct(){
		parent::__construct(false,'Tin-关于本站',array( 'description' => 'Tinection-关于本站' ,'classname' => 'widget_tinaboutsite'));
	}

	function widget($args,$instance){
		extract($args);
	?>
		<?php echo $before_widget; ?>
        <?php if($instance['title'])echo $before_title.$instance['title']. $after_title; ?>
		<div class="aboutsite">知言博客是由WordPress爱好者Zhiyan建立的一个WordPress以及WEB资源站，集WordPress教程、主题、插件以及众多前端素材、代码等其他内容于一体，本站汇集为WordPress以及前端开发提供帮助的前端网页特效、建站PSD模板、网页模板、开发教程等素材，相关内容均保证绿色安全、优质实用，是您建站学习好帮手。Zhiyan欢迎大家多多交流，期待共同学习进步。<a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=9dcf8d2c615e22fe02da09ab4ea5e0fac7c0dd5c75dce415e7781e89874e3546"><img border="0" src="http://pub.idqqimg.com/wpa/images/group.png" alt="WordPress建站交流群" title="WordPress建站交流群" style="vertical-align: bottom;"></a></div>
		<?php echo $after_widget; ?>

	<?php }

	function update($new_instance,$old_instance){
		return $new_instance;
	}

	function form($instance){
		$title = esc_attr($instance['title']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题：','tinection'); ?><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
	<?php
	}
}
add_action('widgets_init',create_function('', 'return register_widget("tinaboutsite");'));?>