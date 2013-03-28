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
        $date = ( $datetemp[0]);
        $childType = (isset( $type[1] ))?  $type[1]  :  $type[0] ;
        $datalist[$init] = array(
      	'id' => $init,
        'parent' => $type[0],
    		'type' => $childType,
    		'albumid' => $value['albumid'],
    		'date' => $date,
    		'filename' => $value['filename']
  		);

    	++ $init;
    }
              
     ?>
       <script src="<?php echo $_zp_themeroot ?>/javascripts/d3.v3.min.js" type="text/javascript"></script>
    <script type="text/javascript">
<?php echo "var singleData =".json_encode($datalist).";"; ?>
 /* function RenderStringGraph(chartID, dataset,  selectString){*/
    // chartID => A unique drawing identifier that has no spaces, no "." and no "#" characters.
    // dataSet => Input Data for the chart, itself.
    // selectString => String that allows you to pass in
    // a D3.selectAll(selectString) string.
    // RenderStringGraph('date',singleData,'#dataHolder');
    var chartID='date', dataset = singleData,  selectString='#dataHolder';
    var by = function(name,minor){
        return function(o,p){
          var a,b;
          if(typeof o === 'object' && typeof p === 'object' && o && p ){
            a = o[name];
            b = p[name];
            if(a === b){
                return typeof minor === 'function'? minor(o,p) : 0 ;
              }
            if( typeof a === typeof b){
              return a < b ? -1 : 1;
            }
              return typeof a < typeof b ? -1 : 1;}
            else{
              throw {name:"Error", message:"Expected an object when sorting by "+ name};
          }
        };
     };  


		var dataSetSingle = dataset.sort(by("date",by('type')));
    dataSetSingle = groupingLikeItems( dataSetSingle );
     dataSetSingle = dataSetSingle.sort(by("date",by('parent',by('type'))));
		 //var dataSetGrouped =dataSetSingle;
    var dataSetGrouped = groupingLikeItems( dataset );
    dataSetGrouped = dataSetGrouped.sort(by("parent",by("type",by("date"))));

function groupingLikeItems(a){

 var arraytemp = [] , compareIndex = 0; index = 0;
   a[0].amp=1;
  for(i=1; i<a.length; i++) {
      if ( a[compareIndex].date == a[i].date && a[compareIndex].albumid == a[i].albumid ){  
        ++a[compareIndex].amp;
      }else{
        arraytemp.push( a[compareIndex] );
        compareIndex = i;
        a[i].amp= 1;
        a[i].index = index;
        ++index;
      }
  }
 
  return arraytemp;
}
function listTypes(a){
  var collection = [];
  for (var i = 0; i < a.length; i++) {
    collection.push(a[i].type);
  };
  return unique(collection).reverse();
}

function unique(arr) { 
  var a = []; var l = arr.length; 
  for(var i=0; i<l; i++) { 
    for(var j=i+1; j<l; j++) { 
      // If a[i] is found later in the array 
        if (arr[i] === arr[j]){ j = ++i;} 
      } 
    a.push(arr[i]); } 
  return a; }


var stringChart ={ w: 1000, h :(1200) ,m:200  };
	stringChart.height =stringChart.h - (2 * stringChart.m);
	stringChart.width = stringChart.w - (2 * stringChart.m);
var colorDomain = listTypes(dataSetSingle);
var photoColors = ["#4C8E33","#769D67","#86C270","#B3E5A0"];
var paperColors =["#28516A","#4F6775","#577B90","#95BAD0"];
var screenColors = ["#CC997F","#B38B77","#A96D4E","#F4CEBB","#D46831","#BA673D","#AF4F1E","#F6A982"];
var canvasColors =["#CA677C","#AA707C" ,"#AF2E4A","#F45476","#ECBBC6"];
var colorList = ["#4C8E33", "#769D67",  "#86C270",  "#B3E5A0",  "#28516A",  "#861006",  "#577B90",  "#95BAD0",  "#2c92c9",        "#B38B77",     "#A96D4E",       "#F4CEBB",  "#D46831",  "#BA673D",    "#AF4F1E",    "#F6A982",  "#CA677C",        "#AA707C" ,   "#AF2E4A",  "#F45476",    "#ECBBC6"];
var colorType= ["Travel",   "Nature",   "People",   "Food",     "Branding", "Books",    "Pages",    "Packages", "Info Graphics",  "UX and UI",    "Power Point",  "Websites", "Mobile",   "Flash Anim", "Flash Apps", "Email",    "Flash Banners",  "Creatures",   "Surreal", "SketchBook", "Fairies"];
var colorRange = colorType.concat(photoColors,paperColors,screenColors,canvasColors);
var color = d3.scale.ordinal().domain(colorType).range(colorList); 
var singledisplacement = 0 , groupdisplacement = 0;
var typeLabel ="";
var dateLabel ="";
		var String_Chart = d3.select(selectString).append("svg")
			.attr("class", function(){return "string"+chartID;})
			.attr("width", stringChart.w)
			.attr("height", stringChart.h)
			.append("g")
			.attr("transform","translate(" + stringChart.m + "," + stringChart.m + ")");
		var singleMarkers = String_Chart.append("g").attr('class','singleunits').selectAll(".single")
		    .data(dataSetSingle)
		    .enter().append("g")
		    .attr("class", "single").append("rect")
		    .attr("class", function(d){singledisplacement = singledisplacement; return "single"+d.id; })
        .attr("x","0")
		    .attr("y", function(d){
          singledisplacement += d.amp + 1;
          return singledisplacement - d.amp;})
        .attr("width", "5" )
        .attr("fill", function(d,i){return color( d.type ); })
        .attr("height",function(d){ 
           return d.amp;
          });


  var groupedMarkers = String_Chart.append("g").attr('class','grouped').selectAll(".groupedunits")
        .data(dataSetGrouped)
        .enter().append("g")
        .attr("class", "groupedunits").append("rect")
        .attr("class", function(d){return "single"+d.id;})
        .attr("height",function(d){return d.amp})
        .attr("width", "10" )
        .attr("fill", function(d,i){return color( d.type )})
        .attr("opacity",".5")
        .attr("x", "600")
        .attr("y", function(d,i){ 
          groupdisplacement += d.amp;
          return groupdisplacement - d.amp;});

