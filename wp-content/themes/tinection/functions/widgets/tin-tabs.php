<?php
/*
	Tinection Widget
	
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	
	Copyright: (c) 2014 知言博客 - http://www.zhiyanblog.com
	
		@package Tinection
		@version 1.1.2
*/

class TinTabs extends WP_Widget {

/*  Constructor
/* ------------------------------------ */
	function TinTabs() {
		parent::__construct( false, 'Tin-选项卡列表', array('description' => 'Tinection-以选项卡式显示文章、评论等内容', 'classname' => 'widget_tin_tabs') );;	
	}

/*  Create tabs-nav
/* ------------------------------------ */
	private function _create_tabs($tabs,$count) {
		// Borrowed from Jermaine Maree, thanks mate!
		$titles = array(
			'recent'	=> __('近期文章','tinection'),
			'popular'	=> __('热门文章','tinection'),
			'comments'	=> __('最新评论','tinection'),
		);
		$icons = array(
			'recent'   => 'fa fa-clock-o',
			'popular'  => 'fa fa-star',
			'comments' => 'fa fa-comments-o',
		);
		$output = sprintf('<ul class="tin-tabs-nav group tab-count-%s">', $count);
		foreach ( $tabs as $tab ) {
			$output .= sprintf('<li class="tin-tab tab-%1$s"><a href="#tab-%2$s" title="%4$s"><i class="%3$s"></i><span>%4$s</span></a></li>',$tab, $tab, $icons[$tab], $titles[$tab]);
		}
		$output .= '</ul>';
		return $output;
	}
	
/*  Widget
/* ------------------------------------ */
	public function widget($args, $instance) {
		extract( $args );
		$instance['title']?NULL:$instance['title']='';
		$title = apply_filters('widget_title',$instance['title']);
		$output = $before_widget."\n";
		if($title)
			$output .= $before_title.$title.$after_title;
		ob_start();
		
/*  Set tabs-nav order & output it
/* ------------------------------------ */
	$tabs = array();
	$count = 0;
	$order = array(
		'recent'	=> $instance['order_recent'],
		'popular'	=> $instance['order_popular'],
		'comments'	=> $instance['order_comments'],
	);
	asort($order);
	foreach ( $order as $key => $value ) {
		if ( $instance[$key.'_enable'] ) {
			$tabs[] = $key;
			$count++;
		}
	}
	if ( $tabs && ($count > 1) ) { $output .= $this->_create_tabs($tabs,$count); }
?>
	<div class="tin-tabs-container">
		<?php if($instance['recent_enable']) { // Recent posts enabled? ?>
			<?php $recent=new WP_Query(); ?>
			<?php $recent->query('showposts='.$instance["recent_num"].'&cat='.$instance["recent_cat_id"].'&ignore_sticky_posts=1');?>
			<ul id="tab-recent" class="tin-tab group <?php if($instance['recent_thumbs']) { echo 'thumbs-enabled'; }else {echo 'no-pic';} ?>">
				<?php while ($recent->have_posts()): $recent->the_post(); ?>
				<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); ?>
				<li>
					<?php if($instance['recent_thumbs']) { // Thumbnails enabled? ?>
					<div class="tab-item-thumbnail">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php if ( has_post_thumbnail() ): ?>
							<div class="thumb-img">
								<img src="<?php echo tin_thumb_source($large_image_url[0],125,78,false); ?>" alt="<?php the_title(); ?>" />
								<span><?php the_article_icon();?></span>
							</div>								
							<?php else: ?>
							<div class="thumb-img">
								<img src="<?php $img = catch_first_image();echo tin_thumb_source($img,125,78,false); ?>" alt="<?php the_title(); ?>" />
								<span><?php the_article_icon();?></span>
							</div>
							<?php endif; ?>
						</a>
					</div>
					<?php } ?>
					
					<div class="tab-item-inner group">
						<p class="tab-item-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></p>
						<?php if($instance['tabs_date']) { ?><span class="tab-item-date"><?php the_time('Y-m-j'); ?></span><?php } ?>&nbsp;
						<?php if($instance['tabs_category']) { ?><span class="tab-item-category"><?php the_category(' / '); ?></span><?php } ?>
					</div>
					
				</li>
				<?php endwhile; ?>
			</ul><!--/.tin-tab-->

		<?php } ?>

