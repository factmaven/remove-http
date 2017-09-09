<?php
/**
 * Plugin Name: Remove HTTP
 * Plugin URI: https://wordpress.org/plugins/remove-http/
 * Description: Removes both HTTP and HTTPS protocols from links.
 * Version: 2.1.0
 * Author: Fact Maven
 * Author URI: https://www.factmaven.com/
 * License: GPLv3
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Remove_HTTP {

    private $option, $plugin;

    public function __construct() {
        # Call the core Plugin API
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        # Get plugin's metadata
        $this->plugin = get_plugin_data( __FILE__ );
        # If the plugin version is lower or not defined, remove plugin options
        if ( ( get_option( 'factmaven_rhttp_version' ) < $this->plugin['Version'] ) || ! get_option( 'factmaven_rhttp_version' ) ) {
            # Remove options with the prefix "factmaven_rhttp_"
            foreach ( wp_load_alloptions() as $option => $value ) {
                if ( strpos( $option, 'factmaven_rhttp' ) === 0 ) delete_option( $option );
            }
            # Add options for new plugin version
            update_option( 'factmaven_rhttp_version', $this->plugin['Version'] );
        }

        # Get plugin options
        $this->option = get_option( 'factmaven_rhttp' );
        # Set default options
        if ( empty( $this->option['format'] ) ) $this->option['format'] = 'protocol-relative';
        if ( empty( $this->option['external'] ) ) $this->option['external'] = '0';
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
        # Register the settings
        register_setting( 'general', 'factmaven_rhttp' );
        # Add settings field
        add_settings_field( 'protocol_relative', 'URL Format', array( $this, 'options' ), 'general' );
    }

    public function settings_location() {
        # Insert the settings field after the 'Site Address (URL)'
        ?> <script type="text/javascript">
        jQuery( '#format-description' ).closest( 'tr' ).insertAfter( jQuery( '#home-description' ).closest( 'tr' ) );
        </script> <?php
    }

    public function options() {
        # Display plugin settings field
        ?> <fieldset>
        <label><input type="radio" name="factmaven_rhttp[format]" value="protocol-relative" <?php checked( 'protocol-relative', $this->option['format'] ); ?> checked="checked"> <span class="date-time-text format-i18n">Protocol-Relative</span><code>//example.com/sample-post/</code></label><br>
        <label><input type="radio" name="factmaven_rhttp[format]" value="relative" <?php checked( 'relative', $this->option['format'] ); ?>> <span class="date-time-text format-i18n">Relative</span><code>/sample-post/</code></label><br>
        <label><input name="factmaven_rhttp[external]" type="checkbox" value="1" <?php checked( '1', $this->option['external'] ); ?>> Ignore external links</label>
        <p class="description" id="format-description">Relative format will only affect internal links.</p>
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
                # Get domain without protocol                
                $website = preg_replace( '/^https?:\/\//', '', home_url() );
                $website = preg_replace( '/\/.*$/', '', $website );
                # Ignore input tags link tags with 'rel=canonical'
                $exceptions = '<(?:input\b[^<]*\bvalue=[\"\']https?:\/\/|link\b[^<]*?\brel=[\'\"]canonical[\'\"][^<]*?>)(*SKIP)(*F)';
                # If 'Ignore external links' is selected, only apply changes to internal links
                if ( $this->option['external'] == 1 ) {
                    if ( $this->option['format'] == 'relative' ) $links = preg_replace( '/' . $exceptions . '|https?:\/\/' . $website . '/', '', $links );
                    else $links = preg_replace( '/' . $exceptions . '|https?:\/\/' . $website . '/', '//$1' . $website, $links );
                }
                else {
                    if ( $this->option['format'] == 'relative' ) $links = preg_replace( '/' . $exceptions . '|https?:\/\/' . $website . '/', '', $links );
                    else $links = preg_replace( '/' . $exceptions . '|https?:\/\//', '//', $links );
                }
            }
            # Return protocol relative links
            return $links;
        } );
    }
}
# Instantiate the class
new Fact_Maven_Remove_HTTP();