<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_commissions']['table']       = 'transaction';
$config['list_commissions']['order']       = 'created';
$config['list_commissions']['sort_dir']    = 'desc';
$config['list_commissions']['where']       = " transaction.type='ref_comm' ";
$config['list_commissions']['table_class'] = 'rwd-table';
$config['list_commissions']['keyfields']   = array(
    'transaction.id', 'transaction.method', 'transaction.item_code', 'users.username', 'u1.username');
$config['list_commissions']['join']        = array(
    array('users', 'users.id = transaction.user_id'),
);

$config['list_commissions']['fields'] = array(
    array('label'      => 'ID',
          'field_name' => 'id',
          "href"       => SITE_ADDRESS."ajax/view_transaction/%d",
          'class'      => "popup underline",
          'href_key'   => 'id',
          'title'      => 'Transaction Details',
    ),
    array('label'      => 'user_id',
          'field_name' => 'user_id',
          'hidden'     => TRUE,
    ),
    array('label'      => 'Username',
          'field_name' => 'username',
          'table_name' => 'users',
          'href'       => SITE_ADDRESS.'admin/user/%s',
          'href_key'   => 'user_id'
    ),
    array('label'      => 'Created',
          'field_name' => 'created',
          'format'     => 'datetime'
    ),
    array('label'      => 'Purchaser',
          'field_name' => 'purchaser',
          'href'     => SITE_ADDRESS.'admin/user/%s',
          'href_key' => 'purchaser_id'
    ),
    array('label'      => 'Item',
          'field_name' => 'description',
          'table_name' => 'p',
    ),
    array('label'      => 'Level',
          'field_name' => 'item_code',
    ),
    array('label'      => 'Amount',
          'field_name' => 'gross_amount',
          'format'     => 'currency'
    ),
);
