<?php
// ───────────────────────────
//namespace FDC\Actions;
use FDC\Admin as fdcAdmin;
// ───────────────────────────
/**
 * Send the action to generate users
 * @since   1.0     2019-09-08      Release
 * @since   1.5     2019-11-11      $refresh variable to indicate an error was added
 */
add_action( 'wp_ajax_generate-users', 'fdc_generate_users' );
function fdc_generate_users() {

    // Variable that indicates if the page refreshes
    $refresh = 0;

    // Sanitize request ajax
    array_walk_recursive( $sanitize_request = wp_unslash( $_REQUEST ), 'sanitize_text_field' );

    if( ! empty( $sanitize_request['nonce'] ) && wp_verify_nonce( $sanitize_request['nonce'], 'fdc_nonce_users' ) ) {

        require_once fdc\PATH . 'include/libs/LZTimer.php';
		$LZTimer = new \LZTimer;

        $metas = $sanitize_request['metas'];
        // ─── User Generating Class Instance ────────
        $user      = new fdcAdmin\Fdc_User( $sanitize_request );
        $type_q    = $user->request['type'] ? 1 : 0;
        $num_exact = $user->request['exact'];
        $num_from  = $user->request['from'];
        $num_to    = $user->request['to'];
        $execute   = $user->composer_fake( (int) $user->numberToGenerate( $type_q, $num_exact, $num_from, $num_to ), $metas );

        if( $execute ){
            wp_send_json_success(
                array(
                    'results' => $execute,
                    'time'    => $LZTimer->getTimer(),
                    'request' => $sanitize_request,
                    'refresh' => $refresh,
                )
            );
        }else{
            wp_send_json_error(
                array(
                    'success' => false,
                    'error' => esc_html__( 'Error while generating.', 'fdc' ),
                    'debug' => $sanitize_request,
                    'refresh' => $refresh,
                )
            );
        }

    }

    wp_send_json_error(
        array(
            'success' => false,
            'error'   => esc_html__( 'Error while generating..', 'fdc' ),
            'debug'   => $sanitize_request,
            'refresh' => $refresh,
        )
    );
}

/**
 * Send the action to generate terms
 * @since   1.0     2019-09-08      Release
 * @since   1.5     2019-11-11      $refresh variable to indicate an error was added
 */
add_action( 'wp_ajax_generate-terms', 'fdc_generate_terms' );
function fdc_generate_terms() {

    // Variable that indicates if the page refreshes
    $refresh = 0;

    // Sanitize request ajax
    array_walk_recursive( $sanitize_request = wp_unslash( $_REQUEST ), 'sanitize_text_field' );

    if( ! empty( $sanitize_request['nonce'] ) && wp_verify_nonce( $sanitize_request['nonce'], 'fdc_nonce_terms' ) ) {

        require_once fdc\PATH . 'include/libs/LZTimer.php';
        $LZTimer = new \LZTimer;

        $metas = $sanitize_request['metas'];
        // ─── Term Generating Class Instance ────────
        $term      = new fdcAdmin\Fdc_Term( $sanitize_request );
        $type_q    = $term->request['type'] ? 1 : 0;
        $num_exact = $term->request['exact'];
        $num_from  = $term->request['from'];
        $num_to    = $term->request['to'];
        $execute   = $term->composer_fake( (int) $term->numberToGenerate( $type_q, $num_exact, $num_from, $num_to ), $metas );

        if( $execute ){
            wp_send_json_success(
                array(
                    'results' => $execute,
                    'time'    => $LZTimer->getTimer(),
                    'request' => $sanitize_request,
                    'refresh' => $refresh,
                )
            );
        }else{
            wp_send_json_error(
                array(
                    'success' => false,
                    'error'   => esc_html__( 'Error while generating.', 'fdc' ),
                    'debug'   => $sanitize_request,
                    'refresh' => $refresh,
                )
            );
        }

    }

    wp_send_json_error(
        array(
            'success' => false,
            'error'   => esc_html__( 'Error while generating..', 'fdc' ),
            'debug'   => $sanitize_request,
            'refresh' => $refresh,
        )
    );
}

/**
 * Send the action to generate posts
 *
 * @since   1.0     2019-10-13      Release
 * @since   1.5     2019-11-11      $refresh variable to indicate an error was added
 */
