<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Plugin Name: Ninja Forms - Session
 * Plugin URI: http://github.com/isleshocky77/ninja-forms-session
 * Description: Add ability to Save for values to the Session and pull them back using MergeTags
 * Version: 3.0.1
 * Requires at least: 4.3
 * Tested up to: 4.7
 * Author: Stephen Ostrow <stephen@ostrow.tech>
 * Author URI: http://ostrow.tech
 * Text Domain: ninja-forms-session
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Copyright 2017 Stephen Ostrow .
 */

if( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3', '<' ) || get_option( 'ninja_forms_load_deprecated', FALSE ) ) {

    //include 'deprecated/ninja-forms-session.php';

} else {

    /**
     * Class NF_Session_Add_On
     */
    final class NF_Session_Add_On
    {
        const VERSION = '3.0.1';
        const SLUG    = 'session';
        const NAME    = 'Session';
        const AUTHOR  = 'Stephen Ostrow ';
        const PREFIX  = 'NF_Session_Add_On';

        /**
         * @var NF_Session_Add_On
         * @since 3.0
         */
        private static $instance;

        /**
         * Plugin Directory
         *
         * @since 3.0
         * @var string $dir
         */
        public static $dir = '';

        /**
         * Plugin URL
         *
         * @since 3.0
         * @var string $url
         */
        public static $url = '';

        /**
         * Main Plugin Instance
         *
         * Insures that only one instance of a plugin class exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 3.0
         * @static
         * @static var array $instance
         * @return NF_Session_Add_On Session Instance
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof NF_Session_Add_On)) {
                self::$instance = new NF_Session_Add_On();

                self::$dir = plugin_dir_path(__FILE__);

                self::$url = plugin_dir_url(__FILE__);

                /*
                 * Register our autoloader
                 */
                spl_autoload_register(array(self::$instance, 'autoloader'));
            }

            return self::$instance;
        }

        public function __construct()
        {
            add_action( 'admin_init', array( $this, 'setup_license') );

            add_filter( 'ninja_forms_register_actions', array($this, 'register_actions'));

            add_filter( 'ninja_forms_register_merge_tags', array($this, 'register_merge_tags'));

            add_shortcode('nf_session_field_value', [$this, 'display_session_field_value']);
        }

        /**
         * Optional. If your extension processes or alters form submission data on a per form basis...
         */
        public function register_actions($actions)
        {
            $actions[ 'save-to-session' ] = new NF_Session_Add_On_Actions_SaveToSession(); // includes/Actions/SessionExample.php

            return $actions;
        }

        public function register_merge_tags($merge_tags)
        {

            $merge_tags['session'] = new NF_Session_Add_On_MergeTags_Session();

            return $merge_tags;
        }

        /*
         * Optional methods for convenience.
         */

        public function autoloader($class_name)
        {
            if (class_exists($class_name)) return;

            if ( false === strpos( $class_name, self::PREFIX ) ) return;

            $class_name = str_replace( self::PREFIX, '', $class_name );
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            if (file_exists($classes_dir . $class_file)) {
                require_once $classes_dir . $class_file;
            }
        }

        /*
         * Required methods for all extension.
         */

        public function setup_license()
        {
            if ( ! class_exists( 'NF_Extension_Updater' ) ) return;

            new NF_Extension_Updater( self::NAME, self::VERSION, self::AUTHOR, __FILE__, self::SLUG );
        }

        /**
         * Displays a field's session value through a shortcode
         *
         * @param array $atts
         * @param string $content
         * @return string
         */
        public function display_session_field_value($atts = [], $content = null)
        {
            $wp_session = \WP_Session::get_instance();

            $fieldKey = isset($atts['field_key']) ? $atts['field_key'] : false;

            if ($fieldKey && isset( $wp_session[ NF_Session_Add_On_Actions_SaveToSession::$sessionPrefix . $fieldKey]) ) {
                return $wp_session[ NF_Session_Add_On_Actions_SaveToSession::$sessionPrefix . $fieldKey];
            }

            return $content;
        }
    }

    /**
     * The main function responsible for returning The Session Plugin
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * @since 3.0
     * @return {class} Session Instance
     */
    function NF_Session_Add_On()
    {
        return NF_Session_Add_On::instance();
    }

    NF_Session_Add_On();
}
