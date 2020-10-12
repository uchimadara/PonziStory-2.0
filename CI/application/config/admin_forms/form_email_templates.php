<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['form_email_templates'] = array(
    'Name'        => array(
        'field_name' => 'name',
        'type'       => 'text',
        'required'   => TRUE,
        'rule'       => 'trim|required|xss_clean|',
    ),
    "Code" => array(
        'field_name' => 'code',
        'type'       => 'text',
        'required'   => TRUE,
        'rule'       => 'trim|required|xss_clean|',
    ),
    "Description" => array(
        'field_name' => 'description',
        'type'       => 'text',
        'required'   => TRUE,
        'rule'       => 'trim|required|xss_clean|',
    ),
    "Category"    => array(
        'field_name'  => 'category',
        'type'        => 'select',
        'select_list' => 'email_template_categories',
        'required'    => TRUE,
        'rule'        => 'trim|required|xss_clean|',
    ),
    "Enabled"    => array(
        'field_name'  => 'enabled',
        'type'        => 'checkbox',
        'required'    => TRUE,
        'rule'        => 'trim|required|xss_clean|',
    ),
    "Fields"      => array(
        'field_name'  => 'fields',
        'type'        => 'text',
        'required'    => TRUE,
        'rule'        => 'trim|required|xss_clean|',
    ),
    "Subject" => array(
        'field_name'  => 'subject',
        'type'        => 'text',
        'required'    => TRUE,
        'rule'        => 'trim|required|xss_clean|',
    ),
    "Content" => array(
        'field_name'  => 'content',
        'value' => '',
        'type'      => 'html',
        'required'  => TRUE,
        'rule'        => 'trim|required|xss_clean|',
    ),
);

