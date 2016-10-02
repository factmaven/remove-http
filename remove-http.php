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
        # remove HTTP and HTTPS protocols
        add_action( 'plugins_loaded', array( $this, 'output_buffering' ), 10, 1 );
    }

    public function output_buffering() {
        # Enable output buffering
        ob_start( array( $this, 'remove_protocols' ) );
    }

    public function remove_protocols( $buffer ) {
        # Find and remove both 'http:' and 'https:' protocols
        $buffer = preg_replace( '~=\s*["\']\s*https?:(.*?)["\']~i', '="$1"', $buffer );
        # Return the links as protocol relative
        return $buffer;
    }

new Fact_Maven_Remove_HTTP();