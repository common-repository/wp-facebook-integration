(function( $ ) {
		
			$("#wpfbint_form").submit(function(e) {
				e.preventDefault();
				var pageid = $('#wpfbint_page_id').val();
				var picnr = $('#wpfbint_pic_nr').val();
				
				if($.isNumeric($('#wpfbint_page_id').val())){					
						$.ajax({
							url: window.location.href,
							data:  $(this).serialize(),
							type: 'POST',
							beforeSend: function() {	
								$(window).scrollTop(0);
								$('#wpfbint_form').append("<img src='"+url.plugin_url+"/images/loader.gif' />");
								
							},						
							success: function(data){
								$('body').html(data);
								$('#wpfbint_page_id').val(pageid);				
							}
						});		
				}else if( $.isNumeric($('#wpfbint_pic_nr').val()) ){
						$.ajax({
							url: window.location.href,
							data:  $(this).serialize(),
							type: 'POST',
							beforeSend: function() {	
								$(window).scrollTop(0);
								$('#wpfbint_form').append("<img src='"+url.plugin_url+"/images/loader.gif' />");							
							},						
							success: function(data){
								$('body').html(data);
								$('#wpfbint_pic_nr').val(picnr);				
							}
						});					
				}else {
					alert('Enter Numbers only please..');	
				}
	
			});

			
			
	$('.wpfbint_wrap .nav-tab-wrapper a').click(function(e){

		e.preventDefault();
		if($(this).hasClass("premium") ){
			$(".wpfbint_wrap .premium_msg").slideDown('slow');
		}else{
			$(".wpfbint_wrap .premium_msg").hide();			
			var url = $(this).attr("href");
			$('.wpfbint_wrap').addClass('loading');
			$("body").load($(this).attr("href"),function(){
				window.history.replaceState("object or string", "Title", url );
			});			
		}

		
	});				
			
		
})( jQuery );