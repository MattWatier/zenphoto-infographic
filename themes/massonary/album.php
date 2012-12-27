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
<div class='container'>

<?php include('_canvas.php' ); ?>
<div id="main" class="span-18 column last">
	<div id="breadcrumb" class="span-18 last"><h4><span><?php printHomeLink('', ' | '); ?><a href="<?php echo html_encode(getGalleryIndexURL());?>" title="<?php echo gettext('Albums Index'); ?>"><?php echo getGalleryTitle();?></a> | <?php printParentBreadcrumb(); ?></span> <?php printAlbumTitle(true);?></h4>
	</div>
	<div id="gallerytitle">
		<h2><?php printAlbumTitle(true);?></h2>
	</div>

		<div id="padbox">
		<div class="span-17 append-bottom last">
		<p><?php printAlbumDesc(true); ?></p></div>
		<?
			$bool=false;
			if(( hasPrevPage() ) || ( hasNextPage() ) ){$bool=true;};
			
			if($bool){echo '<hr class="space"/><div class="span-17 gallerynav last">';};
		 
			 if(hasPrevPage()){
			 	echo '<div class="prev button span-5 append-';
				if( hasNextPage() ){
					echo "1";
				}else{
					echo"12 last";
				};
				echo'"><p class="span-5">';
				printPrevPageLink("Previous Page");
				echo "</p></div>";
				
			 }
		if(hasNextPage()){
		 	echo '<div class="next button span-5  last prepend-';
		 	if( hasPrevPage()   ){
				echo "6";
			}else{
				
				echo"12";
			};
			echo '"><p class="span-5">';
			printNextPageLink("NextPage");
			echo "</p></div>";
			
		 }
		
		
		
	
			$gallery_item = "<div id='album' style='width:1164px'>";
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
			while (next_image($all = true)):
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
			$image_item .= "<div class='images ".getBareAlbumTitle()."' style='display:block;padding:5px;margin-bottom:10px; border:1px #ccc solid;width:".$W."px; height:".$H."px;' >";
			$image_item .= "<a href='".getCustomImageURL(800)."' title='".getBareImageTitle()."' class='fancy ".get_color_class($tags)."'>";
			$image_item .="<img width='".$W."' height='".$H."' class='lazy' src='".$_zp_themeroot."/images/holder.gif' data-original='".getCustomSizedImageMaxSpace( $W, $H)."' /></a></div>";
			
			
			$album_item .= $image_item;	
			endwhile; 
			//end of next_image loop

			
			$gallery_item .= $album_item;
					
		endwhile; //end of next album loop
		
					
		while (next_image($all = true)):
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
			$image_item .= "<div class='images ".getBareAlbumTitle()."' style='display:block;padding:5px;margin-bottom:10px; border:1px #ccc solid;width:".$W."px; height:".$H."px;' >";
			$image_item .= "<a href='".getCustomImageURL(800)."' title='".getBareImageTitle()."' class='fancy ".get_color_class($tags)."'>";
			$image_item .="<img width='".$W."' height='".$H."' class='lazy' src='".$_zp_themeroot."/images/holder.gif' data-original='".getCustomSizedImageMaxSpace( $W, $H)."' /></a></div>";
			
			
			$album_item .= $image_item;	
			
		
		endwhile; //end of next_image loop
			
			
			//end of gallery mechanic and logic
			$gallery_item .= "</div><!-- End of Gallery -->";
			echo $gallery_item;
			
			?>

		


</div><hr class="space" />
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_pinterest_pinit"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4dee9db02e8629a7"></script>
<!-- AddThis Button END -->
<!--Facebook Connected Comments-->
<h1>Facebook Comments</h1>
<fb:comments href="<?php echo 'http://www.'.$_SERVER[HTTP_HOST].getAlbumLinkURL(); ?>" num_posts="5" width="500"></fb:comments>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
<!--  End of Facebook Comments-->

</div>
</div>
	
<?php include('_endofTheme.php' ); ?>
