<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_referrals_expiring']['table'] = 'referrals';
$config['list_referrals_expiring']['order'] = 'expires';
$config['list_referrals_expiring']['sort_dir'] = 'asc';
$config['list_referrals_expiring']['table_class'] = 'rwd-table';
$config['list_referrals_expiring']['keyfields'] = array('username', 'first_name', 'last_name', 'email');
$config['list_referrals_expiring']['join'] = array(
    array('users u', 'u.id = referrals.user_id'),
        array('users ref', 'ref.id = referrals.referee_id'),
            array('expiration e', 'e.user_id = referrals.referee_id'),
                array('purchase_item p', 'p.id = e.upgrade_Id')
);
$config['list_referrals_expiring']['where'] = 'referrals.level = p.code';
//$config['list_referrals_expiring']['view_file'] = 'member/partial/referrals_expiring';

$config['list_referrals_expiring']['fields'] = array(
    array('label'      => 'Level',
          'field_name' => 'level',
          'table_name' => 'referrals'
    ),
    array('label'      => 'Amount',
          'field_name' => 'price',
          'table_name' => 'p',
        'format' => 'currency'
    ),
    array('label'      => 'Member',
          'sql_value'  => 'ifnull(nullif(concat_ws(" ", ref.first_name, ref.last_name), " "), ref.username) member',
          'alias'      => 'member',
          'field_name' => 'member'
    ),
    array('label'      => 'Email',
          'field_name' => 'email',
          'table_name' => 'ref'
    ),
    array('label'      => 'Phone',
          'field_name' => 'phone',
          'table_name' => 'ref'
    ),
    array('label'      => 'Expires',
          'field_name' => 'expires',
          'format' => 'countdown',
          'table_name' => 'e'
    ),
);

