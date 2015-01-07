<?php
// 调整云标签默认样式
    add_filter('widget_tag_cloud_args','style_tags');
    function style_tags($args) {
    $args = array(
          'largest'=> '12', //设置标签云的所有标签中,计数最多(最多文章使用)的标签的字体大小,默认值为22pt
		  'smallest'=> '12', //设置标签云中显示的所有标签中,计数最少(最少文章使用)的标签字体大小,默认值为 8pt 
		  'unit' => 'px', //标签文字字号的单位,默认为pt,可以为px、em、pt、百分比等
          'format'=> 'list', //调用标签的格式,可选"flat"、"list"和"array",默认为"flat"平铺,"list"为列表方式;
          'number' => '0', //设置标签云中显示的最多标签数量,默认值为45个,设置为"0″则调用所有标签;
          'orderby' => 'count', //设置标签云中标签的排序方式,默认值为"name"按名称排序。如果设置成"count"则按关联的文章数量排列;
          'order' => 'DESC' //排序方式,默认为"ASC"按正序,"DESC"按倒序,"RAND"按任意顺序;
		  );
    return $args;
	}
?>