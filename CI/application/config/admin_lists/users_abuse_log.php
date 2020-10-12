<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

#$config['list_users_abuse_log']['table'] = 'users';
$config['list_users_abuse_log']['order'] = 'ip_address';
$config['list_users_abuse_log']['table_class'] = 'table';
$config['list_users_abuse_log']['keyfields'] = array('ip_address');

$config['list_users_abuse_log']['fields'] = array(
   
    
    array('label'      => 'IP Address',
          'width'      => '12%',
          'field_name' => 'ip_address',
          'nosort' => true
    ),
    array('label'      => 'Usernames sharing the same IP',
          'width'      => '15%',
          'field_name' => 'usernames',
          'nosort' => true
    ),
);

