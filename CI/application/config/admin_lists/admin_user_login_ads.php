<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_admin_user_login_ads']['table'] = 'campaign_login_ad';
$config['list_admin_user_login_ads']['table_class'] = 'rwd-table';
$config['list_admin_user_login_ads']['keyfields'] = array('name');
$config['list_admin_user_login_ads']['order'] = 'name';

$config['list_admin_user_login_ads']['fields'] = array(
    array('label' => 'Name',
        'field_name' => 'name'
    ),
    array('label' => 'Target URL',
        'field_name' => 'target_url',
    ),

    array('label' => 'Views',
        'field_name' => 'impressions',
        'format' => 'int',
        'nosort' => true
    ),
    array('label' => 'Clicks',
        'field_name' => 'clicks',
        'format' => 'int',
        'nosort' => true
    ),
    array('label' => 'Total paid',
        'field_name' => 'paid',
        'format' => 'currency',
        'nosort' => true
    ),
    array('label' => 'Total refund',
        'field_name' => 'refund',
        'format' => 'currency',
        'nosort' => true
    )
);