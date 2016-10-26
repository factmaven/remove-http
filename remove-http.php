<?php
/**
 * Plugin Name: Remove HTTP
 * Plugin URI: https://wordpress.org/plugins/remove-http/
 * Description: Removes both HTTP and HTTPS protocols from links.
 * Version: 1.1.0
 * Author: Fact Maven
 * Author URI: https://www.factmaven.com
 * License: GPLv3
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Remove_HTTP {

    private $option;

    public function __construct() {
        # Get plugin option
        $this->option = get_option( 'factmaven_rhttp' );
        # Add link to plugin settings
        add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
        # Add custom settings field
        add_filter( 'admin_init', array( $this, 'settings_field' ), 10, 1 );
        # Relocate settings field using jQuery
        add_action( 'admin_footer', array( $this, 'settings_location' ), 10, 1 );
        # Remove HTTP and HTTPS protocols
        add_action( 'wp_loaded', array( $this, 'protocol_relative' ), 10, 1 );
    }

    public function settings_link( $links, $file ) {
        # Display settings link
        if ( $file == plugin_basename( __FILE__ ) && current_user_can( 'manage_options' ) ) {
            array_unshift( $links, '<a href="options-general.php#home">Settings</a>' );
        }
        # Return the settings link
        return $links;
    }

    public function settings_field() {
        # Register the setting
        register_setting( 'general', 'factmaven_rhttp' );
        # Add settings field
        add_settings_field( 'protocol_relative', 'Protocol Relative URL', array( $this, 'option' ), 'general' );
    }

    public function settings_location() {
        # Insert the settings field after the 'Site Address (URL)'
        ?> <script type="text/javascript">
        jQuery( '#protocol_relative' ).closest( 'tr' ).insertAfter( jQuery( '#home-description' ).closest( 'tr' ) );
        </script> <?php
    }

    public function option() {
        ?> <fieldset>
            <legend class="screen-reader-text"><span>Protocol Relative URL</span></legend>
            <label for="remove_http">
                <input name="factmaven_rhttp" type="checkbox" id="protocol_relative" value="1" <?php checked( '1', $this->option ); ?> /> Only apply to internal links
            </label>
            <p class="description">All external links will not be affected.</p>
        </fieldset> <?php
    }

    public function protocol_relative() {
        # Enable output buffering
        ob_start( function( $links ) {
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
                # If 'Protocol Relative URL' option is checked, only apply change to internal links
                if ( $this->option == 1 ) {
                    # Remove protocol from home URL
                    $website = preg_replace( '/https?:\/\//', '', home_url() );
                    # Remove protocol form internal links
                    $links = preg_replace( '/(<(script|link|base|img|form|a|meta|iframe|svg)([^>]*)(href|src|action|content)=["\'])https?:\/\/' . $website . '/i', '$1//' . $website, $links );
                }
                # Else, remove protocols form all links
                else {
                    $links = preg_replace( '/(<(script|link|base|img|form|a|meta|iframe|svg)([^>]*)(href|src|action|content)=["\'])https?:\/\//i', '$1//', $links );
                }
            }
            # Return protocol relative links
            return $links;
        } );
    }
}
# Instantiate the class
new Fact_Maven_Remove_HTTP();