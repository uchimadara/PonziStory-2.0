<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_purchase_item'] = array(
    'table'  => 'purchase_item',
    'fields' => array(
        array(
            'label'      => 'Code',
            'field_name' => 'code',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '25',
            'size'       => '25',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'Category',
            'field_name' => 'category',
            'type'       => 'select',
            'select_list'      => 'purchase_item_category_list',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'Title',
            'field_name' => 'title',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '50',
            'size'       => '25',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
//        array(
//            'label'      => 'Duration (days)',
//            'field_name' => 'duration',
//            'type'       => 'int',
//            'maxlength' => '5',
//            'size'      => '5',
//        ),
//        array(
//            'label'      => 'TE Credits',
//            'field_name' => 'te_credits',
//            'type'       => 'int',
//            'maxlength'  => '5',
//            'size'       => '5',
//        ),
        array(
            'label'      => 'Text Ad Credits',
            'field_name' => 'text_ad_credits',
            'type'       => 'int',
            'maxlength'  => '6',
            'size'       => '6',
        ),
        array(
            'label'      => 'Maximum Ads',
            'field_name' => 'max_ads',
            'type'       => 'int',
            'maxlength'  => '2',
            'size'       => '5',
        ),
//        array(
//            'label'      => 'Banner Credits',
//            'field_name' => 'banner_credits',
//            'type'       => 'int',
//            'maxlength'  => '5',
//            'size'       => '5',
//        ),
        array(
            'label'       => 'Active',
            'field_name'  => 'status',
            'type'        => 'select',
            'select_list' => 'yes_no_int',
            'rule'        => 'trim|required|',
            'required'    => TRUE
        ),
        array(
            'label'      => 'Price',
            'field_name' => 'price',
            'type'       => 'currency',
            'size'       => '20',
            'maxlength'  => '12',
        ),
    )
);

