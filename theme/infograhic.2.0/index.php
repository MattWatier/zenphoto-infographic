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
        <?php include('_siteHeaderNav.php' ); ?>
        <div class='row evencolumns' style="padding-top:50px;">
            <div id="introduction" class="large-6 medium-8 small-16 column">
                <h1 class="fontface">
                    Welcome to the Fragments of Me.
                </h1>
                <div id="bar_holder"class="row"></div>
                <p>
                    <?php printGalleryDesc(); ?>
                </p>
                <hr>
                <h4>
                    Shards of my work scatter across time
                </h4>
                <p>
                    <em>It is interesteding to see my work plotted into the months they were created over time. This isn't all my work but the work that is represented this repository.</em>
                </p>
            </div><!-- End of Introduction -->
            <?php 
                  $collection = query_full_array("SELECT a.folder, i.filename, i.title, i.date, i.albumid FROM `zp_images` i LEFT JOIN `zp_albums` a ON i.albumid=a.id;");
                  $gallery_item = '<div id="galleries" class="large-10 medium-8 small-16 column" style=" border-left: 1px solid #cccccc; "><div class="row">';
                  while (next_album()):
                    $gallery_item .= '<div class="large-8 medium-16 column gallery '.getAnnotatedAlbumTitle().'" ><div class="row">';
                    $gallery_item .= '<h2><a href="'.getAlbumLinkURL().'" title="View album '.getAnnotatedAlbumTitle().'">'.getAlbumTitle().'&raquo;</a></h2>';
                    $gallery_item .= '<div class="d3_chart column small-16" id="dataholder_'.getAnnotatedAlbumTitle().'">&nbsp;</div>';
                    $images = '<ul class="large-block-grid-1 thumbnails small-2 column small-offset-1">';
                    for ($i=1; $i<=8; $i++) {
                      $randomImage = getRandomImagesAlbum( $rootAlbum = getAnnotatedAlbumTitle(),$daily = false);
                       $images .= "<li class='thumbnail'><a class='fancybox' href='".$randomImage->getFullImage()."'>";
                      if ($randomImage->getWidth() >= $randomImage->getHeight()) {
                      $ih = 30;
                      $iw = NULL;
                      }else{
                      $ih = NULL;
                      $iw = 30;
                      }
                      $images .= "<img width='30' height='30'  class='lazy' src='".$_zp_themeroot."/images/holder.gif' data-original='".html_encode($randomImage->getCustomImage(NULL, $iw, $ih, 30, 30, NULL, NULL, true))."'/>";
                      $images .= "</a></li>";
                      //if($i==3){$images .= "</ul><ul style='list-style: none;'class='thumbnails row'>";}
                    }
                    $images .= "</ul>";
                    $gallery_item .= $images;
                    $gallery_item .= '<p class="column small-13">'.getAlbumCustomData().'<a href="'.getAlbumLinkURL().'">&nbsp; Explore my work on '.getAnnotatedAlbumTitle().'</a></p>'; 
                    $gallery_item .= '</div></div>';
                  endwhile;
                  $gallery_item .= '</div></div></div></div>';
                  echo $gallery_item;
                  ?>
            <div class="row" id="dataholder" style="position: relative;"></div><?php 

            // Request to Mysql;

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
                if(!isset($datetemp[0])){$datetemp[0]="";}
                if(!isset($datetemp[1])){$datetemp[1]="";}
                $date = ( $datetemp[0] + $datetemp[1]/12 );
                $type = explode( '/' , $value['folder'] );
                if(!isset($type[0])){$type[0]="";}
                if(!isset($type[1])){$type[1]="";}
                $datestamp = $datetemp[0].$datetemp[1].$type[0].$type[1];
                $parentdatestamp = $datetemp[0].$datetemp[1].$type[0];
                if( !isset($D3_Master_Array2[$datestamp]) ){ //chekcing if the date is used.
                    $D3_Master_Array2[$datestamp] = array(
                    "parentnode"=>false,
                    "parent"=>$type[0],
                    "type"=>$type[1],
                    "date"=>$date,
                    "r" => 1
                          );
                     if( !isset($D3_Master_Array2[$parentdatestamp] ) ){
                             $D3_Master_Array2[$parentdatestamp] = array(
                             "parentnode"=>true,
                             "parent"=>$type[0],
                             "type"=>$type[0],
                             "date"=>$date,
                             "r" => 1
                                   );}

                  }else{
                    ++$D3_Master_Array2[$parentdatestamp]['r'];
                    ++$D3_Master_Array2[$datestamp]['r'];
                  }
              if(isset($type[0])){
              if(!isset($D3_Grouped_TypeArray[ $type[0] ] )){  //checking if this parent has been used.
                  $D3_Grouped_TypeArray[ $type[0] ] = array(); 
                  array_push($D3_Grouped_TypeArray[ $type[0] ],$type[0]);
                  if(isset($type[1])){ array_push( $D3_Grouped_TypeArray[ $type[0] ], $type[1] );}

                  $subtypes =array();  
                  array_push( $subtypes, $type[0]);

                  //second set up
                  $D3_Grouped_Array[ $type[0] ] = array();
                  $D3_Grouped_Array[ $type[0] ][ $type[1] ] = array("count"=>1, "type"=>$type[1]);

              }
              }


              if(isset($type[1])){ 
                array_push( $subtypes,$type[1] ); 
                if( !isset($D3_Grouped_Array[ $type[0] ][ $type[1] ] ) ){
                     $D3_Grouped_Array[ $type[0] ][ $type[1] ] =  array("count"=>1, "type"=>$type[1]);



                  }else{++ $D3_Grouped_Array[ $type[0] ][ $type[1] ]["count"];}
               }
              $datestamp = $datetemp[0].$datetemp[1].$type[0]; // altering the datestamp
              if( !isset($D3_Master_Array[$datestamp])){
                $tempsubarray = array("type"=>$type[0]);
                array_push($D3_Master_Type_Array, $tempsubarray["type"]);
                $tempsubarray = array("type"=>$type[0],"r"=>1,"date"=>$date);
                $D3_Master_Array[$datestamp] = $tempsubarray;
                }else{
                $D3_Master_Array[$datestamp]["r"] = $D3_Master_Array[$datestamp]["r"]+1;

               }
               
               
               if(!isset($D3_Master_Array[$datestamp]['subtype'][$type[1]])){
                  $D3_Master_Array[$datestamp]['subtype'][$type[1]] = array(
                      "type" => $type[1],
                      "r" => 1,
                      "date" => $date
                    );
               }else{
                 ++$D3_Master_Array[$datestamp]['subtype'][$type[1]]["r"];
               }
              
              
              if(!isset($D3_BarChart_Array[ $type[0] ])){
                 $D3_BarChart_Array[$type[0]] = array(
                    "type" => $type[0],
                    "count" => 1,
                    );

              }else{
                  ++ $D3_BarChart_Array[$type[0]]["count"];
              }

              # code...
            }




            ?><script type="text/javascript">
<?php 

            function drawDonutGraphics($D3_Master_Type_Array,$D3_Grouped_Array){
            $javascriptCall = '';
            foreach( array_unique($D3_Master_Type_Array) as $value ){
            $flat_array= array();
            foreach ($D3_Grouped_Array[$value] as $A_value) {
            array_push($flat_array, $A_value);
            } ;
            echo 'var '.$value.'_d3_data = '.json_encode( $flat_array ).';';
            $javascriptCall .= 'drawDonutChart("'.$value.'",'.$value.'_d3_data,"#dataholder_'.$value.'");';
            }
            return $javascriptCall;
            }

            $javascriptCall = drawDonutGraphics($D3_Master_Type_Array,$D3_Grouped_Array);

            ?>
            var dset = <?php echo json_encode($D3_BarChart_Array); ?>;
            var buble_data = <?php echo json_encode($D3_Master_Array2); ?>;
            $(document).ready(function() {

            drawBarChart("value",dset,"#bar_holder");
            <?php echo $javascriptCall; ?>
            renderBubbleGraph(buble_data);

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