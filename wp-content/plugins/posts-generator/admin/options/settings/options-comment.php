<?php
global $fdc_options_comments, $fdc_options_meta;
$fdc_options_comments = array(
    'title'  => __('Commnets','fdc'),
    'active' => true,
    'fields' => array(
        array(
            'id'     => 'quantity',
            'type'   => 'fieldset',
            'inline' => true,
            'class'  => 'fdc_extact_or_random',
            'fields' => array(
                array(
                    'id'         => 'type',
                    'type'       => 'switcher',
                    'title'      => __('Quantity for each post','fdc'),
                    'text_on'    => __('Exact','fdc'),
                    'text_off'   => __('Random range','fdc'),
                    'text_width' => '150',
                    'desc'   => __('Select between exact quantities or a random range','fdc'),
                ),
                array(
                    'id'         => 'num_exact',
                    'type'       => 'spinner',
                    'title'      => __('Exact','fdc'),
                    'desc'       => __('min: 1 ←→ max: 25 for each generated','fdc'),
                    'max'        => 25,
                    'min'        => 1,
                    'step'       => 1,
                    'attributes' => [
                        'placeholder' => '5',
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
                        'placeholder' => '10',
                    ],
                ),
                array(
                    'id'         => 'to',
                    'type'       => 'spinner',
                    'title'      => __('To','fdc'),
                    'desc'       => __('example: 20','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class' => 'fdc_random_input_spinner',
                    'attributes' => [
                        'placeholder' => '20',
                    ],
                ),
            ),
            'default'    => array(
                'type'      => 0,
                'num_exact' => 10,
                'from'      => 3,
                'to'        => 10,
            ),
            //'desc'       => __('Order by a criterion the related post (Ascending / Descending)','yuzo'),
        ),
        array(
            'id'       => 'post_type',
            'type'     => 'button_set',
            'title'    => __('Post Type','fdc'),
            'desc'     => __('Select the random post types where comments will be generated','fdc'),
            'multiple' => true,
            'options'  => 'post_type',
            'default'  => ['post'],
        ),
        array(
            'id'     => 'date',
            'type'   => 'fieldset',
            'title'  => __( 'Comment Date', 'fdc' ),
            'inline' => true,
            'class'  => 'fdc-no-overflow fdc-date-range',
            'fields' => array(
                array(
                    'id'      => 'date_range',
                    'type'    => 'select',
                    'desc'    => __( '&nbsp;', 'fdc' ),
                    'chosen'  => true,
                    'class'   => 'fdc-no-overflow',
                    'options' => [
                        'today'      => __( 'Today', 'fdc' ),
                        'tomorrow'   => __( 'Tomorrow', 'fdc' ),
                        'yesterday'  => __( 'Yesterday', 'fdc' ),
                        'this-week'  => __( 'This week', 'fdc' ),
                        'this-month' => __( 'This month', 'fdc' ),
                        'this-year'  => __( 'This year', 'fdc' ),
                        'last-7'     => __( 'Last 7 days', 'fdc' ),
                        'last-15'    => __( 'Last 15 days', 'fdc' ),
                        'last-30'    => __( 'Last 30 days', 'fdc' ),
                        'last-60'    => __( 'Last 60 days', 'fdc' ),
                        'last-year'  => __( 'Last year', 'fdc' ),
                        'next-15'    => __( 'Next 15 days', 'fdc' ),
                        'next-30'    => __( 'Next 30 days', 'fdc' ),
                        'next-60'    => __( 'Next 60 days', 'fdc' ),
                    ],
                ),
                array(
                    'id'   => 'date_from',
                    'type' => 'date',
                    'desc' => __( 'Date from', 'fdc' ),
                    'settings' => array(
                        'dateFormat'      => 'yy-mm-dd',
                    ),
                    'attributes' => [
                        'custom-date-format' => 'YYYY-MM-DD',
                        'readonly'           => 'readonly'
                    ]
                ),
                array(
                    'id'   => 'date_to',
                    'type' => 'date',
                    'desc' => __( 'Date to', 'fdc' ),
                    'settings' => array(
                        'dateFormat'      => 'yy-mm-dd',
                    ),
                    'attributes' => [
                        'custom-date-format' => 'YYYY-MM-DD',
                        'readonly'           => 'readonly'
                    ]
                ),
            ),
            'default'    => array(
                'date_range' => 'last-60',
                'date_from'  => date("Y-m-d", time() - (84600 * 60) ),
                'date_to'    => date("Y-m-d"),
            ),
            //'desc'       => __('Order by a criterion the related post (Ascending / Descending)','yuzo'),
        ),

        array(
            'type'    => 'heading',
            'content' => __('Comment Description','fdc')
        ),
        array(
            'id'     => 'paragraph_quantity',
            'type'   => 'fieldset',
            'inline' => true,
            'class'  => 'fdc_extact_or_random',
            'fields' => array(
                array(
                    'id'         => 'type',
                    'type'       => 'switcher',
                    'title'      => __('Paragraph quantity','fdc'),
                    'text_on'    => __('Exact','fdc'),
                    'text_off'   => __('Random range','fdc'),
                    'text_width' => '150',
                    'desc'   => __('Exact quantities or a random range of paragraphs','fdc'),
                ),
                array(
                    'id'         => 'num_exact',
                    'type'       => 'spinner',
                    'title'      => __('Exact','fdc'),
                    'desc'       => __('min: 1 ←→ max: 6 for each generated','fdc'),
                    'max'        => 6,
                    'min'        => 1,
                    'step'       => 1,
                    'attributes' => [
                        'placeholder' => '1',
                    ],
                    'class' => 'fdc_exact_input_spinner hidden'
                ),
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 1','fdc'),
                    'max'        => 3,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => 'fdc_random_input_spinner ',
                    'attributes' => [
                        'placeholder' => '1',
                    ],
                ),
                array(
                    'id'         => 'to',
                    'type'       => 'spinner',
                    'title'      => __('To','fdc'),
                    'desc'       => __('example: 3','fdc'),
                    'max'        => 6,
                    'min'        => 1,
                    'step'       => 1,
                    'class' => 'fdc_random_input_spinner ',
                    'attributes' => [
                        'placeholder' => '3',
                    ],
                ),
            ),
            'default'    => array(
                'type'      => 0,
                'num_exact' => 2,
                'from'      => 1,
                'to'        => 3,
            ),
        ),
        array(
            'id'          => 'description_length',
            'type'        => 'select',
            'title'       => __('Description length','fdc'),
            'chosen'      => true,
            'options'     => array(
                'short'  => __('Short','fdc'),
                'medium' => __('Medium','fdc'),
                'long'   => __('Long','fdc'),
            ),
            'default' => 'short',
            'desc'    => __('Select the text size to be generated in the comment description','fdc'),
            'class'   => 'fdc-no-overflow',
        ),
        array(
            'id'       => 'description_tag',
            'type'     => 'button_set',
            'title'    => __('Select the tags','fdc'),
            'subtitle' => __('HTML elements that would be put in the description','fdc'),
            'desc'     => __('If you do not select any then it will generate plain text','fdc'),
            'multiple' => true,
            'options'  => array(
                'strong'  => __('&lt;strong>','fdc'),
                'em'      => __('&lt;em>','fdc'),
                'a'       => __('&lt;a>','fdc'),
                'code'    => __('&lt;code>','fdc'),
                'h3' => __('&lt;h3>','fdc'),
                'h4' => __('&lt;h4>','fdc'),
                'h5' => __('&lt;h5>','fdc'),
            ),
        ),

        array(
            'type'    => 'heading',
            'content' => __('Meta Comment','fdc')
        ),
        $fdc_options_meta,
        array(
            'id'      => 'button_html',
            'type'    => 'content',
            'content' => '<div class="fdc-footer-bottom"><a data-nonce="'
                        . wp_create_nonce('fdc_nonce_comments')
                        .'" class="button button-primary fdc-button-comments">Generate Comments</a></div>',
        ),
        array(
            'type'    => 'content',
            'class'   => 'fdc_wrap_output',
            'content' => '<div class="fdc_output"></div>',
		),
    )
);