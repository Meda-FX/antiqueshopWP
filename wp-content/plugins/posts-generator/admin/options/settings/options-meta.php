<?php
global $fdc_options_meta;
$fdc_options_meta = array(
    'id'     => 'meta',
    'type'   => 'repeater',
    'title'  => 'Repeater Various',
    'class'   => 'fdc-meta-repeat',
    'fields' => array(
        array(
            'id'      => 'meta_type',
            'type'    => 'select',
            'title'   => 'Type you want to add',
            'chosen'  => true,
            'class'   => 'fdc-no-overflow',
            'options' => array(
                'text'      => 'Text',
                'words'     => 'Words',
                'ip'        => 'IP',
                'domain'    => 'Domain',
                'html'      => 'HTML',
                'element'   => 'Element',
                'long-text' => 'Long text',
                'person'    => 'Person',
                'date'      => 'Date',
            ),
        ),
        array(
            'id'         => 'meta_name',
            'type'       => 'text',
            'title'      => __( 'Meta name', 'fdc' ),
            'attributes' => [
                'placeholder' => __( '__my_meta_name', 'fdc' ),
                'class'       => '_fdc_meta_input_name'
            ],
            'desc' => __( 'If you do not enter a meta name then you will not generate this meta', 'fdc' )
        ),
        array(
            'id'         => 'meta_value_text',
            'type'       => 'text',
            'title'      => __('Value','fdc'),
            'dependency' => array( 'meta_type', '==', 'text' ),
            'attributes' => [
                'placeholder' => __('Some text or number','fdc'),
            ],
        ),
        array(
            'id'         => 'meta_value_ip',
            'type'       => 'switcher',
            'title'      => __('IP type','fdc'),
            'text_on'    => __('IPv6','fdc'),
            'text_off'   => __('IPv4','fdc'),
            'text_width' => '70',
            'desc'       => __('Select the type of IP you want this user goal to have','fdc'),
            'class'      => 'fdc_two_colors',
            'dependency' => array( 'meta_type', '==', 'ip' ),
        ),
        array(
            'type'       => 'notice',
            'style'      => 'info',
            'content'    => 'Fake domain will be generated with extensions
            <code>.com</code> <code>.net</code> <code>.it</code> <code>.co</code> <code>.ec</code> <code>.es</code> <code>.org</code> <code>.edu</code> <code>.xyz</code> <code>.us</code>',
            'dependency' => array( 'meta_type', '==', 'domain' ),
        ),
        array(
            'id'       => 'description_tag',
            'type'     => 'button_set',
            'title'    => __('Select the tags','fdc'),
            'subtitle' => __('HTML elements that would be put in the description','fdc'),
            'desc'     => __('If you do not select any then it will generate plain text','fdc'),
            'multiple' => true,
            'class'    => 'fdc_fix_buttonset fdc-no-overflow',
            'options'  => array(
                'strong'     => __('&lt;strong>','fdc'),
                'em'         => __('&lt;em>','fdc'),
                'a'          => __('&lt;a>','fdc'),
                'code'       => __('&lt;code>','fdc'),
                'heading'    => __('&lt;h2-h6>','fdc'),
                //'p'          => __('&lt;p>','fdc'),
                'div'        => __('&lt;div>','fdc'),
                'ul'         => __('&lt;ul>','fdc'),
                'lo'         => __('&lt;lo>','fdc'),
                'blockquote' => __('&lt;blockquote>','fdc'),
            ),
            'dependency' => array( 'meta_type', '==', 'html' ),
        ),
        array(
            'id'         => 'meta_value_paragraphs',
            'title'      => __( 'Range of paragraphs', 'fdc' ) ,
            'subtitle'   => __( 'Generate random numbers in defined ranges', 'fdc' ),
            'type'       => 'fieldset',
            'inline'     => true,
            'class'      => '',
            'dependency' => array( 'meta_type', '==', 'html' ),
            'fields'     => array(
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 5','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => '',
                    'attributes' => [
                        'placeholder' => '5',
                    ],
                ),
                array(
                    'id'         => 'to',
                    'type'       => 'spinner',
                    'title'      => __('To','fdc'),
                    'desc'       => __('example: 10','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => '',
                    'attributes' => [
                        'placeholder' => '10',
                    ],
                ),
            ),
            'default' => [ 'from' => 4, 'to' => 10 ]
        ),
        array(
            'id'            => 'meta_value_element_tag',
            'title'         => 'Enter items',
            'desc'          => 'After each element (word) press Enter to continue with the other',
            'type'          => 'tag',
            'default'       => '',
            'attributes_js' => array(
                '"placeholder"' => '"my element 1, my other element"',
            ),
            'dependency' => array( 'meta_type', '==', 'element' ),
            'default' => 'my element 1, my other element'
        ),
        array(
            'id'         => 'meta_value_numbers_words',
            'title'      => __( 'Range of words', 'fdc' ) ,
            'subtitle'   => __( 'Generate random numbers of words', 'fdc' ),
            'type'       => 'fieldset',
            'inline'     => true,
            'class'      => 'fdc_extact_or_random',
            'dependency' => array( 'meta_type', '==', 'words' ),
            'fields'     => array(
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 4','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => '',
                    'attributes' => [
                        'placeholder' => '1',
                    ],
                ),
                array(
                    'id'         => 'to',
                    'type'       => 'spinner',
                    'title'      => __('To','fdc'),
                    'desc'       => __('example: 10','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => '',
                    'attributes' => [
                        'placeholder' => '10',
                    ],
                ),
            ),
            'default' => [ 'from' => 4, 'to' => 10 ]
        ),
        array(
            'id'         => 'meta_value_element',
            'title'      => __( 'Range of element', 'fdc' ) ,
            'subtitle'   => __( 'Generate random numbers of element entered', 'fdc' ),
            'type'       => 'fieldset',
            'inline'     => true,
            'class'      => 'fdc_extact_or_random',
            'dependency' => array( 'meta_type', '==', 'element' ),
            'fields'     => array(
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 4','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => '',
                    'attributes' => [
                        'placeholder' => '4',
                    ],
                ),
                array(
                    'id'         => 'to',
                    'type'       => 'spinner',
                    'title'      => __('To','fdc'),
                    'desc'       => __('example: 10','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => '',
                    'attributes' => [
                        'placeholder' => '10',
                    ],
                ),
            ),
            'default' => [ 'from' => 4, 'to' => 10 ]
        ),
        array(
            'id'          => 'description_length',
            'type'        => 'select',
            'title'       => __('Type of text','fdc'),
            'chosen'      => true,
            'options'     => array(
                'sentence'  => __('Sentence','fdc'),
                'paragraph' => __('Paragraph','fdc'),
            ),
            'desc'    => __('Select the text size to be generated','fdc'),
            'dependency' => array( 'meta_type', '==', 'long-text' ),
        ),
        array(
            'id'         => 'meta_value_long_text',
            'title'      => __( 'Range according to criteria', 'fdc' ) ,
            'subtitle'   => __( 'Generate random numbers', 'fdc' ),
            'type'       => 'fieldset',
            'inline'     => true,
            'class'      => 'fdc_extact_or_random',
            'dependency' => array( 'meta_type', '==', 'long-text' ),
            'fields'     => array(
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 2','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => '',
                    'attributes' => [
                        'placeholder' => '2',
                    ],
                ),
                array(
                    'id'         => 'to',
                    'type'       => 'spinner',
                    'title'      => __('To','fdc'),
                    'desc'       => __('example: 6','fdc'),
                    'max'        => 50,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => '',
                    'attributes' => [
                        'placeholder' => '6',
                    ],
                ),
            ),
            'default' => [ 'from'=> 2, 'to' => 6 ]
        ),
        array(
            'id'         => 'meta_value_separate',
            'type'       => 'text',
            'title'      => __('Separator','fdc'),
            'attributes' => [
                'placeholder' => __(',','fdc'),
            ],
            'dependency' => array( 'meta_type', '==', 'element' ),
            'default'    => ',',
        ),
        array(
            'id'          => 'meta_value_person',
            'type'        => 'select',
            'title'       => 'Place your settings',
            'chosen'      => true,
            'multiple'    => true,
            'sortable'    => true,
            'placeholder' => 'Select options',
            'options'     => array(
                'name'     => 'First name',
                'lastname' => 'Last name',
                /* 'title'    => 'Title', */
            ),
            'dependency' => array( 'meta_type', '==', 'person' ),
            'default'    => ['name','lastname'],
        ),
        array(
            'id'         => 'meta_value_date',
            'title'      => __( 'Date Settings', 'fdc' ) ,
            'subtitle'   => __( 'Place the date ranges as well as their format', 'fdc' ),
            'type'       => 'fieldset',
            'class'      => 'fdc_meta_date_fix',
            'dependency' => array( 'meta_type', '==', 'date' ),
            'fields'     => array(
                array(
                    'id'      => 'date1',
                    'type'    => 'date',
                    'from_to' => true,
                    'settings' => array(
                        'dateFormat'      => 'yy-mm-dd',
                    )
                ),
                array(
                    'id'         => 'format',
                    'type'       => 'text',
                    'title'      => __('Format','fdc'),
                    'attributes' => [
                        'placeholder' => __('Y-m-d','fdc'),
                    ],
                    'desc'    => 'You can form your own format with <a href="https://www.php.net/manual/es/function.date.php" target="_blank">this guide</a>',
                ),
            ),
            'default' => [ 'date1'=> [ 'from' => date("Y-m-d"), 'to' => date("Y-m-d") ], 'format' => 'Y-m-d' ]
        ),
    ),
    /* 'default' => array(
        array(
            'meta_type'                => 'text',
            'meta_name'                => '',
            'meta_value_text'          => '',
            'meta_value_numbers_words' => ['from' => 1, 'to' => 2 ],
            'meta_value_paragraphs'    => ['from' => 2, 'to' => 3 ],
            'meta_value_element'       => ['from' => 1, 'to' => 5 ],
            'meta_value_long_text'     => ['from' => 2, 'to' => 6 ],
            'meta_value_ip'            => null,
            'meta_value_element_tag'   => 'my element 1, my other element',
            'meta_value_separate'      => ',',
            'description_length'       => 'sentence',
            'meta_value_person'        => array( 'name', 'lastname' ),
            'meta_value_date'          => [ 'date1'=> [ 'from' => date("Y-m-d"), 'to' => date("Y-m-d") ] ]
        ),
    ), */
);