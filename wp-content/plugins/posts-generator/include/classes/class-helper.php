<?php
/**
 * @since       1.0         2019-09-14     Release
 * @package     FDC
 * @subpackage  FDC/Core
 */
// ───────────────────────────
namespace FDC\Core;
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
class Fdc_Helper {
    private static
    /**
	 * Variable with the generator library
	 * @access	public
	 * @since 	1.0		2019-09-21		Release
	 * @var		LZFakeTextGenerator
     * @static
	 */
    $LZFAKE;

    /**
     * Get the saved settings
     *
     * @since   1.0     2019-09-14      Release
     *
     * @param   string  $id             Id of the option to obtain from the database table 'wp_options'
     * @param   string  $field          If you fill this parameter you will find the value of this name index
     *
     * @return  object|mixed
     */
    public static function get_option( $id = 'fdc-setting', $field = null ){
        if( ! $field ){
            return (object) \get_option( $id );
        }else{
            $a = (object) \get_option( $id );
            return ! empty( $a->{$field} ) ? $a->{$field} : null;
        }
    }

    /**
     * Update a data of the saved settings
     *
     * @since   1.0     2019-09-06  Release
     *
     * @param   string  $id         ID of the configuration
     * @param   mixed   $value      Value to update
     * @param   string  $field      Key|Name of the value to update
     * @return  void
     */
    public static function update_option( $id = 'fdc-setting', $value, $field = null ){
        if( !$field ){
            return \update_option( $id, $value );
        }else{
            $a = \get_option( $id );
            $a[$field] = $value;
            return \update_option($id,$a);
        }
    }

    /**
     * Generates a range of numbers with 3 parameters
     *
     * @since   1.0     2019-09-06     Release
     * @return  array
     */
    public function generate_range( $from = 1, $to = 30, $step = 1 ){
        return array_map( 'strval', range( $from, $to, $step ) );
    }

    /**
     * Valid if you are a permitted user
     *
     * @since   1.0     2019-09-06     Release
     * @return  void
     */
    public function isUserAllow(){

        if( is_network_admin() ){
            if( ! (defined('LOGGED_IN_COOKIE') && isset($_COOKIE['LOGGED_IN_COOKIE'])) ) return;
            if( ! (defined('SECURE_AUTH_COOKIE') && isset($_COOKIE['SECURE_AUTH_COOKIE'])) ) return;
        }

        if( ! function_exists( 'wp_get_current_user' ) ) {
            require_once ABSPATH . "wp-includes/pluggable.php" ;
        }

        $allowed_roles = apply_filters( FDC_ID . '_role_user_allow', array( 'administrator' ) );
        $user          = wp_get_current_user();
        $is_user_admin = array_intersect( $allowed_roles, $user->roles );

        return ( is_admin() || is_customize_preview() || $is_user_admin );
    }

    /**
     * Function that lets you know if the host under test
     * is in localhost or a real domain.
    *
    * @since   1.0     2019-09-06  Release
    * @param   array   $whitelist  Ip localhost list
    */
    public function isLocalhost($whitelist = ['127.0.0.1', '::1']) {
        return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
    }

