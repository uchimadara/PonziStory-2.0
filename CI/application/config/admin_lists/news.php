<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_news']['table'] = 'news';
$config['list_news']['order'] = 'date';
$config['list_news']['sort_dir'] = 'desc';
$config['list_news']['table_class'] = 'rwd-table';
$config['list_news']['keyfields'] = array('title');

$config['list_news']['fields'] = array(
    array('label' => 'Id',
        'field_name' => 'id',
        'hidden' => TRUE
    ),
    array('label' => 'Title',
        'field_name' => 'title',
        'href' => base_url() . 'admin/form/news/%s',
        'href_key' => 'id'
    ),
    array('label' => 'Image',
        'field_name' => 'image',
//        'href' => '',
        'href_key' => 'id'
    ),
    array('label' => 'Slug',
        'field_name' => 'slug',
        'href' => base_url() . 'news/%s',
        'href_key' => 'slug'
    ),



    array('label' => 'Date',
        'field_name' => 'date',
        'format' => 'datetime'
    ),
    array('label'       => 'Edit',
          'field_name'  => 'id',
          'format'      => 'icon',
          'icon'        => 'fa fa-pencil-square-o',
          'href'     => base_url().'admin/form/news/%s',
          'href_key' => 'id',
          'title'       => 'Edit Article',
          'align'       => 'center',
    ),
    array('label'       => 'Delete',
          'field_name'  => 'id',
          'format'      => 'icon',
          'icon'        => 'fa fa-trash-o',
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/admin/delete_item/news/%d', 'Confirm delete news.');",
          'onclick_key' => 'id',
          'title'       => 'Delete this article',
          'align'       => 'center',
    ),

);
?>
