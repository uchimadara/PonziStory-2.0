<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_page'] = array(
    'table'  => 'page',
    'fields' => array(
        array(
            'label'      => 'Title',
            'field_name' => 'title',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '255',
            'size'       => '40',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'URI',
            'field_name' => 'uri',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '255',
            'size'       => '40',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'Content',
            'field_name' => 'page_html',
            'type'       => 'html',
            'value'      => '',
            'rows'       => '45',
            'cols'       => '80',

            'required'   => TRUE,
            'rule'       => 'xss_clean|required',
        ),
    )
);
