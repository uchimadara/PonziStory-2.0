<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_users']['table'] = 'users';
$config['list_users']['order'] = 'id';
$config['list_users']['sort_dir']    = 'desc';
$config['list_users']['table_class'] = 'rwd-table';
$config['list_users']['where'] = 'id > 0 and deleted = 0';
$config['list_users']['keyfields'] = array('username', 'email', 'id','first_name', 'last_name');

$config['list_users']['fields'] = array(
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
    array('label'      => 'First Name',
          'field_name' => 'first_name',
    ),
    array('label'      => 'Last Name',
          'field_name' => 'last_name',
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

