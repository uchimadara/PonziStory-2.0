<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['form_pt_landingpage']['table'] = 'pt_landingpage';
$config['form_pt_landingpage']['fields'] = array(
    
    array('label' => 'Name',
        'field_name' => 'name',
        'type' => 'text',
        'value' => '',
        'maxlength' => '255',
        'rule' => 'trim|xss_clean|required',
    ),
    array('label' => 'Url',
        'field_name' => 'url',
        'type' => 'text',
        'value' => '',
        'maxlength' => '255',
        'rule' => 'trim|xss_clean|required',
    ),
    array('label' => 'Content',
        'field_name' => 'content',
        'type' => 'html',
        'value' => '',
        'rows' => '45',
        'cols' => '80',
        'rule' => 'trim|xss_clean|required',
    ),
);
?>
