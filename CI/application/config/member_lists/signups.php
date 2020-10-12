<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_signups']['table']       = 'users';
$config['list_signups']['order']       = 'came_from';
$config['list_signups']['table_class'] = 'rwd-table';
$config['list_signups']['keyfields']   = array('came_from');
$config['list_signups']['join'] = array(
    array('users u1', 'u1.id = users.referrer_id'),
    array('reflink_clicks r', 'r.referral_user_id = users.id', 'left'),
);


$config['list_signups']['fields'] = array(
    array('label'      => 'Username',
          'field_name' => 'username',
          'table_name' => 'users'
    ),
    array('label'      => 'Upline',
          'field_name' => 'username',
          'table_name' => 'u1',
          'alias' => 'upline'
    ),
    array('label'      => 'Came From',
          'field_name' => 'came_from',
          'table_name' => 'r'
    ),
);



