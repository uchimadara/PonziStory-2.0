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
    array('label'      => 'Donation',
          'field_name' => 'title',
          'table_name' => 'i'
    ),
    array('label'      => 'To Member',
          'sql_value'  => 'ifnull(nullif(concat_ws(" ", users.first_name, users.last_name), " "), users.username) to_member',
          'alias'      => 'to_member',
          'field_name' => 'to_member'
    ),
    array('label'      => 'Date',
          'field_name' => 'created',
          'table_name' => 'payment',
        'format' => 'date'
    ),
//    array('label'      => 'Payment Method',
//          'field_name' => 'method_name',
//          'table_name' => 'm'
//    ),

    array('label'      => 'Transaction ID',
          'field_name' => 'transaction_id',
          'format'     => 'shorten',
          'length'     => 10
    ),
    array('label'      => 'Amount',
          'field_name' => 'amount',
          'format' => 'currency'
    ),
);



