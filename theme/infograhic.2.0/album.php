<?php

// force UTF-8 Ø


if (!defined('WEBPATH')) die();
include_once "masonFunctions.php";
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
	<?php zp_apply_filter('theme_head'); ?> 
    <title><?php echo getBareGalleryTitle(); ?> | <?php echo getBareAlbumTitle();?> | <?php echo getBareImageTitle();?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width">
	<?php include('_htmlHeader.php' ); ?>	

</head>
<body id="albumbody">

<?php zp_apply_filter('theme_body_open'); ?>
<?php include('_siteHeaderNav.php' ); ?>	

<div id="filterHolder"></div>
<div id="main" class="row" style="padding-top:50px;">
	<div id="breadcrumb" class="column ten">
		<h1 style=" font-family: 'SansationLight', 'trebuchet MS', Arial, sans-serif; font-weight: 300; letter-spacing: 1px; font-size: 36px;"><span><?php printHomeLink('', ' | '); ?><a href="<?php echo html_encode(getGalleryIndexURL());?>" title="<?php echo gettext('Albums Index'); ?>"><?php echo getGalleryTitle();?></a> | <?php printParentBreadcrumb(); ?></span> <?php printAlbumTitle(true);?></h1>
		<p><?php printAlbumDesc(true); ?></p>
	</div>
	<div id="dataholder" class="column six">
		<h4 style=" font-family: 'SansationLight', 'trebuchet MS', Arial, sans-serif; font-weight: 300; " >Gallery Colors</h4>

	</div>

</div>
<div class="row">
	
	<?php
	$gallery = new MyGallery(getBareAlbumTitle());
	$gallery_item = "<div id='album' class='rows'>";
	$checked = false;
		while (next_album( $all = true)):
		$crap = getAlbumThumb( getBareAlbumTitle());
		$current_alblum = $_zp_current_album;
		$albumTitle = $_zp_current_album ->name;
		$album_item = "";
		
		$images = $current_alblum -> images;	
		
		
		//Print out List of current images in gallery.
		//Pass those images to a foreach loop
		//Inside the For each loop make this data structure DIV(class="alblum_names image" ) ->image(src = "thumbnail" ) (alt = "image name" )
			while (next_image($all = TRUE)):
			$image_item = "";	
			$maxSQ=30000;
			$h = getFullHeight( );
			$w = getFullWidth( );
			$proportioned = get_proportion($w, $h, $maxSQ);
			$m = get_modifier($proportioned,$w);
			$size = get_image_size($w, $h, $m);
			$W = $size[0];
			$H = $size[1];
			
			//echo "tags";
			$tags= getTags();
			
			
			

			if(isset($tags)){
				array_push($tags, getBareAlbumTitle());
			$space_separated_array = implode("_", array_unique($tags));
			$space_separated_array = str_replace(" ", "-", $space_separated_array);
			$space_separated_array = str_replace("_", " ", $space_separated_array);
				$gallery->add_to_filter(array_unique($tags));
			}
			$image_item .= "<div class='images ".getBareAlbumTitle()." ".$space_separated_array."' style='display:block;padding:5px;margin-bottom:12px; border:1px #ccc solid;width:".($W+10)."px; height:".($H+10)."px;' >";
			$image_item .= "<a href='".getCustomImageURL(800)."' title='".getBareImageTitle()."' class='fancy'>";
			$image_item .="<img width='".$W."' height='".$H."' class='lazy' src='".$_zp_themeroot."/images/holder.gif' data-original='".getCustomSizedImageMaxSpace( $W, $H)."' /></a></div>";
			$album_item .= $image_item;	
			endwhile; 
			//end of next_image loop
			$gallery_item .= $album_item;
		endwhile; //end of next album loop
		
			$album_item="";		
		while (next_image($all = TRUE)):
			$image_item = "";	
			$maxSQ=30000;
			$h = getFullHeight( );
			$w = getFullWidth( );
			$proportioned = get_proportion($w, $h, $maxSQ);
			$m = get_modifier($proportioned,$w);
			$size = get_image_size($w, $h, $m);
			$W = $size[0];
			$H = $size[1];
			
			//echo "tags";
			$tags= getTags();
			if(isset($tags)){
				array_push($tags, getBareAlbumTitle());
				$gallery->add_to_filter(array_unique($tags));
			}
			$space_separated_array = implode("_", array_unique($tags));
			$space_separated_array = str_replace(" ", "-", $space_separated_array);
			$space_separated_array = str_replace("_", " ", $space_separated_array);
			$image_item .= "<div class='images ".getBareAlbumTitle()." ".$space_separated_array."' style='display:block;padding:5px;margin-bottom:12px; border:1px #ccc solid; width:".($W+10)."px; height:".($H+10)."px;' >";
			$image_item .= "<a href='".getCustomImageURL(800)."' title='".getBareImageTitle()."' class='fancy'>";
			$image_item .="<img width='".$W."' height='".$H."' class='lazy' src='".$_zp_themeroot."/images/holder.gif' data-original='".getCustomSizedImageMaxSpace( $W, $H)."' /></a></div>";
			
			
			$album_item .= $image_item;	
			
		
		endwhile; //end of next_image loop
		$gallery_item .= $album_item;
		//end of gallery mechanic and logic
		$gallery_item .= "</div><!-- End of Gallery -->";
		//echo $gallery_item;  
		 $filters = $gallery->get_filters();
		 $D3_BarChart_Array = flattenArray($filters);
		 $colors = $gallery->get_colorfilters();
		 $D3_Wheel_Array= flattenArray($colors);
		 
		 function flattenArray($array){
			  $temparray = array();
			  foreach ($array as $key => $value) {
			      array_push( $temparray , $value);
			     }
			   $array = $temparray;  
			  return $array;
			}
	?>


<?php echo $gallery_item; ?>		


</div>
<hr class="space" />
<div id="filter-bar" class="row">

<script>
	
	var dset = <?php echo json_encode($D3_BarChart_Array); ?>;
	var wheel_dset  = <?php echo json_encode($D3_Wheel_Array); ?>;
	$(document).ready(function() {
	drawBarChartNav("value",dset,"#filterHolder");	
	drawColorWheel( "value" , wheel_dset,"#dataholder",{w:250,h:250,m:10}  );
	
	var $container = $('#album'), $win = $(window);
 	$container.isotope({
   		
  		itemSelector : '.images',
  		animationEngine : 'jquery',
  		masonry : {
  		    columnWidth: 156,
  		    gutterWidth:12
  		},
  		 onLayout: function() {
        $win.trigger("scroll");
    	}
  		  
		});
	$('#filters a').click(function(){
	  var selector = $(this).attr('data-filter');
	  $container.isotope({ filter: selector });
	  
	  return false;
	});
		$('#filterHolder a').click(function(){
	  var selector = $(this).attr('data-filter');
	  $container.isotope({ filter: selector });
	  
	  return false;
	});
	$('#dataholder a').click(function(){
	  var selector = $(this).attr('data-filter');
	  $container.isotope({ filter: selector });
	  
	  return false;
	});	
 
     $imgs = $("img.lazy");
	 $imgs.lazyload({ 
    	effect : "fadeIn",
    	failure_limit: Math.max($imgs.length - 1, 0)
	 });
	});
</script>
	




</div>


	
<?php include('_endofTheme.php' ); ?>
