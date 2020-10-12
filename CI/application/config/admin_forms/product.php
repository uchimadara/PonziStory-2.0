<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_product'] = array(
    'table'  => 'product',
    'fields' => array(
        array(
            'label'       => 'Upgrade',
            'field_name'  => 'purchase_item_code',
            'type'        => 'select',
            'select_list' => 'purchase_item_list',
            'rule'        => 'trim|required|',
            'required'    => TRUE
        ),
        array(
            'label'       => 'Active',
            'field_name'  => 'active',
            'type'        => 'select',
            'select_list' => 'yes_no_int',
            'value' => 1,
            'rule'        => 'trim|required|',
            'required'    => TRUE
        ),
        array(
            'label'      => 'Filename',
            'field_name' => 'file',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '100',
            'size'       => '40',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array (
            'label'      => 'Type',
            'field_name' => 'type',
            'type'       => 'select',
            'value'      => '',
            'select_list'  => 'product_type_list',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'Title',
            'field_name' => 'title',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '100',
            'size'       => '40',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'Description',
            'field_name' => 'description',
            'type'       => 'textarea',
            'value'      => '',
            'rows'       => '5',
            'cols'       => '80',
            'maxlength' => '512',
            'required'   => TRUE,
            'rule'       => 'xss_clean|required',
        ),
    )
);