    /**
     * Adjust the goals you will generate per user
     *
     * @since   1.0     2019-09-26      Release
     *
     * @param   array   $meta           Data transported by ajax
     * @param   string  $where          Type of goal to be transported
     * @return  array
     */
    public static function setup_custom_meta( $metas, $where = 'users' ){

        if( empty($metas) || ! is_array( $metas ) ) return;

        $metas = $metas['fdc-setting'][$where]['meta'];

        $data = [];

        foreach ($metas as $key => $value) {

            if( empty($value) ||  ! $value['meta_name'] ) continue;
            $type = $value['meta_type'];
            $name = $value['meta_name'];

            switch($type) {

                case 'ip':

                    $data[]['ip'] = [
                        'type' => $value['meta_value_ip'],
                        'name' => $name,
                    ];
                break;

                case 'text':

                    $data[]['text'] = [
                        'text' => $value['meta_value_text'],
                        'name' => $name,
                    ];
                break;

                case 'words':

                    $data[]['words'] = [
                        'from' => $value['meta_value_numbers_words']['from'],
                        'to'   => $value['meta_value_numbers_words']['to'],
                        'name' => $name,
                    ];
                break;

                case 'domain':

                    $data[]['domain'] = [
                        $data[]['domain'] = [ 'name' => $name, ]
                    ];
                break;

                case 'element':

                    $data[]['element'] = [
                        'from'     => $value['meta_value_element']['from'],
                        'to'       => $value['meta_value_element']['to'],
                        'tags'     => $value['meta_value_element_tag'],
                        'separate' => $value['meta_value_separate'],
                        'name'     => $name,
                    ];
                break;

                case 'html':

                    $data[]['html'] = [
                        'from' => $value['meta_value_paragraphs']['from'],
                        'to'   => $value['meta_value_paragraphs']['to'],
                        'tags' => $value['description_tag'],
                        'name' => $name,
                    ];
                break;

                case 'long-text':

                    $data[]['long-text'] = [
                        'length' => $value['description_length'],
                        'from'   => $value['meta_value_long_text']['from'],
                        'to'     => $value['meta_value_long_text']['to'],
                        'name'   => $name,
                    ];
                break;

                case 'person':

                    $data[]['person'] = [
                        'tags' => $value['meta_value_person'],
                        'name' => $name,
                    ];
                break;

                case 'date':

                    $data[]['date'] = [
                        'from'   => $value['meta_value_date']['date1']['from'],
                        'to'     => $value['meta_value_date']['date1']['to'],
                        'format' => $value['meta_value_date']['format'],
                        'name'   => $name,
                    ];
                break;

                default:

                    $data[] = null;

            }
        }

        return $data;
    }

    /**
     * Insert the custom meta of a Wordpress object
     *
     * @since   1.0     2019-10-09      Release
     *
     * @param   array   $meta           Meta data set selected by the user
     * @param   string  $$where         Location of the meta, with which you can define to which table they are inserted
     * @param   int     $ID             Object ID
     * @param   LZFakeTextGenerator  $LZFAKE    Object of the class LZFakeTextGenerator
     * @return void
     */
    public static function insert_custom_meta( $metas, $where, $ID, $LZFAKE ){

        // ─── Assign the FAKE content generator class ────────
        self::$LZFAKE = $LZFAKE;
        // OPTIMIZE: If all the goals are images or look alike, then simplify this.
        switch($where) {

            case 'users':
                self::insert_custom_meta_by_tag( $metas, 'user', $ID );
                break;
            case 'terms':
                self::insert_custom_meta_by_tag( $metas, 'term', $ID );
                break;
            case 'posts':
                self::insert_custom_meta_by_tag( $metas, 'post', $ID );
                break;
            case 'comments':
                self::insert_custom_meta_by_tag( $metas, 'comment', $ID );
                break;
            default:
                return null;

        }
    }

    /**
     * Identify where you will insert the data of the custom metas
     *
     *  @since   1.0     2019-10-09      Release
     *
     * @param   array   $metas  Meta data set selected by the user
     * @param   string  $where  Location of the meta, with which you can define to which table they are inserted
     * @param   int     $ID     Object ID
     * @return  void
     */
    public static function insert_custom_meta_by_tag( $metas, $where, $ID ){

        if( ! empty( $metas ) ){

            foreach ($metas as $key => $value) {
                foreach ($value as $k => $v) {

                    $call_function = "get_" . str_replace( "-", "_", $k);
                    $r = call_user_func( "update_{$where}_meta", $ID,  $v['name'], self::$call_function( $v ) );

                }

            }

        }

    }

    /**
     * Get words
     *
     * @since   1.0     2019-10-09      Release
     *
     * @param   array   $data   Arrangements necessary for configuration
     * @return  string
     */
    private function get_words( $data = null ){
        $from = empty( $data['from'] ) ? 1 : $data['from'];
        $to   = empty( $data['to'] ) ? $from : $data['to'];
        $num  = rand( (int)$from, (int)$to );

        return self::$LZFAKE->get_words( $num );
    }

    /**
     * Get a text
     *
     * @since   1.0     2019-10-09      Release
     *
     * @param   array   $data   Arrangements necessary for configuration
     * @return  string
     */
    private function get_text( $data = null ){
        return (string) $data['text'];
    }

