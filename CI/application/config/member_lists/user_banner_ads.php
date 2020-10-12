<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_user_banner_ads']['table']       = 'banner';
$config['list_user_banner_ads']['table_class'] = 'rwd-table';
$config['list_user_banner_ads']['keyfields']   = array('name');
$config['list_user_banner_ads']['order'] = 'name';
$config['list_user_banner_ads']['paging'] = FALSE;

$config['list_user_banner_ads']['fields'] = array(
    array('label'       => 'Name',
          'field_name'  => 'name',
        "href" => SITE_ADDRESS."campaign/view/banner/%d",
          'class'     => "popup wider",
          'href_key' => 'id',
          'title'       => 'Banner Ad Click Stats',
    ),
    array('label'      => 'Target URL',
          'field_name' => 'target_url',
    ),
    array('label'      => 'Banner',
          'field_name' => 'image_url',
          'format'     => 'image',
          'src'        => '%s',
          'img_key'    => 'image_url',
        'img_width' => '25%'
    ),

    array('label'      => 'Status',
          'field_name' => 'status',
    ),
    array('label'      => 'Credits',
          'field_name' => 'credits',
    ),
    array('label'      => 'Shown',
         'field_name' => 'impressions',
    ),
    array('label'      => 'Clicked',
          'field_name' => 'clicks',
    ),
    array('label'      => 'Assign Credits',
        'format' => 'input',
        'size' => 6,
        'maxlength' => 6,
        'extra' => 'onkeyup="javascript:maskAmount(this);"',
          'field_name' => 'credits',
          'value_key' => 'id',
        'nosort' => TRUE
    ),
    array('label'      => 'Delete',
          'field_name' => 'id',
          'format'     => 'icon',
          'icon'        => 'fa fa-trash-o red',
          'onclick'       => "removeRow(this, '".base_url()."adverts/delete_banner_ad/%d', 'Confirm delete banner ad.');",
          'onclick_key'   => 'id',
          'title'      => 'Delete this banner ad',
          'align' => 'center'
    ),

);



