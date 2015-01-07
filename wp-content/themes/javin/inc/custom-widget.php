<?php
// 滚动评论
class rollingcomments extends WP_Widget{
    function rollingcomments(){//命名与上句和最下句保持一致
		$widget_options = array('classname'=>'set_contact','description'=>'自定义滚动显示评论');
		$this->WP_Widget(false,'Javin 滚动评论',$widget_options);
    }
	function widget($instance){
		include("widget/rollingcomments.php");//调用widget目录小工具代码
?>
<?php
}
}
add_action('widgets_init',create_function('', 'return register_widget("rollingcomments");'));

// 推荐文章
class populararticles extends WP_Widget{
    function populararticles(){//命名与上句和最下句保持一致
		$widget_options = array('classname'=>'set_contact','description'=>'自定义显示热门文章');
		$this->WP_Widget(false,'Javin 热门文章',$widget_options);
    }
	function widget($instance){
		include("widget/populararticles.php");//调用widget目录小工具代码
?>
<?php
}
}
add_action('widgets_init',create_function('', 'return register_widget("populararticles");'));

// 随机文章
class randomarticle extends WP_Widget{
    function randomarticle(){//命名与上句和最下句保持一致
		$widget_options = array('classname'=>'set_contact','description'=>'自定义显示随机文章');
		$this->WP_Widget(false,'Javin 随机文章',$widget_options);
    }
	function widget($instance){
		include("widget/randomarticle.php");//调用widget目录小工具代码
?>
<?php
}
}
add_action('widgets_init',create_function('', 'return register_widget("randomarticle");'));

// 最新文章
class newarticles extends WP_Widget{
    function newarticles(){//命名与上句和最下句保持一致
		$widget_options = array('classname'=>'set_contact','description'=>'自定义显示最新文章');
		$this->WP_Widget(false,'Javin 最新文章',$widget_options);
    }
	function widget($instance){
		include("widget/newarticles.php");//调用widget目录小工具代码
?>
<?php
}
}
add_action('widgets_init',create_function('', 'return register_widget("newarticles");'));

// 个人简介
class profileabout extends WP_Widget{
    function profileabout(){//命名与上句和最下句保持一致
		$widget_options = array('classname'=>'set_contact','description'=>'自定义个人说明');
		$this->WP_Widget(false,'Javin 个人简介',$widget_options);
    }
	function widget($instance){
		include("widget/profileabout.php");//调用widget目录小工具代码
?>
<?php
}
}
add_action('widgets_init',create_function('', 'return register_widget("profileabout");'));

// 文章页显示同类目录
class similarcategory extends WP_Widget{
    function similarcategory(){//命名与上句和最下句保持一致
		$widget_options = array('classname'=>'set_contact','description'=>'自定义文章页目录');
		$this->WP_Widget(false,'Javin 文章页目录',$widget_options);
    }
	function widget($instance){
		include("widget/similarcategory.php");//调用widget目录小工具代码
?>
<?php
}
}
add_action('widgets_init',create_function('', 'return register_widget("similarcategory");'));

// 友情链接
class customlinks extends WP_Widget{
    function customlinks(){//命名与上句和最下句保持一致
		$widget_options = array('classname'=>'set_contact','description'=>'自定义友情链接');
		$this->WP_Widget(false,'Javin 友情链接',$widget_options);
    }
	function widget($instance){
		include("widget/customlinks.php");//调用widget目录小工具代码
?>
<?php
}
}
add_action('widgets_init',create_function('', 'return register_widget("customlinks");'));

// 赞助商广告
class googleadsense extends WP_Widget{
    function googleadsense(){//命名与上句和最下句保持一致
		$widget_options = array('classname'=>'set_contact','description'=>'自定义赞助商广告');
		$this->WP_Widget(false,'Javin 赞助商广告',$widget_options);
    }
	function widget($instance){
		include("widget/sponsoredads.php");//调用widget目录小工具代码
?>
<?php
}
}
add_action('widgets_init',create_function('', 'return register_widget("googleadsense");'));

?>