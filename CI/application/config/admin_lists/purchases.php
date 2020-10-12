<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_purchases']['table']       = 'transaction';
$config['list_purchases']['order']       = 'created';
$config['list_purchases']['sort_dir'] = 'desc';
$config['list_purchases']['where'] = " transaction.type='deposit' ";

$config['list_purchases']['join']        = array(
    array('users', 'users.id = transaction.user_id'),
    array('purchase_order p', 'p.id = transaction.reference_id')
);

$config['list_purchases']['table_class'] = 'rwd-table';
$config['list_purchases']['keyfields']   = array(
    'transaction.id', 'transaction.method', 'transaction.reference', 'transaction.identifier', 'transaction.item_code', 'purchase.description', 'transaction.reference_id', 'transaction.status', 'users.username');

$config['list_purchases']['fields'] = array(
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
          'field_name' => 'description',
          'table_name' => 'p',
    ),
    array('label'      => 'Status',
          'field_name' => 'status',
    ),
    array('label'      => 'Method',
          'field_name' => 'method',
          'table_name' => 'p',
          'format'     => 'icon',
          'class'      => 'ppIcon'
    ),
    array('label'      => 'Amount',
          'field_name' => 'gross_amount',
          'format'     => 'currency'
    ),
);
$config['list_purchases']['search_form'] = array(
    array(
        'label'       => 'Keywords',
        'label_width' => '75px',
        'name'        => 'keywords',
        'type'        => 'text',
        'width'       => '200px',
    ),
    array(
        'label'       => 'Payment Method',
        'name'        => 'transaction_x_method',
        'type'        => 'select',
        'select_list' => 'payment_code_list',
        'width'       => '195px',
        'label_width' => '150px',
    ),
);
