<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_page']['table']       = 'page';
$config['list_page']['order']       = 'title';
$config['list_page']['table_class'] = 'table';
$config['list_page']['keyfields']   = array('title', 'uri', 'content');

$config['list_page']['fields'] = array(
    array('label'      => '',
          'width'      => '70px',
          'field_name' => 'id',
          'hidden'     => TRUE
    ),
    array('label'      => 'Title',
          'width'      => '110px',
          'field_name' => 'title',
          'href'       => base_url().'admin/form/page/%s',
          'href_key'   => 'id'
    ),
    array('label'      => 'URI',
          'width'      => '150px',
          'field_name' => 'uri',
    ),
);

