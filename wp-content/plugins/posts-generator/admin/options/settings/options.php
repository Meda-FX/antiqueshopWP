<?php
use FDC as fdc;
/**
 * Main setting options
 *
 * @since   1.0     2019-09-07      Release
 * @since   1.5     2019-11-11      Change text in the 5 star toolip
 * @since   1.7     2020-02-19      - Now the motivation images are from external server
 *                                  - New motto added under the title of the plugin
 *                                  - Options of the new integration tab was added
 */
/*
|--------------------------------------------------------------------------
| Creation options
|--------------------------------------------------------------------------
*/
global  $fdc_options_user,
        $fdc_options_terms,
        $fdc_options_posts,
        $fdc_options_comments,
        $fdc_options_menus,
        $fdc_options_delete,
        $fdc_options_integration;
$id = fdc\ID;
$imagen = [
	'https://i.imgur.com/SFIgSmV.jpg','https://i.imgur.com/SygStno.jpg','https://i.imgur.com/7iMtzX3.jpg',
	'https://i.imgur.com/GGvrpzn.jpg','https://i.imgur.com/7B1FFGU.jpg','https://i.imgur.com/OfXQuix.jpg',
	'https://i.imgur.com/Qj4W84O.jpg','https://i.imgur.com/C7T6SvH.jpg','https://i.imgur.com/jFcViTG.jpg',
	'https://i.imgur.com/bzZq35o.jpg',
];
$fivestart = '<div class="fdc-fives fdc-tooltip top"><a href="https://wordpress.org/support/plugin/posts-generator/reviews/?filter=5" target="_blank" style="text-decoration: none">
<span class="dashicons dashicons-wordpress" style="color:black"></span>
<span class="dashicons dashicons-star-filled" style="color:#ffb900"></span>
<span class="dashicons dashicons-star-filled" style="color:#ffb900"></span>
<span class="dashicons dashicons-star-filled" style="color:#ffb900"></span>
<span class="dashicons dashicons-star-filled" style="color:#ffb900"></span>
<span class="dashicons dashicons-star-filled" style="color:#ffb900"></span>
</a>
<span class="tiptext"><img src="'. $imagen[rand(0,9)] .'" />Do you like the plugin? let me know to improve 💬</span>
</div>';
PF::addSetting( $id . '-setting' , array(
    'menu_title'            => 'Posts Generator',
    'menu_slug'             => 'fdc-setting',
    'setting_vertical_mode' => FALSE,
    'setting_title'         => __('<span>Posts &nbsp;generator</span>' ,'fdc') . '<img src="' . fdc\URL . '/admin/assets/images/logo.png" />' . 
    '<div class="fdc-sub-title">Generate demo content quickly and easily</div>',
    'ajax_save'             => FALSE,
    'sticky_header'         => FALSE,
    'show_search'           => FALSE,
    'show_all_options'      => FALSE,
    'show_buttons_footer'   => FALSE,
    'show_buttons_top'      => TRUE,
        'show_reset_all'      => false,
        'show_reset_section'  => false,
        'show_save_section'   => false,
    'show_footer'           => TRUE,
    'footer_credit'         => 'Made with <span class="fdc-admin-heart">♥</span> by <span class="fdc-admin-credit">Lenin Zapata</span><span class="fdc-admin-footer-separate">|</span>' . $fivestart,
    'show_reset_section'    => FALSE,
    'show_form_warning'     => FALSE
));
// Get options
PF::addSection( $id . '-setting-users' , array(
    'title'  => 'Users',
    'icon'   => 'fa fa-user',
    'parent' => $id . '-setting',
    'fields' => [

        array(
			'id'         => 'users',
			'type'       => 'accordion',
			'accordions' => array(
                $fdc_options_user
            )
        ),
    ]
) );
PF::addSection( $id . '-setting-terms' , array(
    'title'  => 'Terms',
    'icon'   => 'fa fa-tags',
    'parent' => $id . '-setting',
    'fields' => [
        array(
			'id'         => 'terms',
			'type'       => 'accordion',
			'accordions' => array(
                $fdc_options_terms
            )
        )
    ]
) );
PF::addSection( $id . '-setting-posts' , array(
    'title'  => 'Posts',
    'icon'   => 'fa fa-pencil-square',
    'parent' => $id . '-setting',
    'fields' => [
        array(
			'id'         => 'posts',
			'type'       => 'accordion',
			'accordions' => array(
                $fdc_options_posts
            )
        ),
    ]
) );
PF::addSection( $id . '-setting-commnets' , array(
    'title'  => 'Comments',
    'icon'   => 'fa fa-comments-o',
    'parent' => $id . '-setting',
    'fields' => [
        array(
			'id'         => 'comments',
			'type'       => 'accordion',
			'accordions' => array(
                $fdc_options_comments
            )
        ),
    ]
) );
PF::addSection( $id . '-setting-menus' , array(
    'title'  => 'Menus',
    'icon'   => 'fa fa-angle-double-down',
    'parent' => $id . '-setting',
    'fields' => [
        array(
			'id'         => 'menus',
			'type'       => 'accordion',
			'accordions' => array(
                $fdc_options_menus
            )
        ),
    ]
) );
PF::addSection( $id . '-setting-delete' , array(
    'title'  => '',
    'icon'   => 'fa fa-trash',
    'class'  => 'fdc-tab-delete',
    'parent' => $id . '-setting',
    'fields' => [
        array(
			'id'         => 'delete',
			'type'       => 'accordion',
			'accordions' => array(
                $fdc_options_delete
            )
        ),
    ]
) );
PF::addSection( $id . '-setting-integrate' , array(
    'title'  => 'Integration',
    'icon'   => 'fa fa-cogs',
    'parent' => $id . '-setting',
    'fields' => [
        array(
			'id'         => 'integrate',
			'type'       => 'accordion',
			'accordions' => array(
                $fdc_options_integration
            )
        ),
    ]
) );