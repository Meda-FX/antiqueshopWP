<?php
/**
 * @since 		1.0         2019-09-07      Release
 * @since       1.6         2019-11-25      The use FDC as fdc was added
 * @package 	FDC
 * @subpackage 	FDC/Admin
 */
// ───────────────────────────
namespace FDC\Admin;
use FDC as fdc;
// ──────────────────────────
class Fdc_AdminCustom{

    private
    /**
     * The ID
     * @since   1.0     2019-09-07     Release
     * @access  private
     * @var     string
     */
    $name,
    /**
     * The version
     *
     * @since       1.0         2019-09-07     Release
     * @access      private
     * @var         string      $version        The current version of this plugin.
     */
    $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since       1.0         2019-09-07  Release
     *
     * @param       string      $name       The name of this plugin.
     * @param       string      $version    The version of this plugin.
     * @return      void
     */
    public function __construct( $name, $version ) {
        $this->name    = $name;
        $this->version = $version;
    }

    /**
     * Add the support button in the setting options
     *
     * @since   1.0     2019-09-07     Release
     * @return  string|html
     */
    public function add_button_support(){
        //echo  '<a href="#" target="_black" id="wpac-button-support" class="button button-secondary" >' . esc_html__( 'Support', 'wpac' ) . '</a>';
    }

    /**
     * Yuzo main setting header
     *
     * @since   1.0     2019-08-28      Release
     * @return  void
     */
    public function header_in_setting(){ ?>
        <div class="yzp-header-wrapper">
            <span class="yzp-logo-text"><img src='<?php echo FDC_URL . 'admin/assets/images/icon.png'; ?>' />uzo</span>
            <span class="yzp-subtitle">PRO <span class="yzp-version">v.<?php echo FDC_VERSION; ?></span></span>
            <div class='yzp-logo'></div>
        </div><?php
    }

