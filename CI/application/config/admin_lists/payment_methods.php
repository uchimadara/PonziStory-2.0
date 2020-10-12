<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_payment_methods']['table'] = 'payment_method';
$config['list_payment_methods']['order'] = 'sorting';
$config['list_payment_methods']['table_class'] = 'table';
$config['list_payment_methods']['keyfields'] = array('name','code');

$config['list_payment_methods']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
          'hidden' => TRUE
    ),
    array('label'      => 'Name',
          'width'      => '150px',
          'field_name' => 'name',
          'href'     => base_url().'admin/form/payment_item/%d',
          'href_key' => 'id',
    ),
    array('label'      => 'Code',
          'field_name' => 'code',
    ),
    array('label'      => 'Sorting',
          'width'      => '140px',
          'field_name' => 'sorting',
          'href'     => base_url().'admin/form/payment_item/%d',
          'href_key' => 'id',
    ),
    array('label'      => 'Enabled',
          'width'      => '140px',
          'field_name' => 'enabled',
          'format' => 'yesno',
          'href'     => base_url().'admin/form/payment_item/%d',
          'href_key' => 'id',
    ),

);

