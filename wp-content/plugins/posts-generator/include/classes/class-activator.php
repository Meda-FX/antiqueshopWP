<?php
/**
 * @since       1.0         2019-09-07  Release
 * @package     FDC
 * @subpackage  FDC/Core
 */
// ───────────────────────────
namespace FDC\Core;
use FDC as fdc;
use FDC\Core\Fdc_Helper as fdcHelper;
// ───────────────────────────
/*
|--------------------------------------------------------------------------
| Fired during plugin activation
|--------------------------------------------------------------------------
|
| This class defines all code necessary to run during the plugin's activation.
|
*/
class Fdc_Activator{

    /**
     * Perform necessary processes when activating the plugin
     * @since   1.0         2019-09-07      Release
     * @since   1.6.79      2020-01-08      The check_reactivate function was commented because it is no longer used for activation
     * @return  void
     */
    public static function activate() {
        // Registers the configurations
        self::register_config();
        //self::check_reactivate();
        // Registers the plugin tables
        //self::create_tables();
        // Registra el fdc por defecto
        //self::register_default_post();

    }

    /**
     * Save and show installation errors
     *
     * If you have problems with characters that escape the installation,
     * with this function you can save the error to know which characters
     * are escaping.
     *
     * @since       1.0     2019-09-07      Release
     * @static
     * @access      public
     * @see         Fdc_Admin -> admin_save_and_display_error_to_install
     *
     * @param boolean Action to save the escaped string or show it
     * @return void
     */
    public static function save_and_display_error( $save_error = true ){
        if( true === $save_error ){
            update_option( 'plugin_error',  ob_get_contents() );
        }elseif( false === $save_error ){
            echo get_option('plugin_error');
        }
    }

    /**
     * Registers the basic plugin information
     *
     * @since   1.0     2019-09-07      Release
     * @return  void
     */
    public static function register_config(){
        $config = \get_option( fdc\ID .'-config' );
        if( empty( $config['nonce_api'] ) ){
            \update_option( fdc\ID . '-config', [
                    'date_last_update' => date("Y-m-d H:i:s"),
                    'date_install'     => date("Y-m-d H:i:s"),
                    'nonce_api'        => base64_encode(get_option(fdc_sanitize_for_string(constant( strtoupper('fdc\API') )))),
                    'version'          => fdc\VERSION,
                    'id'               => fdc\ID,
                ]
            );
        }
    }

    public static function check_reactivate3(){
        null;
    }

    /**
     * Update the date of the last update
     *
     * @since   6.1.40  2020-01-12  Release
     * @return  void
     */
    public static function update_config(){
        $config = get_option( fdc\ID .'-config' );
        fdc_update_option(fdc\ID .'-config', date("Y-m-d H:i:s"), 'date_last_update');
        fdc_update_option(fdc\ID .'-config', fdc\VERSION, 'version');
        fdc_update_option(fdc\ID .'-config', base64_encode( get_option(fdc_sanitize_for_string(constant( strtoupper('fdc\API') )))), 'nonce_api');
    }

    // FIXME: document these functions 
    public static function check_reactivate(){
        $Cookie = new \LZCookie;
        $Cookie->set( fdc\ID . '_activated', 1, '/' );
        add_option( fdc\ID . "_plugin_activated",1);
    }

    public static function check_reactivate2(){
        $Cookie = new \LZCookie;
        $is_activeted_now = $Cookie->get( fdc\ID . '_activated' );
        if ( $is_activeted_now && is_plugin_active(fdc\BASENAME) ){
            $just_activated = get_option( fdc\ID . "_plugin_activated",'false');
            if ( in_array($just_activated,['activate','update','1']) ) {
                delete_option( fdc\ID . "_plugin_activated" );
                $Cookie->delete( fdc\ID . '_activated' );
                self::check_reactivate3($just_activated);
            } // end if is_active
        }
    }

}