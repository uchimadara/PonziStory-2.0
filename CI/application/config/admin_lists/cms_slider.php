<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_cms_slider']['table'] = 'cms_slider';
$config['list_cms_slider']['order'] = 'position';
$config['list_cms_slider']['table_class'] = 'table';
$config['list_cms_slider']['fields'] = array(
    array('label' => 'Id',
        'width' => '4%',
        'field_name' => 'id',
        'href'     => base_url().'admin/form/cms_slider/%s',
        'href_key' => 'id'
    ),
    array('label'      => 'Position',
          'width'      => '4%',
          'field_name' => 'position',
    ),
    array('label' => 'Headline',
        'width' => '12%',
        'field_name' => 'headline',
        'href' => base_url() . 'admin/form/cms_slider/%s',
        'href_key' => 'id'
    ),
    array('label'      => 'Tagline',
          'width'      => '60%',
          'field_name' => 'tagline',
    ),
  array('label' => 'Image',
        'width' => '20%',
        'field_name' => 'image',
    )
);
?>
