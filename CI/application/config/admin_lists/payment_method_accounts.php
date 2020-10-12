<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_payment_method_accounts']['table'] = 'payment_method_account';
$config['list_payment_method_accounts']['order'] = 'id';
$config['list_payment_method_accounts']['table_class'] = 'table';
$config['list_payment_method_accounts']['keyfields'] = array('name');

$config['list_payment_method_accounts']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
          'hidden' => TRUE
    ),
    array('label'      => 'Name',
          'width'      => '150px',
          'field_name' => 'name',
          'href'     => base_url().'admin/form/payment_account/%d',
          'href_key' => 'id',
    ),
    array('label'      => 'Code',
          'field_name' => 'payment_code',
    ),


);

