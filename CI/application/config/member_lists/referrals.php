<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_referrals']['table'] = 'referrals';
$config['list_referrals']['order'] = 'username';
$config['list_referrals']['sort_dir'] = 'asc';
$config['list_referrals']['table_class'] = 'rwd-table';
$config['list_referrals']['keyfields'] = array('username', 'first_name', 'last_name', 'email');
$config['list_referrals']['join'] = array('referrals r', 'r.user_id=users.id', 'LEFT');
$config['list_referrals']['group'] = 'username';
$config['list_referrals']['view_file'] = 'member/partial/referrals';

$config['list_referrals']['fields'] = array(
    array('label'      => 'Userame',
          'field_name' => 'username',
          'href'       => base_url().'admin/user/%s',
          'href_key'   => 'id'
    ),
    array('label'      => 'Signed up',
          'field_name' => 'created_on',
    ),
    array('label'      => 'Last Login',
          'field_name' => 'last_login',
    ),
    array('label'      => 'Account Expires',
          'field_name' => 'account_expires',
    ),
    array('label'      => 'Referrals',
          'field_name' => 'referrals',
    ),
    array('label'      => 'Sponsor',
          'field_name' => 'sponsor',
    ),
    array('label'      => 'Earnings',
          'field_name' => 'earning',
    ),
    array('label'      => 'Came From',
          'field_name' => 'came_from',
    ),
);

