<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_social'] = array(
    'table'  => 'user_social_network',
    'fields' => array(
        array(
            'label'      => 'Social Network',
            'field_name' => 'name',
            'type'       => 'select',
            'value'      => '',
            'select_list'       => 'social_network_list',
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'Link',
            'field_name' => 'link',
            'type'       => 'text',
            'value'      => 'http://',
            'maxlength'  => '255',
            'size'       => '30',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required|callback_valid_url',
        ),
    )
);
