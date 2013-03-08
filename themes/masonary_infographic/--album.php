<?php

// force UTF-8 Ø

if (!defined('WEBPATH')) die(); $themeResult = getTheme($zenCSS, $themeColor, 'light');
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
<nav id="aContainer"class='span-5 colborder column'><?php printAlbumMenu("list",false,"menuList","galleryName","","","",4,false); ?></nav>
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
		
		
		
		if($bool){echo'</div>';}
		
		?>
			<div id="albums span-17 clear">
		<?php
			$i=0;
			while (next_album()):
			$i++;	
			echo '<div class="album span-5 append-bottom';
			$divid = $i/3;
			if(ceil($divid)==$divid){
				echo " last";
			}
			else{
				echo" append-1";
			}
			echo'">';
			?>

						<div class="thumb">
					<a href="<?php echo html_encode(getAlbumLinkURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo getAnnotatedAlbumTitle();?>"><?php printAlbumThumbImage(getAnnotatedAlbumTitle()); ?></a>
						</div>
				<div class="albumdesc">
					<h3 class="span-3"><a href="<?php echo html_encode(getAlbumLinkURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo getAnnotatedAlbumTitle();?>"><?php printAlbumTitle(); ?></a></h3>
					<small class="span-2 last"><?php printAlbumDate(""); ?></small>
					<hr class="space" />
				</div>
				
			</div>
			<?php if(ceil($divid)==$divid){echo "<hr class='space'>";};?>
			
			<?php endwhile; ?>
		</div>

			<div id="images span-17">
							<?php
			
			while (next_image()):
			$i++;	
			echo '<div class="image span-5 append-bottom';
			$divid = $i/3;
			if(ceil($divid)==$divid){
				echo " last";
			}
			else{
				echo" append-1";
			}
			echo'">';
			?>
				<div class="imagethumb"><a href="<?php echo html_encode(getImageLinkURL());?>" title="<?php echo getBareImageTitle();?>"><?php printImageThumb(getAnnotatedImageTitle()); ?></a></div>
			</div>
			<?php if(ceil($divid)==$divid){echo "<hr class='space'>";};?>
			<?php endwhile; ?>

		</div>

	<?
	

		if((hasPrevPage())||(hasNextPage())){$bool=true;}
		 if($bool){echo '<hr class="space"/><div class="span-17 gallerynav last">';}
		 
		 if(hasPrevPage()){
		 	echo '<div class="prev button span-5 append-bottom append-';
			if( !( hasNextPage()  )   ){
				echo "12 last";
			}else{
				echo"1";
			};
			echo '"><p class="span-5">';
			printPrevPageLink("Previous Page");
			echo "</p></div>";
			
		 }
		if(hasNextPage()){
		 	echo '<div class=" next button span-5 append-bottom  last prepend-';
		 	if( hasPrevPage()   ){
				echo "6";
			}else{
				echo"12";
			};
			echo '"><p class="span-5">';
			printNextPageLink("Next Page");
			echo "</p></div>";
			
		 }
		 if($bool){echo'</div><hr class="space clear">';}
		
		?>

</div><hr class="space" />
<!--Facebook Connected Comments-->
<h1>Facebook Comments</h1>

<fb:like></fb:like>
<fb:comments href="<?php echo 'http://www.'.$_SERVER[HTTP_HOST].getAlbumLinkURL(); ?>" num_posts="5" width="500"></fb:comments>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
<!--  End of Facebook Comments-->

</div>
</div>
	
<?php include('_endofTheme.php' ); ?>
