<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_earning']['table']       = 'transaction';
$config['list_earning']['order']       = 'created';
$config['list_earning']['sort_dir'] = 'desc';
$config['list_earning']['where'] = " type='earning' ";

$config['list_earning']['join']        = array(
    array('users', 'users.id = transaction.user_id'),
);
$config['list_earning']['table_class'] = 'rwd-table';
$config['list_earning']['keyfields']   = array(
    'transaction.id', 'transaction.item_code', 'transaction.reference_id', 'users.username');

$config['list_earning']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
          "href"     => SITE_ADDRESS."ajax/view_transaction/%d",
          'class'    => "popup underline",
          'href_key' => 'id',
          'title'    => 'Transaction Details',
    ),
    array('label'      => 'user_id',
          'field_name' => 'user_id',
          'hidden'     => TRUE,
    ),
    array('label'      => 'Username',
          'width'      => '10%',
          'field_name' => 'username',
          'table_name' => 'users',
          'href'       => SITE_ADDRESS.'admin/user/%s',
          'href_key'   => 'user_id'
    ),
    array('label'      => 'Created',
          'field_name' => 'created',
          'format'     => 'datetime'
    ),
    array('label'      => 'Item',
          'field_name' => 'item_code',
    ),
    array('label'      => 'Reference ID',
          'field_name' => 'reference_id',
    ),
    array('label'      => 'Amount',
          'field_name' => 'gross_amount',
          'format'     => 'currency'
    ),
);
