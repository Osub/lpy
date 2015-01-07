<?php if ( is_home() ) { ?>
<div class="box">
  <h3 class="box_title ption_r mb20 f_l"><span>链接表</span></h3>
  <div class="box_content" id="link">
      <ul>
        <?php wp_list_bookmarks('category_name=首页链接&show_images=0&title_li=&categorize=0&orderby=updated'); ?>
      </ul>
  </div>
</div>
<?php } else { ?>
<?php } ?>
