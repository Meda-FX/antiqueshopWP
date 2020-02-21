<?php
/**
 * @sinc        1.0     2019-09-06      Release
 *
 * @package 	FDC
 * @subpackage 	FDC/Core
 */
// ───────────────────────────
namespace FDC\Core;
use FDC;
use FDC\Core as fdcCore;
use FDC\Admin as fdcdmin;
// ───────────────────────────
/*
|--------------------------------------------------------------------------
| Main Class
|--------------------------------------------------------------------------
|
| This is the initial and main plugin class, here is
    | The bases for the operation of everything are executed.
|
*/
final class Fdc_Core{

    /**
     * Existing instance
     *
     * Instance of the main class of the plugin
     * with this (deny duplicate).
     *
     * @access  protected
     * @since   1.0             2019-09-06     Release
     * @var     object|mixed
     */
    protected static $instance;

    public
    /**
     * The ID|Slug plugin
     * @since   1.0         2019-09-06     Release
     * @access  public
     * @var     string
     */
    $name = FDC\ID,
    /**
     * Version
     * @since   1.0         2019-09-06   Release
     * @access  public
     * @var     string|int
     */
    $version = FDC\VERSION,
    /**
     * URL public plugin
     * @since   1.0     2019-09-06     Release
     * @access  public
     * @var     string
     */
    $url = FDC\URL,
    /**
     * URL server plugin
     * @since   1.0     2019-09-06     Release
     * @access  public
     * @var     string
     */
    $path = FDC\PATH,
    /**
     * Maintains and registers all hooks for the plugin
     * @since   1.0     2019-09-06     Release
     * @access  public
     * @var     object
     */
    $loader,
    /**
     * Refers to the admin object of the plugin
     * @since   1.0     2019-09-06     Release
     * @access  public
     * @var     object
     */
    $admin,
    /**
     * Customize the administration (aesthetic part)
     * @since   1.0     2019-09-06      Release
     * @access  public
     * @var     object
     */
    $admin_custom,
    /**
     * Refers to the public object of the plugin
     * @since   1.0     2019-09-06     Release
     * @access  public
     * @var     object
     */
    $public,
    /**
     * Get all general options
         * of the application.
        * @var mixed|object
        */
    $options = null,
    /**
     * Variable that contains the setting main
     *
     * @since 	6.0
     * @access 	public
     * @var 	Fdc    $options    Get settings from the database
     */
    $settings,
    /**
     * Object that records plugin logs
     *
     * @since   1.0         2019-09-06
     * @access  public
     * @var     phpConsole  Library to register logs in the javascript console
     */
    $logs = null,
    /**
     * Functions that help the plugin in general
     *
     * @since   1.0     2019-09-14
     * @access  public
     * @var     object
     */
    $helper = null;

    /**
     * Get class instance (Singleton is Rock!)
     *
     * @since 	1.0     2019-09-06     Release
     * @return 	object
     */
    public static function instance() {
        if ( ! isset( static::$instance ) || !static::$instance ) {
            static::$instance = new static();
            static::$instance->init();
        }
        return static::$instance;
    }

    /**
     * Initializer of the class.
     *
     * @since   1.0     2019-09-06     Release
     * @return  void
     */
    public function init(){

        // ─── Init action ────────
        do_action( 'FDC_init' );

        // ─── Load Core Files ────────
        $this->load_files_core();

        // ─── Load Setup ────────
        $this->setup();

    }

    /**
     * Load Setup initial plugin
     *
     * @since   1.0     2019-09-06     Release
     * @return void
     */
    public function setup(){
        //$this->register_logs();
        $this->load_dependencies();
        $this->get_options();
        $this->set_locale();
        $this->define_admin_hooks();
    }

    /**
     * Load the necessary files so that the core
         * start working correctly.
        *
        * @since	1.0     2019-09-06     Release
        * @return  void
        */
    public function load_files_core(){
        // ─── Load framework ────────
        $files[] = ['admin/framework/pixel/pixel-framework'];

        // ─── Load libs (no dependency) ────────
        $files[] = [
            //'include/libs/SqlQueryBuilder',
            //'include/libs/phpConsole',
            'include/libs/LZCookie',
        ];

        // ─── Load functions ────────
        $files[] = [
            'include/functions/sanitize',
            'include/functions/validate',
            //'include/functions/helper',
            'include/functions/actions',
        ];

        // ─── Load options plugin ────────
        $files[] = ['admin/options/init'];

        // Fetch ↓
        foreach( $files as $file )
            foreach($file as $value)
                self::load_file( $this->path . '/' . $value .'.php' );

    }

