<?php
global $fdc_options_user, $fdc_options_meta;
$fdc_options_user = array(
    'title'  => __('Users','fdc'),
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
                    'desc'       => __('Select between exact quantities or a random range','fdc'),
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
                        'placeholder' => '12',
                    ],
                    'class' => 'fdc_exact_input_spinner hidden'
                ),
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 4','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => 'fdc_random_input_spinner',
                    'attributes' => [
                        'placeholder' => '5',
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
                'num_exact' => 25,
                'from'      => 5,
                'to'        => 20,
            ),
            //'desc'       => __('Order by a criterion the related post (Ascending / Descending)','yuzo'),
        ),
        array(
            'id'       => 'role',
            'type'     => 'button_set',
            'title'    => __('Roles','fdc'),
            'desc'     => __('Select users role to be generated randomly','fdc'),
            'subtitle' => __('If you do not select any, the default role is<code>administrator</code>','fdc'),
            'multiple' => true,
            'class'    => 'fdc-overflow',
            'options'  => array(
                'administrator' => __('Admnistrator','fdc'),
                'editor'        => __('Editor','fdc'),
                'author'        => __('Author','fdc'),
                'contributor'   => __('Contributor','fdc'),
                'subscriber'    => __('Subscriber','fdc'),
            ),
            'default' => ['administrator','editor']
        ),

        array(
            'type'    => 'heading',
            'content' => __('User Description (Biography)','fdc')
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
                    'class' => 'fdc_exact_input_spinner'
                ),
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 1','fdc'),
                    'max'        => 3,
                    'min'        => 0,
                    'step'       => 1,
                    'class'      => 'fdc_random_input_spinner hidden',
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
                    'class' => 'fdc_random_input_spinner hidden',
                    'attributes' => [
                        'placeholder' => '3',
                    ],
                ),
            ),
            'default'    => array(
                'type'      => 'exact',
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
            'desc'    => __('Select the text size to be generated in the user description','fdc'),
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
            'content' => __('Meta User','fdc')
        ),
        $fdc_options_meta,
        array(
            'id'      => 'button_html',
            'type'    => 'content',
            'content' => '<div class="fdc-footer-bottom"><a data-nonce="'
                        . wp_create_nonce('fdc_nonce_users')
                        .'" class="button button-primary fdc-button-users">Generate Users</a></div>',
        ),
        array(
            'type'    => 'content',
            'class'   => 'fdc_wrap_output',
            'content' => '<div class="fdc_output"></div>',
		),
    )
);