<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_news']['table'] = 'news';
$config['list_news']['order'] = 'date';
$config['list_news']['sort_dir'] = 'desc';
$config['list_news']['table_class'] = 'table';
$config['list_news']['fields'] = array(
 
    array('label' => 'Title',
        'width' => '10%',
        'field_name' => 'title',
        'href' => base_url() . 'news/article/%s',
        'href_key' => 'slug'
    ),
    array('label' => 'Published on',
        'width' => '8%',
        'field_name' => 'date',
        'format' => 'datetime'
    ),
    array('label' => '',
        'width' => '8%',
        'field_name' => 'slug',
        'format' => 'text',
        'hidden' => TRUE
    ),
);
?>
