<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_user_login_ads']['table']       = 'text_ad';
$config['list_user_login_ads']['table_class'] = 'table';
$config['list_user_login_ads']['keyfields']   = array('name');
$config['list_user_login_ads']['order'] = 'name';

$config['list_user_text_ads']['fields'] = array(
    array('label'       => 'Name',
          'field_name'  => 'name',
    ),
    array('label'      => 'Target URL',
          'field_name' => 'target_url',
    ),
    array('label'      => 'Status',
          'field_name' => 'status',
    ),
    array('label'      => 'Credits',
          'field_name' => 'credits',
    ),
    array('label'      => 'Shown',
          'field_name' => 'impressions',
    ),
    array('label'      => 'Clicked',
          'field_name' => 'clicks',
    )

);



