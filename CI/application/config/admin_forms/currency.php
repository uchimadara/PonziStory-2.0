<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['form_currency']['table'] = 'currency';
$config['form_currency']['fields'] = array(
    array('label' => 'Name',
        'field_name' => 'name',
        'type' => 'text',
        'value' => '',
        'maxlength' => '50',
        'rule' => 'trim|xss_clean|required'
    ),
    array('label' => 'Code',
        'field_name' => 'code',
        'type' => 'text',
        'value' => '',
        'maxlength' => '3',
        'rule' => 'trim|xss_clean|required',
    ),
);
?>
