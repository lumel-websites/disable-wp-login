<?php

/**
* Plugin Name: Disable WP Native Login
* Plugin URI: https://github.com/lumel-websites/disable-wp-login
* Description: This plugin disables native login within a WordPress site. This is suitable for WordPress sites that allow login through SSO 
* Version: 1.0.0
* Author: KG
* Author URI: https://lumel.com
* License: GPL2
*/

add_action( 'admin_init', 'disable_wp_login_settings_init' );
add_action( 'admin_menu', 'disable_wp_login_settings_page' );

function disable_wp_login_settings_init() {
    add_settings_section( 'genaral_settings', 'General Settings', null, 'disable-wp-login-settings' );
	add_settings_field( 'disable_wp_login_backdoor_secret', 'Backdoor Secret', 'disable_wp_login_backdoor_secret_callback', 'disable-wp-login-settings', 'genaral_settings' );
    register_setting( 'disable-wp-login-settings' , 'disable_wp_login_backdoor_secret');
}

function disable_wp_login_backdoor_secret_callback() {
    ?>
    <input type="password" id="disable_wp_login_backdoor_secret" class="regular-text" name="disable_wp_login_backdoor_secret" value="<?php echo get_option( 'disable_wp_login_backdoor_secret' ); ?>" />
    <?php
	if( get_option( 'disable_wp_login_backdoor_secret' ) == '' ) {
		echo '<p class="description">Enter a secret above that will be used in the login URL to enable native login</p>';
	} else {
		echo '<p class="description">Native Login URL: <a href="' . add_query_arg( 'bkdoor', get_option( 'disable_wp_login_backdoor_secret' ) , wp_login_url() ) . '">' . add_query_arg( 'bkdoor', get_option( 'disable_wp_login_backdoor_secret' ) , wp_login_url() ) . '</a></p>' ;
	}
}

function disable_wp_login_settings_page() {
    add_options_page( 'Disable WP Login', 'Disable WP Login', 'administrator', 'disable-wp-login-settings', 'disable_wp_login_settings' );
}

function disable_wp_login_settings(){
    ?>
    <div class="wrap">
        <h1>Disable WP Login - Settings</h1>
        <div class="disable-wp-login-settings">
            <form method="post" action="options.php">
                <?php
                    settings_fields( 'disable-wp-login-settings' );
                    do_settings_sections( 'disable-wp-login-settings' );
                    submit_button();
                ?>
            </form>
        </div>
    </div>
    <?php
}

if( isset( $_GET['bkdoor'] ) && $_GET['bkdoor'] == get_option( 'disable_wp_login_backdoor_secret' ) ) {
	//do nothing
} else {
    remove_filter('authenticate', 'wp_authenticate_username_password');	
}

function hide_login_fields() { 
	if( isset( $_GET['bkdoor'] ) && $_GET['bkdoor'] == get_option( 'disable_wp_login_backdoor_secret' ) ) {
        //do nothing
    } else { ?>
    	<style type="text/css">
        	#loginform #mo_saml_button b,#loginform h3.galogin-or, #loginform p:not(.galogin), #loginform div.user-pass-wrap, #login p#nav {
            	display: none;
        	}
			#mo_saml_button {
				height:auto !important;
			}
            #mo_saml_login_sso_button {
                margin-bottom:0px !important;
            }
    	</style>
	<?php } 
}

add_action( 'login_enqueue_scripts', 'hide_login_fields' );

?>
