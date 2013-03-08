<?php if (!defined('WEBPATH')) die(); $themeResult = getTheme($zenCSS, $themeColor, 'light');?>
<!DOCTYPE html>
<html>
<head>
	<?php zenJavascript(); ?>
	<title><?php echo getBareGalleryTitle(); ?></title>
	
	  <!-- Framework CSS -->  
     <link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/blueprint/screen.css" type="text/css" media="screen, projection" />  
     <link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/blueprint/print.css" type="text/css" media="print" />  
     <!--[if IE]><link rel="stylesheet" href=<?php echo $_zp_themeroot ?>/"blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->     
  <!-- Import fancy-type plugin. -->  
     <link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/styles/style.css" type="text/css" media="screen, projection" />  
	  <link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/blueprint/screen.css" type="text/css" media="screen, projection" />  
    
	<?php
	printRSSHeaderLink('Gallery','Gallery RSS');
	setOption('thumb_crop_width', 85, false);
	setOption('thumb_crop_height', 85, false);
	$archivepageURL = htmlspecialchars(getGalleryIndexURL());
	?>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript"></script>

	<script src="<?php echo $_zp_themeroot ?>/js/menuScript.js" type="text/javascript"></script>
	
</head>

<body>
<div class='container'>
<?php include('_siteHeaderNav.php' ); ?>
<div id='canvas' class="span-24 last prepend-top prepend-bottom">	</div>
	<hr />
<nav id="aContainer"class='span-6 colborder column'><?php printAlbumMenu("list",false,"menuList","galleryName","","","",4,false); ?></nav>

	<div id="main" class="span-17 column last">

		<div class="module">
				<h2>Fragments</h2>
				<?php printGalleryDesc(); ?>
			</div>
		<div id="albums">
			<?php 
			$count = 0;
			while (next_album()):
			$count++;
			?>
			<div class="album <?php if($count==3){;$count=0;}?> ">
						<div class="thumb span-6">
					<a href="<?php echo htmlspecialchars(getAlbumLinkURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo getAnnotatedAlbumTitle();?>"><?php printAlbumThumbImage(getAnnotatedAlbumTitle()); ?></a>
 						 </div>
						<div class="albumdesc span-11 last">
					<h3 class="span-7"><a href="<?php echo htmlspecialchars(getAlbumLinkURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo getAnnotatedAlbumTitle();?>"><?php printAlbumTitle(); ?></a>
							</h3>
 					<?php
						$anumber = getNumSubalbums();
						$inumber = getNumImages();
						if ($anumber > 0 || $inumber > 0) {
							echo '<p class="span-4 last quiet"><em>(';
							if ($anumber == 0 && $inumber == 1) {
								printf(gettext('1 photo'));
							} else if ($anumber == 0 && $inumber > 1) {
								printf(gettext('%u photos'), $inumber);
							} else if ($anumber == 1 && $inumber == 1) {
								printf(gettext('1 album,&nbsp;1 photo'));
							} else if ($anumber > 1 && $inumber == 1) {
								printf(gettext('%u album,&nbsp;1 photo'), $anumber);
							} else if ($anumber > 1 && $inumber > 1) {
								printf(gettext('%1$u album,&nbsp;%2$u photos'), $anumber, $inumber);
							} else if ($anumber == 1 && $inumber == 0) {
								printf(gettext('1 album'));
							} else if ($anumber > 1 && $inumber == 0) {
								printf(gettext('%u album'),$anumber);
							} else if ($anumber == 1 && $inumber > 1) {
								printf(gettext('1 album,&nbsp;%u photos'), $inumber);
							}
							echo ')</em></p>';
						}
						?>
					<p><?php printAlbumDesc(); ?></p>
				</div>
				<p style="clear: both; "></p>
			</div>
			<?php endwhile; ?>
		</div>
	

	



</div>
<div class="canvas evencolumns append-bottom span-24">
<div id="randomImages" class="span-6 column colborder">
<h2 class="span-8 last">Random Fragments</h2>
<?php 

							for ($i=1; $i<=9; $i++) {
								echo "<div class='thumbs span-2"." ".$i;
								if(($i==3) || ($i==9) || ($i==6)){echo " last";}
								echo "'>";
								$randomImage = getRandomImages();
								if (is_object($randomImage)) {
									$randomImageURL = htmlspecialchars(getURL($randomImage));
									if ($randomImage->getWidth() >= $randomImage->getHeight()) {
										$iw = 44;
										$ih = 44;
										$cw = 44;
										$ch = 33;
									} else {
										$iw = 44;
										$ih = 44;
										$ch = 44;
										$cw = 33;
									}
									echo '<a class="span-2" href="' . $randomImageURL . '" title="'.gettext("View image:").' ' . html_encode($randomImage->getTitle()) . '">' .
 												'<img src="' . htmlspecialchars($randomImage->getCustomImage(NULL, $iw, $ih, $cw, $ch, NULL, NULL, true)) .
												'" alt="'.html_encode($randomImage->getTitle()).'"';
									echo "/></a></div>";
								}
							}


?>
</div>
<div id="TwitterFeed"  class="span-7 colborder column">
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
<div id="newImage" class="span-8 column last">
	<h2>New Fragments</h2>
	<?php

			$images = getImageStatistic(12, "latest");
			//print_r($images);
			$c = 0;
			foreach ($images as $image) {
				if (is_valid_image($image->filename)) {
					if ($c++ < 3) {
						echo "<div class=span-8>";
						$imageURL = html_encode(getURL($image));
						if ($image->getWidth() >= $image->getHeight()) {
							$iw = 44;
							$ih = 44;
							$cw = 44;
							$ch = 33;
						} else {
							$iw = 44;
							$ih = 44;
							$ch = 44;
							$cw = 33;
						}
						echo '<a class="span-2" href="'.$imageURL.'" title="'.gettext("View image:").' '.
						html_encode($image->getTitle()) . '"><img src="' .
						html_encode($image->getCustomImage(NULL, $iw, $ih, $cw, $ch, NULL, NULL, true)) .
													'" alt="' . html_encode($image->getTitle()) . "\"/></a>";
						echo '<div class="span-6 last">';
						echo '<h5 class="bottom"><a href="'.$imageURL.'" title="'.gettext("View image:").' '.
						html_encode($image->getTitle()) . '">"' .$image->displayname . '</a></h5>';
						echo"<p>";
						$albumnURL = '/'.$image->album->name.'/';
						echo '<a href="'.$albumURL.'" title="the albumn of '.$image->displayname.'">"'.$image->album->name.'</a>';
						echo '</div>';
						
						
						
						echo"</p>"; 
						echo "</div>";
					 
					 }
					}
				}
								
	
	?>
</div>


</div>
	
<div>
	hello
	</div>


<div id="credit" class="span-24 last"><?php printRSSLink('Gallery','','RSS', ' | '); ?> <?php printCustomPageURL(gettext("Archive View"),"archive"); ?> | 
<?php printZenphotoLink(); ?><?php if (function_exists('printAdminToolbox')) printAdminToolbox(); ?>
</div>




<script src="http://twitter.com/javascripts/blogger.js" type="text/javascript"></script>
<script src="http://twitter.com/statuses/user_timeline/mattwatier.json?callback=twitterCallback2&count=3" type="text/javascript"></script>

</body>
</html>
