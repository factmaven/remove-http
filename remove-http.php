<?php
/**
 * Plugin Name: Remove HTTP
 * Plugin URI: https://wordpress.org/plugins/remove-http/
 * Description: Removes both HTTP and HTTPS protocols from links.
 * Version: 2.0.0-beta
 * Author: Fact Maven
 * Author URI: https://www.factmaven.com
 * License: GPLv3
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Remove_HTTP {
    # Define plugin option value
    private $option;
    # Keys are tag names, values are an array of attributes to process for those tags
    /*private $tag_attribute = array(
        'script' => array( 'src' ),
        'link' => array( 'href' ),
        'base' => array( 'href' ),
        'img' => array( 'src', 'srcset' ),
        'form' => array( 'action' ),
        'a' => array( 'href' ),
        'meta' => array( 'content' ),
        'iframe' => array( 'src' ),
        'div' => array( 'data-project-file', 'style' ),
        'svg' => array( 'data-project-file' ),
    );*/
    # These attributes will be processed for all tags
    private $list_attributes = array( 'href', 'src', 'srcset', 'style' );

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
        # New settings section below, will work on it after
        ?>
        <!-- <fieldset>
            <legend class="screen-reader-text"><span>Site Address Format</span></legend>
            <label>
                <input name="factmaven_rhttp_format" type="radio" value="0" <?php checked( '0', $this->option ); ?>> Protocol-Relative URL<code>//example.com/sample-post/</code>
            </label><br>
            <label>
                <input name="factmaven_rhttp_format" type="radio" value="1" <?php checked( '1', $this->option ); ?>> Relative URL<code>/sample-post/</code>
            </label><br>
            <label for="remove_http">
                <input name="factmaven_rhttp_internal" type="checkbox" id="internal_links" value="1" <?php checked( '1', $this->option ); ?> /> Only apply to internal links
            </label>
            <p class="description">All external links will not be affected.</p>
        </fieldset> -->
        <?php
    }

    public function protocol_relative() {
        # Enable output buffering
        ob_start( array( $this, 'ob_flush_handler' ) );
    }

    public function ob_flush_handler( $html ) {
        # Apply processing only if the type is applicable
        if ( $this->is_applicable_content_type() ) {
            return $this->process_html( $html );
        }
        else {
            return $html;
        }
    }

    private function is_applicable_content_type() {
        # Try to find the value of the Content-Type header and check if it is text/html
        foreach ( headers_list() as $header ) {
            if ( stripos( $header, 'content-type:' ) === 0 ) {
                $pieces = explode( ':', $header );
                return strpos( strtolower( trim( $pieces[1] ) ), 'text/html' ) === 0;
            }
        }
        # We didn't find it, it was not declared so assume HTML
        return TRUE;
    }

    private function process_element_attributes( DOMElement $element, array $attributes, $regex ) {
        foreach ( $attributes as $attribute ) {
            # Check that the element actually has the target attribute
            if ( !$element->hasAttribute( $attribute ) ) {
                continue;
            }
            # Get the current attribute value, modify it and set it back on the element
            $content = $element->getAttribute( $attribute );
            $result = preg_replace( $regex, '$1', $content );
            $element->setAttribute( $attribute, $result );
        }
    }

    private function remove_scripts( $html ) {
        $scripts = [];
        $i = 0;
        $template = '___REPLACED_SCRIPT_%d___';

        $html = preg_replace_callback( '#<script.+?</script>#is', function( $match ) use( $html, $template, &$scripts, &$i ) {
            do {
                $replacement = sprintf( $template, $i++ );
            }
            while ( strpos( $html, $replacement ) !== FALSE );
            $scripts[$replacement] = $match[0];
            return $replacement;
        }, $html );

        return [$html, $scripts];
    }

    private function add_scripts( $html, $scripts ) {
        foreach ( $scripts as $placeholder => $script ) {
            $html = str_replace( $placeholder, $script, $html );
        }
        return $html;
    }

    private function process_html( $html ) {
        # Check that we have DOM loaded, return the data unmodified if we don't
        if ( !class_exists( 'DOMDocument' ) || !class_exists( 'DOMXPath' ) ) {
            return $html;
        }

        list( $html, $scripts ) = $this->remove_scripts( $html );

        # Try to create a document and xpath, return the data unmodified if we can't
        $doc = new \DOMDocument();
        if ( !@$doc->loadHTML( $html, LIBXML_HTML_NODEFDTD ) ) {
            return $html;
        }
        $xpath = new \DOMXPath( $doc );

        # Create the regex in use based on the current option value
        if ( $this->option == 1 ) {
            $regex = '#https?:(//' . preg_replace( '/https?:\/\//', '', home_url() ) . ')#i';
        }
        else {
            $regex = '#https?:(//[^/]+)#i';
        }

        # Process the specific tag lists first
        /*foreach ( $this->tag_attribute as $tag => $attributes ) {
            $xpath_expr = '//' . $tag . '[@' . implode( ' or @', $attributes ) . ']';
            foreach ( $xpath->query( $xpath_expr ) as $element ) {
                $this->process_element_attributes( $element, $attributes, $regex );
            }
        }*/

        # Process global attributes
        if ( !empty( $this->list_attributes ) ) {
            foreach ( $xpath->query( '//*[@' . implode( ' or @', $this->list_attributes ) . ']' ) as $element ) {
                $this->process_element_attributes( $element, $this->list_attributes, $regex );
            }
        }

        # Return the modified HTML as a string
        return $this->add_scripts( $doc->saveHTML(), $scripts );
    }
}
# Instantiate the class
new Fact_Maven_Remove_HTTP();