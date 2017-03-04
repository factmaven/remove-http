<?php
/**
 * Plugin Name: Remove HTTP
 * Plugin URI: https://wordpress.org/plugins/remove-http/
 * Description: Removes both HTTP and HTTPS protocols from links.
 * Version: 1.1.1
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
        add_action( 'wp_loaded', array( $this, 'protocol_relative' ), PHP_INT_MAX, 1 );
    }

    public function settings_link( $links, $file ) {
        # Display settings link
        if ( $file == plugin_basename( __FILE__ ) && current_user_can( 'manage_options' ) ) {
            array_unshift( $links, '<a href="options-general.php#home"><span class="dashicons dashicons-admin-settings"></span> Settings</a>' );
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

    private function get_content_type() {
        # Try to find the value of the Content-Type header
        foreach ( headers_list() as $header ) {
            if ( stripos( $header, 'content-type:' ) === 0 ) {
                $pieces = explode( ':', $header );
                return strtolower( trim( $pieces[1] ) );
            }
        }
        # We didn't find it
        return NULL;
    }

    private function process_html( $html ) {
        $tag = 'script|link|base|img|form|a|meta|iframe|svg|div';
        $attribute = 'href|src|srcset|action|content|data-project-file';
        # If 'Protocol Relative URL' option is checked, only apply change to internal links
        if ( $this->option == 1 ) {
            # Remove protocol from home URL
            $website = preg_replace( '/https?:\/\//', '', home_url() );
            # Remove protocol from internal links
            $html = preg_replace( '/(<(' . $tag . ')([^>]*)(' . $attribute . ')=["\'])https?:\/\/' . $website . '/i', '$1//' . $website, $html );
        }
        # Else, remove protocols from all links
        else {
            $html = preg_replace( '/(<(' . $tag . ')([^>]*)(' . $attribute . ')=["\'])https?:\/\//i', '$1//', $html );
        }
        # Return protocol relative links
        return $html;
    }

    public function ob_flush_handler( $html ) {
        # Get the declared Content-Type, if any
        $content_type = $this->get_content_type();

        # Apply processing only if the type is declared as text/html or not declared
        return $content_type === null || strpos( $content_type, 'text/html' ) === 0
            ? $this->process_html( $html )
            : $html;
    }

    public function protocol_relative() {
        # Enable output buffering
        ob_start( array( $this, 'ob_flush_handler' ) );
    }
}
# Instantiate the class
new Fact_Maven_Remove_HTTP();
