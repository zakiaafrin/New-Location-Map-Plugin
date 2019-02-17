<?php
/*
*
Plugin Name: My New Map Plugin
Plugin URI: http://zjeme.techlaunch.online/wordpress-zakia/wp-admin/plugins.php
Description: This is a new Map Plugin to display all locations of a business.  
Author: Zakia Afrin Jeme
Version: 1.0.0
Author URI: http://zjeme.techlaunch.online
*
*/

$plugin_url = WP_PLUGIN_URL . '/new_map';

function plugin_install() {

    global $wpdb;
    return true;

}

function option_menu() {

    add_options_page(
        'Location Finder Plugin',
        'Location Finder Plugin',
        'manage_options',
        'Location Finder Plugin',
        'option_page'
    );

}

add_action('admin_menu', 'option_menu');

function option_page() {   
    
    if( !current_user_can( 'manage_options' ) ) { 

        wp_die( 'You don\'t have sufficient permissions to access this page.' );    

    }

    global $plugin_url;
    global $name;
    
    if( isset( $_POST['form_submitted'] ) ) {

        $hidden_field = esc_html( $_POST['form_submitted'] );
        
        if( $hidden_field == 'Y' ) {
            
            $name = esc_html( $_POST['name'] );
            
            $location =  getName($name );
            
        }
        
    }
    
    require('location.php');
}

function getName($name){   
    
    $json_feed_url = 'https://www.google.com/maps/search/?api=1&amp;query=' . $name;
    
    $args = array('timeoute' => 120);
    
    $json_feed = wp_remote_get( $json_feed_url, $args );
    
    $weather_updates = json_decode( $json_feed['body'] );

?>
<div class="main">
    <div class="inside">
        <article class="plugin">
            <div class="location">
                <h1 class="head">Location Title/Name : <?= $name; ?> </h1>
                <div id="icon"><a href="<?= $json_feed_url; ?>" class="map">View Location Map</a></div>
                
            </div>
        </article>
    </div>
</div>

<?php  
}
function plugin_deactivate()
{
    global $wpdb;
    echo "deactivate";
}
function weather_styles() {
	wp_enqueue_style( 'weather_styles', plugins_url( 'new_map/style.css' ) );
}
add_action( 'admin_head', 'weather_styles' );
add_action('wp_enqueue_scripts', 'getName');
register_activation_hook(__FILE__, 'plugin_install');
register_deactivation_hook(__FILE__, 'plugin_deactivate');
?>