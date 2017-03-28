<?php
/**
 * Plugin Name: Remove HTTP
 * Plugin URI: https://wordpress.org/plugins/remove-http/
 * Description: Removes both HTTP and HTTPS protocols from links.
 * Version: 2.0.0
 * Author: Fact Maven
 * Author URI: https://www.factmaven.com/
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
        add_action( 'wp_loaded', array( $this, 'protocol_relative' ) , PHP_INT_MAX, 1 );
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
        add_settings_field( 'protocol_relative', 'Site Address Format', array( $this, 'options' ), 'general' );
    }

    public function settings_location() {
        # Insert the settings field after the 'Site Address (URL)'
        ?> <script type="text/javascript">
        jQuery( '#protocol-description' ).closest( 'tr' ).insertAfter( jQuery( '#home-description' ).closest( 'tr' ) );
        </script> <?php
    }

    public function options() {
        ?> <fieldset><legend class="screen-reader-text"><span>Site Address Format</span></legend>
        <label><input type="radio" name="factmaven_rhttp" value="1" <?php checked( '1', $this->option ); ?> checked="checked"> <span class="date-time-text format-i18n">Protocol-Relative</span><code>//example.com/sample-post/</code></label><br>
        <label><input type="radio" name="factmaven_rhttp" value="2" <?php checked( '2', $this->option ); ?>> <span class="date-time-text format-i18n">Relative</span><code>/sample-post/</code></label><br>
        <p class="description" id="protocol-description">Selecting Relative will only apply to internal links. External links will become Protocol-Relative.</p></td>
        </fieldset> <?php
    }

    public function protocol_relative() {
        # Enable output buffering
        ob_start( function( $links ) {
            # Check for 'Content-Type' headers only
            $content_type = NULL;
            foreach ( headers_list() as $header ) {
                if ( strpos( strtolower( $header ), 'content-type:' ) === 0 ) {
                    $pieces = explode( ':', strtolower( $header ) );
                    $content_type = trim( $pieces[1] );
                    break;
                }
            }
            # If the content-type is 'NULL' or 'text/html', apply rewrite
            if ( is_null( $content_type ) || substr( $content_type, 0, 9 ) === 'text/html' ) {
                # If 'Relative' option is selected, remove domain from all internal links
                $exceptions = '<(?:input\b[^<]*\bvalue=[\"\']https?:\/\/|link\b[^<]*?\brel=[\'\"]canonical[\'\"][^<]*?>)(*SKIP)(*F)';
                if ( $this->option == 2 ) {
                    $website = preg_replace( '/https?:\/\//', '', home_url() );
                    $links = preg_replace( '/' . $exceptions . '|https?:\/\/' . $website . '/', '', $links );
                }
                # For all external links, remove protocols
                $links = preg_replace( '/' . $exceptions . '|https?:\/\//', '//', $links );
            }
            # Return protocol relative links
            return $links;
        } );
    }
}
# Instantiate the class
new Fact_Maven_Remove_HTTP();