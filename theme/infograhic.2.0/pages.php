<?php

// force UTF-8 

if (!defined('WEBPATH')) die();
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
    <body>
    <!--[if lt IE 8]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
     <![endif]-->
<?php zp_apply_filter('theme_body_open'); ?>

<div id="main">

	<div id="header">
			<h1><?php printGalleryTitle(); ?></h1>
			<?php if (getOption('Allow_search')) {  printSearchForm("","search","",gettext("Search gallery")); } ?>
		</div>

<div id="content">

	<div id="breadcrumb">
	<h2><a href="<?php echo getGalleryIndexURL(false); ?>"><?php echo gettext("Index"); ?></a><?php if(!isset($ishomepage)) { printZenpageItemsBreadcrumb(" » ",""); } ?><strong><?php if(!isset($ishomepage)) { printPageTitle(" » "); } ?></strong>
	</h2>
	</div>
<div id="content-left">
<h2><?php printPageTitle(); ?></h2>
<?php
printPageContent();
printCodeblock(1);
if(getTags()) { echo gettext('<strong>Tags:</strong>'); } printTags('links', '', 'taglist', ', ');
?>
<br style="clear:both;" /><br />
<div id="dataHolder"></div>
	</div><!-- content left-->

<?php  
	$collection = query_full_array("SELECT a.folder, i.filename, i.date, i.albumid FROM `zp_images` i LEFT JOIN `zp_albums` a ON i.albumid=a.id ORDER BY i.date ASC;");
    $datalist = array();
    $groupNumber = array();
    $init = 0;
    foreach ($collection as $key => $value) {
    	$type = explode('/', $value['folder']);
    	$datetemp = explode(' ' , $value['date'] );
        $datetemp = explode( '-' , $datetemp[0] );
        if(!isset($datetemp[0])){$datetemp[0]="";}
        if(!isset($datetemp[1])){$datetemp[1]="";}
        $date = ( $datetemp[0] + $datetemp[1]/12 );
        $type = (isset( $type[1] ))?  $type[1]  :  $type[0] ;
        $datalist[$init] = array(
        	'id' => $init,
    		'type' => $type,
    		'albumid' => $value['albumid'],
    		'date' => $date,
    		'filename' => $value['filename']
    		);

    	++ $init;
    }
              
     ?>
    <script type="text/javascript">
<?php 

	echo "var singleData =".json_encode($datalist).";";


 ?>
   function RenderStringGraph(chartID, dataset,  selectString){
 		// chartID => A unique drawing identifier that has no spaces, no "." and no "#" characters.
      	// dataSet => Input Data for the chart, itself.
      	// selectString => String that allows you to pass in
      	// a D3.selectAll(selectString) string.
 var by = function(name){
      return function(o,p){
        var a,b;
        if(typeof o === 'object' && typeof p === 'object' && o && p ){
          a = o[name];
          b = p[name];
          if(a === b){
              return 0;
            }
          if( typeof a === typeof b){
            console.log('content test');
            return a < b ? -1 : 1;
          }
            console.log('typeof test');
            return typeof a < typeof b ? -1 : 1;}
          else{
            throw {name:"Error", message:"Expected an object when sorting by "+ name};
        }
      };
   };  


		var dataSetSingle = dataset;
		var dataSetGrouped = dataset.sort(by("type"));
    var dataSetGroupedoutput = [];

// copy items to an array so they can be sorted
for (var key in dataset) {
    dataset[key].key = key;   // save key so you can access it from the array (will modify original data)
    dataSetGroupedoutput.push(dataset[key]);
} 
dataSetGroupedoutput.sort(function(a,b) {
    return(a.albumid - b.albumid);
});
console.log(dataSetGroupedoutput);
   
		var  barChart ={ w: 1000, h :(dataset.length*3) ,m:10  };
			barChart.height =barChart.h - (2 * barChart.m);
			barChart.width = barChart.w - (2 * barChart.m);
		var color = d3.scale.category20().domain( d3.range(dataSetGrouped.length) ); 

		var bchart_svg = d3.select(selectString).append("svg")
			.attr("class", function(){return "string"+chartID;})
			.attr("width", barChart.w)
			.attr("height", barChart.h)
			.append("g")
			.attr("transform","translate(" + barChart.m + "," + barChart.m + ")");
		var singleMarkers = bchart_svg.append("g").attr('class','singleunits').selectAll(".single")
		    .data(dataSetSingle)
		    .enter().append("g")
		    .attr("class", "single");

		  singleMarkers.append("rect")
		    .attr("class", function(d){return "single"+d.id;})
		    .attr("height", "3" )
        .attr("width", "10" )
        .attr("fill", function(d,i){return color( d.albumid )})
        .attr("y", function(d,i){return d.id*5;});


  var groupedMarkers = bchart_svg.append("g").attr('class','grouped').selectAll(".groupedunits")
        .data(dataSetGrouped)
        .enter().append("g")
        .attr("class", "groupedunits").attr("transform","translate(100,0)");

  groupedMarkers.append("rect")
        .attr("class", function(d){return "single"+d.id;})
        .attr("height", "3" )
        .attr("width", "10" )
        .attr("fill", function(d,i){return color( d.albumid )})
        .attr("y", function(d,i){return i*5;});


function flattenArray(obj){
  var arraytemp =[];
   for (key in obj) {
      if ( obj.hasOwnProperty(key) ){  arraytemp.push( obj[key] );}
      }
  return arraytemp;
  }
}
            $(document).ready(function() {

          	RenderStringGraph('date',singleData,'#dataHolder');
            $imgs = $("img.lazy");
            $imgs.lazyload({ 
              effect : "fadeIn",
              failure_limit: Math.max($imgs.length - 1, 0)
              });
              });




            </script> <?php include('_endofTheme.php' ); ?>
        </div>
    </body>
</html>