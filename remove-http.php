<?php
/**
    Plugin Name: Remove HTTP
    Plugin URI: https://wordpress.org/plugins/remove-http/
    Description: Automatically remove both HTTP and HTTPS protocols from all web links.
    Version: 1.0.0
    Author: <a href="https://www.factmaven.com/">Fact Maven Corp.</a>
    License: GPLv3
*/

if ( !defined( 'ABSPATH' ) ) { // Exit if accessed directly
    exit;
}

if ( !class_exists( 'FactMaven_RemoveHTTP' ) ) {
    class FactMaven_RemoveHTTP {
        public function __construct() {
            add_action( 'plugins_loaded', array( $this, 'rhttp_buffer' ), 10, 1 );
        }
        public function rhttp_buffer() { // Enable output buffering
            ob_start( array( $this, 'rhttp_remove_http' ) );
        }
        public function rhttp_remove_http( $buffer ) { // Strip HTTP & HTTPS protocols
            $content_type = NULL;
            foreach ( headers_list() as $header ) { // Check for Content-Type headers only
                if (strpos( strtolower( $header ), 'content-type:' ) === 0 ) {
                    $pieces = explode( ':', strtolower( $header ) );
                    $content_type = trim( $pieces[1] );
                    break;
                }
            }
            if ( is_null( $content_type ) || substr( $content_type, 0, 9 ) === 'text/html' ) { // Apply rewrite to "text/html" or NULL
                $return = preg_replace( "/(<(script|link|base|img|form|a|meta|iframe|svg)([^>]*)(href|src|action|content|xmlns)=[\"'])https?:\\/\\//i", "$1//", $buffer );
                if ( $return ) { // Skip overwriting content on regex error
                    $buffer = $return;
                }
            }
            return $buffer;
        }
    }
}

if ( class_exists( 'FactMaven_RemoveHTTP' ) ) {
    global $rhttp;
    $rhttp = new FactMaven_RemoveHTTP();
}