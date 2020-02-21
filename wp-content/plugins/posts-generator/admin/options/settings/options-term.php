<?php
global $fdc_options_terms, $fdc_options_meta;
$fdc_options_terms = array(
    'title'  => __('Terms','fdc'),
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
                    'desc'       => __('min: 1 ←→ max: 50 for each generated','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'attributes' => [
                        'placeholder' => '25',
                    ],
                    'class'   => 'fdc_exact_input_spinner',
                ),
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 4','fdc'),
                    'max'        => 10,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => 'fdc_random_input_spinner hidden',
                    'attributes' => [
                        'placeholder' => '10',
                    ],
                ),
                array(
                    'id'         => 'to',
                    'type'       => 'spinner',
                    'title'      => __('To','fdc'),
                    'desc'       => __('example: 25','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class' => 'fdc_random_input_spinner hidden',
                    'attributes' => [
                        'placeholder' => '25',
                    ],
                ),
            ),
            'default'    => array(
                'type'      => 'exact',
                'num_exact' => 30,
                'from'      => 5,
                'to'        => 25,
            ),
            //'desc'       => __('Order by a criterion the related post (Ascending / Descending)','yuzo'),
        ),
        array(
            'id'       => 'size_word',
            'type'     => 'fieldset',
            'title'    => 'Name Size',
            'subtitle' => __( 'Number of words the term can have', 'fdc' ),
            'inline'   => true,
            'fields'   => array(
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 1','fdc'),
                    'max'        => 2,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => 'fdc_random_input_spinner',
                    'attributes' => [
                        'placeholder' => '1',
                    ],
                ),
                array(
                    'id'         => 'to',
                    'type'       => 'spinner',
                    'title'      => __('To','fdc'),
                    'desc'       => __('example: 2','fdc'),
                    'max'        => 3,
                    'min'        => 1,
                    'step'       => 1,
                    'class' => 'fdc_random_input_spinner',
                    'attributes' => [
                        'placeholder' => '2',
                    ],
                ),
            ),
            'default'    => array(
                'from'      => 1,
                'to'        => 2,
            ),
            //'desc'       => __('Order by a criterion the related post (Ascending / Descending)','yuzo'),
        ),
        array(
            'id'       => 'taxonomies',
            'type'     => 'button_set',
            'title'    => __('Taxonomies','fdc'),
            'desc'     => __('Select the available taxonomies','fdc'),
            //'subtitle' => __('If you do not select any, the default role is<code>administrator</code>','fdc'),
            'multiple' => true,
            'options'  => 'taxonomies',
            'query_args' => [
                'exclude'  => ['post_format'],
            ],
            'default'  => ['category','post_tag']
        ),

        array(
            'type'    => 'heading',
            'content' => __('Term Description','fdc')
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
                    'desc'       => __('min: 0 ←→ max: 6 for each generated','fdc'),
                    'max'        => 6,
                    'min'        => 0,
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
                    'min'        => 0,
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
                'num_exact' => 1,
                'from'      => 1,
                'to'        => 2,
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
            'desc'    => __('Select the text size to be generated in the term description','fdc'),
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
                'h1' => __('&lt;h1>','fdc'),
                'h2' => __('&lt;h2>','fdc'),
                'h3' => __('&lt;h3>','fdc'),
                'h4' => __('&lt;h4>','fdc'),
                'h5' => __('&lt;h5>','fdc'),
                'h6' => __('&lt;h6>','fdc'),
                'h7' => __('&lt;h7>','fdc'),
            ),
        ),

        array(
            'type'    => 'heading',
            'content' => __('Meta Term','fdc')
        ),
        $fdc_options_meta,
        array(
            'id'      => 'button_html',
            'type'    => 'content',
            'content' => '<div class="fdc-footer-bottom"><a data-nonce="'
                        . wp_create_nonce('fdc_nonce_terms')
                        .'" class="button button-primary fdc-button-terms">Generate Terms</a></div>',
        ),
        array(
            'type'    => 'content',
            'class'   => 'fdc_wrap_output',
            'content' => '<div class="fdc_output"></div>',
		),
    )
);