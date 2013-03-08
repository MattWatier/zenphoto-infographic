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
	<?php include('_htmlHeader.php' ); ?>	

</head>
<body id="albumbody">

<?php zp_apply_filter('theme_body_open'); ?>
<?php include('_siteHeaderNav.php' ); ?>	

<div id="filterHolder"></div>
<div id="main" class="row" style="padding-top:50px;">
	<div id="breadcrumb" class="column ten">
		<h1 style=" font-family: 'SansationLight', 'trebuchet MS', Arial, sans-serif; font-weight: 300; letter-spacing: 1px; font-size: 36px;"><span><?php printHomeLink('', ' | '); ?><a href="<?php echo html_encode(getGalleryIndexURL());?>" title="<?php echo gettext('Albums Index'); ?>"><?php echo getGalleryTitle();?></a> | <?php printParentBreadcrumb(); ?></span> <?php printAlbumTitle(true);?></h1>
		<p><?php printAlbumDesc(true); ?></p>
	</div>
	<div id="dataholder" class="column six">
		<h4 style=" font-family: 'SansationLight', 'trebuchet MS', Arial, sans-serif; font-weight: 300; " >Gallery Colors</h4>

	</div>

</div>
<div class="row">
	
	<?php
	$gallery = new MyGallery(getBareAlbumTitle());
	$gallery_item = "<div id='album' class='rows'>";
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
			while (next_image($all = TRUE)):
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
			array_push($tags, getBareAlbumTitle());
			$space_separated_array = implode("_", array_unique($tags));
			$space_separated_array = str_replace(" ", "-", $space_separated_array);
			$space_separated_array = str_replace("_", " ", $space_separated_array);
			$gallery->add_to_filter(array_unique($tags));
			$image_item .= "<div class='images ".getBareAlbumTitle()." ".$space_separated_array."' style='display:block;padding:5px;margin-bottom:12px; border:1px #ccc solid;width:".($W+10)."px; height:".($H+10)."px;' >";
			$image_item .= "<a href='".getCustomImageURL(800)."' title='".getBareImageTitle()."' class='fancy'>";
			$image_item .="<img width='".$W."' height='".$H."' class='lazy' src='".$_zp_themeroot."/images/holder.gif' data-original='".getCustomSizedImageMaxSpace( $W, $H)."' /></a></div>";
			$album_item .= $image_item;	
			endwhile; 
			//end of next_image loop
			$gallery_item .= $album_item;
		endwhile; //end of next album loop
		
					
		while (next_image($all = TRUE)):
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
			array_push($tags, getBareAlbumTitle());
			$gallery->add_to_filter(array_unique($tags));
			$space_separated_array = implode("_", array_unique($tags));
			$space_separated_array = str_replace(" ", "-", $space_separated_array);
			$space_separated_array = str_replace("_", " ", $space_separated_array);
			$image_item .= "<div class='images ".getBareAlbumTitle()." ".$space_separated_array."' style='display:block;padding:5px;margin-bottom:12px; border:1px #ccc solid; width:".($W+10)."px; height:".($H+10)."px;' >";
			$image_item .= "<a href='".getCustomImageURL(800)."' title='".getBareImageTitle()."' class='fancy'>";
			$image_item .="<img width='".$W."' height='".$H."' class='lazy' src='".$_zp_themeroot."/images/holder.gif' data-original='".getCustomSizedImageMaxSpace( $W, $H)."' /></a></div>";
			
			
			$album_item .= $image_item;	
			
		
		endwhile; //end of next_image loop
		//end of gallery mechanic and logic
		$gallery_item .= "</div><!-- End of Gallery -->";
		//echo $gallery_item;  
		 $filters = $gallery->get_filters();
		 $D3_BarChart_Array = flattenArray($filters);
		 $colors = $gallery->get_colorfilters();
		 $D3_Wheel_Array= flattenArray($colors);
		 
		 function flattenArray($array){
			  $temparray = array();
			  foreach ($array as $key => $value) {
			      array_push( $temparray , $value);
			     }
			   $array = $temparray;  
			  return $array;
			}
	?>


<?php echo $gallery_item; ?>		


