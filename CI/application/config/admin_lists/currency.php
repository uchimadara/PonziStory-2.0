<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_currency']['table']       = 'currency';
$config['list_currency']['order']       = 'id';
$config['list_currency']['table_class'] = 'rwd-table';
$config['list_currency']['keyfields']   = array('name', 'code');

$config['list_currency']['fields'] = array(
    array('label'      => '',
          'field_name' => 'id',
          'hidden'     => TRUE
    ),
    array('label'      => 'Code',
          'field_name' => 'code',
          'href'       => base_url().'admin/form/currency/%d',
          'href_key'   => 'id'
    ),
    array('label'      => 'Name',
          'field_name' => 'name',
    ),
    array('label'      => 'Edit',
          'field_name' => 'id',
          'format'     => 'icon',
          'icon'       => 'fa fa-pencil-square-o',
          'href'       => base_url().'admin/form/currency/%s',
          'href_key'   => 'id',
          'title'      => 'Edit Currency',
          'align'      => 'center',
    ),
    array('label'       => 'Delete',
          'field_name'  => 'id',
          'format'      => 'icon',
          'icon'        => 'fa fa-trash-o',
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/admin/delete_item/currency/%d', 'Confirm delete currency.');",
          'onclick_key' => 'id',
          'title'       => 'Delete this currency',
          'align'       => 'center',
    ),

);

