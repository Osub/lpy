<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('WP_DEBUG',true);

class WXLOG_Admin {

	private $settings;

	public function __construct() {

		// Directory protection
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );

		include_once( 'class-wxlog-custom-menu.php' );//先生成一级菜单，后加载二级菜单
		include_once( 'class-wxlog-settings.php' );//先生成一级菜单，后加载二级菜单
		include_once( 'class-wxlog-about.php' );//先生成一级菜单，后加载二级菜单
		include_once( 'class-wxlog-test.php' );//先生成一级菜单，后加载二级菜单

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts_style' ) );

		add_action( 'admin_init', array( $this, 'export_wxlog_log' ) );
		add_action( 'admin_init', array( $this, 'delete_wxlog_log' ) );


	}

	public function admin_enqueue_scripts_style() {
		global $WXLOG;
		wp_enqueue_style( 'wxlog_admin_css', $WXLOG->plugin_url() . '/includes/admin/css/admin.css' );
		wp_enqueue_style( 'wxlog_emoji_css', $WXLOG->plugin_url() . '/includes/admin/css/emoji.css' );
		if(get_bloginfo('version')>=3.8){
			wp_enqueue_style( 'wxlog_menu_css', $WXLOG->plugin_url() . '/includes/admin/css/menu.css' );
		}
	}


	//管理菜单
	public function admin_menu() { 
		$m_icon ='';
		if(get_bloginfo('version')<3.8){
			$m_icon = plugins_url('wxlog/includes/admin/images/weixin.ico');
		}
		add_menu_page('最新消息', '微信日志', 'administrator', 'wxlog_log', array( $this, 'log_viewer' ),$m_icon, 6);
		add_submenu_page( 'wxlog_log', '自定义回复', '自定义回复', 'administrator', 'wxlog_custom_reply', array( $this, 'custom_viewer' ) );
		add_submenu_page( 'wxlog_log', '官方插件功能', '官方插件功能', 'administrator', 'wxlog_plugins_lists', array( $this, 'plugins_viewer' ) );
		add_submenu_page( 'wxlog_log', '高级插件', '高级插件', 'administrator', 'wxlog_advanced_plugins_lists', array( $this, 'advanced_plugins_viewer' ) );
	}


	/**
	 * log_viewer function.
	 *
	 * @access public
	 * @return void
	 */
	function log_viewer() {
		global $WXLOG;
			if ( ! class_exists( 'WP_List_Table' ) )
				require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	
			add_thickbox();		
			require_once( 'class-wxlog-log.php' );
	
			$WXLOG_Log = new WXLOG_Log();
			$WXLOG_Log->prepare_items();
			?>
<div class="wrap">
  <h2>最新消息 <a href="<?php echo wp_nonce_url( add_query_arg( 'export_wxlog_log', 'true' ), 'export_wxlog_log'); ?>" class="add-new-h2">导出消息</a> <a style="display:none;" href="<?php echo wp_nonce_url( add_query_arg( 'delete_wxlog_log', 'true' ), 'delete_wxlog_log' ); ?>" class="add-new-h2">清空消息</a></h2>

<ul class='subsubsub'>
	<li class='all'><a href='admin.php?page=wxlog_log' class="current">全部<span class="count">（<?php echo  $WXLOG_Log->all; ?>）</span></a> |</li>
	<li class='publish'><a href='admin.php?page=wxlog_log&s=subscribe'>关注<span class="count">（<?php echo  $WXLOG_Log->subscribe; ?>）</span></a> |</li>
	<li class='trash'><a href='admin.php?page=wxlog_log&s=unsubscribe'>取消关注<span class="count">（<?php echo  $WXLOG_Log->unsubscribe; ?>）</span></a></li>
</ul>

  <form id="wxlog_log" action="" method="get">
    <?php $WXLOG_Log->search_form(); ?>
    <?php $WXLOG_Log->display() ?>
  </form>
</div>
<?php	
		}

	/**
	 * custom_viewer function.
	 *
	 * @access public
	 * @return void
	 */
	function custom_viewer() {
		global $WXLOG;
		parse_str($_SERVER['QUERY_STRING'], $queryVars);
		if(isset($queryVars['edit'])){
	
			require_once( 'class-wxlog-custom-reply-edit.php' );
			$WXLOG_Custom_reply_edit = new WXLOG_Custom_reply_edit();
			$WXLOG_Custom_reply_edit->edit_page();

		}else{
		
			if ( ! class_exists( 'WP_List_Table' ) )
				require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	
			require_once( 'class-wxlog-custom-reply.php' );
	
			$WXLOG_Custom_reply = new WXLOG_Custom_reply();
			$WXLOG_Custom_reply->prepare_items();
			?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-wxlog_custom_reply"><br/></div>
  <h2>自定义回复 <a href="?page=wxlog_custom_reply&edit" class="add-new-h2">添加自定义回复</a></h2>
  <br/>
  <form id="wxlog_custom_reply" action="" method="get">
    <?php $WXLOG_Custom_reply->display() ?>
  </form>
</div>
<?php
		}	
		
	}



	/**
	 * plugins_viewer function.
	 *
	 * @access public
	 * @return void
	 */
	function plugins_viewer() {
		global $WXLOG;
		parse_str($_SERVER['QUERY_STRING'], $queryVars);
		if(isset($queryVars['edit'])){

		}else{
		
			if ( ! class_exists( 'WP_List_Table' ) )
				require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	
			require_once( 'class-wxlog-plugins-lists.php' );
	
			$WXLOG_Plugins_Lists = new WXLOG_Plugins_Lists();
			$WXLOG_Plugins_Lists->prepare_items();
			?>
            
            <div class="wrap">
            
            <?php if(!function_exists('file_get_contents')){?>  <div class="update-nag">您的服务器不支持 file_get_contents 函数</div> <?php }?>           
            <?php if(!fsockopen("www.phplog.com", 80, $errno, $errstr, 30)) {?>  <div class="update-nag">连接官方插件服务器失败，请检查您的服务器的DNS</div> <?php }?>           
              <div id="icon-edit" class="icon32 icon32-posts-wxlog_plugins_lists"><br/></div>
              <h2>官方插件功能</h2>
              <br/>
              
              <form id="wxlog_plugins_lists" action="" method="get">
                <?php $WXLOG_Plugins_Lists->display() ?>
              </form>
            </div>
            <?php
		}	
		
	}



	/**
	 * advanced_plugins_viewer function.
	 *
	 * @access public
	 * @return void
	 */
	function advanced_plugins_viewer() {
		global $WXLOG;
		parse_str($_SERVER['QUERY_STRING'], $queryVars);
		if(isset($queryVars['edit'])){

		}else{
		
			if ( ! class_exists( 'WP_List_Table' ) )
				require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	
			require_once( 'class-wxlog-advanced-plugins-lists.php' );
	
			$WXLOG_Advanced_Plugins_Lists = new WXLOG_Advanced_Plugins_Lists();
			$WXLOG_Advanced_Plugins_Lists->prepare_items();
			?>
            
            <div class="wrap">
            
            <?php if(!function_exists('file_get_contents')){?>  <div class="update-nag">您的服务器不支持 file_get_contents 函数</div> <?php }?>           
            <?php if(!fsockopen("www.phplog.com", 80, $errno, $errstr, 30)) {?>  <div class="update-nag">连接高级插件服务器失败，请检查您的服务器的DNS</div> <?php }?>           
              <div id="icon-edit" class="icon32 icon32-posts-wxlog_advanced_plugins_lists"><br/></div>
              <h2>高级插件</h2>
              <br/>
              
              <form id="wxlog_advanced_plugins_lists" action="" method="get">
                <?php $WXLOG_Advanced_Plugins_Lists->display() ?>
              </form>
            </div>
            <?php
		}	
		
	}







	//删除回复
	public function delete_wxlog_log() {
		global $wpdb;
		if ( empty( $_GET['delete_wxlog_log'] ) )
			return;
		check_admin_referer( 'delete_wxlog_log' );
		$wpdb->query( "DELETE FROM {$wpdb->wxlog_log};" );
	}

	//导出回复
	public function export_wxlog_log() {
		global $wpdb;
		if ( empty( $_GET['export_wxlog_log'] ) )
			return;
		$filter_status = isset( $_REQUEST['filter_status'] ) ? sanitize_text_field( $_REQUEST['filter_status'] ) : '';
        $filter_month  = ! empty( $_REQUEST['filter_month'] ) ? sanitize_text_field( $_REQUEST['filter_month'] ) : '';
		$items = $wpdb->get_results(
			$wpdb->prepare(
		    	"SELECT * FROM {$wpdb->wxlog_log}
		    	WHERE `message` != ''
		    	" . ( $filter_status ? "AND status = '%s'" : "%s" ) . "
	            " . ( $filter_month ? "AND timestamp >= '%s'" : "%s" ) . "
	            " . ( $filter_month ? "AND timestamp <= '%s'" : "%s" ) . "
		    	ORDER BY ID DESC",
	    		( $filter_status ? $filter_status : "" ),
                ( $filter_month ? date( 'Y-m-01', strtotime( $filter_month ) ) : "" ),
                ( $filter_month ? date( 'Y-m-t', strtotime( $filter_month ) ) : "" )
            )
        );
        $rows   = array();
        $row    = array();
        $row[]  = '用户';
        $row[]  = '内容';
        $row[]  = '类型';
        $row[]  = '状态';
        $row[]  = '时间';
        $row[]  = '用户';
        $row[]  = 'IP';
        $row[]  = '浏览器';
        $row[]  = '消息';
        $row[]  = '回复';
        $rows[] = '"' . iconv("UTF-8","GBK//IGNORE",implode( '","', $row )) . '"';
		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				$row    = array();
				$row[]  = $item->ID;
				$row[]  = $item->content;
				$row[]  = $item->msgtype;
        		if ( $item->msgtype==2 )
        			$row[]  = '已回复';
        		else
        			$row[]  = '未回复';
				
				$row[]  = $item->timestamp;
				$row[]  = $item->user_id;

				if ( $item->user_id )
        			$user = get_user_by( 'id', $item->user_id );

        		if ( ! isset( $user ) || ! $user ) {
	        		//$row[]  = '-';
	        		//$row[]  = '-';
        		} else {
        			//$row[]  = $user->user_login;
	        		//$row[]  = $user->user_email;
        		}
				$row[]  = $item->user_ip;
				$row[]  = $item->user_agent;
				$row[]  = $item->message;
				$row[]  = $item->reply;
				$rows[] = '"' . iconv("UTF-8","GBK//IGNORE",implode( '","', $row )) . '"';
			}
		}
		$log = implode( "\n", $rows );
		header( "Content-type: text/csv" );
		header( "Content-Disposition: attachment; filename=wxlog_log.csv" );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( "Content-Length: " . strlen( $log ) );
		exit($log);
	}

}

new WXLOG_Admin();
