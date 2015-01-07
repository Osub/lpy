<?php
/**
 * Template Name:存档页面
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?> 
<div class="main ption_a">
  <div id="content" class="single f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
	<?php
    $previous_year = $year = 0;
    $previous_month = $month = 0;
    $ul_open = false;

    $myposts = get_posts('numberposts=-1&orderby=post_date&order=DESC');

    foreach($myposts as $post) :
        setup_postdata($post);

        $year = mysql2date('Y', $post->post_date);
        $month = mysql2date('n', $post->post_date);
        $day = mysql2date('j', $post->post_date);

        if($year != $previous_year || $month != $previous_month) :
            if($ul_open == true) : 
                echo '</ul><div class="clear"></div></div>';
            endif;

            echo '<div class="archives_list ption_r"><div class="circle"><div class="archives_date"></div></div><h3 class="archives_list_title fs24 f_w">'; echo the_time('F Y'); echo '</h3>';
            echo '<ul>';
            $ul_open = true;

        endif;

        $previous_year = $year; $previous_month = $month;
    ?>
            <li>
			<div class="f_l archives_d"><?php the_time('j'); ?>日</div>
            <div class="f_l archives_t"><a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
            <div class="f_l archives_v"><?php echo getPostViews(get_the_ID()); ?></div>
            <div class="f_l archives_comm"><a href="<?php comments_link(); ?>" title="查看 <?php the_title(); ?> 的评论"><?php comments_number('0', '1', '%'); ?>条评论</a></div>
            </li>
     <?php endforeach; ?></div>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body>
</html>
