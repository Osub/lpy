<?php
/**
 * Template Name:投稿页面
 * @package WordPress
 * @subpackage Javin
 */
 get_header(); ?>
<div class="main ption_a">
  <div id="content" class="single f_l t_shadow ption_r">
    <div class="left_top_bg ption_a"></div>
    <div class="article_contribute ption_r" id="post-<?php the_ID(); ?>">
    <div class="circle">
      <div class="single_type"></div>
    </div>
    <h1 id="archive_title_reader" class="fs24 f_w ption_r">合伙投稿人<em class="fs14">/Contribute</em></h1>
    <?php
    if( isset($_POST['contribute_form']) && $_POST['contribute_form'] == 'send') {
        global $wpdb;
        $last_post = $wpdb->get_var("SELECT post_date FROM $wpdb->posts WHERE post_type = 'post' ORDER BY post_date DESC LIMIT 1");
    
        // 博客当前最新文章发布时间与要投稿的文章至少间隔120秒。
        // 相比Cookie来验证两次投稿的时间差，读数据库的方式更加安全
        if ( current_time('timestamp') - strtotime($last_post) < 120 ) {  
            echo ('<div class="contribute_info"');
            echo ('<p>投稿失败！</p>');
			echo ('<p>您投稿也太勤快了吧，先歇会儿！</p>');     
            echo ('</div>');      
        }else {
			// 表单变量初始化
			$name = isset( $_POST['contribute_authorname'] ) ? trim(htmlspecialchars($_POST['contribute_authorname'], ENT_QUOTES)) : '';
			$email =  isset( $_POST['contribute_authoremail'] ) ? trim(htmlspecialchars($_POST['contribute_authoremail'], ENT_QUOTES)) : '';
			$photo = get_avatar( $email, 40 );
			$blog =  isset( $_POST['contribute_authorblog'] ) ? trim(htmlspecialchars($_POST['contribute_authorblog'], ENT_QUOTES)) : '';
			$title =  isset( $_POST['contribute_title'] ) ? trim(htmlspecialchars($_POST['contribute_title'], ENT_QUOTES)) : '';
			$category =  isset( $_POST['cat'] ) ? (int)$_POST['cat'] : 0;
			$content = isset( $_POST['contribute_content'] ) ? $_POST['contribute_content'] : '';
			$tags = isset( $_POST['contribute_tags'] ) ? $_POST['contribute_tags'] : '';
			
			// 表单项数据验证
			if ( empty($name) ) {
			wp_die('非法操作！昵称必须填写。<a href="'.$current_url.'">点此返回</a>');
			}
			
			if ( empty($email) ) {
			wp_die('非法操作！Email必须填写。<a href="'.$current_url.'">点此返回</a>');
			}
			
			if ( empty($title) ) {
			wp_die('非法操作！标题必须填写。<a href="'.$current_url.'">点此返回</a>');
			}
			
			if ( empty($content) ) {
			wp_die('非法操作！内容必须填写。<a href="'.$current_url.'">点此返回</a>');
			}
			
			$post_content = $content;
			$contribute = array(
				'post_title' => $title,
				'post_content' => $post_content,
				'tags_input' => $tags,
				'post_category' => array($category)
			);
		     
			// 将文章插入数据库
			$status = wp_insert_post( $contribute );
			
			if ($status != 0) 
				{ 
					// 投稿成功给博主发送邮件
					// somebody#example.com替换博主邮箱
					// My subject替换为邮件标题，content替换为邮件内容
					wp_mail("jwzhong@foxmail.com","投稿通知：$title","网站有新的游客投稿啦，快去审核吧！ http://www.jiawin.com/wp-admin");
					// 将投稿信息作为自定义字段写入数据库，以便调用
					add_post_meta($status, 'contribute_authorname', $name, TRUE);
					add_post_meta($status, 'contribute_authoremail', $email , TRUE);
					add_post_meta($status, 'contribute_authorblog', $blog, TRUE);
					// 显示投稿成功  
					echo ('<div class="contribute_info">');
					echo ('<p>投稿成功，感谢投稿，您的文章将在审核通过后发布！</p>');   
					echo ('<p>你可以继续投稿或随便逛逛</p>');   
					echo ('</div>');
				}
				else
				{
					echo ('<div class="contribute_info"');
					echo ('<p>投稿失败，不要灰心，一次不行我们再来一次！</p>'); 
					echo ('</div>');  
				}
		}
    }
    ?>
     
      <div class="contribute_instruction">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
        <?php endwhile; else: ?>
        <?php endif; ?>
      </div>
      <form id="article_contribute_from" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
        <ul>
          <li>
            <label for="contribute_authorname">
              <span class="f_l">作者*</span>
              <span class="f_r" id="nameinfo"></span>
            </label>
            <input type="text" size="40" value="" id="contribute_authorname" name="contribute_authorname" />
          </li>
          <li>
            <label for="contribute_authoremail">
              <span class="f_l">邮箱*</span>
              <span class="f_r" id="emailinfo"></span>
            </label>
            <input type="text" size="40" value="" id="contribute_authoremail" name="contribute_authoremail" />
          </li>
          <li>
            <label for="contribute_authorblog">
              <span class="f_l">文章地址</span>
              <span class="f_r"></span>
            </label>
            <input type="text" size="40" value="" id="contribute_authorblog" name="contribute_authorblog" />
          </li>
          <li class="contribute_w686">
            <p class="f_l"><label for="contributecategorg">文章分类*</label>
            <?php wp_dropdown_categories('show_option_none=请选择文章分类&id=contribute-cat&show_count=1&hierarchical=1&hide_empty=0'); ?>
            </p>
            <p class="f_r">
            <label>
              <span class="f_l">文章标签</span>
              <span class="f_r" id="tagsinfo"></span>
            </label>
            <input type="text" size="40" value="" id="contribute_tags" name="contribute_tags" />
            </p>
            <div style="clear:both;"></div>
          </li>
          <li class="contribute_w686">
            <label for="contribute_title">
              <span class="f_l">文章标题*</span>
              <span class="f_r" id="titleinfo"></span>
            </label>
            <input type="text" size="40" value="" id="contribute_title" name="contribute_title" />
          </li>
          <li class="contribute_w686">
            <textarea rows="15" cols="55" id="contribute_content" name="contribute_content" class="xheditor-mfull"></textarea>
            
          </li>
          <li class="contribute_w686">
            <input type="hidden" value="send" name="contribute_form" />
            <input type="submit" value="发布文章" />
            <input type="reset" value="重置内容" /><span id="messageinfo" style=" float:right;"></span>
          </li>
        </ul>
      </form>
    </div>
  </div>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
</body></html>