    /**
     * Upload a file sent by a route
     *
     * @since   1.0	    2019-09-06     Release
     *
     * @param   string  $file                   Name or path of the file
     * @return  void
     */
    public static function load_file( $file ){
        require_once $file;
    }

    /**
     * Load the initial dependencies
         * of the plugin, these are environment variables
        *
        * @since	1.0	    2019-09-06     Release
        * @return	void
        */
    protected function load_dependencies(){
        // ─── Set hooks and filters ────────
        $this->loader = new fdcCore\Fdc_Loader;

        // ─── Set the help variable ────────
        $this->helper = new fdcCore\Fdc_Helper;

        // ─── Set admin functions ────────
        $this->admin = new fdcdmin\Fdc_Admin( $this->get_plugin_name(), $this->get_version() );

        // ─── Set admin aesthetics ────────
        $this->admin_custom = new fdcdmin\Fdc_AdminCustom( $this->get_plugin_name(), $this->get_version() );
    }

    /**
     * Get ID plugin
     * The name|slug of the plugin
     *
     * @since   1.0     2019-09-06     Release
     * @return  string
     */
    public function get_plugin_name() {
        return $this->name;
    }

    /**
     * Retrieve the version number of the plugin
     * The version number of the plugin.
     *
     * @since   1.0     2019-09-06     Release
     * @return  string
     */
    public function get_version() {
        return  $this->version;
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the FDC_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since   1.0     2019-09-06    Release
     * @access  private
     */
    private function set_locale() {
        $plugin_i18n = new fdcCore\Fdc_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since   1.0         2019-09-06  Release
     * @since   1.1         2019-10-30  The update_footer filter was added
     * @since   1.3         2019-11-03  The action is added for the function 'add_button_coffee'
     * @since   1.6.3       2019-12-05  Better interpretation when activating a plugin
     * @since   1.7         2020-02-19  New feedback was added
     * @access  private
     * @return  void
     */
    private function define_admin_hooks() {
        // ─── Load style and script ────────
        $this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_script_styles' );

        // ─── Load functionalities (framework)    ↓ ────────
        //$this->loader->add_action( 'pixel_options_fdc_buttons_after', $this->admin_custom, 'add_button_support' );

        // ───Load other actions ────────
        // Change the order of Fdc submenus
        $this->loader->add_action( 'activated_plugin', $this->admin, 'admin_activated_plugin' );
        //$this->loader->add_action( 'activated_plugin', $this->admin, 'admin_save_and_display_error_to_install' );
        $this->loader->add_action( 'in_admin_footer', $this->admin, 'admin_check_reactivate2', 99 );
        $this->loader->add_action( 'upgrader_process_complete', $this->admin, 'after_upgrade_plugin' );
        $this->loader->add_action( 'admin_footer',  $this->admin_custom, 'form_feedback_uninstall', 99999 );

        $this->loader->add_filter( 'update_footer', $this->admin_custom, 'admin_footer2', 99 );
        $this->loader->add_action( 'pixel_options_fdc-setting_buttons_after', $this->admin_custom, 'add_button_coffee' );
        //$this->loader->add_action( 'in_admin_header',  $this->admin_custom, 'yuzo_header_in_cpt', 150 );
        //$this->loader->add_action( 'pf_yuzo-setting_before_header',  $this->admin_custom, 'header_in_setting' );
        // ─── AJAX actions ────────
        //add_action( 'wp_ajax_generate-users', 'fdcActions\\generate_users' );
        //$this->logs->log("Load admin hook");
    }

    /**
     * Load options general
     *
     * @since	1.0	            2019-09-07     Release
     * @return	object|mixed
     */
    public function get_options(){
        return $this->settings = $this->helper->get_option();
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since   1.0   2019-09-06     Release
     * @return  void
     */
    public function run() {
        // Loads hook and filters
        $this->loader->run();
    }
}

/**
 * Initial validation that guarantees that Fdc works with
 * PHP7 onwards.
 */
if( ! version_compare(phpversion(), '7.0', '>=') ){
    //! Fallback for very old php version
    add_action('admin_notices', function () {
    ?>
    <div class="notice notice-error">
        <p><?php _ex('Your PHP version is <a href="http://php.net/supported-versions.php" rel="noreferrer" target="_blank">outdated</a> and not supported by Fdc. Please disable Fdc, upgrade to PHP 7.0 or higher, and enable Fdc again. It is necessary to follow these steps in order.', 'Status message', 'yuzo'); ?></p>
    </div>
    <?php
    } );
}else{
    // Run!
    global  $FDC;
    $FDC 	= Fdc_Core::instance();
    $FDC->run(); //!! 💯
}