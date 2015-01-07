<?php
 
class WXLOG_Plugins_Lists extends WP_List_Table {

	function __construct(){

		parent::__construct( array(
			'singular'  => 'wxlog_plugin',
			'plural'    => 'wxlog_plugins',
			'ajax'      => false
		) );
		
		if($_GET['action']=='wxlog_plugin_open'){
			//echo '<pre>';print_r($_GET['wxlog_plugins']);
			update_option('wxlog_my_plugins', implode(',',$_GET['wxlog_plugins']));
			flush_rewrite_rules();
			echo '<div class="updated fade"><p>操作成功。</p></div>';
		}
		
	}

	/**
	 * column_default function.
	 *
	 * @access public
	 * @param mixed $log
	 * @param mixed $column_name
	 * @return void
	 */
	function column_default( $item, $column_name ) {
		$wxlog_my_plugins = explode(',',get_option( 'wxlog_my_plugins' ));
		switch( $column_name ) {
			case 'pluginname' :
				return $item->name;
			break;
			case 'status' :
				switch ( $item->status ) {
					case '2' :
						$status = '<span class="completed" title="' . esc_attr( '有效' ) . '">&#10004;</span>';
					break;
					default :
						$status = '<span class="development" title="' . esc_attr( '开发中' ) . '">开发中</span>';
					break;
				}
				return $status;
			break;
			case 'msgtype' :
				return $item->msgtype;
			break;
			case 'content' :
				return $item->content;
			break;		
			case 'timestamp' :
				return '<time title="' . date_i18n( get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ), strtotime($item->timestamp) ) . '">' . sprintf( '%s 前', human_time_diff( strtotime($item->timestamp), current_time( 'timestamp' ) ) ) . '</time>';
			break;
		}
	}


	function column_cb( $item ) {
		$wxlog_my_plugins = explode(',',get_option( 'wxlog_my_plugins' ));?>
        <label class="screen-reader-text" for="cb-select-<?php echo $item->ID; ?>"><?php printf( __( 'Select %s' ), '消息' ); ?></label>
        <input<?php if(in_array($item->ID,$wxlog_my_plugins) and $item->status==2){ ?> checked="checked"<?php }?> <?php if($item->status!=2){ ?>  disabled="disabled"<?php }?>id="cb-select-<?php echo  $item->ID; ?>" type="checkbox" name="wxlog_plugins[]" value="<?php  echo  $item->ID; ?>" />
        <div class="locked-indicator"></div>
		<?php
	}


	function extra_tablenav( $which ) {?>
		<div class="alignleft actions bulkactions">
			<input type="hidden" name="page" value="wxlog_plugins_lists" />
			<select name='action'>
			<option value='-1' selected='selected'>批量操作</option>
			<option value='wxlog_plugin_open' class="hide-if-no-js">开启/关闭</option>
			</select>
			<input type="submit" name="" id="doaction" class="button action" value="应用"  />
		</div> 
 			<?php 
	}


	//列表字段
	public function get_columns(){
		static $cb_counter = 1;
		$columns = array(
			'cb'            => '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . __( 'Select All' ) . '</label>'
				. '<input id="cb-select-all-' . $cb_counter . '" type="checkbox" />',
			'pluginname'     => '插件名称',
			'content'     => '描述',
			'msgtype'     => '回复类型',
			'status'   => '开发状态',
			'timestamp'   => '时间',
		);
		return $columns;
	}


	//筛选
	public function display_tablenav( $which ) {?>
		<?php if ( 'top' == $which ) : ?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
        <div class="alignleft actions">只要开启插件就能实现相应的功能，无需另行开发。</div>
			<?php $this->pagination( $which );?>
			<br class="clear" />
		</div>
 			<?php else: ?>
			<div class="tablenav bottom">
				<?php $this->extra_tablenav( $which ); ?>
				<?php $this->pagination( $which ); ?>
				<br class="clear" />
			</div>
		<?php endif;
	}

	//获取记录
	function prepare_items() {
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		if(fsockopen("www.phplog.com", 80, $errno, $errstr, 30)) {
        	$this->items = json_decode(@file_get_contents('http://www.phplog.com/?wxlog_plugins'));
		}
		$total_items = count( $this->items );
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => 1,
			'total_pages' => 1
		) );
	}
	
	
}
