<?php 
/**
 * The template for displaying loop-single.
 * @package WordPress
 * @subpackage Javin
 */
?>
<div id="content" class="single f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="post ption_r" id="post-<?php the_ID(); ?>">
      <div class="circle">
      <div 
      <?php
          if (in_category('HTML/CSS'))
            {echo 'class="single_type_css"';}
          if (in_category('JavaScript'))
            {echo 'class="single_type_js"';}
		  if (in_category('前端设计'))
            {echo 'class="single_type_desi"';}
		  if (in_category('资源技巧'))
            {echo 'class="single_type_cou"';}
		  if (in_category('觉唯图库'))
            {echo 'class="single_type_tu"';}
		  else
		    {echo 'class="single_type"';}
          ?>
      ></div></div>
      <div class="date"><span><?php the_time('j') ?><small><?php $u_time = get_the_time('U'); echo date("M",$u_time); ?></small></span></div>
      <h1 class="post_title fs24 f_w"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	  <?php edit_post_link('编辑文章','',''); ?>
      <div class="meta">
        <p class="meta_info">
          <span class="mr10"><?php _e('时间: '); the_time('Y-n-j') ?></span>
          <?php if ( is_page() ) { ?>
		  <?php } else { ?>
          <span class="mr10"><?php _e('分类: '); the_category(', ') ?></span>
		  <?php } ?>
          <span class="mr10">
		  <?php $contribute_author = get_post_meta(get_the_ID(),'contribute_authorname',true); ?>
          <?php if ( $contribute_author ) { ?>
              <?php echo "投稿者：$contribute_author"; ?>
          <?php } else { ?>
              <?php _e('作者:'); ?><?php printf( __( ' %s ' ), get_the_author() ); ?>
          <?php } ?>
          </span>
          <span class="mr10"><?php setPostViews(get_the_ID()); ?><?php echo getPostViews(get_the_ID()); ?></span>
        </p>
        <p class="meta_tag"><?php if (the_tags('<span>TAGS:</span> ', ', ', ' ')); ?></p>
        <div class="clear"></div>
      </div>
      <div class="single_text">
          <?php if ( is_page() ) { ?>
          <?php } else { ?>
          <?php if( javin_opt('javin_adpost_top_button')!='' ) echo '<div class="single_header_ads">'.javin_opt('javin_adpost_top').'</div>'; ?> 
          <?php } ?>
	      <?php the_content(); ?>
          <?php wp_link_pages( array( 'before' => '<div class="page-links">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
          <?php 
		    $contribute_author = get_post_meta(get_the_ID(),'contribute_authorname',true);
			$contribute_email = get_post_meta(get_the_ID(),'contribute_authoremail',true);
			$contribute_email_photo = get_avatar( $contribute_email, 40, '', $contribute_author );
			$contribute_blog = get_post_meta(get_the_ID(),'contribute_authorblog',true);
		  ?>
          <?php if ( is_single() && $contribute_author ) { ?>
			   <div class="contribute_author_info">
				<?php echo $contribute_email_photo ?>
				<div>
                  <p>本文作者: <?php echo $contribute_author ?></p>
				  <p>原文地址: <a target="_blank" href="<?php echo $contribute_blog ?>"><?php echo $contribute_blog ?></a><span class="contribute_also"><a target="_blank" href="<?php echo javin_opt('javin_contribute'); ?>">我也要投稿</a></span></p>
                </div>
			   </div>
          <?php } else if ( is_single() ) { ?>
               <div class="contribute_author_info">
               <?php 
			   $author_email = get_the_author_email();
               echo get_avatar($author_email, 40, '', get_the_author());
			   ?>
				<div>
                  <p><?php _e('本文作者: '); ?><a target="_blank" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="查看<?php printf( __( ' %s ' ), get_the_author() ); ?>发表的全部文章" rel="author"><?php printf( __( ' %s ' ), get_the_author() ); ?></a></p>
				  <p class="meta_description"><?php the_author_description(FALSE, FALSE, TRUE, FALSE); ?><span class="contribute_also"><a target="_blank" href="<?php echo javin_opt('javin_contribute'); ?>">我要投稿</a></span></p>
                </div>
			   </div>
           <?php } else { ?>
          <?php } ?>
          <?php if ( is_page('关于我们') ) { ?><!-- 页面显示网站统计内容 -->
          <p class="mt10 fs14"><strong>网站统计</strong></p>
          <div class="web_statistics">
            <table border="0" cellspacing="0">
              <tr>
                <td>建站日期</td>
                <td><?php echo ("2012-09-01"); ?></td>
                <td>日志总数</td>
                <td><?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?> 篇</td>
                <td>标签总数</td>
                <td><?php echo $count_tags = wp_count_terms('post_tag'); ?> 个</td>
              </tr>
              <tr>
                <td>最后更新</td>
                <td><?php $last = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");$last = date('Y-m-d', strtotime($last[0]->MAX_m));echo $last; ?></td>
                <td>评论总数</td>
                <td><?php echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments");?> 条</td>
                <td>分类总数</td>
                <td><?php echo $count_categories = wp_count_terms('category'); ?> 个</td>
              </tr>
              <tr>
                <td>运行天数</td>
                <td><?php echo floor((time()-strtotime("2012-09-01"))/86400); ?> 天</td>
                <td>页面总数</td>
                <td><?php $count_pages = wp_count_posts('page'); echo $page_posts = $count_pages->publish; ?> 篇</td>
                <td>用户总数</td>
                <td><?php $users = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users"); echo $users; ?> 个</td>
              </tr>
            </table>
          </div>
          <?php } ?>
          <div id="share">
            <?php if ( is_page() ) { ?>
            <?php } else { ?><div class="bdlikebutton"></div><?php } ?>
            <p>喜欢我们的文章请您与朋友分享:</p>
            <div class="bdsharebuttonbox"><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_more" data-cmd="more"></a></div>
          </div>
          <?php if ( is_page() ) { ?>
		  <?php } else { ?>
          <div class="copyright">除非特殊注明，本文版权归原作者所有，欢迎转载！转载请注明版权以及本文地址，谢谢。<br />
          转载保留版权：<a title="<?php bloginfo('name'); ?>" href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a> >> <a rel="bookmark" title="<?php the_title() ?>" href="<?php the_permalink() ?>"><?php the_title() ?></a><br />
本文地址：<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_permalink() ?></a> + <a href="#" onclick="copy_code('<?php the_permalink() ?>'); return false;">复制链接</a></div>
		  <?php } ?>
          <div class="clear"></div>
      </div>
      <?php if ( is_page() ) { ?>
       <?php } else { ?>
       <?php if( javin_opt('javin_adpost_bottom_button')!='' ) echo '<div class="single_footer_ads"><div class="single_footer_ads_border">'.javin_opt('javin_adpost_bottom').'</div></div>'; ?>
       <div id="related_post">
        <h2 class="f_w mb10">相关文章：</h2>
        <ul>
         <?php
          global $post;
          $cats = wp_get_post_categories($post->ID);
          if ($cats) {
          $args = array(
          'category__in' => array( $cats[0] ),
          'post__not_in' => array( $post->ID ),//排除当前文章
		  'orderby' => 'rand',
          'showposts' => 4,
          'caller_get_posts' => 1
          );
          query_posts($args);
          if (have_posts()) :
          while (have_posts()) : the_post(); update_post_caches($posts); ?>
          <li>
          <div class="related_post_thume">
          <?php if (has_post_thumbnail()){?>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
          <img width="150" height="120" src="<?php echo PostThumbURL_medium(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
          </a>
          <?php }else if (catch_that_image()) {?>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
          <img width="150" height="120" src="<?php echo catch_that_image(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
          </a>
          <?php } ?>
          </div>
          <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
          <?php endwhile; else : ?>
          <li>* 暂无相关文章</li>
         <?php endif; wp_reset_query(); } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="clear"></div>
      <div class="newer_older mt10">
		<span class="pre_post f_l"><?php previous_post('%', '上一篇：', 'yes'); ?></span><span class="next_post f_r"><?php next_post('%', '下一篇：', 'yes'); ?></span>
        <div class="clear"></div>
	  </div>
    </div>
    <div class="discussion ption_r">
      <div class="circle"><div class="bubble">Comments</div></div>
      <?php comments_template( '', true ); ?>
    </div>
    <?php endwhile; /* end loop */ ?>
  </div>