var ylabels = String_Chart.append("g").attr('class','labels').selectAll(".label")
        .data(dataSetSingle)
        .enter().append("text")
        .attr("class", "groupLabel")
        .attr("y", function(d){
             var selectableClass =  ".single"+d.id;
              var dp=[];
              d3.selectAll( selectableClass ).each(function(){
                dp.push(d3.select(this).attr("x"));
                dp.push(d3.select(this).attr("y"));
               })
              return parseInt( dp[1]) + 10;
        })
        .attr("x","-10")
        .attr("fill", "#888888")
        .style("font-size","10px")
        .style("text-anchor", "end")
    .style("font-wieght", 900)
    .style("font-family", "'SansationBold', 'trebuchet MS', Arial, sans-serif")
        .text(function(d){ return d.date; })
        .attr( 'opacity',function(d){
          
              if(d.date !== dateLabel){
                dateLabel = d.date;
                return 1;

                
              }else if (d.date === dateLabel)
              return 0;
              console.log("hide");
            }

          );
var tlabels = String_Chart.append("g").attr('class','labels').selectAll(".label")
        .data(dataSetGrouped)
        .enter().append("text")
        .attr("class", "groupLabel")
        .attr("y", function(d){
             var selectableClass =  ".single"+d.id;
              var dp=[];
              d3.selectAll( selectableClass ).each(function(){
                dp.push(d3.select(this).attr("x"));
                dp.push(d3.select(this).attr("y"));
               })
              return parseInt( dp[3]) + 10;
        })
        .attr("x","620")
        .attr("fill", "#888888")
        .style("font-size","10px")
    .style("font-wieght", 900)
    .style("font-family", "'SansationBold', 'trebuchet MS', Arial, sans-serif")
        .text(function(d){ return d.type; })
        .attr( 'opacity',function(d){
              if(d.type !== typeLabel){
                typeLabel = d.type;
               return 1;
                
              }else if (d.type === typeLabel)
              return 0;
            }

          );
  
 var strings = String_Chart.append("g").attr('class','paths').selectAll(".path")
        .data(dataSetSingle)
        .enter().append('path')
        .attr('class',function(d){return "path"+d.id})
        .attr("stroke-width", function(d){return d.amp;})
        .attr("stroke", function(d){return color(d.type);})
        .attr("fill","none").attr("opacity",".5")
        .attr('d',function(d){
            var selectableClass =  ".single"+d.id;
            var dp=[];
              d3.selectAll( selectableClass ).each(function(){
                dp.push(d3.select(this).attr("x"));
                dp.push(d3.select(this).attr("y"));
               })
            var s = { x:dp[0] , y:(parseInt( dp[1]) + parseInt(d.amp/2)) }, t = {x:dp[2],y:(parseInt( dp[3] )+ parseInt(d.amp/2 ))};
            return link(s,t);
          });

function flattenArray(obj){
  var arraytemp =[];
   for (key in obj) {
      if ( obj.hasOwnProperty(key) ){  arraytemp.push( obj[key] );}
      }
  return arraytemp;
  }


function link(s,t) {
 
  var curvature = Math.abs(s.y - t.y) / stringChart.h;
  if(curvature>.9) curvature = .9;
  if(curvature<.2)curvature = .2;
  var x0 = s.x,
      x1 = t.x,
      xi = d3.interpolateNumber(x0, x1),
      x2 = xi(curvature),
      x3 = xi(1 - curvature),
      y0 = s.y,
      y1 = t.y;
      return "M" + x0 + "," + y0
        + "C" + x2 + "," + y0
        + " " + x3 + "," + y1
        + " " + x1 + "," + y1; 
};
//}

  $(document).ready(function() {

	// RenderStringGraph('date',singleData,'#dataHolder');
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