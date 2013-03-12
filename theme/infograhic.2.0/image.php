<?php

// force UTF-8 Ø

if (!defined('WEBPATH')) die(); $themeResult = getTheme($zenCSS, $themeColor, 'light');
?>
<!DOCTYPE html>
<html">
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
<nav id="aContainer"class='span-5 colborder column'><?php printAlbumMenu("list",false,"menuList","galleryName","","","",4,false); ?></nav>

	<div id="main" class="span-18 column last">
	<div id="breadcrumb" class="span-18 last"><h4><a href="<?php echo html_encode(getGalleryIndexURL());?>" title="<?php gettext('Albums Index'); ?>"><?php echo getGalleryTitle();?>
			</a> | <?php printParentBreadcrumb("", " | ", " | "); printAlbumBreadcrumb("", " | "); ?></h4>
	</div>
	<div id="gallerytitle">
		<h2> <?php printImageTitle(true); ?></h2>
	</div>
	


	<!-- The Image -->
	<div id="image span-17">
	<?php	printDefaultSizedImage(getImageTitle());?>
	</div>

	<div id="narrow' class='span-17">
			<?
			$bool=false;
			if(( hasPrevImage() ) || ( hasNextImage() ) ){$bool=true;};
			
			if($bool){echo '<hr class="space"/><div class="span-17 gallerynav last">';};
		 
			 if(hasPrevImage()){
			 	echo '<div class="prev button span-5 append-1';
				echo'"><p class="span-5">';
				?>
				<a href="<?php echo html_encode(getPrevImageURL());?>" title="<?php echo gettext("Previous Image"); ?>">&laquo; <?php echo gettext("prev"); ?></a>
				<?php
				echo "</p></div>";
				
			 }
			 
			echo '<div class="rating span-5 ';
			if(!hasPrevImage() ){echo 'prepend-6 ';}
			if(!hasNextImage() ){echo 'append-6 last ';}else{echo 'append-1 ';}
			echo '">';
			if (function_exists('printRating')) { printRating(); }
			echo "</div>"; 
			 
			if(hasNextImage()){
			 	echo '<div class="next button span-5 last"><p class="span-5">';
				?>
				<a href="<?php echo html_encode(getNextImageURL());?>" title="<?php echo gettext("Next Image"); ?>"><?php echo gettext("next"); ?> &raquo;</a>
				<?php
				echo "</p></div>";
				
			 }
		
		
		
		if($bool){echo'</div>';}
		
		?>
		
		
		
		
		<div class="span-17 last"><?php printImageDesc(true); ?></div>
		<div id="extras" class="span-17">
		<?php if (function_exists('printSlideShowLink')) printSlideShowLink(gettext('View Slideshow')); ?>
		<hr />
		<?php printTags('links', gettext('<strong>Tags:</strong>').' ', 'taglist', ''); ?>
		<hr class="space clearfix" />

		
		<?php if (function_exists('printShutterfly')) {printShutterfly();
				 echo '<hr class="space clearfix" />'; } ?>

<!--Facebook Connected Comments-->
<h1>Facebook Comments</h1>
<? global $_zp_current_image;?>
<fb:like></fb:like>
<fb:comments href="<?php echo html_encode($_SERVER[HTTP_HOST].$_zp_current_image->webpath); ?>" num_posts="5" width="500"></fb:comments>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
<!--  End of Facebook Comments-->
</div>
	</div>
</div>

<?php include('_endofTheme.php' ); ?>
