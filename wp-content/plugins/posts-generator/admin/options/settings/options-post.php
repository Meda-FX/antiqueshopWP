<?php
use FDC as fdc;
global $fdc_options_posts, $fdc_options_meta;
$fdc_options_posts = array(
    'title'  => __('Posts','fdc'),
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
                    'desc'       => __('min: 1 ←→ max: 15 for each generated','fdc'),
                    'max'        => 15,
                    'min'        => 1,
                    'step'       => 1,
                    'attributes' => [
                        'placeholder' => '10',
                    ],
                    'class'   => 'fdc_exact_input_spinner hidden',
                ),
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 7','fdc'),
                    'max'        => 10,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => 'fdc_random_input_spinner ',
                    'attributes' => [
                        'placeholder' => '7',
                    ],
                ),
                array(
                    'id'         => 'to',
                    'type'       => 'spinner',
                    'title'      => __('To','fdc'),
                    'desc'       => __('example: 12','fdc'),
                    'max'        => 15,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => 'fdc_random_input_spinner ',
                    'attributes' => [
                        'placeholder' => '12',
                    ],
                ),
            ),
            'default'    => array(
                'type'      => 0,
                'num_exact' => 10,
                'from'      => 7,
                'to'        => 12,
            ),
            //'desc'       => __('Order by a criterion the related post (Ascending / Descending)','yuzo'),
        ),
        array(
            'type'    => 'heading',
            'content' => __('Setting','fdc')
        ),
        array(
			'id'          => 'users',
			'type'        => 'select',
			'title'       => 'Select users',
			'desc'        => '<span style="font-weigth:600;color:#3B5BDB;">Note 1:</span> If you do not select any, the default author is a <code>administrator</code><br />
<span style="font-weigth:600;color:#3B5BDB;">Note 2:</span> If you have already generated users and this field is empty, then posts will be generated with random authors',
			'chosen'      => true,
			'multiple'    => true,
			'ajax'        => true,
			'options'     => 'users',
			'placeholder' => 'Select users',
			'class'       => 'fdc-no-overflow'
        ),
        array(
            'id'       => 'posts_status',
            'type'     => 'button_set',
            'title'    => __('Post Status','fdc'),
            'desc'     => __('Select the random states you want to generate the posts','fdc'),
            'multiple' => true,
            'options'  => 'status_post',
            'default'   => ['publish','private']
        ),
        array(
            'id'       => 'comment_status',
            'type'     => 'button_set',
            'title'    => __('Comments Status','fdc'),
            'desc'     => __('Select the available comment status','fdc'),
            'multiple' => true,
            'options'  => [
                'open'   => 'Allow comments',
                'closed' => 'Comments closed',
            ],
            'default'  => ['open']
        ),
        array(
            'id'       => 'post_type',
            'type'     => 'button_set',
            'title'    => __('Post Type','fdc'),
            'desc'     => __('Select the random post types that you want to be generated','fdc'),
            'multiple' => true,
            'options'  => 'post_type',
            'default'  => ['post'],
        ),
        array(
            'id'     => 'date',
            'type'   => 'fieldset',
            'title'  => __( 'Post Date', 'fdc' ),
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
            'content' => __('Title','fdc')
        ),
        array(
            'id'       => 'title_quantity',
            'type'     => 'fieldset',
            'inline'   => true,
            'title'    => __( 'Number of words', 'fdc' ),
            'subtitle' => __( 'Range of words a post can have', 'fdc' ),
            'class'    => 'fdc-overflow',
            'fields'   => array(
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 3','fdc'),
                    'max'        => 7,
                    'min'        => 3,
                    'step'       => 1,
                    'class'      => '',
                    'attributes' => [
                        'placeholder' => '3',
                    ],
                ),
                array(
                    'id'         => 'to',
                    'type'       => 'spinner',
                    'title'      => __('To','fdc'),
                    'desc'       => __('example: 10','fdc'),
                    'max'        => 15,
                    'min'        => 5,
                    'step'       => 1,
                    'class' => '',
                    'attributes' => [
                        'placeholder' => '10',
                    ],
                ),
            ),
            'default'    => array(
                'from' => 5,
                'to'   => 10,
            ),
        ),
        array(
			'id'     => 'title_size',
			'type'   => 'dimensions',
			'title'  => __( 'Maximum title size', 'fdc' ),
			'desc'   => __( 'Maximum number of <code>letters</code> a title can have, If you leave it empty then the limit will be the range of the previous option', 'fdc' ),
			'height' => false,
			'units'  => ['Letters'],

            'width_placeholder' => __( 'No restriction', 'fdc' ),
            'width_icon'        => '<i class="fa fa-text-width" aria-hidden="true"></i>',
            'attributes' => [
                'style' => 'width:100px;'
            ]
		),

        array(
            'type'    => 'heading',
            'content' => __('Content','fdc')
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
                    'desc'       => __('Exact quantities or a random range of paragraphs','fdc'),
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
                        'placeholder' => '3',
                    ],
                    'class' => 'fdc_exact_input_spinner hidden'
                ),
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 3','fdc'),
                    'max'        => 20,
                    'min'        => 3,
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
                    'desc'       => __('example: 10','fdc'),
                    'max'        => 20,
                    'min'        => 3,
                    'step'       => 1,
                    'class' => 'fdc_random_input_spinner',
                    'attributes' => [
                        'placeholder' => '3',
                    ],
                ),
            ),
            'default'    => array(
                'type'      => 'rand',
                'num_exact' => 1,
                'from'      => 5,
                'to'        => 15,
                'type'      => false
            ),
        ),
        array(
            'id'          => 'description_length',
            'type'        => 'select',
            'title'       => __('Paragraph length','fdc'),
            'chosen'      => true,
            'options'     => array(
                'short'  => __('Short','fdc'),
                'medium' => __('Medium','fdc'),
                'long'   => __('Long','fdc'),
            ),
            'default' => 'medium',
            'desc'    => __('Select the paragraph size to be generated in the content','fdc'),
            'class'   => 'fdc-no-overflow',
        ),
        array(
            'id'       => 'description_tag',
            'type'     => 'button_set',
            'title'    => __('Select the tags inline','fdc'),
            'subtitle' => __('HTML elements that would be put in the description','fdc'),
            'desc'     => __('Inline tag are the ones that can enter between the text','fdc'),
            'multiple' => true,
            'options'  => array(
                'strong' => __('&lt;strong>','fdc'),
                'em'     => __('&lt;em>','fdc'),
                'a'      => __('&lt;a>','fdc'),
                'code'   => __('&lt;code>','fdc'),
                'i'      => __('&lt;i>','fdc'),
                'mark'   => __('&lt;mark>','fdc'),
            ),
            'default'   => ['strong','em','a','mark']
        ),
        array(
            'id'       => 'description_tag_block',
            'type'     => 'button_set',
            'title'    => __('Select the tags block','fdc'),
            'subtitle' => __('HTML elements that would be put in the description','fdc'),
            'desc'     => __('Tag block are those that are formed outside the text and have their own space','fdc'),
            'multiple' => true,
            'options'  => array(
                'h2' => __('&lt;h2>','fdc'),
                'h3' => __('&lt;h3>','fdc'),
                'h4' => __('&lt;h4>','fdc'),
                'h5' => __('&lt;h5>','fdc'),
                'h6' => __('&lt;h6>','fdc'),
                'h7' => __('&lt;h7>','fdc'),

                'ul'         => __('&lt;ul>','fdc'),
                'lo'         => __('&lt;lo>','fdc'),
                'dl'         => __('&lt;dl>','fdc'),
                'blockquote' => __('&lt;blockquote>','fdc'),
                //'img'        => __('&lt;img>','fdc'),
                'table'     => __('&lt;table>','fdc'),
                'pre-code'  => __('&lt;pre/code>','fdc'),
                'div'       => __('&lt;div>','fdc'),
                'hr'        => __('&lt;hr>','fdc'),
                'read-more' => __('&lt;-- readmore -->','fdc'),
                'next-page' => __('&lt;-- nextpage -->','fdc'),
            ),
            'default'   => ['h2','h3','h4','h5','hr','ul','lo','blockquote','img','table','read-more']
        ),

        array(
            'type'    => 'heading',
            'content' => __('Media','fdc')
        ),
        array(
            'id'       => 'media_type',
            'type'     => 'select',
            'title'    => __('Post image','fdc'),
            'desc'     => __('','fdc'),
            'multiple' => false,
            'chosen'   => true,
            'class'    => 'fdc-no-overflow',
            'options'  => [
                1 => 'Featured Image (AND) Image inside the posts',
                5 => 'Featured Image (OR) Image inside the posts',
                2 => 'Only Featured Image',
                3 => 'Only Image inside the post',
                4 => 'Post without image',
            ],
            'default'  => 5,
        ),
        array(
            'id'     => 'media_featured_image',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'    => 'subheading',
                    'content' => __('Configuration of the featured the post','fdc')
                ),
                array(
                    'id'       => 'source2',
                    'type'     => 'button_set',
                    'title'    => __('Image sources','fdc'),
                    'desc'     => __('','fdc'),
                    'multiple' => true,
                    'options'  => [
                        'unsplash'   => 'Unsplash.com',
                        'picsum'     => 'Picsum.photos',
                        'ipsumimage' => 'Ipsumimage.com',
                    ],
                ),
                array(
                    'id'    => 'size',
                    'type'  => 'dimensions',
                    'width_icon'  => 'width (px)',
                    'height_icon' => 'height (px)',
                    'title' => __( 'Fixed dimensions', 'fdc' ),
                    'desc' => __( 'Set the sizes you want the images to be generated
<br />If you leave it empty then the sizes will be random', 'fdc' )
                ),
            ),
            'default' => array(
                'source2' => ['unsplash','picsum'],
                'banner' => ['banner-square','banner-vertical','banner-horizontal'],
                'size'   => ['width' => 1024, 'height' => 640],
            ),
            'dependency'   => array( 'media_type', 'any', '1,5,2' ),
        ),
        array(
            'id'     => 'media_post_image',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'    => 'subheading',
                    'content' => __('Configuration of the images inside the post','fdc')
                ),
                array(
                    'id'       => 'source',
                    'type'     => 'button_set',
                    'title'    => __('Image sources','fdc'),
                    'desc'     => __('','fdc'),
                    'multiple' => true,
                    'options'  => [
                        'unsplash'   => 'Unsplash.com',
                        'picsum'     => 'Picsum.photos',
                        'ipsumimage' => 'Ipsumimage.com',
                    ],
                ),
                array(
                    'id'          => 'size',
                    'type'        => 'dimensions',
                    'width_icon'  => 'width (px)',
                    'height_icon' => 'height (px)',
                    'title'       => __( 'Fixed dimensions', 'fdc' ),
                    'desc'        => __( 'Set the sizes you want the images to be generated
<br />If you leave it empty then the sizes will be random (recommended)', 'fdc' )
                ),
                array(
                    'id'       => 'quantity',
                    'type'     => 'fieldset',
                    'inline'   => true,
                    'title'    => __( 'Quantity', 'fdc' ),
                    'subtitle' => __( 'Number of random image to insert per post', 'fdc' ),
                    'class'    => 'fdc-overflow',
                    'fields'   => array(
                        array(
                            'id'         => 'from',
                            'type'       => 'spinner',
                            'title'      => __('From','fdc'),
                            'desc'       => __('example: 1','fdc'),
                            'max'        => 2,
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
                            'desc'       => __('example: 3','fdc'),
                            'max'        => 4,
                            'min'        => 1,
                            'step'       => 1,
                            'class' => '',
                            'attributes' => [
                                'placeholder' => '4',
                            ],
                        ),
                    ),
                    'default'    => array(
                        'from' => 1,
                        'to'   => 2,
                    ),
                ),
                array(
                    'id'       => 'banner',
                    'type'     => 'image_select',
                    'title'    => '+ Imagen banner',
                    'multiple' => true,
                    'options'  => array(
                        'banner-square'     => fdc\URL . 'admin/assets/images/banner-square.png',
                        'banner-vertical'   => fdc\URL . 'admin/assets/images/banner-vertical.png',
                        'banner-horizontal' => fdc\URL . 'admin/assets/images/banner-horizontal.png',
                    ),
                    'attributes' => array(
                        'width'  => '80px',
                        'height' => '80px',
                    ),
                    'desc' =>__( 'Add single color images with features of irregular shapes within the content.<br />
These features are square, straight and vertical, these images also have left or right alignments. <br />
With this you could know how your images look like.', 'fdc' ),
                ),
            ),
            'default' => array(
                'source' => ['unsplash','picsum',''],
                'banner' => ['banner-square','banner-horizontal',]
            ),
            'dependency'   => array( 'media_type', 'any', '1,5,3' ),
        ),
        array(
            'id'       => 'social_embed',
            'type'     => 'button_set',
            'title'    => __('Social embed available','fdc'),
            'subtitle' => __('Social networks with embed available','fdc'),
            'desc'     => __('Currently the bloggers are inserting link from facebook, twitter, youtube, etc. within the content','fdc'),
            'multiple' => true,
            'class' => 'fdc-overflow',
            'options'  => [
                'fb' => 'Facebook',
                'tw' => 'Twitter',
                'in' => 'Instagram',
                'yt' => 'Youtube',
                'sc' => 'SoundCloud',
            ],
            'default'  => [],
        ),

        array(
            'type'    => 'heading',
            'content' => __('Taxonomies','fdc')
        ),
        array(
            'id'       => 'taxonomies_type',
            'type'     => 'button_set',
            'title'    => __('Types of taxonomies to include','fdc'),
            'desc'     => __('','fdc'),
            'multiple' => false,
            'options'  => [
                1 => 'Categories & Tags',
                2 => 'Only Categories',
                3 => 'Only Tags',
            ],
            'default'  => 1,
        ),
        array(
			'id'          => 'taxonomies_cate',
			'title'       => 'Select categories',
			'type'        => 'taxonomies',
			'text_all'    => __( 'All Categories', 'fdc' ),
            'text_select' => __( 'Select several', 'fdc' ),
            'setting' => [
                'value_with_tax' => true,
                'field_as_id'    => 'term_id',
                'force_all_when_without_term' => true,
            ],
			'desc'       => __( 'Select the categories you want to be added randomly to each post', 'fdc' ),
			'default'    => array( 'category' => ['all' => 1] ),
			'dependency' => array( 'taxonomies_type', 'any', '1,2' ),
        ),
        array(
			'id'           => 'taxonomies_tag',
			'title'        => 'Select tags',
			'type'         => 'taxonomies',
			'hierarchical' => false,
			'ajax'         => true,
			'subtitle'     => __( 'If you leave it empty then it will randomly take from those that are already created', 'fdc' ),
			'desc'         => __( 'Type the tags you want to be added randomly in each post', 'fdc' ),
			'dependency'   => array( 'taxonomies_type', 'any', '1,3' ),
			'placeholder'  => __( 'Write a tag or leave this field empty to reference all', 'fdc' )
        ),
        array(
            'id'       => 'quantity_term',
            'type'     => 'fieldset',
            'inline'   => true,
            'title'    => __( 'Quantity', 'fdc' ),
            'subtitle' => __( 'Number of random terms post related', 'fdc' ),
            'class'    => 'fdc-overflow',
            'fields'   => array(
                array(
                    'id'         => 'from',
                    'type'       => 'spinner',
                    'title'      => __('From','fdc'),
                    'desc'       => __('example: 1','fdc'),
                    'max'        => 4,
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
                    'desc'       => __('example: 4','fdc'),
                    'max'        => 15,
                    'min'        => 1,
                    'step'       => 1,
                    'class'      => '',
                    'attributes' => [
                        'placeholder' => '4',
                    ],
                ),
            ),
            'default'    => array(
                'from' => 1,
                'to'   => 4,
            ),
        ),
        array(
            'type'    => 'heading',
            'content' => __('Meta Post','fdc')
        ),
        $fdc_options_meta,
        array(
            'id'      => 'button_html',
            'type'    => 'content',
            'content' => '<div class="fdc-footer-bottom"><a data-nonce="'
                        . wp_create_nonce('fdc_nonce_posts')
                        .'" class="button button-primary fdc-button-posts">Generate Posts</a></div>',
        ),
        array(
            'type'    => 'content',
            'class'   => 'fdc_wrap_output',
            'content' => '<div class="fdc_output"></div>',
		),
    )
);