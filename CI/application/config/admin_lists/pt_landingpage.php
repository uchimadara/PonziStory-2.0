<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_pt_landingpage']['table'] = 'pt_landingpage';
$config['list_pt_landingpage']['order'] = 'id';
$config['list_pt_landingpage']['table_class'] = 'table';
$config['list_pt_landingpage']['fields'] = array(
    array('label' => 'Id',
        'width' => '4%',
        'field_name' => 'id',
    ),
    array('label' => 'Name',
        'width' => '8%',
        'field_name' => 'name',
        'href' => base_url() . 'admin/form/pt_landingpage/%s',
        'href_key' => 'id',
    ),
    array('label' => 'Url',
        'width' => '6%',
        'field_name' => 'url',
    ),
    array('label' => 'Content',
        'width' => '14%',
        'field_name' => 'content',
        
    ),
);
?>
