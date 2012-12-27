// JavaScript Document

  $(document).ready(function(){
			$("#menuList>li>a").wrap('<h4 class="cat"/>');
			$("#menuList>li>ul>li>a").wrap('<h5 class="alb"/>');
			$("#menuList>li>ul>li li a").wrap('<h6 class="subalb"/>');
			$("#menuList ul ul").siblings('h5').addClass('group');
				
		/*		$('#menuList>li>ul ul').each(function(){
					if( this.hasClass('selected') )
						{
						}else{
						this.slideUp(0);
						};
						});
		$('#menuList>li>ul').each(function(){
					if( !this.hasClass('selected') )
						{
						if(this.children('li').hasClass('selected')){}else{
					
						this.slideUp(0);
						}
					};
					
			
			});
			*/
			var storedOpen;
			$('#menuList>li>ul ul').slideUp(0);
			//$('#menuList>li>ul').slideUp(0);
			
			$('#menuList>li>ul').each(function(){
				if( $(this).siblings('h4').children('a').hasClass('galleryName')  ){
					storedOpen=$(this);
				} 
				else{
					$(this).slideUp(0);
				};
			});
			
		/*	$("#menuList").bind().mouseleave(function(){
				if(storedOpen != $('#menuList .selected')){
					$("#menuList .selected").removeClass("selected").children('ul').hide(500).delay(1000);
					storedOpen.clearQueue().show(500).delay(1000);
				};
			});*/
			//slides the element with class "menu_body" when mouse is over the paragraph
			$("#menuList>li").bind("hover",{speed:150,delay:500},function()
			{
				if( $(this).hasClass('selected')){}else{
			
				$('#menuList>li.selected').removeClass('selected').children('ul').hide(500);
			   	$(this).addClass('selected').children('ul').show(500);
	
				}
					
			
			   });
			$("#menuList>li ul li").bind("hover",{speed:150,delay:500},function()
				{
				// check if the thing I am rolling over has something to expand if not don't do anything
				
				if( $(this).hasClass('selected')){}else{
							$('#menuList>li>ul>li.selected').removeClass('selected').children('ul').hide(500);
					   		$(this).addClass('selected').children('ul').show(500);
					};
			   });
	
		
			
			
			});