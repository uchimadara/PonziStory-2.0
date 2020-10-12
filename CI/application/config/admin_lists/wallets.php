<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_wallets']['table']       = 'user_payment_method';
$config['list_wallets']['order']       = 'user_id';
$config['list_wallets']['sort_dir']    = 'desc';
$config['list_wallets']['where'] = 'visible = 1';
$config['list_wallets']['table_class'] = 'rwd-table';
$config['list_wallets']['keyfields'] = array('username', 'account','method_name','first_name','last_name','phone','visible');

$config['list_wallets']['join']        = array(
    array('users u', 'u.id = user_payment_method.user_id'),
);

$config['list_wallets']['fields'] = array(
    array('label'      => '',
          'field_name' => 'id',
          'hidden'     => TRUE,
          'table_name' => 'u',
          'alias'      => 'user_id'
    ),
    array('label'      => 'Username',
          'field_name' => 'username',
          'table_name' => 'u',
          'href'       => base_url().'admin/user/%s',
          'href_key'   => 'user_id',
    ),

    array('label'      => 'Firstname',
        'field_name' => 'first_name',
        'table_name' => 'u',
        'href'       => base_url().'admin/user/%s',
        'href_key'   => 'user_id',
    ),

    array('label'      => 'LastName',
        'field_name' => 'last_name',
        'table_name' => 'u',
        'href'       => base_url().'admin/user/%s',
        'href_key'   => 'user_id',
    ),

    array('label'      => 'Phone',
        'field_name' => 'phone',
        'table_name' => 'u',
        'href'       => base_url().'admin/user/%s',
        'href_key'   => 'user_id',
    ),

    array('label'      => 'Acc No.',
          'field_name' => 'account',
    ),
    array('label'      => 'Acc Name',
        'field_name' => 'method_name',
    ),
    array('label'      => 'Bank',
        'field_name' => 'note',
    ),

);
