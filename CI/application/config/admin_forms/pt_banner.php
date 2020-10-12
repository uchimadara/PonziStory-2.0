<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['form_pt_banner']['table'] = 'pt_banner';
$config['form_pt_banner']['fields'] = array(
   
    array('label' => 'Name',
        'field_name' => 'name',
        'type' => 'text',
        'value' => '',
        'maxlength' => '255',
        'rule' => 'trim|xss_clean|required',
    ),
    array('label' => 'File',
        'field_name' => 'file',
        'type' => 'text',
        'value' => '',
        'maxlength' => '255',
        'rule' => 'trim|xss_clean|required',
    ),
    array('label' => 'Active',
        'field_name' => 'active',
        'type' => 'select',
        'value' => '',
        'maxlength' => '1',
        'rule' => 'trim|xss_clean|required',
        'select_list' => 'status_list'
    ),
    array('label' => 'Size',
        'field_name' => 'size',
        'type' => 'select',
        'value' => '',
        'maxlength' => '',
        'rule' => 'trim|xss_clean|required',
        'select_list' => 'pt_banner_size'
    ),
);
?>
