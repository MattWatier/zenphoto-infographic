	<link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/stylesheets/app.css" media="screen, projector" rel="stylesheet" type="text/css" />
	<!--[if IE lt 9]>
	    <link href="/stylesheets/ie.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	
	
    <link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/javascripts/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen, projection" />  
	<script src="<?php echo $_zp_themeroot ?>/javascripts/jquery.event.hover-1.0.js" type="text/javascript"></script>
	<script src="<?php echo $_zp_themeroot ?>/javascripts/menuScript.js" type="text/javascript"></script>
	<script src="<?php echo $_zp_themeroot ?>/javascripts/jquery.lazyload.min.js" type="text/javascript"></script>
	<script src="<?php echo $_zp_themeroot ?>/javascripts/jquery.isotope.min.js" type="text/javascript"></script>
	<script src="<?php echo $_zp_themeroot ?>/javascripts/fancybox/jquery.fancybox-1.3.4.js" type="text/javascript"></script>
	<script src="<?php echo $_zp_themeroot ?>/javascripts/fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript"></script>

		<script type=text/javascript>
	
	$(document).ready(function() {
	var $container = $('#album');
 	$container.isotope({
   		// options
  		itemSelector : '.images',
  		animationEngine : 'jquery',
  		masonry : {
  		    columnWidth: 156,
  		    gutterWidth:12
  		  }
		});
	$('#filters a').click(function(){
	  var selector = $(this).attr('data-filter');
	  $container.isotope({ filter: selector });
	  
	  return false;
	});
	
	$("img.lazy").lazyload({ 
    	effect : "fadeIn"
	 	});
	$("a.fancy").fancybox();
});







</script>
	<!-- End of CSS For Matt's Site-->
	