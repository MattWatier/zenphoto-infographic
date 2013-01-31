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

  <div id="main"  class='row evencolumns' style="padding-top:50px;">
  	<div id="introduction" class="six column" >
  		<h1 style="">Welcome to the Fragments of Me.</h1>
  		<div id="bar_holder"></div> 
      <p> <?php printGalleryDesc(); ?> </p>
      <hr>
      <h4>Shards of my work scatter across time</h4>
      <p><em>It is interesteding to see my work plotted into the months they were created over time. This isn't all my work but the work that is represented this repository.</em></p>


    </div><!-- End of Introduction -->


		<?php 
			$gallery_item = '<div id="galleries" class="ten column" style=" border-left: 1px solid #cccccc; ">';
			while (next_album()):
				$gallery_item .= '<div class="eight column gallery '.getAnnotatedAlbumTitle().'" >';
				$gallery_item .= '<h2><a href="'.getAlbumLinkURL().'" title="View album '.getAnnotatedAlbumTitle().'">'.getAlbumTitle().'&raquo;</a></h2>';
				$gallery_item .= '<div class="d3_chart" id="dataholder_'.getAnnotatedAlbumTitle().'">&nbsp;</div>';
				$images = "<ul class='thumbnails column four'>";
				for ($i=1; $i<=8; $i++) {
					$randomImage = getRandomImagesAlbum( $rootAlbum = getAnnotatedAlbumTitle(),$daily = false);
					$images .= "<li class='thumbnail'><a class='fancy' href='".htmlspecialchars($randomImage->getCustomImage(800))."'>";
					if ($randomImage->getWidth() >= $randomImage->getHeight()) {
					$ih = 30;
					$iw = NULL;
					}else{
					$ih = NULL;
					$iw = 30;
					}
					$images .= "<img Src='".html_encode($randomImage->getCustomImage(NULL, $iw, $ih, 30, 30, NULL, NULL, true))."'/>";
					$images .= "</a></li>";
					//if($i==3){$images .= "</ul><ul style='list-style: none;'class='thumbnails row'>";}
				}
				$images .= "</ul>";
				$gallery_item .= $images;
				$gallery_item .= '<p class="column thirteen">'.getAlbumCustomData().'<a href="'.getAlbumLinkURL().'">&nbsp; Explore my work on '.getAnnotatedAlbumTitle().'</a></p>';	
				$gallery_item .= '</div>';
			endwhile;
			$gallery_item .= '<div style="clear:both;" /></div></div></div>';
			echo $gallery_item;
			?>
<div class="row" id="dataholder" style="position: relative;" >

   </div>
<?php 

// Request to Mysql;
$collection = query_full_array("SELECT zp_albums.folder, zp_images.filename, zp_images.title, zp_images.date, zp_images.albumid FROM zp_images LEFT JOIN zp_albums ON zp_images.albumid=zp_albums.id;");

$D3_Master_Array = array();
$D3_Master_Type_Array = array();
$D3_Grouped_Array = array();
$D3_Grouped_TypeArray =array();
$D3_Master_Array2 = array();
$D3_BarChart_Array = array();

foreach ($collection as $key => $value) {
    $array = array();
    $datetemp = explode(' ' , $value['date'] );
    $datetemp = explode( '-' , $datetemp[0] );
    $date = ( $datetemp[0] + $datetemp[1]/12 );
    $type = explode( '/' , $value['folder'] );
    $datestamp = $datetemp[0].$datetemp[1].$type[0].$type[1];
    $parentdatestamp = $datetemp[0].$datetemp[1].$type[0];
    if( $D3_Master_Array2[$datestamp] == NULL ){
     $tempsubarray = array();
     $tempsubarray['parent'] = $type[0];
     $tempsubarray['type'] = $type[1];
     $tempsubarray['date'] = $date;
     $tempsubarray['r'] = 1;
     $tempsubarray['parentnode'] = false;
     $tempsubarray['type'] = $type[1];
     $D3_Master_Array2[$datestamp] = $tempsubarray; 
     $tempsubarray['parentnode'] = true;
     $tempsubarray['type'] = $type[0];
     $D3_Master_Array2[$parentdatestamp]  = $tempsubarray;      
      }else{
        ++$D3_Master_Array2[$parentdatestamp]['r'];
        ++$D3_Master_Array2[$datestamp]['r'];
      }




}



