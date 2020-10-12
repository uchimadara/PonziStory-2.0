<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_transactions']['table']       = 'transaction';
$config['list_transactions']['order']       = 'created';
$config['list_transactions']['sort_dir'] = 'desc';

$config['list_transactions']['join']        = array(
    array('users', 'users.id = transaction.user_id'),
    array('purchase_order p', 'p.id = transaction.reference_id')
);
$config['list_transactions']['table_class'] = 'table';
$config['list_transactions']['keyfields']   = array(
    'transaction.id', 'transaction.method', 'transaction.type', 'transaction.item_code', 'username', 'reference_id', 'transaction.status');

$config['list_transactions']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
        'width' => '2%'
    ),
    array('label'      => 'User',
          'width'      => '8%',
          'field_name' => 'username',
          'table_name' => 'users',
          'href'       => SITE_ADDRESS.'admin/user/%s',
          'href_key'   => 'user_id'
    ),
    array('label'      => 'Type',
          'width'      => '6%',
          'field_name' => 'type',
    ),
    array('label'      => 'Code',
          'width'      => '6%',
          'field_name' => 'item_code',
    ),
    array('label'      => 'Description',
          'width'      => '14%',
          'field_name' => 'description',
          'table_name' => 'p'
    ),
    array('label'      => 'Status',
          'width'      => '5%',
          'field_name' => 'status',
    ),
    array('label'      => 'Reference ID',
          'width'      => '5%',
          'field_name' => 'reference',
    ),
    array('label'      => 'Method',
          'width'      => '3%',
          'field_name' => 'method',
          'table_name' => 'p'
    ),
    array('label'      => 'User Account',
          'width'      => '10%',
          'field_name' => 'user_account',
    ),
    array('label'      => 'Gross',
          'width'      => '8%',
          'field_name' => 'gross_amount',
          'format'     => 'currency'
    ),
    array('label'      => 'Fee',
          'width'      => '8%',
          'field_name' => 'fee',
          'format'     => 'currency',
          'table_name' => 'p'
    ),
    array('label'      => 'Cost',
          'width'      => '8%',
          'field_name' => 'cost',
          'format'     => 'currency',
    ),
    array('label'      => 'Created',
          'width'      => '12%',
          'field_name' => 'created',
          'format'     => 'datetime'
    ),
    array('label'       => '',
          'width'       => '7%',
          'field_name'  => 'id',
          'format'      => 'image',
          'src'         => asset('images/icons/edit.png'),
//          'onclick'     => "removeRow('transactions', '".base_url()."adminpanel/users/confirm_order/%d', 'Confirm approve order.');",
//          'onclick_key' => 'id',
//          'title'       => 'Approve this order',
          'align'       => 'center'
    ),
);
