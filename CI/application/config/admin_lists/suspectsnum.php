<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_suspectsnum']['table']       = 'users';
$config['list_suspectsnum']['order']       = 'p_count';
$config['list_suspectsnum']['sort_dir']    = 'desc';
$config['list_suspectsnum']['where'] = 'visible = 1';
$config['list_suspectsnum']['table_class'] = 'rwd-table';
$config['list_suspectsnum']['keyfields'] = array('username','first_name','last_name','phone','visible');



$config['list_suspectsnum']['fields'] = array(
    array('label'      => '',
        'field_name' => 'id',
        'hidden'     => TRUE,
        'table_name' => 'u',
        'alias'      => 'id'
    ),
    array('label'      => 'Username',
        'field_name' => 'username',
        'table_name' => 'u',
        'href'       => base_url().'admin/user/%s',
        'href_key'   => 'id',
    ),

    array('label'      => 'Firstname',
        'field_name' => 'first_name',
        'table_name' => 'u',
        'href'       => base_url().'admin/user/%s',
        'href_key'   => 'id',
    ),

    array('label'      => 'LastName',
        'field_name' => 'last_name',
        'table_name' => 'u',
        'href'       => base_url().'admin/user/%s',
        'href_key'   => 'id',
    ),

    array('label'      => 'Phone',
        'field_name' => 'phone',
        'table_name' => 'u',
        'href'       => base_url().'admin/user/%s',
        'href_key'   => 'id',
    ),



    array('label'      => 'PC',
        'field_name' => 'p_count',
    ),

);
