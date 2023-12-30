<?php 
/**
 * Plugin Name: Button Click Register
 * Description: add a button that register Clicks with shortcode [lc_button_click_register]
 * Plugin URI:  https://github.com/lucassdantas/wpButtonClickRegister.git
 * Version:     1.0.0
 * Author:      Lucas Dantas
 * Author URI:  linkedin.com/in/lucas-de-sousa-dantas/
 */

 if ( ! defined( 'ABSPATH' ) ||  !function_exists('add_action')) {
	exit;
}


register_activation_hook( __FILE__, 'create_table_and_shortcode' );
function create_table_and_shortcode() {
    create_clicks_register_table();

    add_shortcode('lc_button_click_register', 'button_click_register');
}
function create_clicks_register_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lc_button_clicks';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		click_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
    
}


add_shortcode('lc_button_click_register', 'button_click_register');
function button_click_register(){
    echo "<a class='lc_button_click_register'>Botão de registro</a>";
}

add_action('wp_footer', 'button_click_script');
function button_click_script(){
?>
    <script defer>
        let allLcButtons = document.querySelectorAll('.lc_button_click_register') || undefined
        if(allLcButtons){
            allLcButtons.forEach(button => {
                button.addEventListener('click',() => {
                    jQuery.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: "POST",
                        cache: false,
                        data:{ 
                            action: 'send_email', 
                            clickMoment: moment(),
                        },
                        success:function(res){}
                    }); 
                })
            })
        }
    </script>
<?php
}

add_action( 'wp_ajax_register_click', 'register_click' );
add_action( 'wp_ajax_nopriv_register_click', 'register_click' );

function register_click(){
    
}