<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['form_email_templates']['table'] = 'email_templates';
$config['form_email_templates']['fields'] = array(
    array(
        'label' => 'Name',
        'field_name' => 'name',
        'type'       => 'text',
        'required'   => TRUE,
        'rule'       => 'trim|required|xss_clean|',
    ),
    array(
        'label' => 'Code',
        'field_name' => 'code',
        'type'       => 'text',
        'required'   => TRUE,
        'rule'       => 'trim|required|xss_clean|',
    ),
    array(
        'label' => 'Description',
        'field_name' => 'description',
        'type'       => 'text',
        'required'   => TRUE,
        'rule'       => 'trim|required|xss_clean|',
    ),
    array(
        'label' => 'Category',
        'field_name'  => 'category',
        'type'        => 'select',
        'select_list' => 'email_template_categories',
        'required'    => TRUE,
        'rule'        => 'trim|required|xss_clean|',
    ),
    array(
        'label' => 'Enabled',
        'field_name' => 'enabled',
        'type'       => 'checkbox',
        'required'   => TRUE,
        'rule'       => 'trim|required|xss_clean|',
    ),
    array(
        'label' => 'Fields',
        'field_name' => 'fields',
        'type'       => 'text',
        'required'   => TRUE,
        'rule'       => 'trim|required|xss_clean|',
    ),
    array(
        'label' => 'Subject',
        'field_name' => 'subject',
        'type'       => 'text',
        'required'   => TRUE,
        'rule'       => 'trim|required|xss_clean|',
    ),
    array(
        'label' => 'Content',
        'field_name' => 'content',
        'value'      => '',
        'type'       => 'html',
        'required'   => TRUE,
        'rule'       => 'trim|required|xss_clean|',
    ),
);

