<?php				
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// PROCESS 
function wpfbint_processData(){
	
	if($_SERVER['REQUEST_METHOD'] == 'POST' && current_user_can('administrator') ){
	
		check_admin_referer( 'fb_page_id' );
		check_ajax_referer('fb_page_id');	
		$pageid = sanitize_text_field($_REQUEST['wpfbint_page_id']);
		$picnr = (int)$_REQUEST['wpfbint_pic_nr'];
		if($picnr){
			update_option('wpfbint_pic_nr',$picnr);
			echo '<p>You can use the shortcode [wpfbint_gallery] in any page or text widget.</p>';
		}

		if(!empty($pageid)){
			wpfbint_checkId($pageid);	
		}
		
		if($token){
			update_option('wpfbint_accessToken',$token);
		}			
		
	}
}
//1566464580235254 , test
function wpfbint_checkId($pageid){
	$access_token = "572502376264037|Gi1Vj7r5XfnNXtLuaT8MXpGLysg";
	$json_link = "https://graph.facebook.com/{$pageid}/?access_token={$access_token}";
	$json = @file_get_contents($json_link,true);
	if ($json === false){
		print "<b style='color:red'>Proble with your Page ID, check again please.</b>";
	}else{
		update_option('wpfbint_page_id',$pageid);
		echo '<p>You can use the shortcode [wpfbint_gallery] in any page or text widget.</p>';
	}
}

function wpfbint_gallery(){
		$fb_page_id = get_option('wpfbint_page_id');
		$access_token = "572502376264037|Gi1Vj7r5XfnNXtLuaT8MXpGLysg";
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		if($fb_page_id != ''){
			$fields="photos{images},name,id";
			$json_link = "https://graph.facebook.com/{$fb_page_id}/albums/?fields={$fields}&access_token={$access_token}";
			$json = @file_get_contents($json_link,true);
			if ($json === false){
			   print "<b style='color:red'>Failed to Load Gallery. Check your Page ID.</b>";
			}else{				
				$obj = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
				$pic=$obj['data'][0]['photos']['data'];  
				if(get_option('wpfbint_pic_nr')!= '' ) {
					$count  = get_option('wpfbint_pic_nr');
				}else{
					$count = count($pic);
				}				
				//-----      TIMELINE PHOTOS ONLY  -------- //
					print "<div class='wpfbint_gal'>";	
						for($x=0; $x<$count; $x++){
							echo "<a class='wpfbint_slides' href='{$pic[$x]['images'][0]['source']}'><img src='{$pic[$x]['images'][0]['source']}'  class='img-responsive'/></a>";
						}
					print"</div>";
			}		
		}else print '<p>No Photos from Timeline Album Found.</p>';	
}
add_shortcode( 'wpfbint_gallery', 'wpfbint_gallery' );

?>