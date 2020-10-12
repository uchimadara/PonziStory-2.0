<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_user_text_ads']['table']       = 'text_ad';
$config['list_user_text_ads']['table_class'] = 'rwd-table';
$config['list_user_text_ads']['keyfields']   = array('name', 'headline', 'target_url');
$config['list_user_text_ads']['order'] = 'name';

$config['list_user_text_ads']['fields'] = array(
    array('label'       => 'Headline',
          'field_name'  => 'headline',
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



