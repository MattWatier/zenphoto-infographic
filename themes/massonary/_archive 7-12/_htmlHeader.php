
	<!-- Framework CSS -->  
    <link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/blueprint/screen.css" type="text/css" media="screen, projection" />  
    <link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/blueprint/print.css" type="text/css" media="print" />  
    	<!--[if IE]>
    			<link rel="stylesheet" href=<?php echo $_zp_themeroot ?>/"blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->     
  		<!-- Import fancy-type plugin. -->  
    <link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/styles/style.css" type="text/css" media="screen, projection" />  
	<link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/blueprint/screen.css" type="text/css" media="screen, projection" />  
  <script src="<?php echo $_zp_themeroot ?>/js/jquery.event.hover-1.0.js" type="text/javascript"></script>
	<script src="<?php echo $_zp_themeroot ?>/js/menuScript.js" type="text/javascript"></script>
	<script src="<?php echo $_zp_themeroot ?>/js/jquery.lazyload.min.js" type="text/javascript"></script>
		<script src="<?php echo $_zp_themeroot ?>/js/jquery.isotope.min.js" type="text/javascript"></script>
<script type=text/javascript>
	
	
$(document).ready(function() {
 	$('#album').isotope({
   		// options
  		itemSelector : '.images',
  		masonry : {
  		    columnWidth: 156,
  		    gutterWidth:12
  		  }
		});
	$("img.lazy").lazyload({ 
    	effect : "fadeIn"
	 	});
});







</script>
	<!-- End of CSS For Matt's Site-->
	