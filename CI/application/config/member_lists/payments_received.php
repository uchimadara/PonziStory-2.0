<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_payments_received']['table']       = 'payment';
$config['list_payments_received']['order']       = 'payment.created';
$config['list_payments_received']['sort_dir'] = 'desc';
$config['list_payments_received']['table_class'] = 'rwd-table';
$config['list_payments_received']['where'] = 'approved IS NOT NULL';
$config['list_payments_received']['join'] = array(
    array('users', 'users.id = payment.payer_user_id'),
    array('purchase_item i', 'i.id = payment.upgrade_id'),
   // array('user_payment_method m', 'm.id = payment.method_id')
);

$config['list_payments_received']['fields'] = array(
    array('label'      => 'Donation',
          'field_name' => 'title',
          'table_name' => 'i'
    ),
    array('label'      => 'From Member',
          'sql_value' => 'ifnull(nullif(concat_ws(" ", users.first_name, users.last_name), " "), users.username) from_member',
          'alias' => 'from_member',
          'field_name' => 'from_member'
    ),
    array('label'      => 'Date',
          'field_name' => 'created',
          'table_name' => 'payment',
          'format'     => 'date'
    ),
//    array('label'      => 'Payment Method',
//          'field_name' => 'method_name',
//          'table_name' => 'm'
//    ),

    array('label'      => 'Transaction ID',
          'field_name' => 'transaction_id',
        'format' => 'shorten',
        'length' => 10
    ),
    array('label'      => 'Amount',
          'field_name' => 'amount',
          'format' => 'currency'
    ),
);

