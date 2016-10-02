<?php
/**
 * Plugin Name: Remove HTTP
 * Plugin URI: https://wordpress.org/plugins/remove-http/
 * Description: Automatically remove both HTTP and HTTPS protocols from all web links.
 * Author: <a href="https://www.factmaven.com/#plugins">Fact Maven</a>
 * License: GPLv3
 * Version: 1.0.1
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Remove_HTTP {

    public function __construct() {
        # If in the admin panel, don't run function
        if (  ! is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
            # Remove HTTP and HTTPS protocols
            add_action( 'plugins_loaded', array( $this, 'output_buffering' ), 10, 1 );
        }
    }

    public function output_buffering() {
        # Enable output buffering
        ob_start( array( $this, 'remove_protocols' ) );
    }

    public function remove_protocols( $buffer ) {
        $content_type = NULL;
        # Check for Content-Type headers only
        foreach ( headers_list() as $header ) {
            if (strpos( strtolower( $header ), 'content-type:' ) === 0 ) {
                $pieces = explode( ':', strtolower( $header ) );
                $content_type = trim( $pieces[1] );
                break;
            }
        }
        # If the content-type is 'NULL' or 'text/html', apply rewrite
        if ( is_null( $content_type ) || substr( $content_type, 0, 9 ) === 'text/html' ) {
            # Find and remove both 'http:' and 'https:' protocols
            $replace = preg_replace( '~=\s*["\']\s*https?:(.*?)["\']~i', '="$1"', $buffer );
            # If there is a regex error, skip overwriting content
            if ( $replace ) {
                $buffer = $replace;
            }
        }
        # Return protocol relative links
        return $buffer;
    }
}

new Fact_Maven_Remove_HTTP();