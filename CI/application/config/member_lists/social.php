<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_social']['table']       = 'user_social_network';
$config['list_social']['order']       = 'name';
$config['list_social']['table_class'] = 'table';
$config['list_social']['keyfields'] = array('name');
$config['list_social']['paging'] = FALSE;
$config['list_social']['header_on'] = FALSE;

$config['list_social']['fields'] = array(
    array('label'       => 'Network',
          'width'       => '10%',
          'field_name'  => 'name',
          'src'     => asset('images/social/%s.png'),
          'img_key' => 'name',
          'format'       => 'image',
          'img_width' => '20px',
          'img_height' => '20px',

    ),
    array('label'      => 'URL',
          'width'      => '80%',
          'field_name' => 'link',
        'align' => 'fs14'
    ),
    array('label'      => 'Delete',
          'width'      => '10%',
          'field_name' => 'id',

          'format'      => 'icon',
          'icon'        => 'fa fa-trash-o',
          'onclick'     => "removeRow(this, '".base_url()."member/delete_social/%d', 'Confirm delete social network link.');",
          'onclick_key' => 'id',

          'title'      => 'Delete this social network',
          'align' => 'center',
    ),

);



