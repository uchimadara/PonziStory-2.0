<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_locked_members']['table'] = 'users';
$config['list_locked_members']['order'] = 'username';
$config['list_locked_members']['table_class'] = 'rwd-table';
$config['list_locked_members']['where'] = 'locked = 1';
$config['list_locked_members']['keyfields'] = array('username', 'email', 'id');

$config['list_locked_members']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
          'hidden' => TRUE
    ),
    array('label'      => 'Userame',
          'field_name' => 'username',
          'href'       => base_url().'admin/user/%s',
          'href_key'   => 'id'
    ),
    array('label'      => 'Email',
          'field_name' => 'email',
          'href'       => base_url().'admin/email_user/%s',
          'href_key'   => 'id',
        'title' => 'Email User'
    ),
    array('label'      => 'Account Level',
          'field_name' => 'account_level',
    ),
    array('label'      => 'Active',
          'field_name' => 'active',
          'align' => 'center'
    ),
    array('label'      => 'Created On',
          'field_name' => 'created_on',
          'format'     => 'datetime'
    ),
    array('label'      => 'Last Login',
          'field_name' => 'last_login',
          'format'     => 'datetime'
    ),
);

