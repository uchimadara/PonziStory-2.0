<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_pending_text_ads']['table'] = 'text_ad';
$config['list_pending_text_ads']['order'] = 'created';
$config['list_pending_text_ads']['where'] = "text_ad.status ='Pending'";
$config['list_pending_text_ads']['join'] = array(
    array('users', 'users.id = text_ad.user_id')
);
$config['list_pending_text_ads']['table_class'] = 'rwd-table';
$config['list_pending_text_ads']['keyfields'] = array('name', 'username');

$config['list_pending_text_ads']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
    ),
    array('label'      => 'User',
          'field_name' => 'username',
          'table_name' => 'users',
          'href'       => SITE_ADDRESS.'admin/user/%s',
          'href_key'   => 'user_id'
    ),
    array('label'      => 'Name',
          'field_name' => 'name',
    ),
    array('label'      => 'Headline',
          'field_name' => 'headline',
    ),
    array('label'      => 'Body',
          'field_name' => 'body',
    ),
    array('label'      => 'URL',
          'field_name' => 'target_url',
          'href'       => '%s',
          'href_key'   => 'target_url'
    ),
    array('label'      => 'Created On',
          'field_name' => 'created',
          'format'     => 'datetime'
    ),
    array('label'       => '',
          'field_name'  => 'id',
          'format'      => 'icon',
          'icon'         => 'fa fa-check green',
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/adverts/approve/text_ad/%d', 'Confirm approve text ad.');",
          'onclick_key' => 'id',
          'title'       => 'Approve this text ad',
          'align'       => 'center'
    ),
    array('label'       => '',
          'field_name'  => 'id',
          'format'      => 'icon',
          'icon'         => 'fa fa-trash red',
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/adverts/reject/text_ad/%d', 'Confirm reject text ad.');",
          'onclick_key' => 'id',
          'title'       => 'Reject this text ad',
          'align'       => 'center'
    ),

);

