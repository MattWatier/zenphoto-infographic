<?php

// force UTF-8 Ø

if (!defined('WEBPATH')) die(); $themeResult = getTheme($zenCSS, $themeColor, 'light');
include_once "masonFunctions.php";
?>
<!DOCTYPE html>
<html>
<head>
	<?php zp_apply_filter('theme_head'); ?> 
    <title><?php echo getBareGalleryTitle(); ?> | <?php echo getBareAlbumTitle();?> | <?php echo getBareImageTitle();?></title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo getOption('charset'); ?>" />
	<?php printRSSHeaderLink('Gallery',gettext('Gallery RSS')); ?>
	<?php include('_htmlHeader.php' ); ?>
			<script type="text/javascript">
		// <!-- <![CDATA[
		$(document).ready(function(){
			$(".colorbox").colorbox({inline:true, href:"#imagemetadata"});
			$("a.thickbox").colorbox({maxWidth:"98%", maxHeight:"98%"});
		});
		// ]]> -->
	</script>
</head>
<body>
<?php zp_apply_filter('theme_body_open'); ?>
<?php include('_siteHeaderNav.php' ); ?>	
<?php include('_canvas.php' ); ?>

<div id="main" class="row">
	<div id="breadcrumb">
		<h4><span><?php printHomeLink('', ' | '); ?><a href="<?php echo html_encode(getGalleryIndexURL());?>" title="<?php echo gettext('Albums Index'); ?>"><?php echo getGalleryTitle();?></a> | <?php printParentBreadcrumb(); ?></span> <?php printAlbumTitle(true);?>
		</h4>
	</div>
<div id="gallerytitle" class="row">
	<h2><?php printAlbumTitle(true);?></h2>
</div>

<div class="row">
	<p><?php printAlbumDesc(true); ?></p>
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
			array_push($array, getBareAlbumTitle());
			$space_separated_array = implode("_", $tags);
			$space_separated_array = str_replace(" ", "-", $space_separated_array);
			$space_separated_array = str_replace("_", " ", $space_separated_array);
			$gallery->add_to_filter($tags);
			$image_item .= "<div class='images ".getBareAlbumTitle()." ".$space_separated_array."' style='display:block;padding:5px;margin-bottom:12px; border:1px #ccc solid;width:".($W+5)."px; height:".($H+5)."px;' >";
			$image_item .= "<a href='".getCustomImageURL(800)."' title='".getBareImageTitle()."' class='fancy'>";
			$image_item .="<img width='".$W."' height='".$H."' class='lazy' src='".$_zp_themeroot."/images/holder.gif' data-original='".getCustomSizedImageMaxSpace( $W, $H)."' /></a></div>";
			$album_item .= $image_item;	
			endwhile; 
			//end of next_image loop
			$gallery_item .= $album_item;
		endwhile; //end of next album loop
		
					
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
			array_push($array, getBareAlbumTitle());
			$gallery->add_to_filter($tags);
			$space_separated_array = implode("_", $tags);
			$space_separated_array = str_replace(" ", "-", $space_separated_array);
			$space_separated_array = str_replace("_", " ", $space_separated_array);
			$image_item .= "<div class='images ".getBareAlbumTitle()." ".$space_separated_array."' style='display:block;padding:5px;margin-bottom:12px; border:1px #ccc solid; width:".($W+5)."px; height:".($H+5)."px;' >";
			$image_item .= "<a href='".getCustomImageURL(800)."' title='".getBareImageTitle()."' class='fancy'>";
			$image_item .="<img width='".$W."' height='".$H."' class='lazy' src='".$_zp_themeroot."/images/holder.gif' data-original='".getCustomSizedImageMaxSpace( $W, $H)."' /></a></div>";
			
			
			$album_item .= $image_item;	
			
		
		endwhile; //end of next_image loop
		//end of gallery mechanic and logic
		$gallery_item .= "</div><!-- End of Gallery -->";
		//echo $gallery_item;  
	?>
<div id="filter-bar">
<div class="row">
<strong>Gallery Filter</strong>
<ul id="filters" class="button-group radius">
<li><a href="#" class='button radius small' data-filter="*">All</a></li>
<?php $filters = $gallery->get_filters();
$html ="";
foreach ($filters as $key => $value) {
	$html .= "<li><a class='button radius small' data-filter='.";
	$html .= str_replace(" ", "-", $value["value"]);
	$html .= "' href='#'>";
	$html .= $value["value"];
	$html .= "</a></li>";
}
echo $html;

 ?>
</ul>
</div>
<?php echo $gallery_item; ?>
</div>

		


</div><hr class="space" />

<h1>Facebook Comments</h1>
<fb:comments href="<?php echo 'http://www.'.$_SERVER[HTTP_HOST].getAlbumLinkURL(); ?>" num_posts="5" width="500"></fb:comments>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
<!--  End of Facebook Comments-->

</div>
</div>

	
<?php include('_endofTheme.php' ); ?>
