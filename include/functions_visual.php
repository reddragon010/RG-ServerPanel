<?php

function display_money($money){
	if($money < 100){
		$g = 0;
		$s = 0;
		$k = $money;
	} elseif($money < 1000) {
		$g = 0;
		$s = intval($money/100);
		$k = $money - $s*100;
	} else {
		$g = intval($money/1000);
		$s = intval(($money - $g*1000)/100);
		$k = $money - ($g*1000+$s*100);
	}
	return "{$g}g {$s}s {$k}k";
}

function display_avatar($char){
	if($char->data['level'] < 80){
		$path = "images/avatars/def/";
	} else {
		$path = "images/avatars/";
	}
	return $path . $char->data['gender'] . "-" . $char->data['race'] . "-" . $char->data['class'] . ".gif";
}

?>