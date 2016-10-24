<?php
/**
 * Plugin Name: Remove HTTP
 * Plugin URI: https://wordpress.org/plugins/remove-http/
 * Description: Automatically remove both HTTP and HTTPS protocols from all web links.
 * Version: 1.0.2
 * Author: Fact Maven
 * Author URI: https://www.factmaven.com
 * License: GPLv3
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Remove_HTTP {

    public function __construct() {
        # Display brief update notice
        add_action( 'in_plugin_update_message-' . plugin_basename( __FILE__ ), array( $this, 'upgrade_notice' ), 10, 2 );
        # Remove HTTP and HTTPS protocols
        add_action( 'wp_loaded', array( $this, 'output_buffering' ), 10, 1 );
    }

    public function upgrade_notice( $current, $new ) {
        // var_dump( $new ); // Debugging
        # If the `upgrade_notice` exists in the readme, display the info
        if ( isset( $new->upgrade_notice ) && strlen( trim( $new->upgrade_notice ) ) > 0 ) {
            echo '<br>' . '<strong>Upgrade Notice</strong>: ' . $new->upgrade_notice;
        }
        # Otherwise, display a static notice
        else {
            echo '<br>' . '<strong>Upgrade Notice</strong>: <span class="description">Fixed issue with plugin conflicts such as Visual Composer and Revolution slider.</span>';
        }
    }

    public function output_buffering() {
        # Enable output buffering
        ob_start( array( $this, 'remove_protocols' ) );
    }

    public function remove_protocols( $buffer ) {
        $content_type = NULL;
        # Check for 'Content-Type' headers only
        foreach ( headers_list() as $header ) {
            if ( strpos( strtolower( $header ), 'content-type:' ) === 0 ) {
                $pieces = explode( ':', strtolower( $header ) );
                $content_type = trim( $pieces[1] );
                break;
            }
        }
        # If the content-type is 'NULL' or 'text/html', apply rewrite
        if ( is_null( $content_type ) || substr( $content_type, 0, 9 ) === 'text/html' ) {
            # Find and remove both 'http:' and 'https:' protocols
            $buffer = preg_replace( "/(<(script|link|base|img|form|a|meta|iframe|svg|html)([^>]*)(href|src|action|content|xmlns)=[\"'])https?:\\/\\//i", "$1//", $buffer );
        }
        # Return protocol relative links
        return $buffer;
    }
}
# Instantiate the class
new Fact_Maven_Remove_HTTP();