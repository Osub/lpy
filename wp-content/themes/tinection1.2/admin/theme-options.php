<?php
/**
 * Settings of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.2
 * @date      2014.12.29
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

/**
 * Initialize the custom Theme Options.
 */
add_action( 'admin_init', 'custom_theme_options' );
/**
 * Build the custom settings & update OptionTree.
 *
 * @return    void
 * @since     2.0
 */
function custom_theme_options() {
  /**
   * Get a copy of the saved settings array. 
   */
  $saved_settings = get_option( ot_settings_id(), array() );
  $categories = get_categories(); $cats_output='';foreach ($categories as $cat) {$cats_output .= $cat->cat_ID.' => '.$cat->cat_name.';<br>';}
  $posts = get_posts('numberposts=1000&post_type=post&orderby=ID&order=ASC'); $posts_output='';foreach($posts as $post) {$posts_output .= $post->ID.' => '.$post->post_title.'&nbsp;&nbsp;(<span style="color:#1cbdc5">'.$post->post_date.'</span>);<br>';}
  $pages = get_posts('numberposts=1000&post_type=page&orderby=ID&order=ASC'); $pages_output='';foreach($pages as $page) {$pages_output .= $page->ID.' => '.$page->post_title.'&nbsp;&nbsp;(<span style="color:#1cbdc5">'.$page->post_date.'</span>);<br>';}
  $theme = wp_get_theme();
  $version = $theme->get('Version');
  
  /**
   * Custom settings array that will eventually be 
   * passes to the OptionTree Settings API Class.
   */
  $custom_settings = array( 
    /*  Help pages
/* ------------------------------------ */  
/*    'contextual_help' => array(
      'content'       => array( 
        array(
          'id'        => 'general_help',
          'title'     => 'Documentation',
          'content'   => '
            <h1>Tinection</h1>
            <p>'.__('感谢你使用该主题! Enjoy.','tinection').'</p>
            <ul>
                <li>'.__('访问主题专页 ','tinection').'<a target="_blank" href="http://www/zhiyanblog.com/tinection.html">Tinection Theme</a></li>
                <li>'.__('获取主题最新版本 ','tinection').'<a href="http://www.zhiyanblog.com/tinection-theme-download.html">Download</a></li>
                <li>'.__('Tinection github专页','tinection').'<a target="_blank" href="https://github.com/Zhiyanblog/Tinection">GitHub</a></li>
            </ul>
            <hr />'
        )
      )
    ),
/* ------------------------- */   
/*  Admin panel sections
/* ------------------------------------ */  
    'sections'        => array(
        array(
            'id'      => 'tinection',
            'title'   => 'Tinection'
        ),
        array(
            'id'        => 'general',
            'title'     => __('通用','tinection')
        ),
        array(
            'id'        => 'blog',
            'title'     => __('阅读','tinection')
        ),
        array(
            'id'        => 'header',
            'title'     => __('头部','tinection')
        ),
        array(
            'id'        => 'footer',
            'title'     => __('底部','tinection')
        ),
        array(
            'id'        => 'sidebars',
            'title'     => __('边栏','tinection')
        ),
        array(
            'id'        => 'style',
            'title'     => __('样式','tinection')
        ),
		array(
            'id'        => 'color',
            'title'     => __('配色','tinection')
        ),
        array(
            'id'        => 'open',
            'title'     => __('社会化登录','tinection')
        ),
        array(
            'id'        => 'ads',
            'title'     => __('广告','tinection')
        ),
        array(
            'id'        => 'credit',
            'title'     => __('积分','tinection')
        ),
        array(
            'id'        => 'pages',
            'title'     => __('功能页面','tinection')
        ),
        array(
            'id'        => 'admin',
            'title'     => __('管理员','tinection')
        ),
        array(
            'id'        => 'cats_and_posts_IDs',
            'title'     => __('分类及文章ID对照表','tinection')
        )
    ),
/*  Theme options
/* ------------------------------------ */
    'settings'        => array(

        // Tinection
		
        array(
            'id'        => 'intro',
            'label'     => 'Tinection'.$version,
            'type'      => 'textblock-titled',
            'desc'   => '<p>'.__('感谢你使用该主题! Enjoy.','tinection').'</p><ul><li>'.__('访问主题专页','tinection').' <a target="_blank" href="http://www.zhiyanblog.com/tinection.html">Tinection Theme</a></li><li>'.__('获取主题最新版本','tinection').' <a href="http://www.zhiyanblog.com/tinection-theme-download.html">Download</a></li><li>'.__('Tinection github专页','tinection').'<a target="_blank" href="https://github.com/Zhiyanblog/Tinection">GitHub</a></li></ul><hr /><p>'.__('如果你觉得主题不错，可以支付宝扫描下方二维码赞助 – 不论数量多少，我都非常感激.','tinection').'</p><p><img src="http://pic.zhiyanblog.com//image.php?di=200M" title="'.__('赞助我','tinection').'" style="width:160px;height:160px;"></p><div></div>',
            'section'     => 'tinection'
        ),

        // General: Description
        array(
            'id'        => 'tin_description',
            'label'     => __('页面描述','tinection'),
            'desc'      => __('网站首页描述(非必须，但对SEO有影响)','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'section'   => 'general'
        ),
        // General: Keywords
        array(
            'id'        => 'tin_keywords',
            'label'     => __('页面关键词','tinection'),
            'desc'      => __('网站首页、分类页关键词(非必须，但对SEO有影响，不同关键词用英文逗号隔开)','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'section'   => 'general'
        ),
        // General: Favicon图标
        array(
            'id'        => 'favicon',
            'label'     => 'Favicon',
            'desc'      => __('上传一个16x16像素大小的 Png/Gif 图像作为网站的图标','tinection'),
            'type'      => 'upload',
            'section'   => 'general'
        ),
        // General: 是否开启Logo图像
        array(
            'id'        => 'logo-status',
            'label'     => __('Logo图像','tinection'),
            'desc'      => __('使用图像替代文本作为网站的Logo','tinection'),
            'type'      => 'on-off',
            'section'   => 'general',
	    'std'       => 'off'
        ),
        // General: 自定义Logo图像
        array(
            'id'        => 'logo-img',
            'label'     => __('自定义Logo图像','tinection'),
            'desc'      => __('上传一个图像作为网站的Logo，推荐大小60x120(max)像素','tinection'),
            'type'      => 'upload',
            'section'   => 'general'
        ),
        // General: 评论头像缓存
        // array(
        //     'id'        => 'localavatar',
        //     'label'     => '评论头像缓存',
        //     'desc'      => '本地缓存评论者头像，加快加载速度，确保网站根目录有“avatar”文件夹，并设置权限为777',
        //     'type'      => 'on-off',
        //     'std'       => 'off',
        //     'section'   => 'general'
        // ),
        // General: 多说Gravatar头像
        array(
            'id'        => 'duoshuoavatar',
            'label'     => __('多说Gravatar头像','tinection'),
            'desc'      => __('采用多说的Gravatar头像服务器，解决被墙问题','tinection'),
            'type'      => 'on-off',
            'std'       => 'on',
            'section'   => 'general'
        ),
        // General: 分类默认特色图片
        array(
            'id'        => 'catgorydefaultimg',
            'label'     => __('分类默认特色图片','tinection'),
            'desc'      => __('文章无特色图及内容图片时，根据分类选择默认图片，请填入要开启该选项的分类id，以英文逗号分隔，并将名为cat-分类号.jpg的图片存入主题images目录下','tinection'),
            'type'      => 'text',
            'section'   => 'general'
        ),
		// General: 分类链接category字符移除
        array(
            'id'        => 'remove_category_links_letters',
            'label'     => __('分类链接category字符移除','tinection'),
            'desc'      => __('移除分类链接category字符,缩短分类链接，例如原分类链接http://www.zhiyanblog.com/category/wordpress，开启该选项后为http://www.zhiyanblog.com/wordpress','tinection'),
            'type'      => 'on-off',
			'std'		=> 'off',
            'section'   => 'general'
        ),
        // General: 建站日期
        array(
            'id'        => 'sitebuild_date',
            'label'     => __('建站日期','tinection'),
            'desc'      => __('格式 2014-11-05','tinection'),
            'type'      => 'date_picker',
            'section'   => 'general'
        ),
        // General: 取消WP自动保存
        array(
            'id'        => 'wp_auto_save',
            'label'     => __('取消WP自动保存','tinection'),
            'desc'      => __('在WP后台编辑文章过程，会不断自动保存，导致多个版本占用ID，开启此项以取消自动保存','tinection'),
            'type'      => 'on-off',
            'section'   => 'general',
            'std'       => 'off'
        ),
        // Header: 引入用户自定义代码
        array(
            'id'        => 'headercode',
            'label'     => __('Header中加载自定义代码','tinection'),
            'desc'      => __('在网页头部Header中加载用户自定义代码，可以是javascript或者css，包含完整代码外标签','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'header'
        ),
        // Footer: 引入用户自定义代码
        array(
            'id'        => 'footercode',
            'label'     => __('Footer中加载自定义代码','tinection'),
            'desc'      => __('在网页底部Footer中加载用户自定义代码，可以是javascript或者css，包含完整代码外标签','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'footer'
        ),
        // Footer: 备案号
        array(
            'id'        => 'beian',
            'label'     => __('Footer中显示备案号','tinection'),
            'desc'      => __('在网页底部Footer中显示备案号','tinection'),
            'type'      => 'text',
            'rows'      => '1',
            'std'       => '',
            'section'   => 'footer'
        ),
        // Footer: 统计代码
        array(
            'id'        => 'statisticcode',
            'label'     => __('Footer中加载统计代码','tinection'),
            'desc'      => __('在网页底部Footer中加载统计代码，推荐百度统计','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'footer'
        ),
        // Footer: 版权文字
        array(
            'id'        => 'copyright',
            'label'     => __('Copyright信息','tinection'),
            'desc'      => __('网站底部版权文字描述','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => 'All Rights Reserved',
            'section'   => 'footer'
        ),
        // Footer: 底部通栏Widget
        array(
            'id'        => 'footer-widgets-singlerow',
            'label'     => __('底部通栏Widget','tinection'),
            'desc'      => __('网站底部单栏边栏工具','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'footer'
        ),
        // Footer: Widget Columns
        array(
            'id'        => 'footer-widgets',
            'label'     => __('Footer边栏小工具列数','tinection'),
            'desc'      => __('请选择合适列数开启底部边栏<br /><i>推荐数量：3</i>','tinection'),
            'std'       => '0',
            'type'      => 'radio-image',
            'section'   => 'footer',
            'class'     => '',
            'choices'   => array(
                array(
                    'value'     => '0',
                    'label'     => 'Disable',
                    'src'       => get_template_directory_uri() . '/images/widgets/layout-off.png'
                ),
                array(
                    'value'     => '1',
                    'label'     => '1 Column',
                    'src'       => get_template_directory_uri() . '/images/widgets/footer-widgets-1.png'
                ),
                array(
                    'value'     => '2',
                    'label'     => '2 Columns',
                    'src'       => get_template_directory_uri() . '/images/widgets/footer-widgets-2.png'
                ),
                array(
                    'value'     => '3',
                    'label'     => '3 Columns',
                    'src'       => get_template_directory_uri() . '/images/widgets/footer-widgets-3.png'
                ),
                array(
                    'value'     => '4',
                    'label'     => '4 Columns',
                    'src'       => get_template_directory_uri() . '/images/widgets/footer-widgets-4.png'
                )
            )
        ),
        // Blog: 首页排除分类
        array(
            'id'        => 'cmsundisplaycats',
            'label'     => __('首页排除分类','tinection'),
            'desc'      => __('首页排除的分类','tinection'),
            'type'      => 'category_checkbox',
			'std'		=> '',
            'section'   => 'blog'
        ),
        // Blog: CMS首页自定义分类排序
        array(
            'id'        => 'cmsdisplaycats',
            'label'     => __('CMS首页自定义分类排序','tinection'),
            'desc'      => __('CMS首页自定义分类排序，请按要求的顺序填写分类ID，以英文逗号隔开','tinection'),
            'type'      => 'text',
			'std'		=> '',
            'section'   => 'blog'
        ),
        //Blog: 文章列表显示摘要或全文
        array(
            'id'        => 'content_or_excerpt',
            'label'     => __('显示摘要或全文','tinection'),
            'desc'      => __('文章列表页面显示摘要或全文','tinection'),
            'type'      => 'select',
            'section'   => 'blog',
            'choices'   => array( 
                             array(
                                'value'       => 'content',
                                'label'       => __('全文','tinection'),
                                ),
                             array(
                                'value'       => 'excerpt',
                                'label'       => __('摘要','tinection'),
                                ),
                           )
        ),
        // Blog: Blog布局自动摘要截取字数
        array(
            'id'        => 'excerpt-length',
            'label'     => __('自动摘要截取字数','tinection'),
            'desc'      => __('如果开启文章列表页显示摘要，请设置自动截取的字数，默认200','tinection'),
            'type'      => 'numeric-slider',
            'std'       => '200',
            'min_max_step'  => '0,300,10',
            'section'   => 'blog'
        ),
		// Blog: CMS或Blocks布局自动摘要截取字数
        /* array(
            'id'        => 'excerpt-length2',
            'label'     => __('自动摘要截取字数','tinection'),
            'desc'      => __('如果开启文章列表页显示摘要，请设置自动截取的字数，默认100，推荐较少字符以保证布局美观','tinection'),
            'type'      => 'numeric-slider',
            'std'       => '100',
            'min_max_step'  => '0,200,5',
            'section'   => 'blog'
        ), */
        // Blog: Read more字符样式
        array(
            'id'        => 'readmore',
            'label'     => __('Read More样式','tinection'),
            'desc'      => __('摘要模式下read more形式','tinection'),
            'type'      => 'text',
            'section'   => 'blog'
        ),
        // Blog: Timthumb.php缩略图裁剪
        array(
            'id'        => 'timthumb',
            'label'     => __('Timthumb.php缩略图裁剪','tinection'),
            'desc'      => __('采用Timthumb.php缩略图裁剪，使用教程见<a href="http://www.zhiyanblog.com/timthumb-php-wordpress-thumbnail.html" target="_blank">Timthumb.php教程</a>;','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'blog'
        ),
        // Blog: CDN云存储缩略图后缀参数
        array(
            'id'        => 'cloudimgsuffix',
            'label'     => __('CDN云存储缩略图后缀参数','tinection'),
            'desc'      => __('如果你使用了CDN图片云存储并关闭Timthumb.php缩略图裁剪，那么推荐你填写该云存储图片裁剪参数,能够有效防止图片大小不均一导致排版错误,建议保持默认即可并推荐使用水煮鱼的七牛插件自动上传网站图片至云空间','tinection'),
            'type'      => 'text',
            'std'       => '?imageView2/1/w/375/h/250/q/100',
            'section'   => 'blog'
        ),
		// Blog: 文章页特色图
        array(
            'id'        => 'show-single-thumb',
            'label'     => __('文章页特色图','tinection'),
            'desc'      => __('文章页上方是否显示特色图，若开启请保证添加独一无二的特色图片，以免与文章图片重复','tinection'),
            'type'      => 'on-off',
            'std'       => 'on',
            'section'   => 'blog'
        ),
        //Blog: 文章页版权信息
        array(
            'id'        => 'tin_copyright_content_default',
            'label'     => __('文章页版权信息','tinection'),
            'desc'      => __('文章页版权信息，为默认值，如果作者投稿添加了版权信息，将会覆盖此默认值','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'section'   => 'blog',
            'std'       => __('<p>除特别注明外，本站所有文章均为<a href="{url}" title="{name}" target="_blank">{name}</a>原创，转载请注明出处来自<a href="{link}" title="{title}">{link}</a></p>','tinection'),
        ),
        // Blog: 评论者VIP等级显示
        array(
            'id'        => 'comment_vip',
            'label'     => __('评论者VIP等级显示','tinection'),
            'desc'      => __('评论列表添加用户VIP等级认证，评论越多，等级越高','tinection'),
            'type'      => 'on-off',
            'std'       => 'on',
            'section'   => 'blog'
        ),
        // Blog: 评论者浏览器信息显示
        array(
            'id'        => 'comment_ua',
            'label'     => __('评论者浏览器信息显示','tinection'),
            'desc'      => '',
            'type'      => 'on-off',
            'std'       => 'on',
            'section'   => 'blog'
        ),
        // Blog: 评论者IP真实地址信息显示
        array(
            'id'        => 'comment_ip',
            'label'     => __('评论者IP真实地址信息显示','tinection'),
            'desc'      => '',
            'type'      => 'on-off',
            'std'       => 'on',
            'section'   => 'blog'
        ),
        // Blog: 采用本地IP真实地址数据库
        array(
            'id'        => 'local_ip_datebase',
            'label'     => __('是否采用本地IP真实地址数据库','tinection'),
            'desc'      => __('若开启，请至http://pan.baidu.com/s/1c0pNoWC下载QQwry.dat文件放至主题includes文件夹下，其数据比在线API转换的真实地址更详细，但是会占用一定网站空间','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'blog'
        ),
        // Blog: 移动设备隐藏浮动小工具
        array(
            'id'        => 'mobile_hide_float_btn',
            'label'     => __('移动设备隐藏浮动小工具','tinection'),
            'desc'      => __('在低分辨率移动设备上关闭右下角浮动小工具，仅仅保留回到顶部按钮，以减少空间占用','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'blog'
        ),
		// Blog: 移动设备隐藏边栏
        array(
            'id'        => 'mobile_hide_sidebar',
            'label'     => __('移动设备隐藏边栏','tinection'),
            'desc'      => __('在低分辨率移动设备上关闭边栏','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'blog'
        ),
        // Sidebars: Create Areas
        array(
            'id'        => 'sidebar-areas',
            'label'     => __('建立边栏区','tinection'),
            'desc'      => __('新建边栏后必须保存设置. <br /><i>注意: 保证每个边栏区有一个独一无二的ID.</i>','tinection'),
            'type'      => 'list-item',
            'section'   => 'sidebars',
            'choices'   => array(),
            'settings'  => array(
                array(
                    'id'        => 'id',
                    'label'     => 'Sidebar ID',
                    'desc'      => __('边栏ID必须是独一无二的，例如sidebar-about','tinection'),
                    'std'       => 'sidebar-',
                    'type'      => 'text',
                    'choices'   => array()
                )
            )
        ),
        // Sidebar Choices
        array(
            'id'        => 's1-home',
            'label'     => __('首页','tinection'),
            'desc'      => __('[ <strong>is_home</strong> ] 网站首页的边栏.','tinection'),
            'type'      => 'sidebar-select',
            'section'   => 'sidebars'
        ),
        array(
            'id'        => 's1-single',
            'label'     => __('文章页','tinection'),
            'desc'      => __('[ <strong>is_single</strong> ] 文章页的边栏.','tinection'),
            'type'      => 'sidebar-select',
            'section'   => 'sidebars'
        ),
        array(
            'id'        => 's1-archive',
            'label'     => __('归档页','tinection'),
            'desc'      => __('[ <strong>is_archive</strong> ] 包括标签页和分类页等，如果该页面有一个独特的边栏，将覆盖默认边栏.','tinection'),
            'type'      => 'sidebar-select',
            'section'   => 'sidebars'
        ),
        array(
            'id'        => 's1-archive-category',
            'label'     => __('分类页','tinection'),
            'desc'      => __('[ <strong>is_category</strong> ] 分类页的边栏，优先级高于归档页边栏.','tinection'),
            'type'      => 'sidebar-select',
            'section'   => 'sidebars'
        ),
        array(
            'id'        => 's1-search',
            'label'     => __('搜索页','tinection'),
            'desc'      => __('[ <strong>is_search</strong> ] 搜索结果页的边栏.','tinection'),
            'type'      => 'sidebar-select',
            'section'   => 'sidebars'
        ),
        array(
            'id'        => 's1-404',
            'label'     => __('404错误页','tinection'),
            'desc'      => __('[ <strong>is_404</strong> ] 404错误页的边栏.','tinection'),
            'type'      => 'sidebar-select',
            'section'   => 'sidebars'
        ),
        array(
            'id'        => 's1-page',
            'label'     => __('页面','tinection'),
            'desc'      => __('[ <strong>is_page</strong> ] 页面边栏，如果该页面有一个独特的边栏，将覆盖默认边栏.','tinection'),
            'type'      => 'sidebar-select',
            'section'   => 'sidebars'
        ),
        // Style: 背景颜色
        array(
            'id'        => 'bkgdcolor',
            'label'     => __('网页背景颜色','tinection'),
            'desc'      => __('网页背景颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#f1f4f9',
            'section'   => 'style'
        ),
        // Style: 背景图片
        array(
            'id'        => 'bkgdimg',
            'label'     => __('网页背景图像','tinection'),
            'desc'      => __('网页背景图像，会覆盖背景色','tinection'),
            'type'      => 'upload',
            'section'   => 'style'
        ),
        // Style: 背景图片效果
        array(
            'id'        => 'bkgdimgeffect',
            'label'     => __('网页背景图像效果','tinection'),
            'desc'      => __('是否开启网页背景图像像素化效果','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'style'
        ),
        // Style: 登录页logo
        array(
            'id'        => 'custom-login-logo',
            'label'     => __('登录页logo','tinection'),
            'desc'      => __('留空则使用默认wordpress图标','tinection'),
            'type'      => 'upload',
            'section'   => 'style'
        ),
        // Style: 首页幻灯片
        array(
            'id'        => 'openslider',
            'label'     => __('首页幻灯片','tinection'),
            'desc'      => __('是否开启首页幻灯轮播','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'style'
        ),
        // Style: 幻灯来源
        array(
            'id'        => 'homeslider',
            'label'     => __('首页幻灯片列表','tinection'),
            'desc'      => __('首页幻灯片文章及图像来源，请输入需要呈现的文章ID，以英文逗号隔开并为每篇文章设置特色图片','tinection'),
            'type'      => 'text',
            'section'   => 'style'
        ),
        // Style: 幻灯右侧补充文章排序法
        array(
            'id'        => 'slider_recommend_order',
            'label'     => __('首页幻灯片右侧补充文章排序法','tinection'),
            'desc'      => __('首页幻灯片右侧推荐区置顶文章不够6篇时，补充文章的排序算法，默认最多评论文章排序','tinection'),
            'type'      => 'radio',
			'std'		=> 'most_reviewed',
            'section'   => 'style',
            'choices'   => array( 
                             array(
                                'value'       => 'most_viewed',
                                'label'       => __('最多浏览排序','tinection'),
                                ),
                             array(
                                'value'       => 'most_reviewed',
                                'label'       => __('最多评论排序','tinection'),
                                ),
                           )
        ),
        //Style: 首页布局
        array(
            'id'        => 'layout',
            'label'     => __('首页布局','tinection'),
            'desc'      => __('首页CMS布局或Blog布局或Blocks块状布局','tinection'),
            'type'      => 'radio',
			'std'		=> 'cms',
            'section'   => 'style',
            'std'       => 'cms',
            'choices'   => array( 
                             array(
                                'value'       => 'cms',
                                'label'       => 'CMS',
                                ),
                             array(
                                'value'       => 'blog',
                                'label'       => 'BLOG',
                                ),
							array(
                                'value'       => 'blocks',
                                'label'       => 'BLOCKS',
                                ),
                           )
        ),
		// Style: 首页CMS布局分类模板0
        array(
            'id'        => 'cms_catlist_template_bar_0',
            'label'     => __('首页CMS布局分类并列双栏模板-使用该模板的分类','tinection'),
            'desc'      => __('要使用该模板，请勾选对应分类，该模板为分类并列模板，推荐偶数个分类勾选该模板，并在分类输出排序中使其相邻，同一分类不要勾选多个模板，此外，当首页分类输出超过6个后将强制使用该模板。本模板样式见','tinection').'(<a href="http://pic.zhiyanblog.com//image.php?di=DBJ4" target="_blank">http://pic.zhiyanblog.com//image.php?di=DBJ4</a>)',
            'type'      => 'category_checkbox',
            'std'       => '',
            'section'   => 'style'
        ),
        // Style: 首页CMS布局分类模板1
        array(
            'id'        => 'cms_catlist_template_bar_1',
            'label'     => __('首页CMS布局分类模板1-使用该模板的分类','tinection'),
            'desc'      => __('要使用该模板，请勾选对应分类，同一分类不要勾选多个模板，本模板样式见','tinection').'(<a href="http://pic.zhiyanblog.com//image.php?di=PQ5B" target="_blank">http://pic.zhiyanblog.com//image.php?di=PQ5B</a>)',
            'type'      => 'category_checkbox',
            'std'       => '',
            'section'   => 'style'
        ),
        // Style: 首页CMS布局分类模板2
        array(
            'id'        => 'cms_catlist_template_bar_2',
            'label'     => __('首页CMS布局分类模板2-使用该模板的分类','tinection'),
            'desc'      => __('要使用该模板，请勾选对应分类，同一分类不要勾选多个模板，本模板样式见','tinection').'(<a href="http://pic.zhiyanblog.com//image.php?di=KZS3" target="_blank">http://pic.zhiyanblog.com//image.php?di=KZS3</a>)',
            'type'      => 'category_checkbox',
            'std'       => '',
            'section'   => 'style'
        ),
        // Style: 首页CMS布局分类模板3
        array(
            'id'        => 'cms_catlist_template_bar_3',
            'label'     => __('首页CMS布局分类模板3-使用该模板的分类','tinection'),
            'desc'      => __('要使用该模板，请勾选对应分类，同一分类不要勾选多个模板，本模板样式见','tinection').'(<a href="http://pic.zhiyanblog.com//image.php?di=CRWH" target="_blank">http://pic.zhiyanblog.com//image.php?di=CRWH</a>)',
            'type'      => 'category_checkbox',
            'std'       => '',
            'section'   => 'style'
        ),
        // Style: 首页CMS布局分类模板4
        array(
            'id'        => 'cms_catlist_template_bar_4',
            'label'     => __('首页CMS布局分类模板4-使用该模板的分类','tinection'),
            'desc'      => __('要使用该模板，请勾选对应分类，同一分类不要勾选多个模板，本模板样式见','tinection').'(<a href="http://pic.zhiyanblog.com//image.php?di=IAA4" target="_blank">http://pic.zhiyanblog.com//image.php?di=IAA4</a>)',
            'type'      => 'category_checkbox',
            'std'       => '',
            'section'   => 'style'
        ),
        // Style: 首页CMS布局分类模板5
        array(
            'id'        => 'cms_catlist_template_bar_5',
            'label'     => __('首页CMS布局分类模板5-使用该模板的分类','tinection'),
            'desc'      => __('要使用该模板，请勾选对应分类，同一分类不要勾选多个模板，本模板样式见','tinection').'(<a href="http://pic.zhiyanblog.com//image.php?di=BXI5" target="_blank">http://pic.zhiyanblog.com//image.php?di=BXI5</a>)',
            'type'      => 'category_checkbox',
            'std'       => '',
            'section'   => 'style'
        ),
		// Style: 首页CMS布局分类模板6
        array(
            'id'        => 'cms_catlist_template_bar_6',
            'label'     => __('首页CMS布局分类模板6-使用该模板的分类','tinection'),
            'desc'      => __('要使用该模板，请勾选对应分类，同一分类不要勾选多个模板，本模板样式见','tinection').'(<a href="http://pic.zhiyanblog.com//image.php?di=47YW" target="_blank">http://pic.zhiyanblog.com//image.php?di=47YW</a>)',
            'type'      => 'category_checkbox',
            'std'       => '',
            'section'   => 'style'
        ),
		// Style: 浏览器滚动条颜色
        array(
            'id'        => 'browser_scroll_color',
            'label'     => __('浏览器滚动条颜色','tinection'),
            'desc'      => __('浏览器滚动条颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#00a67c',
            'section'   => 'color'
        ),
		// Style: Body主字体颜色
        array(
            'id'        => 'main_body_color',
            'label'     => __('Body主字体颜色','tinection'),
            'desc'      => __('Body主字体颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#303030',
            'section'   => 'color'
        ),
		// Style: Body主字体超链接颜色
        array(
            'id'        => 'main_body_a_color',
            'label'     => __('Body主字体超链接颜色','tinection'),
            'desc'      => __('Body主字体超链接颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#00a67c',
            'section'   => 'color'
        ),
		// Style: Body主字体超链接鼠标悬停颜色
        array(
            'id'        => 'Body主字体超链接鼠标悬停颜色',
            'label'     => __('Body主字体超链接鼠标悬停颜色','tinection'),
            'desc'      => __('Body主字体超链接鼠标悬停颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#d9534f',
            'section'   => 'color'
        ),
		// Style: 文章标题颜色
        array(
            'id'        => 'title_a_color',
            'label'     => __('文章标题颜色','tinection'),
            'desc'      => __('文章标题颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#00a67c',
            'section'   => 'color'
        ),
		// Style: 文章标题鼠标悬停颜色
        array(
            'id'        => 'title_a_hover_color',
            'label'     => __('文章标题鼠标悬停颜色','tinection'),
            'desc'      => __('文章标题鼠标悬停颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#d9534f',
            'section'   => 'color'
        ),
		// Style: Selection选取背景色
        array(
            'id'        => 'selection_bg_color',
            'label'     => __('Selection选取背景色','tinection'),
            'desc'      => __('Selection选取背景色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#72d0eb',
            'section'   => 'color'
        ),
		// Style: Selection选取文字颜色
        array(
            'id'        => 'selection_color',
            'label'     => __('Selection选取文字颜色','tinection'),
            'desc'      => __('Selection选取文字颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#fff',
            'section'   => 'color'
        ),
		// Style: 导航条背景色
        array(
            'id'        => 'nav_bg_color',
            'label'     => __('导航条背景色','tinection'),
            'desc'      => __('导航条背景色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#2e3639',
            'section'   => 'color'
        ),
		// Style: 菜单文字颜色
        array(
            'id'        => 'menu_color',
            'label'     => __('菜单文字颜色','tinection'),
            'desc'      => __('菜单文字颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#fff',
            'section'   => 'color'
        ),
		// Style: 菜单悬停背景色
        array(
            'id'        => 'menu_hover_bg_color',
            'label'     => __('菜单悬停背景色','tinection'),
            'desc'      => __('菜单悬停背景色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#00a67c',
            'section'   => 'color'
        ),
		// Style: 菜单悬停文字颜色
        array(
            'id'        => 'menu_hover_color',
            'label'     => __('菜单悬停文字颜色','tinection'),
            'desc'      => __('菜单悬停文字颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#fff',
            'section'   => 'color'
        ),
		// Style: Logo字体颜色
        array(
            'id'        => 'logo_color',
            'label'     => __('Logo字体颜色','tinection'),
            'desc'      => __('Logo字体颜色','tinection'),
            'type'      => 'colorpicker',
            'std'       => '#fff',
            'section'   => 'color'
        ),
		// Open: QQ快速登录
        array(
            'id'        => 'tin_open_qq',
            'label'     => __('QQ快速登录','tinection'),
            'desc'      => __('在登录弹窗以及评论区域显示QQ快速登录按钮，需要自行申请APP KEY','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'open'
        ),
        // Open: QQ开放平台ID
        array(
            'id'        => 'tin_open_qq_id',
            'label'     => __('QQ开放平台ID','tinection'),
            'desc'      => '',
            'type'      => 'text',
            'std'       => '',
            'section'   => 'open'
        ),
        // Open: QQ开放平台KEY
        array(
            'id'        => 'tin_open_qq_key',
            'label'     => __('QQ开放平台KEY','tinection'),
            'desc'      => '',
            'type'      => 'text',
            'std'       => '',
            'section'   => 'open'
        ),
        // Open: 微博快速登录
        array(
            'id'        => 'tin_open_weibo',
            'label'     => __('微博快速登录','tinection'),
            'desc'      => __('在登录弹窗以及评论区域显示新浪微博快速登录按钮，需要自行申请APP KEY','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'open'
        ),
        // Open: 微博开放平台KEY
        array(
            'id'        => 'tin_open_weibo_key',
            'label'     => __('微博开放平台KEY','tinection'),
            'desc'      => '',
            'type'      => 'text',
            'std'       => '',
            'section'   => 'open'
        ),
        // Open: 微博开放平台SECRET
        array(
            'id'        => 'tin_open_weibo_secret',
            'label'     => __('微博开放平台SECRET','tinection'),
            'desc'      => '',
            'type'      => 'text',
            'std'       => '',
            'section'   => 'open'
        ),
        // Open: 新登录用户角色
        array(
            'id'        => 'tin_open_role',
            'label'     => __('新登录用户角色','tinection'),
            'desc'      => '',
            'type'      => 'select',
			'choices'	=> array(
				array(
                    'value'     => 'subscriber',
                    'label'     => __('订阅者','tinection')
                ),
				array(
                    'value'     => 'contributor',
                    'label'     => __('投稿者','tinection')
                ),
				array(
                    'value'     => 'author',
                    'label'     => __('作者','tinection')
                ),
				array(
                    'value'     => 'editor',
                    'label'     => __('编辑','tinection')
                ),
				array(
                    'value'     => 'administrator',
                    'label'     => __('管理员','tinection')
                ),				
			),
            'std'       => 'contributor',
            'section'   => 'open'
        ),
        // Ads: 顶部广告
        array(
            'id'        => 'headerad',
            'label'     => __('顶部自定义广告','tinection'),
            'desc'      => __('在网页顶部加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，最大宽度1120px','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
        // Ads: 底部广告
        array(
            'id'        => 'bottomad',
            'label'     => __('底部自定义广告','tinection'),
            'desc'      => __('在网页底部加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，最大宽度1120px','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
		// Ads: 首页文章循环内部广告
        array(
            'id'        => 'cmswithsidebar_loop_ad',
            'label'     => __('首页文章循环内部广告','tinection'),
            'desc'      => __('带边栏首页文章循环内部加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，最大宽度800px','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
        // Ads: 文章页上方广告
        array(
            'id'        => 'singletopad',
            'label'     => __('文章页上部广告','tinection'),
            'desc'      => __('在文章顶部加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，最大宽度800px','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
        // Ads: 文章页缩略图下方广告
        array(
            'id'        => 'singlethumbad',
            'label'     => __('文章页缩略图下方广告','tinection'),
            'desc'      => __('在文章页缩略图下方加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，最大宽度800px，如果没有缩略图将不显示','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
        // Ads: 文章页下方广告
        array(
            'id'        => 'singlebottomad',
            'label'     => __('文章页下部广告','tinection'),
            'desc'      => __('在文章底部加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，最大宽度800px','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
		// Ads: 文章页上方移动广告
        array(
            'id'        => 'singlead1_mobile',
            'label'     => __('文章页上部移动广告','tinection'),
            'desc'      => __('在移动设备文章上部加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，推荐宽度320px','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
		// Ads: 文章页下方移动广告
        array(
            'id'        => 'singlead2_mobile',
            'label'     => __('文章页底部移动广告','tinection'),
            'desc'      => __('在移动设备文章底部加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，推荐宽度320px','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
        // Ads: 下载页广告1
        array(
            'id'        => 'dlad1',
            'label'     => __('下载页广告1','tinection'),
            'desc'      => __('在下载页加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，推荐尺寸336*300以内','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
        // Ads: 下载页广告2
        array(
            'id'        => 'dlad2',
            'label'     => __('下载页广告2','tinection'),
            'desc'      => __('在下载页加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，推荐尺寸760*90以内','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
        // Ads: 下载页广告3
        array(
            'id'        => 'dlad3',
            'label'     => __('下载页广告3','tinection'),
            'desc'      => __('在下载页加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，推荐尺寸336*300以内','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
        // Ads: 下载页广告4
        array(
            'id'        => 'dlad4',
            'label'     => __('下载页广告4','tinection'),
            'desc'      => __('在下载页加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，推荐尺寸336*300以内','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
        // Ads: 演示页浮动对联广告
        array(
            'id'        => 'floatad',
            'label'     => __('演示页广告','tinection'),
            'desc'      => __('在演示页加载用户自定义广告代码，可以是javascript或者css，包含完整代码外标签，推荐浮动对联广告','tinection'),
            'type'      => 'textarea_simple',
            'rows'      => '5',
            'std'       => '',
            'section'   => 'ads'
        ),
        // Credit: 新用户注册奖励
        array(
            'id'        => 'tin_reg_credit',
            'label'     => __('新用户注册奖励','tinection'),
            'desc'      => __('新用户首次注册奖励积分','tinection'),
            'type'      => 'text',
            'std'       => '50',
            'section'   => 'credit'
        ),
        // Credit: 投稿一次奖励积分
        array(
            'id'        => 'tin_rec_post_credit',
            'label'     => __('投稿一次奖励积分','tinection'),
            'desc'      => __('投稿者投稿通过后奖励积分','tinection'),
            'type'      => 'text',
            'std'       => '50',
            'section'   => 'credit'
        ),
        // Credit: 每日投稿获得积分次数
        array(
            'id'        => 'tin_rec_post_num',
            'label'     => __('每日投稿获得积分次数','tinection'),
            'desc'      => __('每天通过投稿获得积分的最大次数，超出后将不再获得积分','tinection'),
            'type'      => 'text',
            'std'       => '5',
            'section'   => 'credit'
        ),
        // Credit: 评论一次奖励积分
        array(
            'id'        => 'tin_rec_comment_credit',
            'label'     => __('评论一次奖励积分','tinection'),
            'desc'      => __('评论一次奖励积分','tinection'),
            'type'      => 'text',
            'std'       => '5',
            'section'   => 'credit'
        ),
        // Credit: 每日评论获得积分次数
        array(
            'id'        => 'tin_rec_comment_num',
            'label'     => __('每日评论获得积分次数','tinection'),
            'desc'      => __('每天通过投稿获得积分的最大次数，超出后将不再获得积分','tinection'),
            'type'      => 'text',
            'std'       => '20',
            'section'   => 'credit'
        ),
        // Credit: 注册推广一次奖励积分
        array(
            'id'        => 'tin_rec_reg_credit',
            'label'     => __('注册推广一次奖励积分','tinection'),
            'desc'      => __('通过专属推广链接推广新用户注册一次所奖励积分','tinection'),
            'type'      => 'text',
            'std'       => '50',
            'section'   => 'credit'
        ),
        // Credit: 每日注册推广奖励积分次数
        array(
            'id'        => 'tin_rec_reg_num',
            'label'     => __('每日注册推广奖励积分次数','tinection'),
            'desc'      => __('每天通过注册推广获得积分的最大次数，超出后将不再获得积分','tinection'),
            'type'      => 'text',
            'std'       => '5',
            'section'   => 'credit'
        ),
        // Credit: 访问推广一次奖励积分
        array(
            'id'        => 'tin_rec_view_credit',
            'label'     => __('访问推广一次奖励积分','tinection'),
            'desc'      => __('通过专属推广链接推广用户访问一次奖励积分','tinection'),
            'type'      => 'text',
            'std'       => '5',
            'section'   => 'credit'
        ),
        // Credit: 每日访问推广奖励积分次数
        array(
            'id'        => 'tin_rec_view_num',
            'label'     => __('每日访问推广奖励积分次数','tinection'),
            'desc'      => __('每天通过访问推广获得积分的最大次数，超出后将不再获得积分','tinection'),
            'type'      => 'text',
            'std'       => '50',
            'section'   => 'credit'
        ),
        // Credit: 作者发布的资源被下载一次可得积分
        array(
            'id'        => 'tin_rec_resource_dl_credit',
            'label'     => __('作者发布的资源被下载一次可得积分','tinection'),
            'desc'      => __('作者发布的资源被用户下载后获得积分奖励，不限次数','tinection'),
            'type'      => 'text',
            'std'       => '5',
            'section'   => 'credit'
        ),
        // Pages: 下载页面
        array(
            'id'        => 'page_dl',
            'label'     => __('下载页面链接后缀','tinection'),
            'desc'      => __('下载页面的后缀设置，例如www.zhiyanblog.com/dl为我以download page为模板建立的下载页面，那么填写dl即可','tinection'),
            'type'      => 'text',
            'std'       => 'dl',
            'section'   => 'pages'
        ),
        // Pages: 演示页面
        array(
            'id'        => 'page_demo',
            'label'     => __('演示页面链接后缀','tinection'),
            'desc'      => __('演示页面的后缀设置，例如www.zhiyanblog.com/demo为我以demo page为模板建立的下载页面，那么填写demo即可','tinection'),
            'type'      => 'text',
            'std'       => 'demo',
            'section'   => 'pages'
        ),
        // Pages: 邮件订阅页面
        array(
            'id'        => 'page_newsletter',
            'label'     => __('邮件订阅及在线阅读页面链接后缀','tinection'),
            'desc'      => __('邮件订阅及在线阅读页面的后缀设置，例如www.zhiyanblog.com/newsletter为我以newsletter为模板建立的邮件订阅页面，那么填写newsletter即可','tinection'),
            'type'      => 'text',
            'std'       => 'newsletter',
            'section'   => 'pages'
        ),
        // Admin: 站点消息
        array(
            'id'        => 'sitenews',
            'label'     => __('站点消息','tinection'),
            'desc'      => __('网站顶部显示站点自定义消息，多条消息请以 | 分隔','tinection'),
            'type'      => 'textarea',
            'rows'       => '5',
            'std'       => '',
            'section'   => 'admin'
        ),
        // Admin: 主题语言
        array(
            'id'        => 'lan_en',
            'label'     => __('主题英文语言','tinection'),
            'desc'      => __('是否开启主题英文语言','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'admin'
        ),
        // Admin: 邮件周刊
        array(
            'id'        => 'newsletter',
            'label'     => __('邮件周刊','tinection'),
            'desc'      => __('每周向订阅用户发送上周内发布的新文章','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'admin'
        ),
        // Admin: 登录错误提醒
        array(
            'id'        => 'login_failed_notify',
            'label'     => __('登陆错误提醒','tinection'),
            'desc'      => __('登陆错误时邮件提醒','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'admin'
        ),
        // Admin: 登录成功提醒
        array(
            'id'        => 'login_success_notify',
            'label'     => __('登录成功提醒','tinection'),
            'desc'      => __('登录成功时邮件提醒','tinection'),
            'type'      => 'on-off',
            'std'       => 'on',
            'section'   => 'admin'
        ),
		// Admin: 评论过滤
        array(
            'id'        => 'span_comments',
            'label'     => __('评论过滤','tinection'),
            'desc'      => __('纯英文或日文评论过滤','tinection'),
            'type'      => 'on-off',
            'std'       => 'off',
            'section'   => 'admin'
        ),
        // Admin: 投稿
        array(
            'id'        => 'tin_can_post_cat',
            'label'     => __('允许投稿分类','tinection'),
            'desc'      => __('允许投稿的分类，不勾选则不开放投稿','tinection'),
            'type'      => 'category_checkbox',
            'std'       => '',
            'section'   => 'admin'
        ),
        // Admin: 新浪微博
        array(
            'id'        => 'tin_sinaweibo',
            'label'     => __('新浪微博','tinection'),
            'desc'      => __('新浪微博，例如我的新浪微博主页http://weibo.com/touchumind/，则填写touchumind即可s','tinection'),
            'type'      => 'text',
            'std'       => 'touchumind',
            'section'   => 'admin'
        ),
        // Admin: 腾讯微博
        array(
            'id'        => 'tin_qqweibo',
            'label'     => __('腾讯微博','tinection'),
            'desc'      => __('腾讯微博，例如我的腾讯微博主页http://t.qq.com/touchumind，则填写touchumind即可','tinection'),
            'type'      => 'text',
            'std'       => 'touchumind',
            'section'   => 'admin'
        ), 
        // Admin: QQ空间
        array(
            'id'        => 'tin_qzone',
            'label'     => __('QQ空间','tinection'),
            'desc'      => __('QQ空间，例如我的QQ空间主页http://user.qzone.qq.com/813920477/，则填写813920477即可','tinection'),
            'type'      => 'text',
            'std'       => '813920477',
            'section'   => 'admin'
        ), 
        // Admin: 微信
        array(
            'id'        => 'tin_weixin',
            'label'     => __('微信','tinection'),
            'desc'      => __('微信二维码图片','tinection'),
            'type'      => 'upload',
            'std'       => 'http://pic.zhiyanblog.com/?di=Z4EI',
            'section'   => 'admin'
        ), 
        // Admin: QQ
        array(
            'id'        => 'tin_qq',
            'label'     => 'QQ',
            'desc'      => __('QQ号码，用于QQ在线交谈，请至http://wp.qq.com设置你的QQ在线交谈状态','tinection'),
            'type'      => 'text',
            'std'       => '813920477',
            'section'   => 'admin'
        ),
        // Admin: QQ List
        array(
            'id'        => 'tin_qqlist',
            'label'     => __('QQ邮件列表','tinection'),
            'desc'      => __('QQ邮件列表订阅链接，详见http://list.qq.com','tinection'),
            'type'      => 'text',
            'std'       => 'http://list.qq.com/cgi-bin/qf_invite?id=38c32a0083496c8c74265b09a0a7e2af923171f3704f4a7b',
            'section'   => 'admin'
        ),
        //分类ID对照表
        array(
            'id'        => 'cats',
            'label'     => __('分类ID对照表','tinection'),
            'type'      => 'textblock-titled',
            'desc'   => '
            <p>'.$cats_output.'</p>
            ',
            'section'     => 'cats_and_posts_IDs'
        ), 
        //文章ID对照表
        array(
            'id'        => 'posts',
            'label'     => __('文章ID对照表','tinection'),
            'type'      => 'textblock-titled',
            'desc'   => '
            <p>'.$posts_output.'</p>
            ',
            'section'     => 'cats_and_posts_IDs'
        ),
		//页面ID对照表
        array(
            'id'        => 'pages',
            'label'     => __('页面ID对照表','tinection'),
            'type'      => 'textblock-titled',
            'desc'   => '
            <p>'.$pages_output.'</p>
            ',
            'section'     => 'cats_and_posts_IDs'
        ),
    )
);
  
  /* allow settings to be filtered before saving */
  $custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );
  
  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( ot_settings_id(), $custom_settings ); 
  }
  
}