<?php  



function get_image_size($w, $h, $m){

	$W = ceil($m * $w);
	$H = ceil($m * $h);
	return array($W, $H);
}

function get_modifier($proportioned,$w) {
$containerSize = 1164;
$gutter = 12;
$column = 156;
$size_1x = 134;
$size_2x = 290;
$size_3x = 444;
$breakpoint_small = 210;
$breakpoint_medium = 370;


	if($proportioned > 0 && $proportioned < $breakpoint_small){
		$m = $size_1x/$w;
		
		}
	elseif($proportioned >= $breakpoint_small && $proportioned <= $breakpoint_medium) {	
		$m = $size_2x/$w;
		
		}
	else{
		$m = $size_3x/$w;
		
		}
	return $m;
	
}

function get_proportion($w, $h, $maxSQ){

	$proportion = sqrt($maxSQ/($w * $h));
	return($w * $proportion);
}




?>