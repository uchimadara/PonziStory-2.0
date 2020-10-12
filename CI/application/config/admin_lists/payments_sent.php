<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_payments_sent']['table']       = 'payment';
$config['list_payments_sent']['order']       = 'payment.created';
$config['list_payments_sent']['sort_dir'] = 'desc';
$config['list_payments_sent']['table_class'] = 'rwd-table';
$config['list_payments_sent']['where'] = 'approved IS NOT NULL';
$config['list_payments_sent']['join'] = array(
    array('users', 'users.id = payment.payee_user_id'),
    //array('user_payment_method m', 'm.id = payment.method_id'),
    array('purchase_item i', 'i.id = payment.upgrade_id')
);

$config['list_payments_sent']['fields'] = array(
    array('label'      => 'Date',
          'field_name' => 'created',
          'table_name' => 'payment',
        'format' => 'datetime'
    ),
    array('label'      => 'To Member',
          'field_name' => 'username',
          'table_name' => 'users'
    ),
//    array('label'      => 'Payment Method',
//          'field_name' => 'method_name',
//          'table_name' => 'm'
//    ),

    array('label'      => 'Level',
          'field_name' => 'code',
          'table_name' => 'i'
    ),
    array('label'      => 'Amount',
          'field_name' => 'amount',
          'format' => 'currency'
    ),
    array('label'      => 'Transactions ID',
          'field_name' => 'transaction_id',
          'format'     => 'shorten',
          'length'     => 10
    ),

    array('label'      => 'Edit',
              'field_name' => 'id',

              'table_name' => 'payment',

              'format'     => 'icon',
              'icon'       => 'fa fa-pencil-square-o',
              'href'       => base_url()."adminpanel/users/edit_payment/%d",
              'href_key'   => 'id',
              'title'      => 'Edit payment',
              'align'      => 'center',
              'class'      => 'popup'
        ),

);



