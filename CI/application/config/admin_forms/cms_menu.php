<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['form_cms_menu']['table'] = 'cms_menu';
$config['form_cms_menu']['fields'] = array(
    array('label' => 'Name',
        'field_name' => 'name',
        'type' => 'text',
        'value' => '',
        'maxlength' => '45',
        'rule' => 'trim|xss_clean|required'
    ),
    array('label' => 'Url',
        'field_name' => 'url',
        'type' => 'text',
        'value' => '',
        'maxlength' => '255',
        'rule' => 'trim|xss_clean',
    ),
    array('label' => 'Parent Id',
        'field_name' => 'parent_id',
        'type' => 'select',
        'value' => '',
        'maxlength' => '10',
        'rule' => 'trim|xss_clean',
        'select_list' => 'cms_menu_parent_list'
    ),
    array('label' => 'Position',
        'field_name' => 'position',
        'type' => 'int',
        'maxlength' => '10',
        'rule' => 'trim|xss_clean',
    ),
    array('label' => 'Icon',
        'field_name' => 'icon',
        'type' => 'text',
        'value' => '',
        'maxlength' => '45',
        'rule' => 'trim|xss_clean',
    ),
    array('label' => 'Place',
        'field_name' => 'place',
        'value' => '',
        'maxlength' => '',
        'rule' => 'trim|xss_clean|required',
        'type' => 'select',
        'select_list' => 'cms_menu_list',
    ),
);
?>
