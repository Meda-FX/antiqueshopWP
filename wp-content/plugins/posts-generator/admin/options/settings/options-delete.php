<?php
global $fdc_options_delete, $fdc_options_meta;
$fdc_options_delete = array(
    'title'  => __('Delete','fdc'),
    'active' => true,
    'fields' => array(
        array(
            'id'       => 'delete_object',
            'type'     => 'button_set',
            'title'    => __('What do you want to eliminate?','fdc'),
            'desc'     => __('Select what you want to delete','fdc'),
            'multiple' => true,
            'options'  => array(
                'users'    => __('Users','fdc'),
                'terms'    => __('Terms','fdc'),
                'posts'    => __('Posts','fdc'),
                'comments' => __('Comments','fdc'),
                'menus'    => __('Menus','fdc'),
            ),
            'default' => ['terms','posts','comments'],
        ),
        array(
            'id'      => 'delete_type',
            'type'    => 'select',
            'title'   => __('Way of Elimination','fdc'),
            'desc'    => __('Select what type of data you want to delete','fdc'),
            'chosen'  => true,
            'options' => array(
                'only' => __('Delete data from Content Generator only','fdc'),
                'all'  => __('Remove all','fdc'),
            ),
            'default'    => 'only',
            'class'      => 'fdc-no-overflow',
            'attributes' => [
                'style' => '300px',
            ]
        ),
        array(
			'type'    => 'notice',
			'style'   => 'danger',
            'content' => 'To delete all selected data please type <strong>I want to delete all the data</strong>',
            'dependency' => ['delete_type','==','all']
		),
        array(
            'id'    => 'text_verification',
            'type'  => 'text',
            'title' => '&nbsp;',
            'attributes' => [
                'placeholder' => __( 'I want to delete all the data', 'fdc' ),
                'style'       => 'width:350px;',
            ],
            'dependency' => ['delete_type','==','all']
        ),
        array(
            'id'      => 'button_html',
            'type'    => 'content',
            'content' => '<div class="fdc-footer-bottom"><a data-nonce="'
                        . wp_create_nonce('fdc_nonce_delete')
                        .'" class="button button-primary fdc-button-delete">DELETE</a></div>',
        ),
    )
);