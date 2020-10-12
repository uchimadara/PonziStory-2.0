<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_text_ad'] = array(
    'table'  => 'text_ad',
    'fields' => array(
        array(
            'label'      => 'Reference Name',
            'field_name' => 'name',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '50',
            'size'       => '35',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
            'tip' => 'This field is not displayed in the ad. It is used for your reference in charts and graphs. Keep it short for best display.'
        ),
        array(
            'label'      => 'Target URL',
            'field_name' => 'target_url',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '100',
            'size'       => '40',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required|callback_valid_url',
            'tip'        => 'Enter your affiliate link to the program website.'
        ),
        array(
            'label'      => 'Headline (25 characters max.)',
            'field_name' => 'headline',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '25',
            'size'       => '30',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required|callback_text_ad_case',
            'tip' => 'This is the first line of the ad displayed in <b>bold</b> text.'
        ),
        array(
            'label'      => 'Body (60 characters max.)',
            'field_name' => 'body',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '60',
            'size'       => '30',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required|callback_text_ad_case',
        ),
        array(
            'label'      => 'Assign Credits',
            'field_name' => 'credits',
            'type'       => 'int',
            'value'      => '',
            'maxlength'  => '6',
            'size'       => '6',

            'rule'       => 'trim|xss_clean|is_numeric|callback_valid_text_credits',
        ),
    )
);
