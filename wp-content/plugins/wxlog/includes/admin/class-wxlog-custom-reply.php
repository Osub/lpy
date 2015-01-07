<?php
 
class WXLOG_Custom_reply extends WP_List_Table {

	function __construct(){
		global $status, $page, $wpdb;

		$id = intval($_GET['del']);
		if($id>0){
			$wpdb->delete( $wpdb->wxlog_custom_reply, array( 'ID' => $id ), array( '%d' ) );
		}

		parent::__construct( array(
			'singular'  => 'reply',
			'plural'    => 'replys',
			'ajax'      => false
		) );

		$this->filter_status = isset( $_REQUEST['filter_status'] ) ? sanitize_text_field( $_REQUEST['filter_status'] ) : '';
		$this->logs_per_page = ! empty( $_REQUEST['logs_per_page'] ) ? intval( $_REQUEST['logs_per_page'] ) : 25;
		$this->filter_month  = ! empty( $_REQUEST['filter_month'] ) ? sanitize_text_field( $_REQUEST['filter_month'] ) : '';

		if ( $this->logs_per_page < 1 )
			$this->logs_per_page = 9999999999999;
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
			case 'keyword' :
				return $item->keyword;
			break;
			case 'status' :
				switch ( $item->status ) {
					case '2' :
						$status = '<span class="completed" title="' . esc_attr( '有效' ) . '">&#10004;</span>';
					break;
					default :
						$status = '<span class="failed" title="' . esc_attr( '无效' ) . '">&#10082;</span>';
					break;
				}
				return $status;
			break;
			case 'msgtype' :
				return $item->msgtype;
			break;
			case 'content' :
				if($item->msgtype=='news'){
					$wxlog_news_list_title = explode('|phplogcom|',stripslashes($item->title));
					return $wxlog_news_list_title[0];
				}
				return $item->content;
			break;		
			case 'timestamp' :
				return '<time title="' . date_i18n( get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ), strtotime($item->timestamp) ) . '">' . sprintf( '%s 前', human_time_diff( strtotime($item->timestamp), current_time( 'timestamp' ) ) ) . '</time>';
			break;
			case 'operating' :
				return '<a href="?page=wxlog_custom_reply&edit='.$item->ID.'">编辑</a> <a onClick="return confirm(\'您确定要删除么\')" href="?page=wxlog_custom_reply&del='.$item->ID.'">删除</a>';
			break;

		}
	}

	//列表字段
	public function get_columns(){
		$columns = array(
			'keyword'     => '关键字',
			'content'     => '内容',
			'msgtype'     => '类型',
			'status'      => '状态',
			'timestamp'   => '时间',
			'operating'   => '操作'						
		);
		return $columns;
	}

	//筛选
	public function display_tablenav( $which ) {
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<?php if ( 'top' == $which ) : ?>
				<div class="alignleft actions">
					<input type="hidden" name="page" value="wxlog_custom_reply" />
					<select name="filter_status">
						<option value="">显示所有状态</option>
						<option value="0" <?php selected( $this->filter_status, '0' ); ?>>无效</option>
						<option value="1" <?php selected( $this->filter_status, '1' ); ?>>有效</option>
					</select>
					<?php
						global $wpdb, $wp_locale;
						$months = $wpdb->get_results( "
							SELECT DISTINCT YEAR( timestamp ) AS year, MONTH( timestamp ) AS month
							FROM {$wpdb->wxlog_custom_reply}
							WHERE `ID` != ''
							ORDER BY timestamp DESC");
						$month_count = count( $months );
						if ( $month_count && ! ( 1 == $month_count && 0 == $months[0]->month ) ) :
							$m = isset( $_GET['filter_month'] ) ? $_GET['filter_month'] : 0;
							?>
							<select name="filter_month">
								<option <?php selected( $m, 0 ); ?> value='0'>显示所有日期</option>
								<?php
										foreach ( $months as $arc_row ) {
											if ( 0 == $arc_row->year )
												continue;
											$month = zeroise( $arc_row->month, 2 );
											$year = $arc_row->year;
											printf( "<option %s value='%s'>%s</option>\n",
												selected( $m, $year . '-' . $month, false ),
												esc_attr( $year . '-' . $month ),
												sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year )
											);
										}
								 ?>
							</select>
						<?php endif;
					?>
					<select name="logs_per_page">
						<option value="25">每页25行</option>
						<option value="50" <?php selected( $this->logs_per_page, 50 ) ?>>每页25行</option>
						<option value="100" <?php selected( $this->logs_per_page, 100 ) ?>>每页100行</option>
						<option value="200" <?php selected( $this->logs_per_page, 200 ) ?>>每页200行</option>
						<option value="-1" <?php selected( $this->logs_per_page, -1 ) ?>>显示全部</option>
					</select>
					<input type="submit" value="筛选" class="button" />
				</div>
			<?php endif; ?>
			<?php
					$this->extra_tablenav( $which );
					$this->pagination( $which );
			?>
			<br class="clear" />
		</div><?php
	}

	/**
	 * prepare_items function.
	 *
	 * @access public
	 * @return void
	 */
	function prepare_items() {
		global $wpdb;

		$per_page      = $this->logs_per_page;
		$current_page  = $this->get_pagenum();
		$filter_status = $this->filter_status;
		$filter_month  = date( "m", strtotime( $this->filter_month ) );
		$filter_year   = date( "Y", strtotime( $this->filter_month ) );

		// Init headers
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		$query_where = " `ID` != '' ";

		if ( $this->filter_status !== '' )
			$query_where .= " AND status = '{$filter_status}' ";

		if ( $this->filter_month )
			$query_where .= " AND timestamp >= '" . date( 'Y-m-01', strtotime( $this->filter_month ) ) . "' ";

		if ( $this->filter_month )
			$query_where .= " AND timestamp <= '" . date( 'Y-m-t', strtotime( $this->filter_month ) ) . "' ";

		//总数
		$total_items = $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->wxlog_custom_reply} WHERE {$query_where};" );

		//列表数据
		$this->items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->wxlog_custom_reply} WHERE {$query_where} ORDER BY timestamp DESC LIMIT %d, %d;",
				( $current_page - 1 ) * $per_page,
				$per_page
			)
		);
		
		//print_r("SELECT COUNT(ID) FROM {$wpdb->wwy_custom_reply} WHERE {$query_where};");
		//print_r($this->items);
		
		// 分页
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page )
		) );

	}
}
