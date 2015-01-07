<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WXLOG_About {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
	}

	//管理菜单
	public function admin_menu() {
		add_submenu_page( 'wxlog_log', '使用帮助', '使用帮助', 'administrator', 'wxlog_about', array( $this, 'wxlog_about_page' ) );
	}

	//关于页面内容
	public function wxlog_about_page() {
		?>
		<div class="wrap">
            <h2>使用帮助 <a href="?page=wxlog_setting" class="add-new-h2">配置微信日志</a></h2>
            <div>
 
<b>官方最新消息</b>
<div id="wxlog_news"></div><pre>
<b>相关网址 </b>        
WP插件网址：<a href="http://wordpress.org/plugins/wxlog/" target="_blank">http://wordpress.org/plugins/wxlog/</a>
WP插件SVN网址：<a href="http://plugins.svn.wordpress.org/wxlog/" target="_blank">http://plugins.svn.wordpress.org/wxlog/</a>
微信日志网址：<a href="http://www.phplog.com/wxlog/" target="_blank">http://www.phplog.com/wxlog/</a>  
功能提议：<a href="http://www.phplog.com/proposal/" target="_blank">http://www.phplog.com/proposal/</a>        
微信公众平台：<a href="http://mp.weixin.qq.com/" target="_blank">http://mp.weixin.qq.com/</a>
常见问题：<a href="http://www.phplog.com/detail/415.html" target="_blank">http://www.phplog.com/detail/415.html</a>
子插件开发教程：<a href="http://www.phplog.com/detail/355.html" target="_blank">http://www.phplog.com/detail/355.html</a>

<b>回复内容优先级</b>
1 黑名单过滤
2 调用自定义回复
3 调用官方免费插件
4 调用WP插件
5 调用WP数据
6 小黄鸡聊天	
7 默认的回复内容	

<b>配置步骤</b>
第一步 进入微信日志的配置页面设置Token为abc
第二步 登录微信公众平台进行设置 URL（如：http://www.xxx.com/?abc）
注：其中的abc是可以任意改的，xxx是你的域名	

<b>高级接口功能演示</b>
<img src="http://mmbiz.qpic.cn/mmbiz/MN9J2IGQ3YZaPA2eHHcRdBd1TTXpYiauE7ia0roJeWLGWqNsSfibHdaShlPwY9qKUKPSlzXdSDGX81YgQyUhxutnA/0" />

因为这是一个测试号，只允许20个人关注，所以请大家测试完就取消关注吧，谢谢。




          </pre>  </div>
          
          
		</div>
		<?php  global $WXLOG; $WXLOG->add_inline_js("jQuery('#wxlog_news').load('http://www.phplog.com/?wxlog_plugins&wn=1');");
	}




}

new WXLOG_About();