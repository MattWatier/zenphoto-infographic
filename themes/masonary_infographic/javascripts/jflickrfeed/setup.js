$(document).ready(function(){


	$('#cycle').jflickrfeed({
		limit: 14,
		qstrings: {
			id: '78776397@N08'
		},
		itemTemplate: '<li class="columns twelve"><img src="{{image}}" alt="{{title}}" /><div>{{title}}</div></li>'
	}, function(data) {
		$('#cycle div').hide();
		$('#cycle').cycle({
			timeout: 5000
		});
		$('#cycle li').hover(function(){
			$(this).children('div').show();
		},function(){
			$(this).children('div').hide();
		});
	});
	

});