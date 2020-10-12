<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_pending_cashouts']['table']       = 'transaction';
$config['list_pending_cashouts']['order']       = 'created';
$config['list_pending_cashouts']['where']       = array('status' => "'pending'", 'type' => "'cashout'");
$config['list_pending_cashouts']['table_class'] = 'table';
$config['list_pending_cashouts']['keyfields']   = array('username', 'method');
$config['list_pending_cashouts']['join']        = array(
    array('users', 'users.id = transaction.user_id')
);

$config['list_pending_cashouts']['fields'] = array(
    array('label'      => 'ID',
          'width'      => '1%',
          'field_name' => 'id',
//          'onclick'     => "popup('".base_url()."campaign/view/%d');",
//          'onclick_key' => 'id',
//          'title'       => 'View Details',
    ),
    array('label'      => 'Date/Time',
          'width'      => '15%',
          'field_name' => 'created',
          'format'     => 'datetime'
    ),
    array('label'      => 'Username',
          'width'      => '10%',
          'field_name' => 'username',
        'table_name' => 'users',
        'href'       => SITE_ADDRESS.'admin/user/%s',
        'href_key'   => 'user_id'
    ),
    array('label'      => 'Method',
          'width'      => '15%',
          'field_name' => 'method',
          'format'     => 'icon',
          'class'      => 'ppIcon'
    ),
    array('label'      => 'Account',
          'width'      => '15%',
          'field_name' => 'user_account',
    ),
    array('label'      => 'Amount',
          'width'      => '15%',
          'field_name' => 'amount',
          'format'     => 'currency',
          'align'      => 'right'
    ),
    array('label'       => '',
          'width'       => '5%',
          'field_name'  => 'id',
          'format'      => 'image',
          'src'         => asset('images/icons/check.png'),
          'onclick'     => "checkCashout('pending_cashouts', '".base_url()."adminpanel/cashier/approve_cashout/%d');",
          'onclick_key' => 'id',
          'title'       => 'Approve this cashout',
          'align'       => 'center'
    ),
    array('label'       => '',
          'width'       => '5%',
          'field_name'  => 'id',
          'format'      => 'image',
          'src'         => asset('images/icons/delete.png'),
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/cashier/reject_cashout/%d', 'Confirm delete cashout');",
          'onclick_key' => 'id',
          'title'       => 'Reject this cashout',
          'align'       => 'center'
    ),

);



