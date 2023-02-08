<?php

/**
* Plugin Name: Disable WP Login
* Plugin URI: https://github.com/lumel-websites/disable-wp-login
* Description: This plugin disables native login within a WordPress site. This is suitable for WordPress sites that allow login through SSO 
* Version: 1.0.0
* Author: KG
* Author URI: https://lumel.com
* License: GPL2
*/

remove_filter('authenticate', 'wp_authenticate_username_password');

function hide_login_fields() { ?>
    <style type="text/css">
        #loginform b,#loginform h3.galogin-or, #loginform p:not(.galogin), #loginform div.user-pass-wrap, #login p#nav {
            display: none;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'hide_login_fields' );


?>
