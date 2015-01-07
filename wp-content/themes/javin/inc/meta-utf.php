<?php
// 解决meta头部描述utf8截取乱码问题
function csubstr($string, $beginIndex, $length){
if(strlen($string) < $length){
return substr($string, $beginIndex);
}
$char = ord($string[$beginIndex + $length - 1]);
if($char >= 224 && $char <= 239){
$str = substr($string, $beginIndex, $length - 1);
return $str;
}
$char = ord($string[$beginIndex + $length - 2]);
if($char >= 224 && $char <= 239){
$str = substr($string, $beginIndex, $length - 2);
return $str;
}
return substr($string, $beginIndex, $length);
}
?>