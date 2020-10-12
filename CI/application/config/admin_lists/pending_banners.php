<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_pending_banners']['table'] = 'banner';
$config['list_pending_banners']['order'] = 'created';
$config['list_pending_banners']['where'] = "banner.status ='Pending'";
$config['list_pending_banners']['join'] = array(
    array('users', 'users.id = banner.user_id')
);
$config['list_pending_banners']['table_class'] = 'table';
$config['list_pending_banners']['keyfields'] = array('name', 'username');

$config['list_pending_banners']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
    ),
    array('label'      => 'User',
          'width'      => '8%',
          'field_name' => 'username',
          'table_name' => 'users',
          'href' => SITE_ADDRESS.'admin/user/%s',
        'href_key' => 'user_id'
    ),
    array('label'      => 'Name',
          'width'      => '8%',
          'field_name' => 'name',
    ),
    array('label'      => 'Image',
          'width'      => '30%',
          'field_name' => 'image_url',
          'format' => 'image',
            'src' => '%s',
            'img_key' => 'image_url'
    ),
    array('label'      => 'URL',
          'width'      => '20%',
          'field_name' => 'target_url',
          'href'       => '%s',
          'href_key'   => 'target_url'
    ),
    array('label'      => 'Created On',
          'width'      => '20%',
          'field_name' => 'created',
          'format'     => 'datetime'
    ),
    array('label'       => '',
          'width'       => '5%',
          'field_name'  => 'id',
          'format'      => 'image',
          'src'         => asset('images/icons/check.png'),
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/adverts/approve/banner/%d', 'Confirm approve banner ad.');",
          'onclick_key' => 'id',
          'title'       => 'Approve this text ad',
          'align'       => 'center',
          'img_width'   => '15'
    ),
    array('label'       => '',
          'width'       => '5%',
          'field_name'  => 'id',
          'format'      => 'image',
          'src'         => asset('images/icons/delete.png'),
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/adverts/reject/banner/%d', 'Confirm reject banner ad.');",
          'onclick_key' => 'id',
          'title'       => 'Reject this text ad',
          'align'       => 'center',
        'img_width' => '15'
    ),

);

