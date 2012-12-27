<div id='canvas' ><div class="row">
	<?php
	// search for gallery switch between canvas,screen,paper
	if(getParentAlbums()){
		$modeVar = getParentAlbums();
		$modeVar= $modeVar[0]->name;
	
	}else{
		$modeVar=getAlbumTitle();	
		if(!getAlbumTitle()){$modeVar="Fragments";};
		}
	switch($modeVar){
		case "Fragments":
			 echo '<img src="'.$_zp_themeroot.'/images/fragments.jpg" alt="Fragments"/>';
			break;
		case "Canvas":
			 echo '<img src="'.$_zp_themeroot.'/images/canvas.jpg" alt="Canvas Art"/>';
			break;
		case "Screen":
			echo '<img src="'.$_zp_themeroot.'/images/screen.jpg" alt="Screen Art"/>';			
			break;
		case "Paper":
			echo '<img src="'.$_zp_themeroot.'/images/paper.jpg" alt="Paper"/>';			
			break;
		default:
			break;		
		
		
	}
	
	
	
	 ?>
	
	
	
	
</div></div>
	