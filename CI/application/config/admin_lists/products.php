<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_products']['table']       = 'product';
$config['list_products']['order']       = 'title';
$config['list_products']['table_class'] = 'rwd-table';
$config['list_products']['keyfields']   = array('title', 'file');

$config['list_products']['fields'] = array(
    array('label'      => '',
          'field_name' => 'id',
          'hidden'     => TRUE
    ),
    array('label'      => 'Title',
          'field_name' => 'title',
          'href'       => base_url().'admin/form/product/%s',
          'href_key'   => 'id'
    ),
    array('label'      => 'Upgrade',
          'field_name' => 'purchase_item_code',
           'format' => 'select',
        'select_list' => 'purchase_item_list'
    ),

    array('label'       => 'Type',
          'field_name'  => 'file_type',
          'format'      => 'select',
          'select_list' => 'product_type_list'
    ),
    array('label'       => 'File',
          'field_name'  => 'file',
    ),

);

