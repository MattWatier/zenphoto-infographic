<?php

// force UTF-8 

if (!defined('WEBPATH')) die(); $themeResult = getTheme($zenCSS, $themeColor, 'light');
?>
<!DOCTYPE html>
<html>
<head>
	<?php zp_apply_filter('theme_head'); ?>
	<title><?php echo getBareGalleryTitle(); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo getOption('charset'); ?>" />
	<?php printRSSHeaderLink('Gallery',gettext('Gallery RSS')); ?>
	<?php include('_htmlHeader.php' ); ?>
	
</head>
<body>
<?php zp_apply_filter('theme_body_open'); ?>

<?php include('_siteHeaderNav.php' ); ?>


<?php include('_canvas.php' ); ?>
<div class='row'>
	<div id="main" class="">
		<h1>Welcome to the Fragments of Me.</h1>
		<p>
		<?php printGalleryDesc(); ?>
		</p>
		<?php 
			$gallery_item = '<div id="galleries" class="row">';
			while (next_album()):
				$gallery_item .= '<div class="four columns gallery '.getAnnotatedAlbumTitle().'">';
				$gallery_item .= '<h2><a href="'.getAlbumLinkURL().'" title="View album '.getAnnotatedAlbumTitle().'">'.getAlbumTitle().'</a></h2>';
				
				$images = "<ul style='list-style: none; 'class='thumbnails'>";
				for ($i=1; $i<=6; $i++) {
					$randomImage = getRandomImagesAlbum( $rootAlbum = getAnnotatedAlbumTitle(),$daily = false);
					
					$images .= "<li class='' style='float:left; margin:2px;'><a class='fancy' style='display: inline-block;border: 1px solid #ccc;margin:5px auto;padding:2px;' href='".htmlspecialchars($randomImage->getCustomImage(800))."'>";
					if ($randomImage->getWidth() >= $randomImage->getHeight()) {
					$ih = 30;
					$iw = NULL;
					}else{
					$ih = NULL;
					$iw = 30;
					}
					$images .= "<img style='border: 0px none #fff;vertical-align: middle;' src='".html_encode($randomImage->getCustomImage(NULL, $iw, $ih, 30, 30, NULL, NULL, true))."'/>";
					$images .= "</a></li>";
					//if($i==3){$images .= "</ul><ul style='list-style: none;'class='thumbnails row'>";}
				}
				$images .= "</ul>";
				$gallery_item .= $images;
				$gallery_item .= '<p style="display:block; clear:both;">'.getAlbumCustomData().'</p>';	
				$gallery_item .= '<a href="'.getAlbumLinkURL().'">Explore my work on '.getAnnotatedAlbumTitle().'</a>';
				$gallery_item .= '<br/><br/></div>';
			endwhile;
			$gallery_item .= '<hr style="clear:both;" /></div>';
			echo $gallery_item;
		
			?>
	





<div class="canvas row">

<div id="TwitterFeed"  class="columns four">
<h2><a href="http://twitter.com/#!/mattwatier">@MattWatier</a></h2>
<div id="twitter_t"></div>
<div id="twitter_m">
   <div id="twitter_container">
       <ul id="twitter_update_list"></ul>
   </div>
</div>
<div id="twitter_b">


</div>



</div>
<div id="newImage" class="columns four">
	<h2>Photos from Flicker</h2>
	<ul id="cycle" class="row"></ul>
</div>


</div>

<script src="<?php echo $_zp_themeroot ?>/javascripts/jflickrfeed/jflickrfeed.min.js" type="text/javascript"></script>
<script src="<?php echo $_zp_themeroot ?>/javascripts/jflickrfeed/cycle/jquery.cycle.all.min.js" type="text/javascript"></script>
<script src="<?php echo $_zp_themeroot ?>/javascripts/jflickrfeed/setup.js" type="text/javascript"></script>

<link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/javascripts/jflickrfeed/style.css" type="text/css" media="screen, projection" />  


<script src="http://twitter.com/javascripts/blogger.js" type="text/javascript"></script>
<script src="http://twitter.com/statuses/user_timeline/mattwatier.json?callback=twitterCallback2&count=3" type="text/javascript"></script>
<?php include('_endofTheme.php' ); ?>