foreach ($collection as $key => $value) {
	
	$datetemp = explode(' ' , $value['date'] );
	$datetemp = explode( '-' , $datetemp[0] );
	$date = ( $datetemp[0] + $datetemp[1]/12 );
  $type = explode( '/' , $value['folder'] );
  if($D3_Grouped_TypeArray[ $type[0] ] == NULL){$D3_Grouped_TypeArray[ $type[0] ] = array(); array_push($D3_Grouped_TypeArray[ $type[0] ],$type[0]);}
  array_push( $D3_Grouped_TypeArray[ $type[0] ], $type[1] );
	$datestamp = $datetemp[0].$datetemp[1].$type[0];
  $subtypes = array();  
  array_push( $subtypes, $type[0]);
  if($type[1] != NULL){ array_push( $subtypes,$type[1] );  }; 
	if( $D3_Master_Array[$datestamp] == NULL ){
		$tempsubarray = array();
    $tempsubarray['type'] = $type[0];
    array_push($D3_Master_Type_Array, $tempsubarray['type']);
		$tempsubarray['r'] = 1;
    $tempsubarray['date'] = $date;
		$D3_Master_Array[$datestamp] = $tempsubarray;
    }else{
	 	$D3_Master_Array[$datestamp]["r"] = $D3_Master_Array[$datestamp]["r"]+1;

	 }
   
   
   if($D3_Master_Array[$datestamp]['subtype'][$type[1]]==NULL){
      $D3_Master_Array[$datestamp]['subtype'][$type[1]]=array();
      $D3_Master_Array[$datestamp]['subtype'][$type[1]]["type"]= $type[1];
      $D3_Master_Array[$datestamp]['subtype'][$type[1]]["r"]= 1;
      $D3_Master_Array[$datestamp]['subtype'][$type[1]]["date"]= $date;

   }else{
     ++$D3_Master_Array[$datestamp]['subtype'][$type[1]]["r"];
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
  
  if($D3_BarChart_Array[ $type[0] ] == NULL){   
      $D3_BarChart_Array[$type[0]]= array();
      $D3_BarChart_Array[$type[0]]["type"] = $type[0];
      $D3_BarChart_Array[$type[0]]["count"] = 1;
  }else{
      ++ $D3_BarChart_Array[$type[0]]["count"];
  }

	# code...
}




function simpleArray($array){
  $temparray = array();
  foreach ($array as $key => $value) {
      $value = flattenArray( array_unique($value));
       array_push($temparray, $value );
   }
  $array = $temparray;
  return $array;
}


function flattenArray($array){
  $temparray = array();
  foreach ($array as $key => $value) {
     /* if( is_array($value) ){
          $value = flattenArray($value);
      } */
      array_push( $temparray , $value);
     }
   $array = $temparray;  
  return $array;
}

?>	

<style>
  .axis path,.axis line { fill: none; stroke: #000; shape-rendering: crispEdges; }
  .dot {  stroke: #000; }
</style>
<script src="<?php echo $_zp_themeroot ?>/javascripts/d3.v3.min.js"></script>

<script>
<? 

foreach( array_unique($D3_Master_Type_Array) as $value ){
  $flat_array= array();
  foreach ($D3_Grouped_Array[$value] as $A_value) {
      array_push($flat_array, $A_value);
  }
  echo 'var '.$value.'_d3_data = '.json_encode( $flat_array ).';';
  echo 'drawDonutChart("'.$value.'",'.$value.'_d3_data,"#dataholder_'.$value.'");';
}
$D3_Master_Type_Array = flattenArray( array_unique($D3_Master_Type_Array) );
$D3_Grouped_Array = flattenArray( $D3_Grouped_Array  );
$D3_Master_Array = flattenArray( $D3_Master_Array );
$D3_Master_Array2 = flattenArray( $D3_Master_Array2 );
$D3_Grouped_TypeArray = simpleArray($D3_Grouped_TypeArray);
$D3_BarChart_Array = flattenArray($D3_BarChart_Array);
?>

function drawBarChart(chartID, dataSet, selectString){
      // chartID => A unique drawing identifier that has no spaces, no "." and no "#" characters.
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


  var  barChart ={ w: 442, h :300 ,m:20  };
  barChart.height =barChart.h - (2 * barChart.m);
  barChart.width = barChart.w - (2 * barChart.m);
  var color = d3.scale.category20().domain( d3.range(dataSet.length) ); 
  var x = d3.scale.ordinal().domain( d3.range(dataSet.length) ).rangeRoundBands([0,barChart.width],.05); 
  var y = d3.scale.linear().domain( [0,d3.max(dataSet, function(d) { return d.count}) ]).range([0, barChart.height],0);



var bchart_svg = d3.select(selectString).append("svg")
   
    .attr("class", function(){return "bar"+chartID;})
    .attr("width", barChart.w)
    .attr("height", barChart.h)
    .append("g")
    .attr("transform","translate(" + barChart.m + "," + barChart.m + ")");


var bchart = bchart_svg.selectAll(".bar")
    .data(dataSet)
    .enter().append("g")
    .attr("class", "bar");

  bchart.append("svg:a")
    .attr("xlink:href", function(d){return d.type;}) 
    .append("rect")
    .attr("class", function(d){return d.type})
    .attr("height", function(d){return y( d.count ) })
    .attr("width", x.rangeBand())
    .attr("x", function(d,i){ return x( i ) })
    .attr("fill", function(d,i){return color( i )})
    .attr("y", function(d){return   barChart.height  - y( d.count ); });
  bchart.append("text")
    .attr("class", "count")
    .attr("x", function(d,i){ return x( i ) + 10 })
    .attr("width", x.rangeBand())
    .attr("fill", "#ffffff")
    .attr("y", function(d){return   barChart.height  - y( d.count ) + 55; })
    .style("font-size","40px")
    .style("font-wieght", 900)
  .text(function(d){ return d.count; });
  bchart.append("text")
    .attr("class", "label")
    .attr("x", function(d,i){ return x( i ) + 10 })
    .attr("width", x.rangeBand())
    .attr("fill", "#ffffff")
    .attr("y", function(d){return   barChart.height  - y( d.count ) + 20; })
    .style("fontsize","10px")
    .text(function(d){ return d.type; });
}

var dset = <?php echo json_encode($D3_BarChart_Array); ?>;
drawBarChart("value",dset,"#bar_holder");
function drawDonutChart(chartID, dataSet, selectString) {
      // chartID => A unique drawing identifier that has no spaces, no "." and no "#" characters.
      // dataSet => Input Data for the chart, itself.
      // selectString => String that allows you to pass in
      // a D3.selectAll() string.
var  donutChart ={ w: 441,h :234, r: Math.min(497, 234) / 2};
var color = d3.scale.category20c(); 
var pie = d3.layout.pie().sort(null).value(function(d){return d.count});
var arc = d3.svg.arc()
    .outerRadius(donutChart.r - 10)
    .innerRadius(donutChart.r - 70);
var _svg = d3.select(selectString).append("svg")
    .data([dataSet])
    .attr("class", function(){return "pie"+chartID;})
    .attr("width", donutChart.w)
    .attr("height", donutChart.h)
    .append("svg:a")
    .attr("xlink:href", chartID)
    .append("svg:g")
    .attr("transform", "translate(" + donutChart.w / 4 + "," + donutChart.h / 2 + ")");
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
    .attr("x", (donutChart.w/2)-18)
    .attr("y", -(donutChart.h/2) )
    .attr("width", 18)
    .attr("height", 18)
    .style("fill", color);
 legend.append("text")
      .attr("x", (donutChart.w/2) + 4)
      .attr("y", 9 - (donutChart.h/2) )
      .attr("dy", ".35em")
      .style("text-anchor", "start")
      .text(function(d) { return d; });
}
//drawDonutChart('Photos',Screen_d3_data,'#dataholder_Photos');


var margin = {top: 80, right: 250, bottom: 70, left: 20},
    width = 1260 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;
var d3_data = <? echo json_encode($D3_Master_Array2); ?>;
var y_domain = domainArray(d3_data, "type");
var parentArray = domainArray(d3_data, "type");


function domainArray(a , type, included) {
 
  var parentdata   = [];
  var data =[];// ["Photos","Canvas","Page","Screen"];
  for (var i = 0; i < a.length; i++) {
    parentdata.push(a[i]['parent']);
    if( included == a[i]['parent'] || included == "all" ){
       data.push( a[i][type] );
    }
  }
  if( parentdata.indexOf(included) == -1 && included != "all" ){ included = undefined; }
  if(included == undefined){ return unique( parentdata );}
 
  if(included != "all"){ data.push( included );}
  return unique(data);
}

var x = d3.scale.linear().range([0, width],1);
var y = d3.scale.ordinal().rangePoints([0, height],1);

var colorDomain = colorDomain();
var photoColors = ["#4ab000","#fe5e9a","#ffa6c7","#ffd1e3"]
var paperColors =["#b137f0","#56ad16","#9ed976","#3d89ba","#8ecef5"]
var screenColors = ["#3081c2","#ff5e14","#ff883c","#ffbe78","#ffe4c7","#a24bff","#c691ff","#e2c7ff"];
var canvasColors =["#3182bd","#6baed6" ,"#e55600","#5e4000","#6e592a","#94815c"];
var colorType= [];
var colorRange = colorType.concat(photoColors,paperColors,canvasColors,screenColors);
var color = d3.scale.ordinal().domain(colorDomain).range(colorRange); 

function colorDomain(){
  var collection = [];
  var parents = unique( domainArray(d3_data, "parent","all")  );
  
  for (var i = 0; i < parents.length; i++) {
    collection.push(parents[i]);
    var group = [];
    var type = unique(domainArray(d3_data, "type",parents[i]));
    for (var a = 0; a < type.length; a++) {
      if(parent[i] != type[a]){ collection.push( type[a]  ); 
    };}
     
   
  };
  console.log(unique(collection).reverse()); 
  return unique(collection).reverse();

}



var svg = d3.select("#dataholder").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  	.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


x.domain(d3.extent(d3_data, function(d) { return d.date}));
y.domain( y_domain ); 



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


var circles = svg.selectAll(".circles")
    .data(d3_data)
  	.enter().append("g")
   .attr("class", "circles");




  circles.append("circle")
  .attr("class", "category")
 	.attr("cx",function(d){ return x(d.date); })
  	.attr("cy",function(d){ return y(d.type); })
    .attr("data-role",function(d){ return d.type; })
    .attr("r", function(d,i){ return d.parentnode ?   (Math.sqrt( d.r ) * 4) :  0; })
    .style("fill", function(d) { return color(d.type); })
    .style("fill-opacity",0.75)
    .style("stroke-opacity", .25);


createLegend( y_domain );

function createLegend( ydomain ){
  var recthieght = height / Object.keys(ydomain).length; 
  svg.selectAll(".legend").remove();
  var legend = svg.selectAll(".legend")
    .data(ydomain)
    .enter().append("g")
    .attr("class", "legend")
    .attr("transform", function(d, i) { return "translate("+((x(1)-x(0)))+"," + ( recthieght * i )+ ")"; });
legend.append("rect")
    .attr("x", width + 10)
    .attr("width", 5)
    .attr("height", recthieght )
    .style("fill", color);
legend.append("text")
    .attr("x", width + 19)
    .attr("y", 9)
    .attr("dy", ".35em")
    .style("text-anchor", "start")
    .text(function(d) { return d; });
legend.append("rect")
    .attr("class","hitarea")
    .attr("x", width + 10)
    .attr("width",80)
    .attr("height", recthieght)
    .attr("data-role", function(d){return d} )
    .style("cursor","pointer")
    .style("fill-opacity",0)
    .on("click", transitioncircles);


}
function transitioncircles( d ){
    console.log(d);
    y = d3.scale.ordinal();
    y.rangePoints([0, height],1);
    y_domain = domainArray(d3_data, "type", d);
    y.domain( y_domain ); 
    var topLevel = ( parentArray.indexOf(d) != -1)? false : true;
      svg.selectAll(".category")
        .transition().duration(500)
        .attr("cy",function(d){ 
            if( topLevel ){  
              return (d.parentnode)? y(d.type) : y(d.parent) ; 
            }else{
              return y( d.type ); 
            }
          })
        .attr("r",function(d){
            if( topLevel ){  
               return (d.parentnode)? ( Math.sqrt(d.r) * 4 ): 0 ; 
            }else{
               return ( y_domain.indexOf(d.type) != -1  )? (Math.sqrt( d.r ) * 4) : 0;
              }
          })
        .style("fill", function(d){ 
           if (topLevel) {
              return (d.parentnode)? color(d.type) : "#cccccc";
           }else{
              return ( d.parentnode )? "#cccccc" : color(d.type) ;
            }
        })
        .style("fill-opacity", function(d){
            if (topLevel) {
              return(d.parentnode)? 0.75: 0 ; 
            }else{
              return ( y_domain.indexOf(d.type) != -1 )? .75 : 0;
            }
        });
        createLegend( y_domain  );
}


function unique(arr) { 
  var a = []; var l = arr.length; 
  for(var i=0; i<l; i++) { 
    for(var j=i+1; j<l; j++) { 
  // If a[i] is found later in the array 
    if (arr[i] === arr[j]) j = ++i; } 
    a.push(arr[i]); } 
return a; };

 
 
 
</script>
<?php include('_endofTheme.php' ); ?>
