<hr class="space" />
<div id="credit"class="container"><?php printZenphotoLink(); ?></div>
 <!-- <script src="<?php echo $_zp_themeroot ?>/javascripts/default.js" type="text/javascript"></script>-->
 
  <script src="<?php echo $_zp_themeroot ?>/javascripts/d3.v3.min.js" type="text/javascript"></script>
  <script src="<?php echo $_zp_themeroot ?>/javascripts/graphs.js" type="text/javascript"></script>
  
	<script src="<?php echo $_zp_themeroot ?>/javascripts/jquery.lazyload.min.js" type="text/javascript"></script>
	<script src="<?php echo $_zp_themeroot ?>/javascripts/jquery.isotope.min.js" type="text/javascript"></script>
	<script src="<?php echo $_zp_themeroot ?>/javascripts/fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript"></script>
  <script type="text/javascript">stLight.options({publisher: "204b14bf-1829-43ba-a1b0-4931301a44eb", doNotHash: false, doNotCopy: false, hashAddressBar: true});</script>
<script>
var options={ "publisher": "204b14bf-1829-43ba-a1b0-4931301a44eb", "position": "right", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}, "chicklets": { "items": ["facebook", "twitter", "googleplus", "pinterest", "email", "sharethis"]}};
var st_hover_widget = new sharethis.widgets.hoverbuttons(options);
</script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script type="text/javascript">
 
            var _gaq=[['_setAccount','UA-20288415-1'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
  
  $(document).ready(function() {
  $("a.fancy").fancybox();
});







</script>

<?php 	zp_apply_filter('theme_body_close'); ?>
</body>
</html>