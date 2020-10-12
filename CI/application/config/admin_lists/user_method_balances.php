<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_user_method_balances']['table'] = 'user_payment_method';
$config['list_user_method_balances']['order'] = 'payment_code';
$config['list_user_method_balances']['table_class'] = 'table';
$config['list_user_method_balances']['paging'] = FALSE;

$config['list_user_method_balances']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
          'href'     => base_url().'adminpanel/users/getBalanceForm/%d',
          'href_key' => array('id'),
          'class'    => 'popup'
    ),
    array('label'      => 'Payment Method',
          'field_name' => 'payment_code',
    ),
    array('label'      => 'Balance',
          'width'      => '140px',
          'field_name' => 'balance',
          'format' => 'currency',
    ),
);

