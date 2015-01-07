<?php
/**
 * The template for displaying image attachments.
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="single f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="post ption_r" id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
      <div class="circle">
        <div class="single_type"></div>
      </div>
      <div class="date"><span><?php the_time('j') ?><small><?php $u_time = get_the_time('U'); echo date("M",$u_time); ?></small></span></div>
      <h1 class="post_title fs24 f_w">多媒体：<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	  <?php edit_post_link('编辑文章','',''); ?>
      <div class="meta">
        <p class="meta_info">
        <?php
			$metadata = wp_get_attachment_metadata();
			printf( __( 
			'
			<span class="mr10 ">上传时间: <time class="entry-date" datetime="%1$s">%2$s</time></span>
			<span class="mr10 ">原图尺寸: <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a></span>
			<span class="mr10 ">上传至: <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a></span>
			' ),
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_url( wp_get_attachment_url() ),
				$metadata['width'],
				$metadata['height'],
				esc_url( get_permalink( $post->post_parent ) ),
				esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
				get_the_title( $post->post_parent )
			);
		?>
        <span class="mr10"><?php setPostViews(get_the_ID()); ?><?php echo getPostViews(get_the_ID()); ?></span>
        </p>
        <div class="clear"></div>
      </div>
      <div class="single_text">
          <?php if ( is_page() ) { ?>
          <?php } else { ?>
          <?php if( javin_opt('javin_adpost_top_button')!='' ) echo '<div class="single_header_ads">'.javin_opt('javin_adpost_top').'</div>'; ?> 
          <?php } ?>
          <div class="newer_older mt10">
		    <span class="pre_post f_l"><?php previous_image_link( false, __( '&larr; 上一张' ) ); ?></span><span class="next_post f_r"><?php next_image_link( false, __( '下一张 &rarr;' ) ); ?></span>
            <div class="clear"></div>
	      </div>
	      <div class="entry-content">
            <div class="entry-attachment">
                <div class="attachment">
<?php
$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
foreach ( $attachments as $k => $attachment ) :
if ( $attachment->ID == $post->ID )
break;
endforeach;

$k++;
// If there is more than 1 attachment in a gallery
if ( count( $attachments ) > 1 ) :
if ( isset( $attachments[ $k ] ) ) :
// get the URL of the next image attachment
$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
else :
// or get the URL of the first image attachment
$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
endif;
else :
// or, if there's only 1 image, get the URL of the image
$next_attachment_url = wp_get_attachment_url();
endif;
?>
                    <a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php
                    $attachment_size = apply_filters( 'twentytwelve_attachment_size', array( 674, 999999 ) );
                    echo wp_get_attachment_image( $post->ID, $attachment_size );
                    ?></a>

                    <?php if ( ! empty( $post->post_excerpt ) ) : ?>
                    <div class="entry-caption">
                        <?php the_excerpt(); ?>
                    </div>
                    <?php endif; ?>
                </div><!-- .attachment -->
            </div><!-- .entry-attachment -->
			<div class="entry-description">
				<?php the_content(); ?>
                <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentytwelve' ), 'after' => '</div>' ) ); ?>
		    </div><!-- .entry-description -->
          </div>
          <div id="share">
            <?php if ( is_page() ) { ?>
            <?php } else { ?><div class="bdlikebutton"></div><?php } ?>
            <p>喜欢我们的文章请您与朋友分享:</p>
            <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">
               <a class="bds_qzone"></a>
               <a class="bds_tsina"></a>
               <a class="bds_tqq"></a>
               <a class="bds_kaixin001"></a>
               <a class="bds_renren"></a>
               <a class="bds_bdhome"></a>
               <span class="bds_more">更多</span>
               <a class="shareCount"></a>
            </div>
          </div>
          <p>除非特殊注明，本文版权归原作者所有，欢迎转载！转载请注明版权以及本文地址，谢谢。<br />
          转载保留版权：<a title="<?php bloginfo('name'); ?>" href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a> >> <a rel="bookmark" title="<?php the_title() ?>" href="<?php the_permalink() ?>"><?php the_title() ?></a><br />
本文地址：<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_permalink() ?></a> + <a href="#" onclick="copy_code('<?php the_permalink() ?>'); return false;">复制链接</a></p>
          <div class="clear"></div>
      </div>
       <?php if( javin_opt('javin_adpost_bottom_button')!='' ) echo '<div class="single_footer_ads"><div class="single_footer_ads_border">'.javin_opt('javin_adpost_bottom').'</div></div>'; ?>
       <div class="clear"></div>
    </div>
    <div class="discussion ption_r">
      <div class="circle"><div class="bubble">Comments</div></div>
      <?php comments_template( '', true ); ?>
    </div>
    <?php endwhile; /* end loop */ ?>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>
