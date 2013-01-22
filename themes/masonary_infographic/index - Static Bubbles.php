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
				$gallery_item .= '<div class="six columns gallery '.getAnnotatedAlbumTitle().'" style="padding:0 25px">';
				$gallery_item .= '<h2 style="list-style: none;" ><a href="'.getAlbumLinkURL().'" title="View album '.getAnnotatedAlbumTitle().'">'.getAlbumTitle().'</a></h2>';
				$gallery_item .= '<div style="list-style: none;margin-left: -25px" id="dataholder_'.getAnnotatedAlbumTitle().'">&nbsp;</div>';
				$images = "<ul style='list-style: none;'class='thumbnails'>";
				for ($i=1; $i<=10; $i++) {
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
				$gallery_item .= '<p style="display:block; clear:both;margin-bottom: 0;">'.getAlbumCustomData().'<a style="padding: 0 4em;" href="'.getAlbumLinkURL().'">&nbsp; Explore my work on '.getAnnotatedAlbumTitle().'</a></p>';	
				
				$gallery_item .= '<br/><br/></div>';
			endwhile;
			$gallery_item .= '<hr style="clear:both;" /></div>';
			echo $gallery_item;
			?>
<div class="" id="dataholder" style="position:relative" >
<div class="intro six" style="position:absolute">
<h4>Shards of my work scatter across time</h4>
</div>
<p style="position:absolute; bottom: -2px;left: 50px;line-height: 1.25;color: grey;" class="eight"><em>It is interesteding to seem my work plotted into the months they were created over time. This isn't all my work but the work that is represented this repository.</em></p>
</div>
<?php 

// Request to Mysql;
$collection = query_full_array("SELECT zp_albums.folder, zp_images.filename, zp_images.title, zp_images.date, zp_images.albumid FROM zp_images LEFT JOIN zp_albums ON zp_images.albumid=zp_albums.id;");

$D3_Master_Array = array();
$D3_Master_Type_Array = array();
$D3_Grouped_Array = array();


foreach ($collection as $key => $value) {
	
	$datetemp = explode(' ' , $value['date'] );
	$datetemp = explode( '-' , $datetemp[0] );
	$date = ( $datetemp[0] + $datetemp[1]/12 );
  $type = explode( '/' , $value['folder'] );
	$datestamp = $datetemp[0].$datetemp[1].$type[0];
	if( $D3_Master_Array[$datestamp] == NULL ){
		$tempsubarray = array();
    $tempsubarray['type'] = $type[0];
    array_push($D3_Master_Type_Array, $tempsubarray['type']);
		$tempsubarray['r'] = 1;
		$tempsubarray['date'] = $date;
		if($tempsubarray['type'] == "Canvas"){$typeID=1;}
  		elseif($tempsubarray['type'] == "Photos"){$typeID=2;}
  		elseif($tempsubarray['type']=="Screen"){$typeID=3;}
  		elseif($tempsubarrayv['type']=="Paper"){$typeID=4;}
  		else{$typeID=5;}
  		$tempsubarray['typeID'] = $typeID;
  		$D3_Master_Array[$datestamp] = $tempsubarray;
    }else{
	 	$D3_Master_Array[$datestamp]["r"] = $D3_Master_Array[$datestamp]["r"]+1;

	 }	
  if( $D3_Grouped_Array[ $type[0] ] == NULL ){
        $D3_Grouped_Array[ $type[0] ] = array();
    }
  if( $D3_Grouped_Array[ $type[0] ][ $type[1] ] == NULL ){
       $D3_Grouped_Array[ $type[0] ][ $type[1] ] = array();
       $D3_Grouped_Array[ $type[0] ][ $type[1] ][count] = 1;
       $D3_Grouped_Array[ $type[0] ][ $type[1] ][type] =  $type[1];

    }
      $D3_Grouped_Array[ $type[0] ][ $type[1] ][count] =  $D3_Grouped_Array[ $type[0] ][ $type[1] ]['count']  + 1;
  


	# code...
}



$tempcollectionarray = array();
foreach ($D3_Master_Array as $key => $value) {
	array_push($tempcollectionarray, $value);
}
$D3_Master_Array = $tempcollectionarray;
?>	

<style>
  .axis path,.axis line { fill: none; stroke: #000; shape-rendering: crispEdges; }
  .dot {  stroke: #000; }
</style>
<script src="<?php echo $_zp_themeroot ?>/javascripts/d3.v3.min.js"></script>


<script src="<?php echo $_zp_themeroot ?>/javascripts/jflickrfeed/jflickrfeed.min.js" type="text/javascript"></script>
<script src="<?php echo $_zp_themeroot ?>/javascripts/jflickrfeed/cycle/jquery.cycle.all.min.js" type="text/javascript"></script>
<script src="<?php echo $_zp_themeroot ?>/javascripts/jflickrfeed/setup.js" type="text/javascript"></script>
<script>
<? 

foreach( array_unique($D3_Master_Type_Array) as $value ){
   $flat_array= array();
    foreach ($D3_Grouped_Array[$value] as$A_value) {
      array_push($flat_array, $A_value);
     } ;
  echo 'var '.$value.'_d3_data = '.json_encode( $flat_array ).';';
  echo 'drawDonutChart("'.$value.'",'.$value.'_d3_data,"#dataholder_'.$value.'");';


}




?>
var Screen_d3_data = [{"count":49,"type":"Power Point"},
                      {"count":16,"type":"Mobile"},{"count":15,"type":"Flash Anim"},{"count":27,"type":"Email"},{"count":25,"type":"Flash Banners"},{"count":136,"type":"Websites"},{"count":220,"type":"Flash Apps"},{"count":7,"type":"UX and UI"}];


function drawDonutChart(chartID, dataSet, selectString) {
      // chartID => A unique drawing identifier that has no spaces, no "." and no "#" characters.
      // dataSet => Input Data for the chart, itself.
      // selectString => String that allows you to pass in
      //           a D3.selectAll() string.

var  w= 497,h = 234,r = Math.min(w, h) / 2;

var color = d3.scale.category20c(); 

var pie = d3.layout.pie().sort(null).value(function(d){return d.count});

var arc = d3.svg.arc()
    .outerRadius(r - 10)
    .innerRadius(r - 70);

var _svg = d3.select(selectString).append("svg")
    .data([dataSet])
    .attr("class", function(){return "pie"+chartID;})
    .attr("width", w)
    .attr("height", h)
    .append("svg:g")
    .attr("transform", "translate(" + w / 4 + "," + h / 2 + ")");

var _arc = _svg.selectAll("g.slice")
    .data(pie).enter().append("svg:g").attr("class","slice")
  _arc.append("svg:path")
    .attr("fill", function(d,i){return color(d.data.type);})
    .attr("class",function(d){return d.data.type }).attr("d", arc);

 var legend = _svg.selectAll(".legend")
    .data(color.domain())
    .enter().append("g")
    .attr("class", "legend")
    .attr("transform", function(d, i) { return "translate(-90," + i * 20 + ")"; });
 legend.append("rect")
    .attr("x", (w/2)-18)
    .attr("y", -(h/2) )
    .attr("width", 18)
    .attr("height", 18)
    .style("fill", color);

 legend.append("text")
      .attr("x", (w/2) + 4)
      .attr("y", 9 - (h/2) )
      .attr("dy", ".35em")
      .style("text-anchor", "start")
      .text(function(d) { return d; });
     

}
//drawDonutChart('Photos',Screen_d3_data,'#dataholder_Photos');


var margin = {top: 80, right: 250, bottom: 70, left: 20},
    width = 1024 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;
var d3_data = <? echo json_encode($D3_Master_Array); ?>;
var y_domain = <? echo json_encode( $D3_Master_Type_Array ) ?>;
var x = d3.scale.linear().range([0, width],1);
var y = d3.scale.ordinal().rangePoints([0, height],1);

var color = d3.scale.category10();



var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");
var svg = d3.select("#dataholder").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  	.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


x.domain(d3.extent(d3_data, function(d) { return d.date}));
y.domain(y_domain);

var shadedblocks = svg.selectAll(".shadedblocks")
    .data(x.ticks(10))
    .enter().append("g")
    .attr("class", "shadedblocks")
    .attr("transform", function(d,i){return "translate("+x(d)+",0)";});
shadedblocks.append("rect")
    .attr("class", "blocks")
    .attr("height",height)
    .attr("width", function(d,i){ return (x(1)-x(0)); })
    .attr("x", 0 )
    .attr("y", 0 )
    .style("fill", "#000000")
    .style("fill-opacity",function(d,i){ return ( i & 1 ) ? .01 : .1; });
shadedblocks.append("text")
    .attr("class", "toplabel")
    .attr("x", function(d,i){ return (x(1)-x(0))- 5; })
    .attr("y", 10)
    .attr("dy", ".35em")
    .style("text-anchor","end")
    .style("fill", "#999999")
    .text(function(d){return d});
shadedblocks.append("text")
    .attr("class", "bottomlabel")
    .attr("x", function(d,i){ return (x(1)-x(0))- 5; })
    .attr("y",height-10)
    .attr("dy", ".35em")
    .style("text-anchor","end")
    .style("fill", "#999999")
    .text(function(d){return d});


 svg.selectAll("circle")
    .data(d3_data)
 	.enter().append("circle")
  	.attr("class", "dot")
 	.attr("cx",function(d){ return x(d.date); })
  	.attr("cy",function(d){ return y(d.type); })
    .attr("r", function(d,i){ return Math.sqrt(d.r)*4; })
    .style("fill", function(d) { return color(d.type); })
    .style("fill-opacity",0.5)
    .style("stroke-opacity", .25);
  var legend = svg.selectAll(".legend")
    .data(y.domain())
    .enter().append("g")
    .attr("class", "legend")
    .attr("transform", function(d, i) { return "translate("+((x(1)-x(0)))+"," + (y(d) + ( (y(1) - y(0))/2 )) + ")"; });
 legend.append("rect")
    .attr("x", width + 10)
    .attr("width", 5)
    .attr("height", function(d){ return ( y(0)- y(1) )-2;  })
    .style("fill", color);

 legend.append("text")
      .attr("x", width + 19)
      .attr("y", 9)
      .attr("dy", ".35em")
      .style("text-anchor", "start")
      .text(function(d) { return d; });

</script>
<link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/javascripts/jflickrfeed/style.css" type="text/css" media="screen, projection" />  


<script src="http://twitter.com/javascripts/blogger.js" type="text/javascript"></script>
<script src="http://twitter.com/statuses/user_timeline/mattwatier.json?callback=twitterCallback2&count=3" type="text/javascript"></script>
<?php include('_endofTheme.php' ); ?>