</div>
<hr class="space" />
<div id="filter-bar" class="row">
	<script src="<?php echo $_zp_themeroot ?>/javascripts/d3.v3.min.js"></script>
	<script>
	
	var dset = <?php echo json_encode($D3_BarChart_Array); ?>;
	drawBarChart("value",dset,"#filterHolder");	
	function drawBarChart(selectData, dataSet, selectString){
      // selectData => A unique drawing identifier that has no spaces, no "." and no "#" characters.
      // dataSet => Input Data for the chart, itself.
      // selectString => String that allows you to pass in
      // a D3.selectAll() string.

      function domainArray(a , type) {
        var data =[];// ["Photos","Canvas","Page","Screen"];
        for (var i = 0; i < a.length; i++) {
          data.push( a[i][type] );
         
        }
        console.log(data);
        return unique(data);
      }


  var  barChart ={ w: 250, h :600 ,m:20  };
  barChart.height =barChart.h - (2 * barChart.m);
  barChart.width = barChart.w - (2 * barChart.m);
  var color = d3.scale.category20().domain( d3.range(dataSet.length) ); 
  var y = d3.scale.ordinal().domain( d3.range(dataSet.length) ).rangeRoundBands([0,barChart.height],.05); 
  var x = d3.scale.linear().domain( [0,d3.max(dataSet, function(d) { return d.count}) ]).range([0, (barChart.width- (barChart.m * 2)) ],0);
  


var bchart_svg = d3.select(selectString).append("svg")
   
    .attr("class", function(){return "bar"+selectData;})
    .attr("width", barChart.w)
    .attr("height", barChart.h)
    .append("g")
    .attr("transform","translate(" + barChart.m + "," + barChart.m * 2 + ")");


var bchart = bchart_svg.selectAll(".bar")
    .data(dataSet)
    .enter().append("g")
    .attr("class", "bar")
    .attr("transform",function(d,i){return "translate(0," +y(i)+")" } )
    .on("mouseover",function(){
    	d3.select(this).select(".count").attr("fill", "#000000");
    	d3.select(this).select(".datalabel").attr("fill", "#000000");
    	d3.select(this).select(".bar").attr("opacity", "1");

    })
    .on("mouseout",function(){
    	d3.select(this).select(".count").attr("fill", "#888888");
    	d3.select(this).select(".datalabel").attr("fill", "#888888");
    	d3.select(this).select(".bar").attr("opacity", ".75");

    });
 bchart.append("rect")  
    .attr("id", function(d){return d.classtype})
    .attr("class", "bar")
    .attr("height", y.rangeBand() )
    .attr("width", function(d){return x( d.count ) })
    .attr("y", "0")
    .attr("fill", function(d,i){return color( i )})
    .attr("x", "0")
    .attr("opacity", ".75");
  bchart.append("text")
    .attr("class", "count")
    .attr("y", "18")
    .attr("x", function(d){return  x( d.count ) + 4; })
    .attr("fill", "#888888")
    .style("font-size","20px")
    .style("font-wieght", 900)
    .style("font-family", "'SansationBold', 'trebuchet MS', Arial, sans-serif")
  	.text(function(d){ return d.count; });
  bchart.append("text")
    .attr("class", "datalabel")
    .attr("y", "28")
    .attr("fill", "#888888")
    .attr("x", function(d){return   x( d.count ) + 6; })
    .style("font-size","9px")
    .text(function(d){ return d.type; });
  bchart.append("svg:a")
    .attr("xlink:href", "#") 
    .attr("data-filter", function(d){return "."+d.classtype;})
    .append("rect")
    .attr("height", y.rangeBand() )
    .attr("width", barChart.width )
    .attr("y", "0")
    .attr("opacity", "0")
    .attr("x", "0");

}


var wheel_dset  = <?php echo json_encode($D3_Wheel_Array); ?>;
drawColorWheel( "value" , wheel_dset,"#dataholder",{w:250,h:250,m:10}  )
function drawColorWheel( selectData , dataSet, selectString,dimensions){
      // selectData => A unique drawing identifier that has no spaces, no "." and no "#" characters.
      // dataSet => Input Data for the chart, itself.
      // selectString => String that allows you to pass in
      // a D3.selectAll() string.
      
  var  wheel = dimensions;
  wheel.height =wheel.h;
  wheel.width = wheel.w;
  wheel.radius = Math.min(wheel.height , wheel.width)/2;
  var color = d3.scale.ordinal();
  	color.domain( ["_color-black","_color-white","_color-pink","_color-red","_color-orange","_color-brown","_color-yellow", "_color-green","_color-blue","_color-purple"                ]); 
 	color.range( ["#222222","#dfdfdf","#EF3368","#EB1313","#E75516","#5D3A18" ,"#FDE93A" ,"#5DD245","#448BD2", "#6E4ACB"                           ]);
 var pie = d3.layout.pie().sort(null).value(function(d){return d.count});
var arc = d3.svg.arc()
    .outerRadius(wheel.radius - 10)
    .innerRadius(50);
var _svg = d3.select(selectString).append("svg")
    .data([dataSet])
    .attr("class", function(){return "pie"+selectData;})
    .attr("width", wheel.width)
    .attr("height", wheel.height)
    .append("svg:g")
    .attr("transform", "translate(" + (wheel.width /2) + "," + (wheel.height / 2) + ")");
 var _arc = _svg.selectAll("g.slice")
    .data(pie).enter().append("svg:g").attr("class","slice");
	_arc.append("svg:a").attr("xlink:href", "#").attr("data-filter",function(d){return "."+d.data.classtype})
	.append("svg:path")
    .attr("fill", function(d,i){return color(d.data.type);})
    .attr("class",function(d){return d.data.type }).attr("d", arc)
    .attr("stroke", "#ffffff")
    .attr("stroke-width","1")
    .attr("opacity","0.5")
    .on("mouseover",function(){ d3.select(this).attr( "opacity", "1" ).attr("stroke-width","1"); })
    .on("mouseout",function(){ d3.select(this).attr( "opacity", ".5" ).attr("stroke-width","1"); });   

	
}		
		
		
		
	</script>
	




</div>


	
<?php include('_endofTheme.php' ); ?>
