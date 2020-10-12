<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_pending_orders']['table']       = 'purchase_order';
$config['list_pending_orders']['order']       = 'created';
$config['list_pending_orders']['where']       = "purchase_order.status ='processing' AND t.status='ok'";
$config['list_pending_orders']['join']        = array(
    array('users', 'users.id = purchase_order.user_id'),
    array('transaction t', 't.id = purchase_order.transaction_id')
);
$config['list_pending_orders']['table_class'] = 'table';
$config['list_pending_orders']['keyfields']   = array('name', 'username');

$config['list_pending_orders']['fields'] = array(
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
    array('label'      => 'Description',
          'width'      => '20%',
          'field_name' => 'description',
    ),
    array('label'      => 'Method',
          'width'      => '5%',
          'field_name' => 'method',
    ),
    array('label'      => 'User Account',
          'width'      => '10%',
          'field_name' => 'user_account',
        'table_name' => 't'
    ),
    array('label'      => 'Balance Applied',
          'width'      => '10%',
          'field_name' => 'apply_balance',
          'format'     => 'currency'
    ),
    array('label'      => 'total',
          'width'      => '10%',
          'field_name' => 'total',
          'format'     => 'currency'
    ),
    array('label'      => 'Created On',
          'width'      => '12%',
          'field_name' => 'created',
          'format'     => 'datetime'
    ),
    array('label'       => '',
          'width'       => '5%',
          'field_name'  => 'id',
          'format'      => 'image',
          'src'         => asset('images/icons/check.png'),
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/users/confirm_order/%d', 'Confirm approve order.');",
          'onclick_key' => 'id',
          'title'       => 'Approve this order',
          'align'       => 'center'
    ),
    array('label'       => '',
          'width'       => '5%',
          'field_name'  => 'id',
          'format'      => 'image',
          'src'         => asset('images/icons/delete.png'),
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/users/cancel_order/%d', 'Confirm cancel order.');",
          'onclick_key' => 'id',
          'title'       => 'Cancel this order',
          'align'       => 'center'
    ),

);
