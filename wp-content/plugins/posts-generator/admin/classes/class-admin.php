<?php
/**
 * @since 		1.0     2019-09-07     Release
 * @package 	FDC
 * @subpackage 	FDC/Admin
 */
// ───────────────────────────
namespace FDC\Admin;
use FDC as fdc;
use FDC\Core as fdcCore;
// ───────────────────────────
/*
|--------------------------------------------------------------------------
| The admin-specific functionality of the plugin.
|--------------------------------------------------------------------------
|
| Defines the plugin name, version, hooks for how to
| enqueue the admin-specific stylesheet, javaScript and admin processes
|
*/
class Fdc_Admin{
    private
    /**
     * The ID
     * @since   1.0   2019-09-07     Release
     * @access  private
     * @var     string
     */
    $name,
    /**
     * The version
     *
     * @since    1.0       2019-09-07     Release
     * @access   private
     * @var      string    $version                The current version of this plugin.
     */
    $version;

    /**
     * Indicates that if the login user can have permission
     * to load panel setting.
     *
     * @since    1.0    2019-09-07     Release
     * @access   public
     * @var      bool
     */
    public static
    $is_user_panel_setting = false;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0       2019-09-07      Release
     *
     * @param    string    $name 		            The name of this plugin.
     * @param    string    $version 	            The version of this plugin.
     * @return   void
     */
    public function __construct( $name, $version ) {

        $this->name    = $name;
        $this->version = $version;

        // ─── Load Core Files ────────
        if( self::isUserAllow() ){
            $this->load_files_admin();
        }

    }

    /**
     * Validate a user according to the role he has
     * default is now the 'administrator'
     *
     * @since       1.0         2019-09-07     Release
     * @access      public
     * @static
     *
     * @return boolean
     */
    public static function isUserAllow(){

        if( is_network_admin() ){
            if( ! (defined('LOGGED_IN_COOKIE') && isset($_COOKIE['LOGGED_IN_COOKIE'])) ) return;
            if( ! (defined('SECURE_AUTH_COOKIE') && isset($_COOKIE['SECURE_AUTH_COOKIE'])) ) return;
        }

        if( ! function_exists('wp_get_current_user' ) ) {
            require_once ABSPATH . "wp-includes/pluggable.php" ;
        }
        $allowed_roles = apply_filters( fdc\ID . '_role_user_allow',array('administrator') );
        $user          = wp_get_current_user();
        $is_user_admin = array_intersect($allowed_roles, $user->roles );
        $is_value      = ( is_admin() || is_customize_preview() || $is_user_admin );
        if( $is_value ){ self::$is_user_panel_setting = true; }
        return $is_value;
    }

    /**
     * Register the stylesheets and script for the admin area.
     * @since   1.0 2019-09-07  Release
     * @since   1.7 2020-02-19  Now the files run minified for faster speed
     */
    public function enqueue_script_styles() {
        //$min = ! fdc\MODE_DEV ? '.min' : '';
        $min = '.min';
        $version_file = fdc\MODE_DEV ? rand(111,999) : $this->version;
        /**
         * An instance of this class should be passed to the run() function
         * defined in Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style( $this->name, fdc\URL . 'admin/assets/css/style' . $min . '.css', array(), $version_file, 'all' );
        wp_enqueue_script( $this->name . '-plugins', fdc\URL . 'admin/assets/js/fdc-plugins' . $min . '.js', array( 'jquery' ), $version_file, true );
        wp_enqueue_script( $this->name, fdc\URL . 'admin/assets/js/fdc' . $min . '.js', array( 'jquery' ), $version_file, true );

        // Var JS
        $args_localizer = array(
            'url' => fdc\URL, 'type' => 'LITE', 'version' => fdc\VERSION, 'aip' => fdc_get_real_ip(), 'id' => fdc\ID,
            'updates' => (int)0, 'desc' => get_bloginfo( 'description', 'display' ), 'host' => parse_url(home_url(), PHP_URL_HOST),
            'title' => get_bloginfo('name'), 'nonce'  => \fdc_get_option( fdc\ID .'-config','nonce_api'),'di' => fdc_get_option( fdc\ID . '-config','date_install'),
            'nonce_generate' => wp_create_nonce('fdc_nonce')
        );
        wp_localize_script( $this->name  , 'fdc_vars', $args_localizer );
    }

    /**
     * Load the necessary files so that the public
     * start working correctly.
     *
     * @since   1.0     2019-09-07     Release
     *
     * @return  void
     */
    public function load_files_admin(){
        // ─── Load functions ────────
        $files[] = [
                    'admin/functions/helpers',
                    'admin/functions/actions',
                ];

        // Fetch ↓
        foreach($files as $file)
            foreach($file as $value)
                $this->load_file( fdc\PATH . '/' . $value .'.php' );
    }

    /**
     * Upload a file sent by a route
     *
     * @since   1.0	    2019-09-07      Release
     *
     * @param   string  $file           Name or path of the file
     * @return  void
     */
    public function load_file( $file ){
        require_once $file;
    }

    /**
     * Performs functions when activating the plugin
     *
     * @since   1.0                 2019-09-07     Release
     * @hook    activated_plugin
     * @access  public
     * @see     FDC -> define_admin_hooks
     */
    public static function admin_activated_plugin( $plugin ){
        fdcCore\Fdc_Activator::activate();
        //fdcCore\Fdc_Activator::check_update_and_activate();
         // ─── The path to our plugin's main file ────────
        /* $our_plugin = fdc\BASENAME;
        // ─── If an update has taken place and the updated type is plugins and the plugins element exists ────────
        if( $plugin == $our_plugin ) {
            // ─── Iterate through the plugins being updated and check if ours is there ────────
            fdcCore\Fdc_Activator::activate();
        } */
    }

    /**
     * Show output errors and save them
     *
     * @since   1.0     2019-09-07      Release
     * @return  void
     */
    public static function admin_save_and_display_error_to_install(){
        fdcCore\Fdc_Activator::save_and_display_error();
    }

    /**
     * Perform processes after an update
     *
     * @since   1.0     2019-09-07      Release
     * @since   1.5.8   2019-11-25      The namespace-based constant is corrected
     * @return  void
     */
    public static function after_upgrade_plugin( $upgrader_object, $options ){
        // ─── The path to our plugin's main file ────────
        $our_plugin = fdc\BASENAME;
        // ─── If an update has taken place and the updated type is plugins and the plugins element exists ────────
        if( is_array($options) && $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            // ─── Iterate through the plugins being updated and check if ours is there ────────
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == $our_plugin ) {
                    // ─── Update new configuration information ────────
                    self::check_update_config();
                    // ─── It guarantees that everything is correct ────────
                    // fdcCore\Fdc_Activator::check_reactivate();
                    //self::admin_check_reactivate2( 'update' );
                }
            }
        }
    }

    public static function admin_check_reactivate2(){
        fdcCore\Fdc_Activator::check_reactivate2();
    }

    /**
     * Verify new structure changes in the plugin table
     *
     * @since   1.0     2019-09-07     Release
     * @access  private
     */
    private function check_update_table_db(){
        //Fdc_Activator::create_tables();
    }

    // FIXME: documenta esta funcion
    private function check_update_config(){
        fdcCore\Fdc_Activator::register_config();
        fdcCore\Fdc_Activator::update_config();
        //fdcCore\Fdc_Activator::check_reactivate();
    }

}