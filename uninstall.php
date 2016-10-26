<?php
/**
 * Runs automatically when the plugin is deleted.
 *
 * @author Fact Maven Corp.
 * @link https://wordpress.org/plugins/remove-http/
 */

# If uninstall is not called by WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )  exit;

# Remove options with the prefix "factmaven_rhttp"
foreach ( wp_load_alloptions() as $option => $value ) {
    if ( strpos( $option, 'factmaven_rhttp' ) === 0 ) {
        delete_option( $option );
    }
}