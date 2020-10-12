<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_history']['table']       = 'transaction';
$config['list_history']['order']       = 'created';
$config['list_history']['sort_dir'] = 'desc';
$config['list_history']['table_class'] = 'rwd-table';
$config['list_history']['keyfields']   = array(
    'transaction.id',
    'transaction.method',
    'transaction.reference',
    'transaction.identifier',
    'transaction.item_code',
    'transaction.status',
    'users.username');

$config['list_history']['join'] = array(
    array('users', 'users.id = transaction.user_id'),
);

$config['list_history']['fields'] = array(
    array('label'      => 'ID',
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
    array('label'      => 'Date/Time',
          'field_name' => 'created',
          'format'     => 'datetime'
    ),
    array('label'      => 'Transaction',
          'field_name' => 'type',
        'format'    => 'wordify'
    ),
    array('label'      => 'Item',
          'field_name' => 'item_code',
          'format'     => 'wordify'
    ),
    array('label'      => 'Method',
          'field_name' => 'method',
        'format' => 'icon',
        'class' => 'ppIcon'
    ),
    array('label'      => 'Status',
          'field_name' => 'status',
    ),
    array('label'      => 'Total',
          'field_name' => 'gross_amount',
        'format' => 'currency',
        'align' => 'right'
    ),

);
$config['list_history']['search_form'] = array(
    array(
        'label' => 'Keywords',
        'label_width' => '75px',
        'name'  => 'keywords',
        'type'        => 'text',
        'width'       => '200px',
    ),
    array(
        'label'       => 'Payment Method',
        'name'  => 'method',
        'type'        => 'select',
        'select_list' => 'payment_code_list',
        'width'       => '195px',
        'label_width' => '150px',
    ),
);




