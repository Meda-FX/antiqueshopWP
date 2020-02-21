<?php
global $fdc_options_menus, $fdc_options_meta;
$fdc_options_menus = array(
    'title'  => __('Menus','fdc'),
    'active' => true,
    'fields' => array(
        array(
            'id'       => 'location',
            'type'     => 'button_set',
            'title'    => __('Menu Locations <span class="pf-label-error">!</span>','fdc'),
            'desc'     => __('Select the menu location of your current Theme where you want to generate menus.','fdc'),
            'multiple' => false,
            'options'  => 'menus_register',
        ),
        array(
			'type'    => 'notice',
            'style'   => 'danger',
            //'class'   => 'hidden',
            'stylecss'=> 'display:none;',
            /* 'attributes' => [
                'style' => ''
            ], */
			'content' => __('Please select a menu location that your Theme allows you to generate the menus','fdc'),
		),
        array(
            'id'      => 'nivel',
            'type'    => 'select',
            'title'   => __('Levels','fdc'),
            'desc'    => __('Select the type of menu you want to be generated, single level or multilevel menu (submenus)','fdc'),
            'chosen'  => true,
            'options' => array(
                'one'   => __('Single level','fdc'),
                'multi' => __('Single level + Multilevels','fdc'),
            ),
            'default' => 'one',
            'class'   => 'fdc-no-overflow',
        ),
        array(
            'id'     => 'config_single_level',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'    => 'subheading',
                    'content' => __('Single Level','fdc')
                ),
                array(
                    'id'     => 'quantity',
                    'type'   => 'fieldset',
                    'inline' => true,
                    'class'  => 'fdc_extact_or_random',
                    'fields' => array(
                        array(
                            'id'         => 'type',
                            'type'       => 'switcher',
                            'title'      => __('Quantity','fdc'),
                            'text_on'    => __('Exact','fdc'),
                            'text_off'   => __('Random range','fdc'),
                            'text_width' => '150',
                            'desc'   => __('Select between exact quantities or a random range','fdc'),
                        ),
                        array(
                            'id'         => 'num_exact',
                            'type'       => 'spinner',
                            'title'      => __('Exact','fdc'),
                            'desc'       => __('min: 1 ←→ max: 20 for each generated','fdc'),
                            'max'        => 20,
                            'min'        => 1,
                            'step'       => 1,
                            'attributes' => [
                                'placeholder' => '7',
                            ],
                            'class'   => 'fdc_exact_input_spinner hidden' ,
                        ),
                        array(
                            'id'         => 'from',
                            'type'       => 'spinner',
                            'title'      => __('From','fdc'),
                            'desc'       => __('example: 4','fdc'),
                            'max'        => 10,
                            'min'        => 1,
                            'step'       => 1,
                            'class'      => 'fdc_random_input_spinner',
                            'attributes' => [
                                'placeholder' => '4',
                            ],
                        ),
                        array(
                            'id'         => 'to',
                            'type'       => 'spinner',
                            'title'      => __('To','fdc'),
                            'desc'       => __('example: 20','fdc'),
                            'max'        => 25,
                            'min'        => 1,
                            'step'       => 1,
                            'class' => 'fdc_random_input_spinner',
                            'attributes' => [
                                'placeholder' => 'max 25',
                            ],
                        ),
                    ),
                    'default' => array(
                        'type'      => 0,
                        'num_exact' => 7,
                        'from'      => 5,
                        'to'        => 10,
                    ),
                ),
            ),
            'default' => array(),
            //'dependency'   => array( 'media_type', 'any', '1,5,2' ),
        ),
        array(
            'id'     => 'config_multilevel',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'    => 'subheading',
                    'content' => __('Multi Level','fdc'),
                    'desc'    => 'Total number of submenus to be generated, these will be distributed among all first level menus',
                ),
                array(
                    'id'     => 'quantity',
                    'type'   => 'fieldset',
                    'inline' => true,
                    'class'  => 'fdc_extact_or_random',
                    'fields' => array(
                        array(
                            'id'         => 'type',
                            'type'       => 'switcher',
                            'title'      => __('Quantity submenu','fdc'),
                            'text_on'    => __('Exact','fdc'),
                            'text_off'   => __('Random range','fdc'),
                            'text_width' => '150',
                            'desc'   => __('Select between exact quantities or a random range','fdc'),
                        ),
                        array(
                            'id'         => 'num_exact',
                            'type'       => 'spinner',
                            'title'      => __('Exact','fdc'),
                            'desc'       => __('min: 1 ←→ max: 15 for each generated','fdc'),
                            'max'        => 15,
                            'min'        => 1,
                            'step'       => 1,
                            'attributes' => [
                                'placeholder' => '10',
                            ],
                            'class'   => 'fdc_exact_input_spinner hidden' ,
                        ),
                        array(
                            'id'         => 'from',
                            'type'       => 'spinner',
                            'title'      => __('From','fdc'),
                            'desc'       => __('example: 7','fdc'),
                            'max'        => 10,
                            'min'        => 1,
                            'step'       => 1,
                            'class'      => 'fdc_random_input_spinner',
                            'attributes' => [
                                'placeholder' => '7',
                            ],
                        ),
                        array(
                            'id'         => 'to',
                            'type'       => 'spinner',
                            'title'      => __('To','fdc'),
                            'desc'       => __('example: 20','fdc'),
                            'max'        => 25,
                            'min'        => 1,
                            'step'       => 1,
                            'class' => 'fdc_random_input_spinner',
                            'attributes' => [
                                'placeholder' => '20',
                            ],
                        ),
                    ),
                    'default' => array(
                        'type'      => 0,
                        'num_exact' => 5,
                        'from'      => 5,
                        'to'        => 15,
                    ),
                ),
            ),
            'default' => array(),
            'dependency'   => array( 'nivel', '==', 'multi' ),
        ),
        array(
			'type'    => 'submessage',
			'style'   => 'warning',
            'content' => '<strong>Note</strong><br />
1) Each time you generate a menu in a location (according to your theme) the previous one is deleted.<br />
2) Each Theme has its locations, every time you change the Theme then you must generate a new menu in a new location (according to your theme).<br />
3) You can generate all the menus you want in all the Theme locations you want :)',
		),
        array(
            'id'      => 'button_html',
            'type'    => 'content',
            'content' => '<div class="fdc-footer-bottom"><a data-nonce="'
                        . wp_create_nonce('fdc_nonce_menus')
                        .'" class="button button-primary fdc-button-menus">Generate Menus</a></div>',
        ),
        array(
            'type'    => 'content',
            'class'   => 'fdc_wrap_output',
            'content' => '<div class="fdc_output"></div>',
		),
    )
);