    /**
     * Generated Object Counter (filter)
     *
     * @since   1.1     2019-10-30      Release
     * @since   1.4     2019-11-07      Tooltip was added for the description
     * @since   1.5     2019-11-11      - Now shows the detail of the created objects
     *                                  - Now shows the time saved by the plugin
     * @since   1.7     2020-02-19      Statistics add to the integration plugins, for now with the YUZO plugin
     *
     * @param   string  $text           Footer text to update
     * @return  string
     */
    public static function admin_footer2( $text ) {
        global $pagenow;
        if ( 'admin.php' == $pagenow && pf_get_var('page') == 'fdc-setting' ) {
            $result = fdc_get_total_number_generate();
            require_once fdc\PATH . 'include/libs/LZTimer.php';
            $time = new \LZTimer;

            // ─── Integration ────────
            // Yuzo
            $_html_integration = '';
            if(  $result['objects']['yuzo_views'] || $result['objects']['yuzo_clicks'] ){
                $_html_integration .= "<span class='fdc-number-obj--separate'></span>";
                $_html_integration .= "<strong>Yuzo</strong><br />";
                $_html_integration .= "Views: " . $result['objects']['yuzo_views'] . '<br />';
                $_html_integration .= "Clicks: " . $result['objects']['yuzo_clicks'] . '<br />';
            }
            // ───────────────────────────

            $text = "<span class='fdc-tooltip top fdc-number-obj' >⚡ <strong>". $result['number'] ."</strong>
            <span class='tiptext'>".__( '
            Total number generated<br />
            <span class="fdc-number-obj--separate"></span>
            <strong>Core</strong><br />
            Users: ' . $result['objects']['users'] . '<br />'
            .'Terms: ' . $result['objects']['terms'] . '<br />'
            .'Posts: ' . $result['objects']['posts'] . '<br />'
            .'Comments: ' . $result['objects']['comments'] . '<br />'
            .'Menus: ' . $result['objects']['menus']
            , 'fdc' )."
                ". $_html_integration . "
                </span>
            </span>
            <span class='fdc-tooltip left' >&nbsp;&nbsp;&nbsp;🕚 <strong>". $time->getTimer($result['time']) ."</strong>
            <span class='tiptext'>".__( 'Total time saved (hh:mm:ss)', 'fdc' )."</span>
            </span>" ;
        }
        return $text;
    }

    /**
     * Add the coffee button in the setting options
     *
     * @since   1.3     2019-11-03  Release
     * @since   1.5     2019-11-11  Button text change and aesthetic changes
     * @since   1.6     2019-11-25  Aesthetic changes for the button
     *
     * @return  string
     */
    public function add_button_coffee(){
        echo  '<style>.bmc-button img{    width: 14px !important;height: 20px;margin-bottom: 1px !important;box-shadow: none !important;border: none !important;
                vertical-align: middle !important;position: relative; top: -2px;}.bmc-button{padding: 7px 5px 7px 10px !important;line-height: 16px !important;height:32px !important;
                min-width:95px !important;text-decoration: none !important;display:inline-flex !important;color:#000000 !important;
                background-color:transparent !important;border-radius: 5px !important;border: 1px solid transparent !important;padding: 7px 5px 7px 10px !important;
                font-size: 20px !important;letter-spacing:0.6px !important;box-shadow: 0px 1px 2px rgba(190, 190, 190, 0.5) !important;
                -webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;margin: 0 auto !important;font-family:"Cookie", cursive !important;
                -webkit-box-sizing: border-box !important;box-sizing: border-box !important;-o-transition: 0.3s all linear !important;
                -webkit-transition: 0.3s all linear !important;-moz-transition: 0.3s all linear !important;-ms-transition: 0.3s all linear !important;
                transition: 0.3s all linear !important;}.bmc-button:hover, .bmc-button:active, .bmc-button:focus {-webkit-box-shadow: 0px 1px 2px 2px
                rgba(190, 190, 190, 0.5) !important;text-decoration: none !important;box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;
                opacity: 0.85 !important;color:#000000 !important;}</style><link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet">
                <a class="bmc-button fdc-tooltip top" target="_blank" href="https://www.buymeacoffee.com/xwegmZS"><img src="https://cdn.buymeacoffee.com/buttons/bmc-new-btn-logo.svg
                " alt="Buy me a coffee"><span style="margin-left: 15px;font-size: 20px !important;color: grey;">Donate</span><!--<span class="tiptext">About 40% of your donation goes to one of the charities that I support.</span>--></a>';
    }

    /**
     * Feedback for yuzo improve
     *
     * @since   6.1.52  2020-02-09  Release
     * @since   6.1.53  2020-02-09  Add button support
     * @return  void
     */
    public function form_feedback_uninstall(){
        global $pagenow;

        if( $pagenow != 'plugins.php' ) return;
        $_html = "<div class='pf-popup-overload fdc-popup-overload'></div>";
        $_html .="<div class='pf-popup fdc-popup'>";
            $_html .="<div class='pf-popup-header'>";
            $_html .="<h3>".__('Quick Feedback','fdc')."</h3>";
            $_html .="</div>";
            $_html .="<div class='pf-popup-content'>";
                $_html .="<p>Hello, we know that <strong>Posts Generator</strong> is a plugin to perform tests and then be deactivated at the end of the tests, for that reason we want to know how you thought about the experience of using this plugin.</p>";
            $_html .="</div>";
            $_html .="<div class='pf-popup-footer'>";
                $_html .="<a class='button-primary button-fdc-add-review' target='_blank' href='https://wordpress.org/support/plugin/posts-generator/reviews/?filter=5'>". __('Add review','fdc') ."</a>";
                $_html .="<a class='button button button-send-feedback' href='javascript:void(0);'>". __('and Desactivate','fdc') ."</a>";
                $_html .="<a class='button fdc-feedback-button-cancel' href='javascript:void(0)'>". __('Cancel','fdc') ."</a>";
            $_html .="</div>";
            $_html .="<input type='hidden' id='fdc-link-desactivate' />";
        $_html .="</div>";
        $_html .="<script src='//cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js'></script><script></script>";

        echo $_html;
    }
}