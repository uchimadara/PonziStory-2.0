<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_users_ip']['table']       = 'user_login';
$config['list_users_ip']['order']       = 'username';
$config['list_users_ip']['table_class'] = 'table';
$config['list_users_ip']['keyfields']   = array('username');
$config['list_users_ip']['join'] = array(
    array('users u', 'u.id = user_login.user_id')
);

$config['list_users_ip']['fields'] = array(
    array('label'      => '',
          'width'      => '70px',
          'field_name' => 'id',
        'table_name' => 'u',
          'hidden'     => TRUE
    ),
    array('label'      => 'Username',
          'width'      => '110px',
          'field_name' => 'username',
          'table_name' => 'u',
          'href'       => base_url().'admin/users_ip/%s',
          'href_key'   => 'id'
    ),
    array('label'      => 'IP',
          'width'      => '150px',
          'field_name' => 'user_ip',
        'format' => 'ip'
    ),
);

