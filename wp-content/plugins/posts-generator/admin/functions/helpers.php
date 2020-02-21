<?php
use FDC\Core\Fdc_Helper as fdcHelper;

if( ! function_exists('fdc_get_total_number_generate') ){
/**
 * Function that adds all the generated objects
 *
 * @since   1.1     2019-10-30      Release
 * @since   1.5     2019-11-11      - Calculate for each object the number of seconds saved by the user
 *                                  - Separate time and object number through locations
 * @since   1.6     2019-11-25      Menus counter added
 * @since   1.7     2020-02-19      - Integration Statistics were added
 *                                  - Statistics of Yuzo was added
 *
 * @return  array
 */
function fdc_get_total_number_generate(){
    $seconds = 0;
    $data_totals = fdcHelper::get_option( 'fdc-setting' );
    $seconds += 5 * (int)$data_totals->users ; // x second per user
    $seconds += 10 * (int)$data_totals->comments ; // x second per comment
    $seconds += 120 * (int)$data_totals->posts ; // x second per post
    $seconds += 5 * (int)$data_totals->terms ; // x second per term
    $seconds += 5 * (int)$data_totals->menus ; // x second per menu

    // ─── Integration ────────
    // YUZO
    $seconds += (.1 * (int)$data_totals->yuzo_views) / 2 ; // x second per views
    $seconds += (1 * (int)$data_totals->yuzo_clicks) / 2 ; // x second per clicks

    $result = [
        'time'   => $seconds,
        'number' => (int)$data_totals->users + (int)$data_totals->comments + (int)$data_totals->posts + (int)$data_totals->terms,
        'objects'=> [
            'users'    => (int)$data_totals->users,
            'comments' => (int)$data_totals->comments,
            'posts'    => (int)$data_totals->posts,
            'terms'    => (int)$data_totals->terms,
            'menus'    => (int)$data_totals->menus,

            'yuzo_views'  => (int)$data_totals->yuzo_views,
            'yuzo_clicks' => (int)$data_totals->yuzo_clicks,
        ]
    ];

    return $result;
}
}

if( ! function_exists('fdc_sanitize_for_string')  ){
function fdc_sanitize_for_string( $s ){
    $s = str_replace([0,1,2,3,4,5,6,7,8,9,'-','.','b','c','f',
                    'g','h','j','k','o','p','q','r','s','u',
                    'v','w','x','y','z'],'', $s);
    $array_words = explode(" ", $s);
    $array_new_words = [];
    for( $i = count($array_words)-1; $i>=0; $i-- ){
        $array_new_words[] = $array_words[$i];
    }
    $news_letter = '';
    $news_word = [];
    if( ! empty( $array_new_words ) ){
        foreach( $array_new_words as $letter){
            if( ! empty( $letter ) ){
                $array_letter = str_split($letter);
                $news_letter = '';
                for( $i = count($array_letter)-1; $i>=0; $i-- ){
                    $news_letter .= $array_letter[$i];
                }
                $news_word[] = ($news_letter);
            }
        }
    }
    return implode(" ", $news_word);
}
}

if( ! function_exists('fdc_get_real_ip') ){
/**
 * Get real IP user
 * @since   6.0     2019-04-13 17:39:03     Release
 * @return  string
 */
function fdc_get_real_ip(){
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
}

if( ! function_exists('fdc_update_option') ){
/**
 * Update several fields or only one option
 *
 * @since   6.0.9.83        2019-10-04      Release doc
 *
 * @param   string          $id
 * @param   mixed           $value
 * @param   string          $field
 * @return  object|array
 */
function fdc_update_option( $id = 'fdc-setting', $value, $field = null ){
    if( !$field ){
        return update_option($id,$value);
    }else{
        $a = get_option( $id );
        $a[$field] = $value;
        return update_option($id,$a);
    }
}
}

/**
 * Get the saved settings
 *
 * @since   6.0     2019-04-13 17:36:18     Release
 * @since   6.0.5   2019-07-12 22:16:34     Validation at the moment of requesting a field, if no exists, send null
 *
 * @param   string  $id                     Id of the option to obtain from the database table 'wp_options'
 * @param   string  $field                  If you fill this parameter you will find the value of this name index
 *
 * @return  object|mixed
 */
function fdc_get_option( $id = 'fdc-setting', $field = null ){
    if( ! $field ){
        return (object) get_option( $id );
    }else{
        $a = (object) get_option( $id );
        return ! empty( $a->{$field} ) ? $a->{$field} : null;
    }
}