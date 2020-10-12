<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['form_ad_placement']['table'] = 'ad_placement';
$config['form_ad_placement']['fields'] = array(
    
   
    array('label' => 'Cost',
        'field_name' => 'cost',
        'type' => 'text',
        'value' => '',
        'maxlength' => '',
        'rule' => 'trim|xss_clean|required',
    ),
    array('label' => 'Member Max',
        'field_name' => 'member_max',
        'type' => 'text',
        'value' => '',
        'maxlength' => '10',
        'rule' => 'trim|xss_clean|required',
    ),
    array('label' => 'Ads Limit',
        'field_name' => 'ads_limit',
        'type' => 'text',
        'value' => '',
        'maxlength' => '10',
        'rule' => 'trim|xss_clean|required',
    ),
    array('label' => 'Type',
        'field_name' => 'type',
        'type' => 'select',
        'value' => '',
        'maxlength' => '',
        'rule' => 'trim|xss_clean|required',
        'select_list' => 'ad_placement_type'
    ),
    array('label' => 'Size',
        'field_name' => 'size',
        'type' => 'select',
        'value' => '',
        'maxlength' => '',
        'rule' => 'trim|xss_clean|required',
        'select_list' => 'ad_placement_size'
    ),
    array('label' => 'Group',
        'field_name' => 'group',
        'type' => 'select',
        'value' => '',
        'maxlength' => '',
        'rule' => 'trim|xss_clean|required',
        'select_list' => 'ad_placement_group'
    ),
);
