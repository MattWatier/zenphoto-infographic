
<?php 
//indexjson.php
// Request to Mysql;
$collection = query_full_array("SELECT zp_albums.folder, zp_images.filename, zp_images.title, zp_images.date, zp_images.albumid FROM zp_images LEFT JOIN zp_albums ON zp_images.albumid=zp_albums.id;");

$D3_Master_Array = array();//+
$D3_Master_Type_Array = array(); // Donut
$D3_Grouped_Array = array(); // Donut
$D3_Grouped_TypeArray =array();
$D3_Master_Array2 = array();//bubbles
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





$D3_Master_Type_Array = flattenArray( array_unique($D3_Master_Type_Array) );
$D3_Master_Array = flattenArray( $D3_Master_Array );
$D3_Master_Array2 = flattenArray( $D3_Master_Array2 );
$D3_BarChart_Array = flattenArray($D3_BarChart_Array);



?>	


