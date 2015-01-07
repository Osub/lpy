<?php

class WXLOG_Log extends WP_List_Table {

	 var $all;
	 var $subscribe;
	 var $unsubscribe;
	 
	function __construct(){
		global $status, $page, $wpdb;

		parent::__construct( array(
			'singular'  => 'log',
			'plural'    => 'logs',
			'ajax'      => false
		) );

		if($_GET['action']=='del' and $_GET['massage']){
			//echo '<pre>';print_r($_GET['massage']);
			$wpdb->query('DELETE FROM `'.$wpdb->wxlog_log.'` WHERE ID in('.implode(',',$_GET['massage']).')');
			flush_rewrite_rules();
			echo '<div class="updated fade"><p>删除成功。</p></div>';
		}

		$this->filter_status = isset( $_REQUEST['filter_status'] ) ? sanitize_text_field( $_REQUEST['filter_status'] ) : '';
		$this->logs_per_page = ! empty( $_REQUEST['logs_per_page'] ) ? intval( $_REQUEST['logs_per_page'] ) : 25;
		$this->filter_month  = ! empty( $_REQUEST['filter_month'] ) ? sanitize_text_field( $_REQUEST['filter_month'] ) : '';
		$this->keyword = $_REQUEST['s'];

		if ( $this->logs_per_page < 1 )
			$this->logs_per_page = 9999999999999;
			
		//总数
		$this->all = $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->wxlog_log} WHERE 1=1;" );
		$this->subscribe = $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->wxlog_log} WHERE content='subscribe';" );
		$this->unsubscribe = $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->wxlog_log} WHERE content='unsubscribe';" );
	}


	function column_cb( $item ) {?>
        <label class="screen-reader-text" for="cb-select-<?php echo $item->ID; ?>"><?php printf( __( 'Select %s' ), '消息' ); ?></label>
        <input id="cb-select-<?php echo  $item->ID; ?>" type="checkbox" name="massage[]" value="<?php  echo  $item->ID; ?>" />
        <div class="locked-indicator"></div>
		<?php
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
			case 'status' :
				switch ( $item->status ) {
					case '2' :
						$status = '<span class="completed" title="' . esc_attr( '已回复' ) . '">&#10004;</span>';
					break;
					case '1' :
						$status = '<span class="redirected" title="' . esc_attr( '处理中' ) . '">&#10140;</span>';
					break;
					default :
						$status = '<span class="failed" title="' . esc_attr( '未回复' ) . '">&#10082;</span>';
					break;
				}
				return $status;
			break;
			case 'timestamp' :
				return '<time title="' . date_i18n( get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ), strtotime($item->timestamp) ) . '">' . sprintf( '%s 前', human_time_diff( strtotime($item->timestamp), current_time( 'timestamp' ) ) ) . '</time>';
			break;
			case 'title' :
				if($item->msgtype=='image'){
					return '<img src="'.$item->content.'" style="max-height:20px;">';
				}
				return wxlog_emoji(wxlog_qqface($item->content,'img'),'html');
				
			break;
			case 'msgtype' :
				return $item->msgtype;
			break;
			case 'fromusername' :
				return $item->fromusername;
			break;
			case 'user' :
				if ( $item->user_id )
					$user = get_user_by( 'id', $item->user_id );
				if ( ! isset( $user ) || ! $user ) {
					$user_string  = '未知会员';
				} else {
					$user_string  = '<a href="' . admin_url( 'user-edit.php?user_id=' . $user->ID ) . '">';
					$user_string .= $user->user_login . ' &ndash; ';
					$user_string .= '<a href="mailto:' . $user->user_email . '">';
					$user_string .= $user->user_email;
					$user_string .= '</a>';
				}
				return $user_string;
			break;
			case 'user_ip' :
				return '<a href="http://whois.arin.net/rest/ip/' . $item->user_ip . '" target="_blank">' . $item->user_ip . '</a>';
			break;
			case 'user_ua' :
				return $item->user_agent;
			break;
			case 'reply' :
				return '<a href="'.admin_url( 'admin.php?page=wxlog_log&preview='.$item->ID ).'&amp;TB_iframe=true&amp;height=480&amp;width=470" class="thickbox">预览</a>';
			break;
		}
	}


	//列表字段
	public function get_columns(){
		static $cb_counter = 1;
		$columns = array(
			'cb'            => '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . __( 'Select All' ) . '</label>'
				. '<input id="cb-select-all-' . $cb_counter . '" type="checkbox" />',
			'title'  		 => '内容',
			'fromusername'    => '用户',
			'msgtype'    	 => '类型',
			'status'    	 => '状态',
			'timestamp'       => '时间',
			'reply'       => '回复',
		);
		return $columns;
	}
	
	
	function get_sortable_columns() {
		return array(
			'fromusername'    => 'fromusername',
			'content'   => 'content',
			'timestamp'     => array( 'timestamp', true ),
		);
	}	


	//筛选
	public function display_tablenav( $which ) {
		if ( 'top' == $which ) : ?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>" style="position: relative;">
				<div class="alignleft actions">
					<input type="hidden" name="page" value="wxlog_log" />
					<select name="filter_status">
						<option value="">显示所有状态</option>
						<option value="0" <?php selected( $this->filter_status, '0' ); ?>>未回复</option>
						<option value="1" <?php selected( $this->filter_status, '1' ); ?>>处理中</option>
						<option value="2" <?php selected( $this->filter_status, '2' ); ?>>已回复</option>
					</select>
					<?php
						global $wpdb, $wp_locale;
						$months = $wpdb->get_results( "
							SELECT DISTINCT YEAR( timestamp ) AS year, MONTH( timestamp ) AS month
							FROM {$wpdb->wxlog_log}
							WHERE `message` != ''
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
			<?php
					$this->pagination( $which );
			?>
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


	function extra_tablenav( $which ) {?>
		<div class="alignleft actions bulkactions">
			<select name='action'>
			<option value='-1' selected='selected'>批量操作</option>
			<option value='del' class="hide-if-no-js">删除</option>
			</select>
			<input type="submit" name="" id="doaction" class="button action" value="应用"  />
		</div> 
 			<?php 
	}


	function search_form() {global $wpdb;?>
    
        <p class="search-box">
        <label class="screen-reader-text" for="post-search-input">搜索日志:</label>
            <input id="post-search-input" name="k" type="search" value="<?=$_REQUEST['k']?>" list="fruits" />   
            <datalist id="fruits">
                <?php $keyword = $wpdb->get_results("SELECT keyword FROM {$wpdb->wxlog_custom_reply} WHERE status=2 ORDER BY timestamp DESC");
                    //print_r($keyword);
                    foreach($keyword as $value){?>
                        <option value="<?=$value->keyword?>" <?php selected( $_GET['k'], $value->keyword ) ?>><?=$value->keyword?></option>	
                <?php }?>
            </datalist>          
        <input class="button" id="search-submit" type="submit" value="搜索日志"></p>  

	<?php }
	function prepare_items() {
		global $wpdb;

		$per_page      = $this->logs_per_page;
		$current_page  = $this->get_pagenum();
		$filter_status = $this->filter_status;
		$filter_month  = date( "m", strtotime( $this->filter_month ) );
		$filter_year   = date( "Y", strtotime( $this->filter_month ) );

		// Init headers
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		$query_where = " `message` != '' ";

		if ( $this->filter_status !== '' )
			$query_where .= " AND status = '{$filter_status}' ";
			
		if ( $this->keyword == 'subscribe' )
			$query_where .= " AND content = 'subscribe' ";

		if ( $this->keyword == 'unsubscribe' )
			$query_where .= " AND content = 'unsubscribe' ";

		if ( $this->filter_month )
			$query_where .= " AND timestamp >= '" . date( 'Y-m-01', strtotime( $this->filter_month ) ) . "' ";

		if ( $this->filter_month )
			$query_where .= " AND timestamp <= '" . date( 'Y-m-t', strtotime( $this->filter_month ) ) . "' ";

		if ( $_REQUEST['k'] )
				$query_where .= " AND `content` like '%".$_REQUEST['k']."%' ";//echo 'asdfasdf';

		//总数
		$total_items = $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->wxlog_log} WHERE {$query_where};" );

		//列表数据
		/*$this->items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->wxlog_log} WHERE {$query_where} ORDER BY timestamp DESC LIMIT %d, %d;",
				( $current_page - 1 ) * $per_page,
				$per_page
			)
		);*/
		
		$this->items = $wpdb->get_results("SELECT * FROM {$wpdb->wxlog_log} WHERE {$query_where} ORDER BY timestamp DESC LIMIT ".( $current_page - 1 ) * $per_page.",{$per_page}");

		//print_r("SELECT COUNT(ID) FROM {$wpdb->wxlog_log} WHERE {$query_where};");
		//print_r($this->items);
		
		// 分页
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page )
		) );
	}
	
	
}