add_action( 'wp_ajax_generate-posts', 'fdc_generate_posts' );
function fdc_generate_posts() {

    // Variable that indicates if the page refreshes
    $refresh = 0;

    // Sanitize request ajax
    array_walk_recursive( $sanitize_request = wp_unslash( $_REQUEST ), 'sanitize_text_field' );

    if( ! empty( $sanitize_request['nonce'] ) && wp_verify_nonce( $sanitize_request['nonce'], 'fdc_nonce_posts' ) ) {

        require_once fdc\PATH . 'include/libs/LZTimer.php';
        $LZTimer = new \LZTimer;

        $metas = $sanitize_request['metas'];
        // ─── Post Generating Class Instance ────────
        $post      = new fdcAdmin\Fdc_Post( $sanitize_request );
        $type_q    = $post->request['type'] ? 1 : 0;
        $num_exact = $post->request['exact'];
        $num_from  = $post->request['from'];
        $num_to    = $post->request['to'];
        $execute   = $post->composer_fake( (int) $post->numberToGenerate( $type_q, $num_exact, $num_from, $num_to ), $metas );

        if( $execute ){
            wp_send_json_success(
                array(
                    'results' => $execute,
                    'time'    => $LZTimer->getTimer(),
                    'request' => $sanitize_request,
                    'refresh' => $refresh,
                )
            );
        }else{
            wp_send_json_error(
                array(
                    'success' => false,
                    'error' => esc_html__( 'Error while generating.', 'fdc' ),
                    'debug' => $sanitize_request,
                    'refresh' => $refresh,
                )
            );
        }

    }else{
        $refresh = 1;
    }

    wp_send_json_error(
        array(
            'success' => false,
            'error'   => esc_html__( 'Error while generating..', 'fdc' ),
            'debug'   => $sanitize_request,
            'refresh' => $refresh,
        )
    );
}

/**
 * Send the action to generate comments
 * @since   1.0     2019-10-20      Release
 * @since   1.5     2019-11-11      $refresh variable to indicate an error was added
 */
add_action( 'wp_ajax_generate-comments', 'fdc_generate_comments' );
function fdc_generate_comments() {

    // Variable that indicates if the page refreshes
    $refresh = 0;

    // Sanitize request ajax
    array_walk_recursive( $sanitize_request = wp_unslash( $_REQUEST ), 'sanitize_text_field' );

    if( ! empty( $sanitize_request['nonce'] ) && wp_verify_nonce( $sanitize_request['nonce'], 'fdc_nonce_comments' ) ) {

        require_once fdc\PATH . 'include/libs/LZTimer.php';
        $LZTimer = new \LZTimer;

        $metas = $sanitize_request['metas'];
        // ─── comment Generating Class Instance ────────
        $comment = new fdcAdmin\Fdc_Comment( $sanitize_request );
        $execute = $comment->composer_fake( $metas );

        if( $execute ){
            wp_send_json_success(
                array(
                    'results' => $execute,
                    'time'    => $LZTimer->getTimer(),
                    'request' => $sanitize_request,
                    'refresh' => $refresh,
                )
            );
        }else{
            wp_send_json_error(
                array(
                    'success' => false,
                    'error'   => esc_html__( 'Error while generating.', 'fdc' ),
                    'debug'   => $sanitize_request,
                    'refresh' => $refresh,
                )
            );
        }

    }

    wp_send_json_error(
        array(
            'success' => false,
            'error'   => esc_html__( 'Error while generating...', 'fdc' ),
            'debug'   => $sanitize_request,
            'refresh' => $refresh,
        )
    );
}


/**
 * Send the action to generate menus
 *
 * @since   1.6     2019-11-23      Release
 */