		<?php if($instance['popular_enable']) { // Popular posts enabled? ?>
				
			<?php
				$popular = new WP_Query( array(
					'post_type'				=> array( 'post' ),
					'showposts'				=> $instance['popular_num'],
					'cat'					=> $instance['popular_cat_id'],
					'ignore_sticky_posts'	=> true,
					'orderby'				=> 'comment_count',
					'order'					=> 'dsc',
					'date_query' => array(
						array(
							'after' => $instance['popular_time'],
						),
					),
				) );
			?>
			<ul id="tab-popular" class="tin-tab group <?php if($instance['popular_thumbs']) { echo 'thumbs-enabled'; } else {echo 'no-pic';} ?>">
				
				<?php while ( $popular->have_posts() ): $popular->the_post(); ?>
				<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); ?>
				<li>
				
					<?php if($instance['popular_thumbs']) { // Thumbnails enabled? ?>
					<div class="tab-item-thumbnail">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php if ( has_post_thumbnail() ): ?>
							<div class="thumb-img">
								<img src="<?php echo tin_thumb_source($large_image_url[0],125,78,false); ?>" alt="<?php the_title(); ?>" />
								<span><?php the_article_icon();?></span>
							</div>
							<?php else: ?>
							<div class="thumb-img">
								<img src="<?php $img = catch_first_image();echo tin_thumb_source($img,125,78,false); ?>" alt="<?php the_title(); ?>" />
								<span><?php the_article_icon();?></span>
							</div>
							<?php endif; ?>
						</a>
					</div>
					<?php } ?>
					
					<div class="tab-item-inner group">
						<p class="tab-item-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></p>
						<?php if($instance['tabs_date']) { ?><span class="tab-item-date"><?php the_time('Y-m-j'); ?></span><?php } ?>&nbsp;
						<?php if($instance['tabs_category']) { ?><span class="tab-item-category"><?php the_category(' / '); ?></span><?php } ?>
					</div>
					
				</li>
				<?php endwhile; ?>
			</ul><!--/.tin-tab-->
			
		<?php } ?>
		<?php if($instance['comments_enable']) { // Recent comments enabled? ?>

			<?php $comments = get_comments(array('number'=>$instance["comments_num"],'status'=>'approve','post_status'=>'publish')); ?>
			
			<ul id="tab-comments" class="tin-tab group <?php if($instance['comments_avatars']) { echo 'avatars-enabled'; } else {echo 'no-avatar';} ?>">
				<?php foreach ($comments as $comment): ?>
				<li>
					
						<?php if($instance['comments_avatars']) { // Avatars enabled? ?>
						<div class="tab-item-avatar">
							<?php echo tin_get_avatar( $comment->user_id , '96' , tin_get_avatar_type($comment->user_id) ); ?>
						</div>
						<?php } ?>
						
						<div class="tab-item-inner group">
							<?php 
							$comment_excerpt = preg_replace("'\[private](.*?)\[\/private]'",'***该评论仅父级评论者及管理员可见***',get_comment_excerpt($comment->comment_ID));
							$str=explode(' ',$comment_excerpt); $comment_excerpt=implode(' ',array_slice($str,0,11)); if(count($str) > 11 && substr($comment_excerpt,-1)!='.') $comment_excerpt.='...' ?>					
							<div class="tab-item-comment"><span class="arrow-poptop"></span><a href="<?php echo esc_url(get_permalink($comment->comment_post_ID)); ?>"><i><?php echo $comment->comment_author; ?></i><?php _e('说: ','tinection'); ?><?php echo $comment_excerpt; ?></a></div>
							
						</div>

				</li>
				<?php endforeach; ?>
			</ul><!--/.tin-tab-->

		<?php } ?>
	</div>

<?php
		$output .= ob_get_clean();
		$output .= $after_widget."\n";
		echo $output;
	}
	
/*  Widget update
/* ------------------------------------ */
	public function update($new,$old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		$instance['tabs_category'] = $new['tabs_category']?1:0;
		$instance['tabs_date'] = $new['tabs_date']?1:0;
	// Recent posts
		$instance['recent_enable'] = $new['recent_enable']?1:0;
		$instance['recent_thumbs'] = $new['recent_thumbs']?1:0;
		$instance['recent_cat_id'] = strip_tags($new['recent_cat_id']);
		$instance['recent_num'] = strip_tags($new['recent_num']);
	// Popular posts
		$instance['popular_enable'] = $new['popular_enable']?1:0;
		$instance['popular_thumbs'] = $new['popular_thumbs']?1:0;
		$instance['popular_cat_id'] = strip_tags($new['popular_cat_id']);
		$instance['popular_time'] = strip_tags($new['popular_time']);
		$instance['popular_num'] = strip_tags($new['popular_num']);
	// Recent comments
		$instance['comments_enable'] = $new['comments_enable']?1:0;
		$instance['comments_avatars'] = $new['comments_avatars']?1:0;
		$instance['comments_num'] = strip_tags($new['comments_num']);
	// Order
		$instance['order_recent'] = strip_tags($new['order_recent']);
		$instance['order_popular'] = strip_tags($new['order_popular']);
		$instance['order_comments'] = strip_tags($new['order_comments']);
		$instance['order_tags'] = strip_tags($new['order_tags']);
		return $instance;
	}

