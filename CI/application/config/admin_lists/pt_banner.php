<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_pt_banner']['table'] = 'pt_banner';
$config['list_pt_banner']['order'] = 'name';
$config['list_pt_banner']['table_class'] = 'table';
$config['list_pt_banner']['fields'] = array(
    array('label' => 'ID',
        'width' => '4%',
        'field_name' => 'id',
    ),
    array('label' => 'Name',
        'width' => '8%',
        'field_name' => 'name',
        'href' => base_url() . 'admin/form/pt_banner/%s',
        'href_key' => 'id',
    ),
    array('label' => 'File',
        'width' => '8%',
        'field_name' => 'file',
    ),
    array('label' => 'Active',
        'width' => '12%',
        'field_name' => 'active',
    ),
    array('label' => 'Size',
        'width' => '8%',
        'field_name' => 'size',
    ),
);
?>
