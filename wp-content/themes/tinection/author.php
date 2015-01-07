<?php
/**
 * Main Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.2
 * @date      2014.12.28
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com> & Dmeng <http://www.dmeng.net/>
 * @copyright Copyright (c) 2014, Zhiyan & Dmeng
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

global $wp_query;
$curauth = $wp_query->get_queried_object();
$user_name = filter_var($curauth->user_url, FILTER_VALIDATE_URL) ? '<a href="'.$curauth->user_url.'" target="_blank" rel="external">'.$curauth->display_name.'</a>' : $curauth->display_name;
$user_info = get_userdata($curauth->ID);
$posts_count =  $wp_query->found_posts;
$comments_count = get_comments( array('status' => '1', 'user_id'=>$curauth->ID, 'count' => true) );
$collects = $user_info->tin_collect?$user_info->tin_collect:0;
$collects_array = explode(',',$collects);
$collects_count = $collects!=0?count($collects_array):0;
$credit = intval($user_info->tin_credit);
$credit_void = intval($user_info->tin_credit_void);
//$unread = intval(get_tin_message($curauth->ID, 'count', "msg_type='unread' OR msg_type='unrepm'"));

$current_user = wp_get_current_user();

$oneself = $current_user->ID==$curauth->ID || current_user_can('edit_users') ? 1 : 0;

$tabs = array(
		'post' => __('文章','tinection')."($posts_count)",
		'comment' => __('评论','tinection')."($comments_count)",
		'collect' => __('收藏','tinection')."($collects_count)",
		'credit' => __('积分','tinection')."($credit)",
		'message' => __('消息','tinection'),
		'profile' => __('资料','tinection'),
);

foreach( $tabs as $tab_key=>$tab_value ){
	if( $tab_key ) $tab_array[] = $tab_key;
}

$get_tab = isset($_GET['tab']) && in_array($_GET['tab'], $tab_array) ? $_GET['tab'] : 'post';

	// 提示
	$message = $pages = '';
	
	if($get_tab=='profile' && ($current_user->ID!=$curauth->ID && current_user_can('edit_users')) ) $message = sprintf(__('你正在查看的是%s的资料，修改请慎重！', 'tinection'), $curauth->display_name);
	
	// 积分start
	
	if( isset($_POST['creditNonce']) && current_user_can('edit_users') ){
		if ( ! wp_verify_nonce( $_POST['creditNonce'], 'credit-nonce' ) ) {
			$message = __('安全认证失败，请重试！','tinection');
		}else{
			$c_user_id =  $curauth->ID;
			if( isset($_POST['creditChange']) && sanitize_text_field($_POST['creditChange'])=='add' ){
				$c_do = 'add';
				$c_do_title = __('增加','tinection');
			}else{
				$c_do = 'cut';
				$c_do_title = __('减少','tinection');
			}

			$c_num =  intval($_POST['creditNum']);
			$c_desc =  sanitize_text_field($_POST['creditDesc']);
			
			$c_desc = empty($c_desc) ? '' : __('备注','tinection') . ' : '. $c_desc;

			update_tin_credit( $c_user_id , $c_num , $c_do , 'tin_credit' , sprintf(__('%1$s将你的积分%2$s %3$s 分。%4$s','tinection') , $current_user->display_name, $c_do_title, $c_num, $c_desc) );
			
			$message = sprintf(__('操作成功！已将%1$s的积分%2$s %3$s 分。','tinection'), $user_name, $c_do_title, $c_num);
		}
	}
	
	//~ 积分end
	
	//~ 私信start
	$get_pm = isset($_POST['pm']) ? trim($_POST['pm']) : '';
	if( isset($_POST['pmNonce']) && $get_pm && is_user_logged_in() ){
		if ( ! wp_verify_nonce( $_POST['pmNonce'], 'pm-nonce' ) ) {
			$message = __('安全认证失败，请重试！','tinection');
		}else{
			$pm_title = json_encode(array(
				'pm' => $curauth->ID,
				'from' => $current_user->ID
			));
			if( add_tin_message( $curauth->ID, 'unrepm', '', $pm_title, $get_pm ) ) $message = __('发送成功！','tinection');
		}
	}
	
	//~ 私信end

//~ 页码start
$paged = max( 1, get_query_var('page') );
$number = get_option('posts_per_page', 10);
$offset = ($paged-1)*$number;
//~ 页码end

$item_html = '<li class="tip">'.__('没有找到记录','tinection').'</li>';

	//~ 个人资料
if( $oneself ){
	$user_id = $curauth->ID;
	$avatar = $user_info->tin_avatar;
	$qq = tin_is_open_qq();
	$weibo = tin_is_open_weibo();
	if( isset($_POST['update']) && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) {
		$message = __('没有发生变化','tinection');	
		$update = sanitize_text_field($_POST['update']);
		if($update=='info'){
			$update_user_id = wp_update_user( array(
				'ID' => $user_id, 
				'nickname' => sanitize_text_field($_POST['display_name']),
				'display_name' => sanitize_text_field($_POST['display_name']),
				'user_url' => esc_url($_POST['url']),
				'description' => $_POST['description']
			 ) );
			if (($_FILES['file']['error'])==0&&!empty($_FILES['file'])) {
				define( 'AVATARS_PATH', ABSPATH.'/wp-content/uploads/avatars/' );
				$filetype=array("jpg","gif","bmp","jpeg","png");
    			$ext = pathinfo($_FILES['file']['name']);
    			$ext = strtolower($ext['extension']);
    			$tempFile = $_FILES['file']['tmp_name'];
    			$targetPath   = AVATARS_PATH;
    			if( !is_dir($targetPath) ){
        			mkdir($targetPath,0755,true);
    			}
    			$new_file_name = 'avatar-'.randomname(10).'.'.$ext;
    			$targetFile = $targetPath . $new_file_name;
    			if(!in_array($ext, $filetype)){
    				$message = __('仅允许上传JPG、GIF、BMP、PNG图片','tinection');
    			}else{
    				move_uploaded_file($tempFile,$targetFile);
    				if( !file_exists( $targetFile ) ){
	        			$message = __('图片上传失败','tinection');
    				} elseif( !$imginfo=getImageInfo($targetFile) ) {
        				$message = __('图片不存在','tinection');
    				} else {
        				$img = $new_file_name;
        				resize($img);
        				$message = __('头像上传成功','tinection');
        				$update_user_avatar = update_user_meta( $user_id , 'tin_avatar', 'customize');
						$update_user_avatar_img = update_user_meta( $user_id , 'tin_customize_avatar', $img);
   	 				}
   	 			}
			} else {
	    		$update_user_avatar = update_user_meta( $user_id , 'tin_avatar', sanitize_text_field($_POST['avatar']) );
				if ( ! is_wp_error( $update_user_id ) || $update_user_avatar ) $message = __('基本信息已更新','tinection');	
			}
		}
		if($update=='info-more'){
			$update_user_id = wp_update_user( array(
				'ID' => $user_id, 
				'tin_sina_weibo' => $_POST['tin_sina_weibo'],
				'tin_qq_weibo' => $_POST['tin_qq_weibo'],
				'tin_twitter' => $_POST['tin_twitter'],
				'tin_googleplus' => $_POST['tin_googleplus'],
				'tin_weixin' => $_POST['tin_weixin'],
				'tin_donate' => $_POST['tin_donate']
			 ) );
			if ( ! is_wp_error( $update_user_id ) ) $message = __('扩展资料已更新','tinection');
		}	
		if($update=='pass'){
			$data = array();
			$data['ID'] = $user_id;
			$data['user_email'] = sanitize_text_field($_POST['email']);
			if( !empty($_POST['pass1']) && !empty($_POST['pass2']) && $_POST['pass1']===$_POST['pass2'] ) $data['user_pass'] = sanitize_text_field($_POST['pass1']);
			$user_id = wp_update_user( $data );
			if ( ! is_wp_error( $user_id ) ) $message = __('安全信息已更新','tinection');
		}
		
		$message .= ' <a href="'.tin_get_current_page_url().'">'.__('点击刷新','tinection').'</a>';
		
		$user_info = get_userdata($curauth->ID);
	}
}
//~ 个人资料end
	
	
//~ 投稿start

if( isset($_GET['action']) && in_array($_GET['action'], array('new', 'edit')) && $oneself ){
	
	if( isset($_GET['id']) && is_numeric($_GET['id']) && get_post($_GET['id']) && intval(get_post($_GET['id'])->post_author) === get_current_user_id() ){
		$action = 'edit';
		$the_post = get_post($_GET['id']);
		$post_title = $the_post->post_title;
		$post_content = $the_post->post_content;
		foreach((get_the_category($_GET['id'])) as $category) { 
			$post_cat[] = $category->term_id; 
		}
	}else{
		$action = 'new';
		$post_title = !empty($_POST['post_title']) ? $_POST['post_title'] : '';
		$post_content = !empty($_POST['post_content']) ? $_POST['post_content'] : '';
		$post_cat = !empty($_POST['post_cat']) ? $_POST['post_cat'] : array();
	}

	if( isset($_POST['action']) && trim($_POST['action'])=='update' && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) {
		
		$title = sanitize_text_field($_POST['post_title']);
		$content = $_POST['post_content'];
		$cat = (!empty($_POST['post_cat'])) ? $_POST['post_cat'] : '';
		
		if( $title && $content ){
			
			if( mb_strlen($content,'utf8')<140 ){
				
				$message = __('提交失败，文章内容至少140字。','tinection');
				
			}else{
				
				$status = sanitize_text_field($_POST['post_status']);
				
				if( $action==='edit' ){

					$new_post = wp_update_post( array(
						'ID' => intval($_GET['id']),
						'post_title'    => $title,
						'post_content'  => $content,
						'post_status'   => ( $status==='pending' ? 'pending' : 'draft' ),
						'post_author'   => get_current_user_id(),
						'post_category' => $cat
					) );

				}else{

					$new_post = wp_insert_post( array(
						  'post_title'    => $title,
						  'post_content'  => $content,
						  'post_status'   => ( $status==='pending' ? 'pending' : 'draft' ),
						  'post_author'   => get_current_user_id(),
						  'post_category' => $cat
						) );

				}
				
				if( is_wp_error( $new_post ) ){
					$message = __('操作失败，请重试或联系管理员。','tinection');
				}else{
					
					update_post_meta( $new_post, 'tin_copyright_content', htmlspecialchars($_POST['post_copyright']) );
					
					wp_redirect(tin_get_user_url('post'));
				}

			}
		}else{
			$message = __('投稿失败，标题和内容不能为空！','tinection');
		}
	}
}
//~ 投稿end

get_header();

?>
<?php get_template_part( 'includes/breadcrumbs');?>
<!-- Header Banner -->
<?php $headerad=ot_get_option('headerad');if (!empty($headerad)) {?>
<div id="header-banner">
	<div class="container">
		<?php echo ot_get_option('headerad');?>
	</div>
</div>
<?php }?>
<!-- /.Header Banner -->
<!-- Main Wrap -->
<div id="main-wrap">
	<div id="sitenews-wrap" class="container"><?php get_template_part('includes/sitenews'); ?></div>
	<div class="container pagewrapper clr" id="author-page">
		<aside class="pagesidebar">
		<?php $tab_output = '';
			foreach( $tab_array as $tab_term ){
				$class = $get_tab==$tab_term ? ' class="active" ' : '';
				$tab_output .= sprintf('<li%s><a href="%s">%s</a></li>', $class, add_query_arg('tab', $tab_term, esc_url( get_author_posts_url( $curauth->ID ) )), $tabs[$tab_term]);
			}
			echo '<li id="page-sort-menu-btn"><a href="#">'.__('菜单','tinection').'</a></li><ul class="pagesider-menu user-tab">'.$tab_output.'</ul>';
		?>
		</aside>
		<div class="pagecontent">
		<!-- Content -->
		<div class="content">
			<div class="user-basic-info">
				<a class="user-avatar" href="<?php echo esc_url( get_author_posts_url( $curauth->ID ) ) ;?>">
					<?php echo tin_get_avatar( $curauth->ID , '80' , tin_get_avatar_type($curauth->ID) ); ?>
				</a>
				<div class="user-text">
				<h4 class="user-display-name"><?php echo $user_name;?><?php
				if( $current_user->ID && $current_user->ID!=$curauth->ID ) echo '<small class="pm"><a href="'.add_query_arg('tab', 'message', get_author_posts_url( $curauth->ID )).'">'.__('私信TA','tinection').'</a></small>';
				?></h4>
				<p class="user-register-time"><?php
				 echo date( 'Y年m月d日', strtotime( $user_info->user_registered ) ).'<span>'.__('注册','tinection').'</span>';
				 if($current_user&&$current_user->ID==$curauth->ID&&!empty($user_info->tin_latest_ip_before)) {echo date( 'Y年m月d日', strtotime( $user_info->tin_latest_login_before ) ).'<span>'.__('上次登录','tinection').'</span>'.$user_info->tin_latest_ip_before.' '.convertip($user_info->tin_latest_ip_before).'<span>'.'&nbsp;IP&nbsp;'.'</span>';}else{
				 if($user_info->tin_latest_login) echo date( 'Y年m月d日', strtotime( $user_info->tin_latest_login ) ).'<span>'.__('最后登录','tinection').'</span>';}
				 ?></p>
				<p class="user-description"><?php 
						$description = $curauth->description;
						echo $description ? $description : __('没有个人说明','tinection'); ?>
				</p>
			  </div>
			</div>
<?php
if($message) echo '<div class="alert alert-success">'.$message.'</div>'; 
//~ 积分列表start
if( $get_tab=='credit' ) {

	//~ 积分变更
	if ( current_user_can('edit_users') ) {

	?>
				<div class="panel panel-danger">
					<div class="panel-heading"><?php echo $curauth->display_name.__('积分变更（仅管理员可见）','tinection');?></div>
					<div class="panel-body">
	<form id="creditform" role="form"  method="post">
		<input type="hidden" name="creditNonce" value="<?php echo  wp_create_nonce( 'credit-nonce' );?>" >
		<p>
			<label class="radio-inline"><input type="radio" name="creditChange" value="add" aria-required='true' required checked><?php _e('增加积分','tinection');?></label>
			<label class="radio-inline"><input type="radio" name="creditChange" value="cut" aria-required='true' required><?php _e('减少积分','tinection');?></label>
		</p>
		<div class="form-inline">
		  <div class="form-group">
			<div class="input-group">
			  <div class="input-group-addon"><?php _e('积分','tinection');?></div>
			  <input class="form-control" type="text" name="creditNum" aria-required='true' required>
			</div>
		  </div>
		  <div class="form-group">
			<div class="input-group">
			  <div class="input-group-addon"><?php _e('备注','tinection');?></div>
			  <input class="form-control" type="text" name="creditDesc" aria-required='true' required>
			</div>
		  </div>
		   <button class="btn btn-default" type="submit" style="margin-bottom:20px;"><?php _e('提交','tinection');?></button>
		</div>
		<p class="help-block"><?php _e('请谨慎操作！积分数只能填写数字，备注将显示在用户的积分记录中。','tinection');?></p>
	</form>
				  </div>
				</div>

	<?php
	} //~ if ( current_user_can('edit_users') ) 

	$item_html = '<li class="tip">' . sprintf(__('共有 %1$s 个积分，其中 %2$s 个已消费， %3$s 个可用。','tinection'), ($credit+$credit_void), $credit_void, $credit) . '</li>';

	if($oneself){
		$all = get_tin_message($curauth->ID, 'count', "msg_type='credit'");
		$pages = ceil($all/$number);
		
		$creditLog = get_tin_credit_message($curauth->ID, $number,$offset);

		if($creditLog){
			foreach( $creditLog as $log ){
				$item_html .= '<li>'.$log->msg_date.' <span class="message-content" style="background:transparent;">'.$log->msg_title.'</span></li>';
			}
			if($pages>1) $item_html .= '<li class="tip">' . sprintf(__('第 %1$s 页，共 %2$s 页，每页显示 %3$s 条。','tinection'),$paged, $pages, $number). '</li>';
		}
	}
	
	echo '<ul class="user-message">'.$item_html.'</ul>';
	
	if($oneself) echo tin_pager($paged, $pages);

	?>
    <table class="table table-bordered credit-table">
      <thead>
        <tr class="active">
          <th><?php _e('积分方法','tinection');?></th>
          <th><?php _e('一次得分','tinection');?></th>
          <th><?php _e('可用次数','tinection');?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php _e('注册奖励','tinection');?></td>
          <td><?php printf( __('%1$s 分','tinection'), get_option('tin_reg_credit','50'));?></td>
          <td><?php _e('只有 1 次','tinection');?></td>
        </tr>
        <tr>
          <td><?php _e('文章投稿','tinection');?></td>
          <td><?php printf( __('%1$s 分','tinection'), get_option('tin_rec_post_credit','50'));?></td>
          <td><?php printf( __('每天 %1$s 次','tinection'), get_option('tin_rec_post_num','5'));?></td>
        </tr>
        <tr>
          <td><?php _e('评论回复','tinection');?></td>
          <td><?php printf( __('%1$s 分','tinection'), get_option('tin_rec_comment_credit','5'));?></td>
          <td><?php printf( __('每天 %1$s 次','tinection'), get_option('tin_rec_comment_num','50'));?></td>
        </tr>
        <tr>
          <td><?php _e('访问推广','tinection');?></td>
          <td><?php printf( __('%1$s 分','tinection'), get_option('tin_rec_view_credit','5'));?></td>
          <td><?php printf( __('每天 %1$s 次','tinection'), get_option('tin_rec_view_num','50'));?></td>
        </tr>
        <tr>
          <td><?php _e('注册推广','tinection');?></td>
          <td><?php printf( __('%1$s 分','tinection'), get_option('tin_rec_reg_credit','50'));?></td>
          <td><?php printf( __('每天 %1$s 次','tinection'), get_option('tin_rec_reg_num','5'));?></td>
        </tr>
      </tbody>
    </table>
	<?php

} 
//~ 积分列表end

//~ 默认文章列表start
if( $get_tab=='post' ) {
	$can_post_cat = ot_get_option('tin_can_post_cat')?ot_get_option('tin_can_post_cat'):0;
	$cat_count = $can_post_cat!=0?count($can_post_cat):0;
	if( isset($_GET['action']) && in_array($_GET['action'], array('new', 'edit')) && $cat_count && is_user_logged_in() && $oneself && current_user_can('edit_posts') ){
		echo '<ul class="user-message"><li class="tip">'.__('请发表你自己的文章','tinection').'</ul></li>';
		wp_enqueue_script('my_quicktags',get_stylesheet_directory_uri().'/includes/js/my_quicktags.js',array('quicktags'));
?>
			<article class="panel panel-default archive" role="main">
					<div class="panel-body">
						<h3 class="page-header"><?php _e('投稿','tinection');?> <small><?php _e('POST NEW','tinection');?></small></h3>
						<form role="form" method="post">
							<div class="form-group">
								<input type="text" class="form-control" name="post_title" placeholder="<?php _e('在此输入标题','tinection');?>" value="<?php echo $post_title;?>" aria-required='true' required>
							</div>
							<div class="form-group">
								<?php wp_editor(  wpautop($post_content), 'post_content', array('media_buttons'=>false, 'quicktags'=>true, 'editor_class'=>'form-control', 'editor_css'=>'<style>.wp-editor-container{border:1px solid #ddd;}.switch-html, .switch-tmce{height:25px !important}</style>' ) ); ?>
							</div>
							<div class="form-group">
						<?php
							$can_post_cat = ot_get_option('tin_can_post_cat');
							if($can_post_cat){
								$post_cat_output = '<p class="help-block">'.__('选择文章分类', 'tinection').'</p>';
								$post_cat_output .= '<select name="post_cat[]" class="form-control">';
								foreach ( $can_post_cat as $term_id ) {
									$category = get_category( $term_id );
									//~ if( (!empty($post_cat)) && in_array($category->term_id,$post_cat)) 
									$post_cat_output .= '<option value="'.$category->term_id.'">'. $category->name.'</option>';
								}
								$post_cat_output .= '</select>';
								echo $post_cat_output;
							}
						?>
							</div>
							<div class="form-group">
								<p class="help-block"><?php _e('文章来源或版权信息，{link}代表文章链接，{title}代表文章标题，{url}代表本站首页地址，{name}代表本站名称', 'tinection');?></p>
								<?php
								$cc = '';
								if(isset($_GET['id'])) $cc = get_post_meta( intval($_GET['id']), 'tin_copyright_content', true );
								$copyright_content = $cc ? $cc : get_option('tin_copyright_content_default',sprintf(__('<p>原文链接：%s，转发请注明来源！</p>','tinection'),'<a href="{link}" rel="author">{title}</a>'));
								?>
								<textarea name="post_copyright" rows="1" cols="50" class="form-control"><?php echo stripcslashes(htmlspecialchars_decode($copyright_content));?></textarea>
							</div>
							<div class="form-group text-right">
								<select name="post_status">
									<option value ="pending"><?php _e('提交审核','tinection');?></option>
									<option value ="draft"><?php _e('保存草稿','tinection');?></option>
								</select>
								<input type="hidden" name="action" value="update">
								<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
								<button type="submit" class="btn btn-success"><?php _e('确认操作','tinection');?></button>
							</div>	
						</form>
					</div>
			 </article>
<?php
		
	}else{

		if($cat_count){

			$item_html = sprintf( __('现有%s个分类接受投稿。', 'tinection'), $cat_count );
			
			if( is_user_logged_in() && !current_user_can('edit_posts') ){
					
					$item_html .= __('遗憾的是，你现在登录的账号没有投稿权限！', 'tinection');
					
				}else{
					
					$item_html .= '<a href="'.( is_user_logged_in() ? add_query_arg(array('tab'=>'post','action'=>'new'), get_author_posts_url($current_user->ID)) : wp_login_url() ).'">'.__('点击投稿', 'tinection').'</a>';
			}
		}else{

			if( have_posts() ) $item_html = sprintf( __('发表了 %s 篇文章', 'tinection'), $posts_count );
		}
		
		echo '<ul class="user-message"><li class="tip">'.$item_html.'</ul></li>';
	
	global $wp_query;
	$args = is_user_logged_in() ? array_merge( $wp_query->query_vars, array( 'post_status' => array( 'publish', 'pending', 'draft' ) ) ) : $wp_query->query_vars;
	query_posts( $args );
	
		while ( have_posts() ) : the_post();
			get_template_part('includes/content','archive');
		endwhile; // end of the loop. 
		tin_paginate();

	wp_reset_query();
	}
}
//~ 默认文章列表end

//~ 评论start
if( $get_tab=='comment' ) {

	$comments_status = $oneself ? '' : 'approve';
	
	$all = get_comments( array('status' => '', 'user_id'=>$curauth->ID, 'count' => true) );
	$approve = get_comments( array('status' => '1', 'user_id'=>$curauth->ID, 'count' => true) );
	
	$pages = $oneself ? ceil($all/$number) : ceil($approve/$number);

	$comments = get_comments(array(
		'status' => $comments_status,
		'order' => 'DESC',
		'number' => $number,
		'offset' => $offset,
		'user_id' => $curauth->ID
	));

	if($comments){
		$item_html = '<li class="tip">' . sprintf(__('共有 %1$s 条评论，其中 %2$s 条已获准， %3$s 条正等待审核。','tinection'),$all, $approve, $all-$approve) . '</li>';
		foreach( $comments as $comment ){
			$item_html .= ' <li>';
			if($comment->comment_approved!=1) $item_html .= '<small class="text-danger">'.__( '这条评论正在等待审核','tinection' ).'</small>';
			$item_html .= '<div class="message-content">'.$comment->comment_content . '</div>';
			$item_html .= '<a class="info" href="'.htmlspecialchars( get_comment_link( $comment->comment_ID) ).'">'.sprintf(__('%1$s  发表在  %2$s','tinection'),$comment->comment_date,get_the_title($comment->comment_post_ID)).'</a>';
			$item_html .= '</li>';
		}
		if($pages>1) $item_html .= '<li class="tip">'.sprintf(__('第 %1$s 页，共 %2$s 页，每页显示 %3$s 条。','tinection'),$paged, $pages, $number).'</li>';
	}

	echo '<ul class="user-message">'.$item_html.'</ul>';
	echo tin_pager($paged, $pages);
}
//~ 评论end

// 收藏start
if( $get_tab=='collect'){
	$item_html = '<li class="tip">'.__('共收藏了','tinection').$collects_count.'篇文章</li>';
	echo '<ul class="user-message"><li class="tip">'.$item_html.'</ul></li>';
	//global $wp_query;
	//$args = array_merge( $wp_query->query_vars, array( 'post__in' => $collects_array, 'post_status' => 'publish' ) );
	query_posts( array( 'post__not_in'=>get_option('sticky_posts'), 'post__in' => $collects_array, 'post_status' => 'publish' ) );
		while ( have_posts() ) : the_post();
			get_template_part('includes/content','archive');
		endwhile; // end of the loop. 
		tin_paginate();
	wp_reset_query();
}
// 收藏end

//~ 消息start
if( $get_tab=='message' ) {

	if($current_user->ID==$curauth->ID){
		$all_sql = "( msg_type='read' OR msg_type='unread' OR msg_type='repm' OR msg_type='unrepm' )";

		$all = get_tin_message($curauth->ID, 'count', $all_sql);
		
		$pages = ceil($all/$number);
		

		$mLog = get_tin_message($curauth->ID, '', $all_sql, $number,$offset);

		$unread = intval(get_tin_message($curauth->ID, 'count', "msg_type='unread' OR msg_type='unrepm'"));
		
		if($mLog){
			$item_html = '<li class="tip">' . sprintf(__('共有 %1$s 条消息，其中 %2$s 条是新消息（绿色标注）。','tinection'), $all, $unread) . '</li>';
			foreach( $mLog as $log ){
				$unread_tip = $unread_class = '';
				if(in_array($log->msg_type, array('unread', 'unrepm'))){
					$unread_tip = '<span class="tag">'.__('新！', 'tinection').'</span>';
					$unread_class = ' class="unread"';
					update_tin_message_type( $log->msg_id, $curauth->ID , ltrim($log->msg_type, 'un') );
				}
				$msg_title =  $log->msg_title;
				if(in_array($log->msg_type, array('repm', 'unrepm'))){
					$msg_title_data = json_decode($log->msg_title);
					$msg_title = get_the_author_meta('display_name', intval($msg_title_data->from));
					$msg_title = sprintf(__('%s发来的私信','tinection'), $msg_title).' <a href="'.add_query_arg('tab', 'message', get_author_posts_url(intval($msg_title_data->from))).'#'.$log->msg_id.'">'.__('查看对话','tinection').'</a>';
				}
				$item_html .= '<li'.$unread_class.'><div class="message-content">'.htmlspecialchars_decode($log->msg_content).' </div><p class="info">'.$unread_tip.'  '.$msg_title.'  '.$log->msg_date.'</p></li>';
			}
			if($pages>1) $item_html .= '<li class="tip">'.sprintf(__('第 %1$s 页，共 %2$s 页，每页显示 %3$s 条。','tinection'),$paged, $pages, $number).'</li>';
		}
		
	}else{
		
		if( is_user_logged_in() ){
			
			$item_html = '<li class="tip">'.sprintf(__('与 %s 对话','tinection'), $user_info->display_name).'</li><li><form id="pmform" role="form" method="post"><input type="hidden" name="pmNonce" value="'.wp_create_nonce( 'pm-nonce' ).'" ><p><textarea class="form-control" rows="3" name="pm" required></textarea></p><p class="clearfix"><a class="btn btn-link pull-left" href="'.add_query_arg('tab', 'message', get_author_posts_url($current_user->ID)).'">'.__('查看我的消息','tinection').'</a><button type="submit" class="btn btn-primary pull-right">'.__('确定发送','tinection').'</button></p></form></li>';
			
			$all = get_tin_pm( $curauth->ID, $current_user->ID, true );
			$pages = ceil($all/$number);
			
			$pmLog = get_tin_pm( $curauth->ID, $current_user->ID, false, false, $number, $offset );
			if($pmLog){
				foreach( $pmLog as $log ){
					$pm_data = json_decode($log->msg_title);
					if( $pm_data->from==$curauth->ID ){
						update_tin_message_type( $log->msg_id, $curauth->ID , 'repm' );
					}
					$item_html .= '<li id="'.$log->msg_id.'"><div class="message-content clearfix"><a class="'.( $pm_data->from==$current_user->ID ? 'pull-right' : 'pull-left' ).'" href="'.get_author_posts_url($pm_data->from).'">'.tin_get_avatar( $pm_data->from , '34' , tin_get_avatar_type($pm_data->from), false ).'</a><div class="pm-box"><div class="pm-content'.( $pm_data->from==$current_user->ID ? '' : ' highlight' ).'">'.htmlspecialchars_decode($log->msg_content).'</div><p class="pm-date">'.date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), strtotime($log->msg_date)).'</p></div></div></li>';
				}
			}
			
			if($pages>1) $item_html .= '<li class="tip">'.sprintf(__('第 %1$s 页，共 %2$s 页，每页显示 %3$s 条。','tinection'),$paged, $pages, $number).'</li>';

		}else{
			$item_html = '<li class="tip">'.sprintf(__('私信功能需要<a href="%s">登录</a>才可使用！','tinection'), wp_login_url() ).'</li>';
		}
	}
	
	echo '<ul class="user-message">'.$item_html.'</ul>'.tin_pager($paged, $pages);

}
//~ 消息end

//~ 资料start
if( $get_tab=='profile' ) {

		$avatar_type = array(
			'default' => __('默认头像', 'tinection'),
			'qq' => __('腾讯QQ头像', 'tinection'),
			'weibo' => __('新浪微博头像', 'tinection'),
			'customize' => __('自定义头像', 'tinection'),
		);
		
		$author_profile = array(
			__('头像来源','tinection') => $avatar_type[tin_get_avatar_type($user_info->ID)],
			__('用户名','tinection') => $user_info->user_login,
			__('昵称','tinection') => $user_info->display_name,
			__('站点','tinection') => $user_info->user_url,
			__('个人说明','tinection') => $user_info->description
		);
		
		$profile_output = '';
		foreach( $author_profile as $pro_name=>$pro_content ){
			$profile_output .= '<tr><td class="title">'.$pro_name.'</td><td>'.$pro_content.'</td></tr>';
		}
		
		$days_num = round(( strtotime(date('Y-m-d')) - strtotime( $user_info->user_registered ) ) /3600/24);
		
		echo '<ul class="user-message"><li class="tip">'.sprintf(__('%s来%s已经%s天了', 'tinection') , $user_info->display_name, get_bloginfo('name'), ( $days_num>1 ? $days_num : 1 ) ).'</li></ul>'.'<table id="author-profile"><tbody>'.$profile_output.'</tbody></table>';
		
	if( $oneself ){
		
	?>

<form id="info-form" class="form-horizontal" role="form" method="POST" action="">
	<input type="hidden" name="update" value="info">
	<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
			<div class="page-header">
				<h3 id="info"><?php _e('基本信息','tinection');?> <small><?php _e('公开资料','tinection');?></small></h>
			</div>

	<div class="form-group">
		<label class="col-sm-3 control-label"><?php _e('头像','tinection');?></label>
		<div class="col-sm-9">

<div class="radio">
<?php echo tin_get_avatar( $user_info->ID , '40' , tin_get_avatar_type($user_info->ID) ); ?>
  <label>
	<input type="radio" name="avatar"  value="default" <?php if( ($avatar!='qq' || tin_is_open_qq($user_info->ID)===false) && ($avatar!='weibo' || tin_is_open_weibo($user_info->ID)===false) ) echo 'checked';?>><?php _e('默认头像','tinection'); ?>
  </label>
  <label id="edit-avatar">(上传头像)</label>
</div>

<div id="upload-input">    
    <input name="file" type="file"  value="浏览" >              
    <span id="upload-avatar">上传</span>   
</div>
<p id="upload-avatar-msg"></p>

<?php if(tin_is_open_qq($user_info->ID)){ ?>
<div class="radio">
<?php echo tin_get_avatar( $user_info->ID , '40' , 'qq' ); ?>
  <label>
    <input type="radio" name="avatar" value="qq" <?php if($avatar=='qq') echo 'checked';?>> <?php _e('QQ头像', 'tinection');?>
  </label>
</div>
<?php } ?>

<?php if(tin_is_open_weibo($user_info->ID)){ ?>
<div class="radio">
<?php echo tin_get_avatar( $user_info->ID , '40' , 'weibo' ); ?>
  <label>
    <input type="radio" name="avatar" value="weibo" <?php if($avatar=='weibo') echo 'checked';?>> <?php _e('微博头像', 'tinection');?>
  </label>
</div>
<?php } ?>

		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label"><?php _e('用户名','tinection');?></label>
		<div class="col-sm-9">
			<p class="form-control-static"><?php echo $user_info->user_login;?></p>
		</div>
	</div>
	
	<div class="form-group">
		<label for="display_name" class="col-sm-3 control-label"><?php _e('昵称','tinection');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="display_name" name="display_name" value="<?php echo $user_info->display_name;?>">
		</div>
	</div>

	<div class="form-group">
		<label for="url" class="col-sm-3 control-label"><?php _e('站点','tinection');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="url" name="url" value="<?php echo $user_info->user_url;?>">
		</div>
	</div>
	
	<div class="form-group">
		<label for="description" class="col-sm-3 control-label"><?php _e('个人说明','tinection');?></label>
		<div class="col-sm-9">
			<textarea class="form-control" rows="3" name="description" id="description"><?php echo $user_info->description;?></textarea>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" class="btn btn-primary"><?php _e('保存更改','tinection');?></button>
		</div>
	</div>
	
</form>

<form id="info-more-form" class="form-horizontal" role="form" method="post">
	<input type="hidden" name="update" value="info-more">
	<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
			<div class="page-header">
				<h3 id="info"><?php _e('扩展资料','tin');?> <small><?php _e('社会化信息等','tin');?></small></h>
			</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><?php _e('新浪微博','tin');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="tin_sina_weibo" name="tin_sina_weibo" value="<?php echo $user_info->tin_sina_weibo;?>">
			<span class="help-block"><?php _e('请填写新浪微博账号','tin');?></span>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label"><?php _e('腾讯微博','tin');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="tin_qq_weibo" name="tin_qq_weibo" value="<?php echo $user_info->tin_qq_weibo;?>">
			<span class="help-block"><?php _e('请填写腾讯微博账号','tin');?></span>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label"><?php _e('Twitter','tin');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="tin_twitter" name="tin_twitter" value="<?php echo $user_info->tin_twitter;?>">
			<span class="help-block"><?php _e('请填写Twitter账号','tin');?></span>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label"><?php _e('Goolge +','tin');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="tin_googleplus" name="tin_googleplus" value="<?php echo $user_info->tin_googleplus;?>">
			<span class="help-block"><?php _e('请填写Google+主页的完整Url','tin');?></span>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label"><?php _e('微信二维码','tin');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="tin_weixin" name="tin_weixin" value="<?php echo $user_info->tin_weixin;?>">
			<span class="help-block"><?php _e('请填写微信账号二维码图片的Url地址','tin');?></span>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label"><?php _e('支付宝收款二维码','tin');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="tin_donate" name="tin_donate" value="<?php echo $user_info->tin_donate;?>">
			<span class="help-block"><?php _e('请填写支付宝收款二维码图片的Url地址','tin');?></span>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" class="btn btn-primary"><?php _e('提交资料','tin');?></button>
		</div>
	</div>
	
</form>


<?php if($current_user&&$current_user->ID==$curauth->ID) { ?>
<form id="aff-form" class="form-horizontal" role="form">
	<div class="page-header">
		<h3 id="open"><?php _e('推广链接','tin');?> <small><?php _e('可赚取积分','tin');?></small></h>
	</div>
	<div class="form-group">
		<label for="aff" class="col-sm-3 control-label"><?php _e('推广链接','tin');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control tin_aff_url" value="<?php echo get_bloginfo('home').'/?aff='.$current_user->ID; ?>">
		</div>
	</div>
</form>
<?php } ?>
<?php if( $qq || $weibo ) { ?>
<form id="open-form" class="form-horizontal" role="form" method="post">
			<div class="page-header">
				<h3 id="open"><?php _e('绑定账号','tin');?> <small><?php _e('可用于直接登录','tin');?></small></h>
			</div>
			
	<?php if($qq){ ?>
		<div class="form-group">
			<label class="col-sm-3 control-label"><?php _e('QQ账号','tin');?></label>
			<div class="col-sm-9">
		<?php  if(tin_is_open_qq($user_info->ID)) { ?>
			<span class="help-block"><?php _e('已绑定','tin');?> <a href="<?php echo home_url('/?connect=qq&action=logout'); ?>"><?php _e('点击解绑','tin');?></a></span>
			<?php echo tin_get_avatar( $user_info->ID , '100' , 'qq' ); ?>
		<?php }else{ ?>
			<a class="btn btn-primary" href="<?php echo home_url('/?connect=qq&action=login&redirect='.urlencode(get_edit_profile_url())); ?>"><?php _e('绑定QQ账号','tin');?></a>
		<?php } ?>
			</div>
		</div>
	<?php } ?>

	<?php if($weibo){ ?>
		<div class="form-group">
			<label class="col-sm-3 control-label"><?php _e('微博账号','tin');?></label>
			<div class="col-sm-9">
		<?php if(tin_is_open_weibo($user_info->ID)) { ?>
			<span class="help-block"><?php _e('已绑定','tin');?> <a href="<?php echo home_url('/?connect=weibo&action=logout'); ?>"><?php _e('点击解绑','tin');?></a></span>
			<?php echo tin_get_avatar( $user_info->ID , '100' , 'weibo' ); ?>
		<?php }else{ ?>
			<a class="btn btn-danger" href="<?php echo home_url('/?connect=weibo&action=login&redirect='.urlencode(get_edit_profile_url())); ?>"><?php _e('绑定微博账号','tin');?></a>
		<?php } ?>
			</div>
		</div>
	<?php } ?>
</form>
<?php } ?>
<form id="pass-form" class="form-horizontal" role="form" method="post">
	<input type="hidden" name="update" value="pass">
	<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
			<div class="page-header">
				<h3 id="pass"><?php _e('账号安全','tin');?> <small><?php _e('仅自己可见','tin');?></small></h>
			</div>
	<div class="form-group">
		<label for="email" class="col-sm-3 control-label"><?php _e('电子邮件 (必填)','tin');?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="email" name="email" value="<?php echo $user_info->user_email;?>" aria-required='true' required>
		</div>
	</div>
	<div class="form-group">
		<label for="pass1" class="col-sm-3 control-label"><?php _e('新密码','tin');?></label>
		<div class="col-sm-9">
			<input type="password" class="form-control" id="pass1" name="pass1" >
			<span class="help-block"><?php _e('如果您想修改您的密码，请在此输入新密码。不然请留空。','tin');?></span>
		</div>
	</div>
	<div class="form-group">
		<label for="pass2" class="col-sm-3 control-label"><?php _e('重复新密码','tin');?></label>
		<div class="col-sm-9">
			<input type="password" class="form-control" id="pass2" name="pass2" >
			<span class="help-block"><?php _e('再输入一遍新密码。 提示：您的密码最好至少包含7个字符。为了保证密码强度，使用大小写字母、数字和符号（例如! " ? $ % ^ & )）。','tin');?></span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" class="btn btn-primary"><?php _e('保存更改','tin');?></button>
		</div>
	</div>
</form>
	<?php
	}
} 
//~ 资料end

			?>
		 </div>
		<!-- /.Content -->
		</div>
	</div>
</div>
<!--/.Main Wrap -->
<!-- Bottom Banner -->
<?php $bottomad=ot_get_option('bottomad');if (!empty($bottomad)) {?>
<div id="bottom-banner">
	<div class="container">
		<?php echo ot_get_option('bottomad');?>
	</div>
</div>
<?php }else{?>
<div style="height:50px;"></div>
<?php }?>
<!-- /.Bottom Banner -->
<?php if(ot_get_option('footer-widgets-singlerow') == 'on'){?>
<div id="footer-widgets-singlerow">
	<div class="container">
	<?php dynamic_sidebar( 'footer-row'); ?>
	</div>
</div>
<?php }?>
<?php get_footer(); ?>
