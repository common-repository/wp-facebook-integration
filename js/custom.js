(function( $ ) {

	$(".wpfbint_slides").colorbox({rel:'slides',scalePhotos:true,width:'70%'});
		
		$('.wpfbint_gal').each(function(){
			if($(this).parent().width()	<=400){
				$(this).css('column-count','1');
			}
		});	
		
})( jQuery );