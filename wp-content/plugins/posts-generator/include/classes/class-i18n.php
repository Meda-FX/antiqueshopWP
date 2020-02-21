<?php
/**
 * @since       1.0         2019-09-07     Release
 * @package     FDC
 * @subpackage  FDC/Core
 */
// ───────────────────────────
namespace FDC\Core;
use FDC as fdc;
// ───────────────────────────
/*
|--------------------------------------------------------------------------
| Define the internationalization functionality
|--------------------------------------------------------------------------
|
| Loads and defines the internationalization files for this plugin
| so that it is ready for translation.
|
*/
class Fdc_i18n {
    /**
         * Load the plugin text domain for translation.
        *
        * @since   1.0   2019-09-07     Release
        * @return  void
        */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            fdc\TEXTDOMAIN,
            false,
            fdc\PATH . '/languages/'
        );
    }
}
?>