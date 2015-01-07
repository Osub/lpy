<?php
/*
Plugin Name: 微信日志 For Wordpress
Plugin URI: http://www.phplog.com/wxlog
Description: 微信日志是基于Wordpress的完全开源的微信插件，利用微信日志能很方便扩展微信公众号的功能。微信日志完美支持Wordpress的插件方式来扩展更多的功能。
Version: 1.1.5
Author: zhangji
Author URI: http://www.phplog.com/
Requires at least: 3.8
Tested up to: 3.8

	Copyright: © 2013 zhangji.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( "TOKEN", get_option( 'wxlog_token' ) ); 

class WXLOG {

	private $plugin_url;
	private $plugin_path;
	private $_inline_js;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		global $wpdb;

		//定义常量

		//表名
		$wpdb->wxlog_log = $wpdb->prefix . 'wxlog_log';
		$wpdb->wxlog_custom_reply = $wpdb->prefix . 'wxlog_custom_reply';		

		include_once( 'includes/wxlog-functions.php' );

		add_action( 'admin_footer', array( $this, 'output_inline_js' ), 25 );

		//该插件函数在插件被激活时运行。 		
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array( $this, 'install_tables' ), 12 );		

		add_filter( 'plugin_action_links', array( $this, 'settings_link' ),                                 10, 2 );
		add_action('parse_request', array( $this, 'register_wxlog' ), 1);
	
		if ( is_admin() )
			include_once( 'includes/admin/class-wxlog-admin.php' );
			
	}

	// Add a "Settings" link to the plugins page
	function settings_link(  $links, $file ) {
		static $this_plugin;

		if( empty($this_plugin) )
			$this_plugin = plugin_basename(__FILE__);

		if ( $file == $this_plugin )
			$links[] = '<a href="' . admin_url( 'admin.php?page=wxlog_setting' ) . '">配置</a>';

		return $links;
	}
 
	function register_wxlog(){
		if(isset($_GET[TOKEN])){
			include_once( 'includes/class-wxlog.php' );
			exit();
		}
	}
 
	public function plugin_url() {
		if ( $this->plugin_url )
			return $this->plugin_url;
		return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
	}
 
	//插件路径
	public function plugin_path() {
		if ( $this->plugin_path )
			return $this->plugin_path;
		return $this->plugin_path = plugin_dir_path( __FILE__ );
	} 
 
 	//增加JS代码
	public function add_inline_js( $code ) {
		$this->_inline_js .= "\n" . $code . "\n";
	}
 
	//输出JS代码
	public function output_inline_js() {
		if ( $this->_inline_js ) {
			echo "<script type=\"text/javascript\">\njQuery(document).ready(function($) {";
			echo $this->_inline_js;
			echo "});\n</script>\n";
			$this->_inline_js = '';
		}
	} 

	//添加自定义回复和微信消息表 只在开启插件时调用
	public function install_tables() {
		global $wpdb;
		$wpdb->hide_errors();
		$collate = '';
	    if ( $wpdb->has_cap( 'collation' ) ) {
			if( ! empty( $wpdb->charset ) )
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			if( ! empty( $wpdb->collate ) )
				$collate .= " COLLATE $wpdb->collate";
	    }
	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    $sql = "CREATE TABLE {$wpdb->wxlog_log} (
			  ID bigint(20) NOT NULL auto_increment,
			  fromusername varchar(40) NOT NULL,
			  msgtype varchar(20) NOT NULL,
			  content varchar(500) NOT NULL,
			  signature varchar(40) NOT NULL,
			  timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  nonce int(11) NOT NULL,
			  
			  user_id bigint(20) NOT NULL,
			  user_ip varchar(200) NOT NULL,
			  user_agent varchar(200) NOT NULL,
			  
			  status int(11) NOT NULL,
			  message text NOT NULL,			 
			  reply text NOT NULL,			 
			 
			  PRIMARY KEY  (ID),
			  KEY attribute_name (content)
			) $collate;";
	    dbDelta( $sql );
	    $sql = "CREATE TABLE {$wpdb->wxlog_custom_reply} (
			  ID bigint(20) NOT NULL auto_increment,
			  keyword varchar(200) NOT NULL,
			  msgtype varchar(20) NOT NULL,
			  title varchar(1000) NOT NULL,
			  content varchar(500) NOT NULL,
			  image_url  varchar(1000) NOT NULL,
			  url  varchar(1000) NOT NULL,
			  timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  status int(11) NOT NULL,
			  PRIMARY KEY  (ID),
			  KEY attribute_name (content)
			) $collate;";
	    dbDelta( $sql );
		$sql = "INSERT INTO `wp_wxlog_custom_reply` (`ID`, `keyword`, `msgtype`, `title`, `image_url`, `url`, `content`, `timestamp`, `status`) VALUES
				(1, 'subscribe', 'text', '', '', '', '<强>[强]欢迎关注微信日志http://www.phplog.com/wxlog', '2014-01-14 10:31:50', 2),
				(2, 'unsubscribe', 'text', '', '', '', '<火><火>欢迎再次关注微信日志http://www.phplog.com/wxlog', '2014-01-14 10:31:50', 2),
				(3, 'h,help,帮助', 'text', '', '', '', '<马><马>功能帮助菜单（自己改吧）', '2014-01-14 11:32:45', 2);";
		$wpdb->query($sql);
		
	}	
		
}

$GLOBALS['WXLOG'] = new WXLOG();