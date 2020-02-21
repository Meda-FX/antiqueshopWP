<?php
global $fdc_options_integration;
$fdc_options_integration = array(
    'title'  => __('Integration','fdc'),
    'active' => true,
    'fields' => array(
        array(
            'id'     => 'yuzo',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'    => 'subheading',
                    'content' => 'Yuzo <a target="_blank" href="https://wordpress.org/plugins/yuzo-related-post/"><i class="fa fa-external-link" aria-hidden="true"></i></a>'
                ),
                array(
                    'id'     => 'config_yuzo',
                    'type'   => 'fieldset',
                    'title'  => '',
                    'fields' => array(
                        array(
                            'type'    => 'subheading',
                            'content' => __('Generator of visits and clicks','fdc')
                        ),
                        array(
                            'id'       => 'post_type',
                            'type'     => 'button_set',
                            'title'    => __('Post Type','fdc'),
                            'desc'     => __('Select post types that you want to be generated views','fdc'),
                            'multiple' => true,
                            'options'  => 'post_type',
                            'default'  => ['post'],
                        ),
                        array(
                            'type'    => 'content',
                            'content' => '<strong>Views range</strong>',
                        ),
                        array(
                            'id'     => 'quantity',
                            'type'   => 'fieldset',
                            'inline' => true,
                            'class'  => 'fdc_extact_or_random',
                            'fields' => array(
                                array(
                                    'id'         => 'from',
                                    'type'       => 'spinner',
                                    'title'      => __('From','fdc'),
                                    'desc'       => __('example: 100','fdc'),
                                    'max'        => 5000,
                                    'min'        => 10,
                                    'step'       => 2,
                                    'class'      => 'fdc_random_input_spinner',
                                    'attributes' => [
                                        'placeholder' => '100',
                                    ],
                                ),
                                array(
                                    'id'         => 'to',
                                    'type'       => 'spinner',
                                    'title'      => __('To','fdc'),
                                    'desc'       => __('example: 25000','fdc'),
                                    'max'        => 50000,
                                    'min'        => 1,
                                    'step'       => 50,
                                    'class' => 'fdc_random_input_spinner',
                                    'attributes' => [
                                        'placeholder' => 'max 50000',
                                    ],
                                ),
                            ),
                            'default' => array(
                                'from'      => 100,
                                'to'        => 25000,
                            ),
                        ),
                        array(
                            'type'    => 'content',
                            'content' => '<strong>Clicks range</strong>',
                        ),
                        array(
                            'id'     => 'quantity2',
                            'type'   => 'fieldset',
                            'inline' => true,
                            'class'  => 'fdc_extact_or_random',
                            'fields' => array(
                                array(
                                    'id'         => 'from2',
                                    'type'       => 'spinner',
                                    'title'      => __('From','fdc'),
                                    'desc'       => __('example: 5','fdc'),
                                    'max'        => 50,
                                    'min'        => 1,
                                    'step'       => 2,
                                    'class'      => 'fdc_random_input_spinner',
                                    'attributes' => [
                                        'placeholder' => '5',
                                    ],
                                ),
                                array(
                                    'id'         => 'to2',
                                    'type'       => 'spinner',
                                    'title'      => __('To','fdc'),
                                    'desc'       => __('example: 250','fdc'),
                                    'max'        => 1000,
                                    'min'        => 1,
                                    'step'       => 5,
                                    'class' => 'fdc_random_input_spinner',
                                    'attributes' => [
                                        'placeholder' => 'max 1000',
                                    ],
                                ),
                            ),
                            'default' => array(
                                'from2'      => 5,
                                'to2'        => 250,
                            ),
                        ),
                    ),
                    'default' => array(),
                    //'dependency'   => array( 'media_type', 'any', '1,5,2' ),
                ),
                array(
                    'id'       => 'button_html',
                    'type'     => 'content',
                    'callback' => array("FDC\\Admin\\Fdc_Integration","yuzo_update_views_button"),
                ),
                array(
                    'type'    => 'content',
                    'class'   => 'fdc_wrap_output',
                    'content' => '<div class="fdc_output"></div>',
                ),
            ),
            'default' => array(),
            //'dependency'   => array( 'media_type', 'any', '1,5,2' ),
        ),
        array(
            'id'     => 'woocommerce',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'    => 'subheading',
                    'content' => 'WooCommerce <a target="_blank" href="https://wordpress.org/plugins/woocommerce/"><i class="fa fa-external-link" aria-hidden="true"></i></a>'
                ),
                array(
                    'id'       => 'button_html',
                    'type'     => 'content',
                    'content'  => '
<div data-id="4444422as1" class="pf-field pf-field-submessage">
<div class="pf-submessage pf-submessage-warning">
<h2>In construction...</h2>
<div class="clear"></div>
</div><br /></div>
',
                ),
            ),
            'default' => array(),
        ),
        array(
            'id'     => 'edd',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'    => 'subheading',
                    'content' => 'Easy Digital Downloads <a target="_blank" href="https://wordpress.org/plugins/easy-digital-downloads/"><i class="fa fa-external-link" aria-hidden="true"></i></a>'
                ),
                array(
                    'id'       => 'button_html',
                    'type'     => 'content',
                    'content'  => '
<div data-id="44344422as1" class="pf-field pf-field-submessage">
<div class="pf-submessage pf-submessage-warning">
<h2>In construction...</h2>
<div class="clear"></div>
</div><br />
',
                ),
            ),
            'default' => array(),
            //'dependency'   => array( 'media_type', 'any', '1,5,2' ),
        ),
    )
);