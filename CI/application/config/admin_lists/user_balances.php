<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_user_balances']['table'] = 'users';
$config['list_user_balances']['order'] = 'total_bal';
$config['list_user_balances']['where'] = 'u.balance > 0 ';
$config['list_user_balances']['table_class'] = 'table';
$config['list_user_balances']['keyfields'] = array('username', 'email');

$config['list_user_balances']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
    ),
    array('label'      => 'Username',
          'field_name' => 'username',
          'href'     => base_url().'admin/user/%s',
          'href_key' => 'id'
    ),
    array('label'      => 'PM',
          'width'      => '100px',
          'field_name' => 'pm_bal',
          'format' => 'currency',
    ),
    array('label'      => 'PP',
          'width'      => '100px',
          'field_name' => 'pp_bal',
          'format' => 'currency',
    ),
    array('label'      => 'ST',
          'width'      => '100px',
          'field_name' => 'st_bal',
          'format' => 'currency',
    ),
    array('label'      => 'BC',
          'width'      => '100px',
          'field_name' => 'bc_bal',
          'format' => 'currency',
    ),
    array('label'      => 'PZ',
          'width'      => '100px',
          'field_name' => 'pz_bal',
          'format' => 'currency',
    ),
    array('label'      => 'EB',
          'width'      => '100px',
          'field_name' => 'eb_bal',
          'format' => 'currency',
    ),
    array('label'      => 'Balance',
          'width'      => '140px',
          'field_name' => 'total_bal',
          'format' => 'currency',
            'href' => base_url().'adminpanel/users/getBalanceForm/%d',
            'href_key' => array('id'),
            'class' => 'popup'
    ),
    array('label'      => 'Status',
          'width'      => '140px',
          'field_name' => 'status',
          'format' => 'currency',
    ),
);

