<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_referrers']['table'] = 'users';
$config['list_referrers']['order'] = 'ref_count';
$config['list_referrers']['sort_dir'] = 'desc';
$config['list_referrers']['table_class'] = 'table';
$config['list_referrers']['keyfields'] = array('username', 'email');

$config['list_referrers']['fields'] = array(
    array('label'      => '',
          'width'      => '15px',
          'field_name' => 'id',
          'format'     => 'image',
          'src'        => asset('images/adminpanel/lists/tree.png'),
          'href'       => base_url().'admin/reportsTo?locate=%s#%s',
          'href_key'   => array('id', 'id'),
          'title'      => 'View in Hierarchy'
    ),
    array('label'      => 'Userame',
          'width'      => '110px',
          'field_name' => 'username',
          'href'       => base_url().'admin/user/%s',
          'href_key'   => 'id'
    ),
    array('label'      => 'Referrals',
          'width'      => '50px',
          'field_name' => 'ref_count',
    ),
);