/*  Widget form
/* ------------------------------------ */
	public function form($instance) {
		// Default widget settings
		$defaults = array(
			'title' 			=> '',
			'tabs_category' 	=> 1,
			'tabs_date' 		=> 1,
		// Recent posts
			'recent_enable' 	=> 1,
			'recent_thumbs' 	=> 1,
			'recent_cat_id' 	=> '0',
			'recent_num' 		=> '5',
		// Popular posts
			'popular_enable' 	=> 1,
			'popular_thumbs' 	=> 1,
			'popular_cat_id' 	=> '0',
			'popular_time' 		=> '0',
			'popular_num' 		=> '5',
		// Recent comments
			'comments_enable' 	=> 1,
			'comments_avatars' 	=> 1,
			'comments_num' 		=> '5',
		// Order
			'order_recent' 		=> '1',
			'order_popular' 	=> '2',
			'order_comments' 	=> '3',
			'order_tags' 		=> '4',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>

	<style>
	.widget .widget-inside .tin-options-tabs .postform { width: 100%; }
	.widget .widget-inside .tin-options-tabs p { margin: 3px 0; }
	.widget .widget-inside .tin-options-tabs hr { margin: 20px 0 10px; }
	.widget .widget-inside .tin-options-tabs h4 { margin-bottom: 10px; }
	</style>
	
	<div class="tin-options-tabs">
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题:','tinection'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
		</p>
		
		<h4><?php _e('近期文章','tinection'); ?></h4>
		
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('recent_enable'); ?>" name="<?php echo $this->get_field_name('recent_enable'); ?>" <?php checked( (bool) $instance["recent_enable"], true ); ?>>
			<label for="<?php echo $this->get_field_id('recent_enable'); ?>"><?php _e('启用近期文章','tinection'); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('recent_thumbs'); ?>" name="<?php echo $this->get_field_name('recent_thumbs'); ?>" <?php checked( (bool) $instance["recent_thumbs"], true ); ?>>
			<label for="<?php echo $this->get_field_id('recent_thumbs'); ?>"><?php _e('显示缩略图','tinection'); ?></label>
		</p>	
		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo $this->get_field_id("recent_num"); ?>"><?php _e('要显示的条目','tinection'); ?></label>
			<input style="width:20%;" id="<?php echo $this->get_field_id("recent_num"); ?>" name="<?php echo $this->get_field_name("recent_num"); ?>" type="text" value="<?php echo absint($instance["recent_num"]); ?>" size='3' />
		</p>
		<p>
			<label style="width: 100%; display: inline-block;" for="<?php echo $this->get_field_id("recent_cat_id"); ?>"><?php _e('分类:','tinection'); ?></label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("recent_cat_id"), 'selected' => $instance["recent_cat_id"], 'show_option_all' => 'All', 'show_count' => true ) ); ?>		
		</p>
		
		<hr>
		<h4><?php _e('热门文章','tinection'); ?></h4>
		
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('popular_enable'); ?>" name="<?php echo $this->get_field_name('popular_enable'); ?>" <?php checked( (bool) $instance["popular_enable"], true ); ?>>
			<label for="<?php echo $this->get_field_id('popular_enable'); ?>"><?php _e('开启热门文章','tinection'); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('popular_thumbs'); ?>" name="<?php echo $this->get_field_name('popular_thumbs'); ?>" <?php checked( (bool) $instance["popular_thumbs"], true ); ?>>
			<label for="<?php echo $this->get_field_id('popular_thumbs'); ?>"><?php _e('显示缩略图','tinection'); ?></label>
		</p>	
		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo $this->get_field_id("popular_num"); ?>"><?php _e('要显示的条目','tinection'); ?></label>
			<input style="width:20%;" id="<?php echo $this->get_field_id("popular_num"); ?>" name="<?php echo $this->get_field_name("popular_num"); ?>" type="text" value="<?php echo absint($instance["popular_num"]); ?>" size='3' />
		</p>
		<p>
			<label style="width: 100%; display: inline-block;" for="<?php echo $this->get_field_id("popular_cat_id"); ?>"><?php _e('分类:','tinection'); ?></label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("popular_cat_id"), 'selected' => $instance["popular_cat_id"], 'show_option_all' => 'All', 'show_count' => true ) ); ?>		
		</p>
		<p style="padding-top: 0.3em;">
			<label style="width: 100%; display: inline-block;" for="<?php echo $this->get_field_id("popular_time"); ?>"><?php _e('最热评论文章来自：','tinection'); ?></label>
			<select style="width: 100%;" id="<?php echo $this->get_field_id("popular_time"); ?>" name="<?php echo $this->get_field_name("popular_time"); ?>">
			  <option value="0"<?php selected( $instance["popular_time"], "0" ); ?>><?php _e('任何时间','tinection'); ?></option>
			  <option value="1 year ago"<?php selected( $instance["popular_time"], "1 year ago" ); ?>><?php _e('今年','tinection'); ?></option>
			  <option value="1 month ago"<?php selected( $instance["popular_time"], "1 month ago" ); ?>><?php _e('本月','tinection'); ?></option>
			  <option value="1 week ago"<?php selected( $instance["popular_time"], "1 week ago" ); ?>><?php _e('本周','tinection'); ?></option>
			  <option value="1 day ago"<?php selected( $instance["popular_time"], "1 day ago" ); ?>><?php _e('过去24小时','tinection'); ?></option>
			</select>	
		</p>
		
		<hr>
		<h4><?php _e('最新评论','tinection'); ?></h4>
		
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('comments_enable'); ?>" name="<?php echo $this->get_field_name('comments_enable'); ?>" <?php checked( (bool) $instance["comments_enable"], true ); ?>>
			<label for="<?php echo $this->get_field_id('comments_enable'); ?>"><?php _e('开启最新评论','tinection'); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('comments_avatars'); ?>" name="<?php echo $this->get_field_name('comments_avatars'); ?>" <?php checked( (bool) $instance["comments_avatars"], true ); ?>>
			<label for="<?php echo $this->get_field_id('comments_avatars'); ?>"><?php _e('显示头像','tinection'); ?></label>
		</p>
		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo $this->get_field_id("comments_num"); ?>"><?php _e('显示的条目数量','tinection'); ?></label>
			<input style="width:20%;" id="<?php echo $this->get_field_id("comments_num"); ?>" name="<?php echo $this->get_field_name("comments_num"); ?>" type="text" value="<?php echo absint($instance["comments_num"]); ?>" size='3' />
		</p>

		<hr>

		<h4><?php _e('Tab顺序','tinection'); ?></h4>
		
		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo $this->get_field_id("order_recent"); ?>"><?php _e('近期文章','tinection'); ?></label>
			<input class="widefat" style="width:20%;" type="text" id="<?php echo $this->get_field_id("order_recent"); ?>" name="<?php echo $this->get_field_name("order_recent"); ?>" value="<?php echo $instance["order_recent"]; ?>" />
		</p>
		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo $this->get_field_id("order_popular"); ?>"><?php _e('热门文章','tinection'); ?></label>
			<input class="widefat" style="width:20%;" type="text" id="<?php echo $this->get_field_id("order_popular"); ?>" name="<?php echo $this->get_field_name("order_popular"); ?>" value="<?php echo $instance["order_popular"]; ?>" />
		</p>
		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo $this->get_field_id("order_comments"); ?>"><?php _e('最新评论','tinection'); ?></label>
			<input class="widefat" style="width:20%;" type="text" id="<?php echo $this->get_field_id("order_comments"); ?>" name="<?php echo $this->get_field_name("order_comments"); ?>" value="<?php echo $instance["order_comments"]; ?>" />
		</p>		
		<hr>
		<h4><?php _e('Tab信息','tinection'); ?></h4>
		
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('tabs_category'); ?>" name="<?php echo $this->get_field_name('tabs_category'); ?>" <?php checked( (bool) $instance["tabs_category"], true ); ?>>
			<label for="<?php echo $this->get_field_id('tabs_category'); ?>"><?php _e('显示分类','tinection'); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('tabs_date'); ?>" name="<?php echo $this->get_field_name('tabs_date'); ?>" <?php checked( (bool) $instance["tabs_date"], true ); ?>>
			<label for="<?php echo $this->get_field_id('tabs_date'); ?>"><?php _e('显示日期','tinection'); ?></label>
		</p>
		
		<hr>
		
	</div>
<?php

}

}

/*  Register widget
/* ------------------------------------ */
if ( ! function_exists( 'tin_register_widget_tabs' ) ) {

	function tin_register_widget_tabs() { 
		register_widget( 'tinTabs' );
	}
	
}
add_action( 'widgets_init', 'tin_register_widget_tabs' );