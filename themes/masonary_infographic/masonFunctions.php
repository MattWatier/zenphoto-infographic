<?php  



function get_image_size($w, $h, $m){

	$W = ceil($m * $w);
	$H = ceil($m * $h);
	return array($W, $H);
}

function get_modifier($proportioned,$w) {
$containerSize = 1140;
$gutter = 12;
$column = 152;
$padding = -10;
$size_1x = $column+$padding;
$size_2x = $column+$column+$gutter+$padding;
$size_3x = $column+$column+$gutter+$column+$gutter+$padding;
$breakpoint_small =$column+$padding+($column/3)+$gutter;
$breakpoint_medium = $column+$gutter+$column+$padding+($column/2)+$gutter;


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

function get_classes($tags){
	


}


class MyGallery{

 	public  $parent;
 	public  $color;
 	public  $filters;
 	public	function __construct($parent) {
     	$this->parent = $parent;
   		$this->filters = array();
 	}

	public	function add_to_filter($tags) {
		
		foreach ($tags as $key => $value) {
			$pos = strpos( $value , "color_" );
			if( $pos != FALSE ){
				if( $this->color[$value] == NULL) {	
					 $this->color[$value] = array();
					 $this->color[$value]["value"] = $value;
					 $this->color[$value]["count"] = 1;
				}
				else
				{
					$this->color[$value]["count"] = $this->color[$value]["count"] + 1;
				}

			}else{
				if( $this->filters[$value] == NULL) {	
					 $this->filters[$value] = array();
					 $this->filters[$value]["value"] = $value;
					 $this->filters[$value]["count"] = 1;
				}
				else
				{
					$this->filters[$value]["count"] = $this->filters[$value]["count"] + 1;
				}
			}
		}// end of foreach
		

		
	}
	
	 public	function find_color_tag($tags){
	 	
 		
 	}
 	public function print_filters() {
 		$filterString = "";
	 	foreach ($this->filters as $key => $value) {
	 		$filterString .= $value." ";
	 	}
	 	return $filterString;
 	}
 	public function get_filters(){
 		
 		return $this->filters;
 	}
  	

}



?>