add_action( 'wp_ajax_generate-menus', 'fdc_generate_menus' );
function fdc_generate_menus() {

    // Variable that indicates if the page refreshes
    $refresh = 0;

    // Sanitize request ajax
    array_walk_recursive( $sanitize_request = wp_unslash( $_REQUEST ), 'sanitize_text_field' );

    if( ! empty( $sanitize_request['nonce'] ) && wp_verify_nonce( $sanitize_request['nonce'], 'fdc_nonce_menus' ) ) {

        require_once fdc\PATH . 'include/libs/LZTimer.php';
        $LZTimer = new \LZTimer;

        //$metas = $sanitize_request['metas'];
        // ─── Menu Generating Class Instance ────────
        $menu = new fdcAdmin\Fdc_Menu( $sanitize_request );
        $execute = $menu->composer_fake( [] );

        if( $execute ){
            $parent = $execute['parent'];
            $child  =  ! empty( $execute['child'] ) ? $execute['child'] : [];
            wp_send_json_success(
                array(
                    'results' => array_merge($parent,$child),
                    'time'    => $LZTimer->getTimer(),
                    'request' => $sanitize_request,
                    'refresh' => $refresh,
                    'mesagge' => 'Generated ' . count($parent) . ' menus' . ( count($child) > 0 ? ' and '.count($child). ' submenus' : '' )
                )
            );
        }else{
            wp_send_json_error(
                array(
                    'success' => false,
                    'error'   => esc_html__( 'Error while generating.', 'fdc' ),
                    'debug'   => $sanitize_request,
                    'refresh' => $refresh,
                )
            );
        }

    }

    wp_send_json_error(
        array(
            'success' => false,
            'error'   => esc_html__( 'Error while generating...', 'fdc' ),
            'debug'   => $sanitize_request,
            'refresh' => $refresh,
        )
    );
}

/**
 * Send the action to delete data
 * @since   1.0     2019-10-20      Release
 */
add_action( 'wp_ajax_generate-delete-data', 'fdc_delete_data' );
function fdc_delete_data() {

    // Sanitize request ajax
    array_walk_recursive( $sanitize_request = wp_unslash( $_REQUEST ), 'sanitize_text_field' );

    if( ! empty( $sanitize_request['nonce'] ) && wp_verify_nonce( $sanitize_request['nonce'], 'fdc_nonce_delete' ) ) {

        // ─── Delete class ────────
        $delete  = new fdcAdmin\Fdc_Delete( $sanitize_request );
        $execute = $delete->delete();

        if( $execute ){
            wp_send_json_success(
                array(
                    'deletes' => $execute,
                    'request' => $sanitize_request,
                )
            );
        }else{
            wp_send_json_error(
                array(
                    'success' => false,
                    'error'   => esc_html__( 'Error while deleting.', 'fdc' ),
                    'debug'   => $sanitize_request
                )
            );
        }

    }

    wp_send_json_error(
        array(
            'success' => false,
            'error' => esc_html__( 'Error while deleting...', 'fdc' ),
            'debug' => $sanitize_request
        )
    );
}

/**
 * INTEGRATION: Yuzo - Generate random visits to each post
 * @since   1.7 2020-02-19  Release
 */
add_action( 'wp_ajax_generate-yuzo-views', 'fdc_yuzo_views' );
function fdc_yuzo_views() {

    // Variable that indicates if the page refreshes
    $refresh = 0;

    // Sanitize request ajax
    array_walk_recursive( $sanitize_request = wp_unslash( $_REQUEST ), 'sanitize_text_field' );

    if( ! empty( $sanitize_request['nonce'] ) && wp_verify_nonce( $sanitize_request['nonce'], 'fdc_nonce_yuzo' ) ) {

        require_once fdc\PATH . 'include/libs/LZTimer.php';
        $LZTimer = new \LZTimer;
        // ─── Integration class ────────
        $integration  = new fdcAdmin\Fdc_Integration( $sanitize_request );
        $execute = $integration->yuzo_update_views();

        if( $execute ){
            wp_send_json_success(
                array(
                    'results' => $execute['return'],
                    'time'    => $LZTimer->getTimer(),
                    'request' => $sanitize_request,
                    'refresh' => $refresh,
                    'mesagge' => $execute['total_views'].' number of views has been generated with '. $execute['total_clicks'] .' number of clicks within '. $execute['total_posts'] .' number of posts',
                )
            );
        }else{
            wp_send_json_error(
                array(
                    'success' => false,
                    'error'   => esc_html__( 'Error while deleting.', 'fdc' ),
                    'debug'   => $sanitize_request
                )
            );
        }

    }

    wp_send_json_error(
        array(
            'success' => false,
            'error' => esc_html__( 'Error while generate views yuzo...', 'fdc' ),
            'debug' => $sanitize_request
        )
    );
}