<?php
/**
 * Includes of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.0.7
 * @date      2014.12.05
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<?php $sitenews=ot_get_option('sitenews');if (!empty($sitenews)) { ?>
<div class="contextual bg-sitenews">
	<i class="fa fa-volume-up" style=""></i>
	<div id="news-scroll-zone">
		<div class="news-scroll-list">
			<?php $sitenews = explode('|',$sitenews);foreach ($sitenews as $sitenew) {echo '<li class="sitenews-list">'.$sitenew.'</li>';} ?>
		</div>
	</div>
	<span class="infobg-close" style="color:#aaa;"><i class="fa fa-close"></i></span>
</div>
<?php } ?>