<?php
 
class WXLOG_Advanced_Plugins_Lists extends WP_List_Table {

	function __construct(){

		parent::__construct( array(
			'singular'  => 'wxlog_advanced_plugin',
			'plural'    => 'wxlog_advanced_plugins',
			'ajax'      => false
		) );
		
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
		switch( $column_name ) {
			case 'pluginname' :
				return $item->name;
			break;
			case 'status' :
				switch ( $item->status ) {
					case '3' :
						$status = '<span class="updated" title="' . esc_attr( '更新中'.$item->progress.'%' ) . '">更新中'.$item->progress.'%</span>';
					break;
					case '2' :
						$status = '<span class="completed" title="' . esc_attr( '有效' ) . '">&#10004;</span>';
					break;
					default :
						$status = '<span class="development" title="' . esc_attr( '开发中'.$item->progress.'%' ) . '">开发中'.$item->progress.'%</span>';
					break;
				}
				return $status;
			break;
			case 'msgtype' :
				return $item->msgtype;
			case 'price' :
				return '￥'.$item->price.'.00';
			case 'version' :
				return $item->version;
			break;
			case 'content' :
				return $item->content;
			break;		
			case 'timestamp' :
				return '<time title="' . date_i18n( get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ), strtotime($item->timestamp) ) . '">' . sprintf( '%s 前', human_time_diff( strtotime($item->timestamp), current_time( 'timestamp' ) ) ) . '</time>';
			break;
		}
	}

	//列表字段
	public function get_columns(){
		static $cb_counter = 1;
		$columns = array(
			'pluginname'  => '插件名称',
			'content'     => '描述',
			'msgtype'     => '回复类型',
			'version'      => '版本',
			'price'      => '价格',
			'status'   => '开发状态',
			'timestamp'   => '时间',
		);
		return $columns;
	}


	//筛选
	public function display_tablenav( $which ) {?>
		<?php if ( 'top' == $which ) : ?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
        <div class="alignleft actions">为了更好的为大家服务，高级插件采用付费方式获得，谢谢大家的支持。</div>
			<?php $this->pagination( $which );?>
			<br class="clear" />
		</div>
 			<?php else: ?>
			<div class="tablenav bottom">
				<?php $this->pagination( $which ); ?>
				<br class="clear" />
			</div>
		<?php endif;
	}

	//获取记录
	function prepare_items() {
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		if(fsockopen("www.phplog.com", 80, $errno, $errstr, 30)) {
        	$this->items = json_decode(@file_get_contents('http://www.phplog.com/?wxlog_plugins&t=a&host='.$_SERVER['HTTP_HOST']));
		}
		//print_r($this->items);
		$total_items = count( $this->items );
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => 1,
			'total_pages' => 1
		) );
	}
		
}
