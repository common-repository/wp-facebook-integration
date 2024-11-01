<?php
/*
 * Plugin Name: WordPress Integration with Social Media -- display Gallery Photo Album
 * Plugin URI: https://webdeveloping.gr/projects/wordpress-facebook-integration-facebook-gallery-wordpress-site/
 * Description: Integrate WordPress with Facebook - display Facebook Gallery Photo Album  from your Facebook Page with a Shortcode.
 * Version: 2.0
 * Author: extendWP
 * Author URI: https://extend-wp.com
 * License: GPL2
 * Created On: 28-05-2016
 * Updated On: 30-07-2019
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
include( plugin_dir_path(__FILE__) .'/wpfb-options.php');


function wpfbint_scripts(){

    wp_enqueue_style( 'wpfbint_css', plugins_url( '/css/style.css', __FILE__ ) );	
	wp_enqueue_style( 'wpfbint_css');	
	
    wp_enqueue_style( 'wpfbint_colorbox_css', plugins_url( '/css/colorbox.css', __FILE__ ) );	
	wp_enqueue_style( 'wpfbint_colorbox_css');	

	wp_enqueue_script('jquery');
    wp_enqueue_script( 'wpfbint_colorbox_js', plugins_url( '/js/colorbox.js', __FILE__ ) , array('jquery') , null, true);	
	wp_enqueue_script( 'wpfbint_colorbox_js');
	
    wp_enqueue_script( 'wpfbint_custom_js', plugins_url( "/js/custom.js", __FILE__ ) , array('jquery') , null, true);	
	wp_enqueue_script( 'wpfbint_custom_js');	
}
add_action('wp_enqueue_scripts', 'wpfbint_scripts');

function wpfbint_admin_scripts(){
	
    wp_enqueue_style( 'wpfbint_admin_css', plugins_url( "/css/admin.css", __FILE__ ) );	
	wp_enqueue_style( 'wpfbint_admin_css');	
	
    wp_enqueue_script('jquery');
	
    wp_enqueue_script( 'wpfbint_admin_js', plugins_url( "/js/admin.js", __FILE__ ), array('jquery') , null, true);	
	wp_enqueue_script( 'wpfbint_admin_js');
	
    $wpfbUrl = array( 'plugin_url' => plugins_url( '', __FILE__ ) );
    wp_localize_script( 'wpfbint_admin_js', 'url', $wpfbUrl );
}
add_action('admin_enqueue_scripts', 'wpfbint_admin_scripts');


//ALLOW SHORTCODES ON WIDGETS
add_filter('widget_text', 'do_shortcode');

//ADD MENU LINK AND PAGE FOR WOO PRODUCT IMPORTER
add_action('admin_menu', 'wpfbint_menu');

function wpfbint_menu() {
	add_menu_page('WP Facebook Integration Settings', 'WP Facebook Integration', 'administrator', 'wpfbint_settings', 'wpfbint_settingsform', 'dashicons-admin-generic','100');
}


//ADD ACTION LINKS
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_wpfbint_links' );

function add_wpfbint_links ( $links ) {
 $links[] =  '<a href="' . admin_url( 'admin.php?page=wpfbint_settings' ) . '">Settings</a>';
 $links[] = '<a href="https://extend-wp.com/product/wordpress-facebook-integration-display-gallery-photo-album/" target="_blank">PRO Version</a>';
   return $links;
}

function wpfbint_page_id(){
	?>
    	<input type="text" name="wpfbint_page_id" id="wpfbint_page_id" required value="<?php echo esc_attr(get_option('wpfbint_page_id')); ?>" />
    <?php
}
function wpfbint_pic_nr(){
	?>
    	<input type="text" name="wpfbint_pic_nr" id="wpfbint_pic_nr"   value="<?php echo  esc_attr(get_option('wpfbint_pic_nr')); ?>" />
    <?php
}

function wpfbint_accessToken(){
	?><input type="text" name="wpfbint_accessToken" id="wpfbint_accessToken" value="<?php echo esc_attr(get_option('wpfbint_accessToken')); ?>" />
    <?php
}

function wpfbint_panel_fields(){

	add_settings_section("wpfb-general", "", null, "wpfb-general-options");
	add_settings_field("wpfbint_page_id", "Facebook Page ID", "wpfbint_page_id", "wpfb-general-options", "wpfb-general");
	add_settings_field("wpfbint_accessToken", "Access Token", "wpfbint_accessToken", "wpfb-general-options", "wpfb-general");
	
	add_settings_section("wpfb-gallery", "", null, "wpfb-gallery-options");
	add_settings_field("wpfbint_pic_nr", "Number of Pics in Gallery", "wpfbint_pic_nr", "wpfb-gallery-options", "wpfb-gallery");
	
	register_setting("wpfb-general-general", "wpfbint_page_id");
	register_setting("wpfb-general-gallery", "wpfbint_pic_nr");
	register_setting("wpfb-general-gallery", "wpfbint_accessToken");
}
add_action("admin_init", "wpfbint_panel_fields");	

//MAIN SETTINGS FORM FOR WP Facebook Integration Plugin

function wpfbint_settingsform() {
	?>
	<div class="wpfbint_wrap">

	<h2>WP Integration with Social Media</h2>
	
	<form method="post" id='wpfbint_form'  action= "<?php echo admin_url( 'admin.php?page=wpfbint_settings' ); ?>">

        <?php
            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : '';
            } // end if
        ?>
			<div class='premium_msg'>
				<p>
					<strong>
					Only available on Premium Version <a class='premium_button' target='_blank'  href='https://extend-wp.com/product/wordpress-facebook-integration-display-gallery-photo-album/'>Get it Here</a>
					</strong>
				</p>
			</div>
			
		 <h3>Get Picture Gallery from your FB Page with colorbox feature enabled.</h3><br/>
         <p><b>INSTRUCTIONS:</b> Insert your Facebook Page ID <a href='https://www.facebook.com/help/community/question/?id=378910098941520' target='_blank'>(How to Find Page ID)</a> or <a href="https://findmyfbid.com/" target="_blank">here</a>. Then, use the shortcode [wpfbint_gallery] in any page or widget.</p>
        <h2 class="nav-tab-wrapper">
            <a href="?page=wpfbint_settings" class="nav-tab <?php echo $active_tab == '' ? 'nav-tab-active' : ''; ?>">General</a>
            <a href="?page=wpfbint_settings&tab=pic_options" class="nav-tab <?php echo $active_tab == 'pic_options' ? 'nav-tab-active' : ''; ?>">Gallery Options</a>
            <a href="#" class="nav-tab premium ">Events Options</a>
            <a href="#" class="nav-tab premium ">Posts Options</a>	
            <a href="#" class="nav-tab premium ">Slideshow Options</a>
		</h2>

		<div class='left_wrap' >
		
		<?php wpfbint_processData(); ?>
		
		<?php
        if( $active_tab == 'pic_options' ) {
			print "<p>You need to add <b>Photos in your Timeline</b> of your Fan Page for gallery to work.</p>";
            settings_fields( 'wpfb-gallery-options' );
            do_settings_sections( 'wpfb-gallery-options' );
			
        }else{
			print "<p>Facebook <b>Page ID</b> is required to make any feature work.</p>";
            settings_fields( 'wpfb-general-options' );
            do_settings_sections( 'wpfb-general-options' );
			
			?>
			<h3>GET ACCESS TOKEN GUIDE</h3>
			<p>1) Go to <a href='https://developers.facebook.com/' target='_blank'>developers.facebook.com</a> and login with your Facebook account.</p>
			<p>2) On the upper right corner, click “My Apps” and “Add New App”.</p>
			<p>3) On the pop up, enter your “Display Name” and “Contact Email” and click “Create App ID” button.</p>
			<p>4) Pass the security check if it pops up.</p>
			<p>5) Go to <a href='https://developers.facebook.com/tools/explorer/' target='_blank'>Graph API Explorer</a></p>
			<p>6) On the right side, click the “Facebook App” drop-down and select the app you just created.</p>
			<p>7) Click the “User or Page” drop-down. Click “Get Page Access Token”.</p>
			<p>8) On the pop up, click “Continue as…” button. Click “OK” button.</p>
			<p>9) Click “User or Page” drop-down again. Under the “Page Access Token” section, click the Facebook page you want to use.</p>
			<p>10) Copy the generated page access token on the “Access Token” field.</p>
			<p>11) Go to <a href='https://developers.facebook.com/tools/debug/accesstoken' target='_blank'>Access Token Debugger</a>. Remove the value on the input box and paste the page access token you just copied.</p>
			<p>12) Click “Debug” button. Make sure the “App ID” says your app name. The “Type” is “Page” and the “Page ID” says the Facebook page you want to use.</p>
			<p>13) On the bottom part, click “Extend Access Token” button. Click “Debug” button. Copy the new page access token from the input box.</p>
			<p>14) Go to <a href='https://www.sociablekit.com/app/convert_token_to_never_expire.php' target='_blank'>Access Token Converter</a>.</p>
			<p>15) Paste the page access token you just copied. Click the green button.</p>
			<p>16) Click “Debug” button. You will see a page where “Expires” says “Never”. Copy the page access token from the input box</p>
			<p>17) Insert token in field above!</p>			
			
			<?php //218536115268156
        } // end if/else 
		?>
		<?php wp_nonce_field('fb_page_id'); ?>	
		<?php submit_button(); ?>
		
		</div>
		
		<div class='right_wrap rightToLeft'>
			<h2  class='center'>NEED MORE FEATURES? </h2>
				<ul>
					<li>Get a Slideshow of your Latest Cover Photos using a shortcode in any page or widget</li>
					<li>Get your page’s Events using a shortcode in any page or widget</li>
					<li>Get your page’s Posts using a shortcode in any page or widget</li>

				</ul>	
			<p class='center'>			
				<a target='_blank'  href='https://extend-wp.com/product/wordpress-facebook-integration-display-gallery-photo-album/'>
					<img class='premium_img' src='<?php echo plugins_url( 'images/wp_integration-social.png', __FILE__ ); ?>' alt='wp facebook integration premium' title='wp facebook integration premium' />
				</a>
			<p  class='center'>
				<a class='premium_button' target='_blank'  href='https://extend-wp.com/product/wordpress-facebook-integration-display-gallery-photo-album/'>
					<?php _e("Get it here","wpfbint");?>	
				</a>
			</p>
		</div>		
		
	</form>

	</div>
	
	<hr>
	<h2><i><?php _e("You want to add FB Slideshow, FB Events, FB Posts, FB Map from your Facebook Page?","wpfbint");?></i> <a target='_blank' style='background:#0085ba;color:#fff;padding:5px;text-decoration:none;border-radius:5px;' href='https://extend-wp.com/product/wordpress-facebook-integration-display-gallery-photo-album/'><?php _e("Get WP Facebook Integration PREMIUM","wpfbint");?></a></h2>
	<hr>
	<a target='_blank' style='float:right' href='https://extend-wp.com'><img style='max-width:150px;height:auto !important;' src='<?php echo plugins_url( 'images/extendwp.png', __FILE__ ); ?>' alt='Get more plugins by extendWP' title='Get more plugins by extendWP' /></a>		
	
	<?php  
	
}

//ON DEACTIVATION , DELETE THE OPTIONS CREATED

function wpfbint_deactivation() {
  delete_option('wpfbint_page_id');
  delete_option('wpfbint_pic_nr');
}
register_deactivation_hook( __FILE__, 'wpfbint_deactivation' );

?>