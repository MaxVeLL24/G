function rrating(score,table,user) {
		$(".score_pr"+table).parent().parent().addClass('scored');
		$.get("includes/modules/rating/rating.php?score="+score+"&table="+table+"&user="+user+"&update=true", {}, function(data){
			$('.scored').fadeOut("normal",function() {
				$(this).html(data);
				$(this).fadeIn();
				$(this).removeClass('scored');
				
			});
		});

}	
/*
$(document).ready(function() {

	$('.score a').click(function() 
    {      
 //   alert($(this).attr('id'));
		$(this).parent().parent().parent().addClass('scored');
		$.get("includes/modules/rating/rating.php" + $(this).attr("id") +"&update=true", {}, function(data){
			$('.scored').fadeOut("normal",function() {
				$(this).html(data);
				$(this).fadeIn();
				$(this).removeClass('scored');
				
			});
		});
		
		return false; 
	});
	
});
*/