    /**
     * Get an IP
     *
     * @since   1.0     2019-10-09  Release
     *
     * @param   array   $data       Arrangements necessary for configuration
     * @return  string
     */
    private function get_ip( $data = null ){
        return ! $data['type'] ? self::$LZFAKE->get_ip() : self::$LZFAKE->get_ip( true );
    }

    /**
     * Get a domain
     *
     * @since   1.0     2019-10-09  Release
     *
     * @param   array   $data       Arrangements necessary for configuration
     * @return string
     */
    private function get_domain(){
        return self::$LZFAKE->get_domain_company();
    }

    /**
     * Undocumented function
     *
     * @since   1.0     2019-10-09  Release
     *
     * @param   array   $data       Arrangements necessary for configuration
     * @return string
     */
    private function get_html( $data = null ){
        $from          = empty( $data['from'] ) ? 1 : $data['from'];
        $to            = empty( $data['to'] ) ? $from : $data['to'];
        $num_paragraph = rand( (int)$from, (int)$to );
        $tag_inline    = array_intersect( ['strong','em','a','code'], $data['tags'] );
        $tag_block     = array_intersect( ['p','heading','div','ul','lo','blockquote'], $data['tags'] );
        $html          = self::$LZFAKE->get_paragraphs( $num_paragraph );
        if(  ! empty( $tag_inline )  ){
            $html = self::$LZFAKE->set_tag_inline( $html, $tag_inline );
        }
        if( ! empty( $tag_block ) ){
            $html = self::$LZFAKE->set_tag_block( $html, $tag_block );
        }

        return $html;
    }

    /**
     * Get a random item set by the same user
     *
     * @since   1.0     2019-10-09  Release
     *
     * @param   array   $data       Arrangements necessary for configuration
     * @return string
     */
    private function get_element( $data = null ){
        $from     = empty( $data['from'] ) ? 1 : $data['from'];
        $to       = empty( $data['to'] ) ? $from : $data['to'];
        $arr      = empty( $data['tags'] ) ? [] : explode( "," , $data['tags'] );
        $num_elem = rand( (int)$from, (int)$to );

        if(  !empty( $arr )  ){
            shuffle($arr);
            return implode( $data['separate'], array_slice($arr, 0, $num_elem) );
        }
        return '';
    }

    /**
     * Get a logn text
     *
     * @since   1.0     2019-10-09  Release
     *
     * @param   array   $data       Arrangements necessary for configuration
     * @return string
     */
    private function get_long_text( $data = null ){
        $from = empty( $data['from'] ) ? 1 : $data['from'];
        $to   = empty( $data['to'] ) ? $from : $data['to'];
        $type = empty( $data['length'] ) ? $from : $data['to'];

        if( $type == 'sentence' ){
            return self::$LZFAKE->get_sentence( (int)$from, (int)$to );
        }else{

            $num   = rand( (int)$from, (int)$to );
            $html = self::$LZFAKE->get_paragraphs( $num );

            return $html;
        }
    }

    /**
     * Get name of people
     *
     * @since   1.0     2019-10-09  Release
     *
     * @param   array   $data       Arrangements necessary for configuration
     * @return string
     */
    private function get_person( $data = null ){

        $result = [];
        if(  !empty( $data['tags'] && is_array( $data['tags'] ) )  ){
            foreach ($data['tags'] as $value) {
                switch ( $value ) {
                    case 'name':
                        $result[] = self::$LZFAKE->get_name();
                        break;

                    case 'lastname':
                        $result[] = self::$LZFAKE->get_lastname();
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        // OPTIMIZE: you can make a separator here
        // that separate had to be added in the interface
        return implode( ' ', $result );
    }

    /**
     * Get a random date
     *
     * @since   1.0     2019-10-09  Release
     *
     * @param   array   $data       Arrangements necessary for configuration
     * @return string
     */
    private function get_date( $data = null ){
        $from   = empty( $data['from'] ) ? 1 : $data['from'];
        $to     = empty( $data['to'] ) ? $from : $data['to'];
        $format = empty( $data['format'] ) ? 'Y-m-d' : $data['format'];

        // Convert to timetamps
        $min = strtotime($from);
        $max = strtotime($to);

        // Generate random number using above bounds
        $val = rand($min, $max);

        // Convert back to desired date format
        return date($format, $val);
    }

    private function set_time( $type = 'start' ){

        if( $type == 'start' ){
            $starttime = microtime(true);
        }

    }
}
?>