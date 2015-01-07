<?php
/**
 * Template Name:读者墙
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="single f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <div class="post ption_r">
    <div class="circle">
      <div class="single_type"></div>
    </div>
    <h1 id="archive_title_reader" class="fs24 f_w ption_r">读者墙<em class="fs14">/Readers (取前 30 名)</em></h1>
    <div class="ption_r" id="reader_wall">
    <?php
    $query="SELECT COUNT(comment_ID) AS cnt, comment_author, comment_author_url, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_date > date_sub( NOW(), INTERVAL 24 MONTH ) AND user_id='0' AND comment_author_email != 'jwzhong@foxmail.com' AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author_email ORDER BY cnt DESC LIMIT 30";//邮箱自己改下,最后的DESC LIMIT 30,30可以改成适合你主题宽度的数字,即显示的头像总数,并按评论数降序排列
    $wall = $wpdb->get_results($query);
    $maxNum = $wall[0]->cnt;
    foreach ($wall as $comment)
    {
        $width = round(40 / ($maxNum / $comment->cnt),2);//此处是对应的血条的宽度
        if( $comment->comment_author_url )
        $url = $comment->comment_author_url;
        else $url="#";
  $avatar = get_avatar( $comment->comment_author_email, $size = '36', '', $comment->comment_author );
        $tmp = "<li><a target=\"_blank\" href=\"".$comment->comment_author_url."\">".$avatar."<em>".$comment->comment_author."</em> <strong>+".$comment->cnt."</strong></br>".$comment->comment_author_url."</a></li>";
        $output .= $tmp;
     }
    $output = "<ul class=\"readers-list\">".$output."</ul>";
    echo $output ; 
?>
   </div>
   </div>
    <div class="discussion ption_r">
      <div class="circle"><div class="bubble">Comments</div></div>
      <?php comments_template( '', true ); ?>
    </div>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>
