<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_cms_menu']['table'] = 'cms_menu';
$config['list_cms_menu']['order'] = 'id';
$config['list_cms_menu']['table_class'] = 'table';
$config['list_cms_menu']['join'] = array(
    array('cms_menu m', 'm.id = cms_menu.parent_id', 'left')
);
$config['list_cms_menu']['fields'] = array(
    array('label' => 'Id',
        'width' => '4%',
        'field_name' => 'id',
    ),
    array('label' => 'Position',
        'width' => '4%',
        'field_name' => 'position',
    ),
    array('label' => 'Parent Menu',
        'width' => '8%',
        'field_name' => 'parent_id',
    ), 
    array('label' => 'Name',
        'width' => '8%',
        'field_name' => 'name',
        'href' => base_url() . 'admin/form/cms_menu/%s',
        'href_key' => 'id'
    ),
    array('label' => 'Url',
        'width' => '6%',
        'field_name' => 'url',
    ),    
    array('label' => 'Icon',
        'width' => '8%',
        'field_name' => 'icon',
    ),
    array('label' => 'Place',
        'width' => '10%',
        'field_name' => 'place',
    ),
);
