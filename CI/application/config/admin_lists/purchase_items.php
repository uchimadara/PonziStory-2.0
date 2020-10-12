<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_purchase_items']['table'] = 'purchase_item';
$config['list_purchase_items']['order'] = 'category';
$config['list_purchase_items']['table_class'] = 'rwd-table';
$config['list_purchase_items']['keyfields'] = array('title');

$config['list_purchase_items']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
          'hidden' => TRUE
    ),
    array('label'      => 'Category',
          'field_name' => 'category',
    ),
    array('label'      => 'Code',
          'field_name' => 'code',
          'href'     => base_url().'admin/form/purchase_item/%d',
          'href_key' => 'id',
    ),
    array('label'      => 'Title',
          'field_name' => 'title',
    ),
//    array('label'      => 'Duration',
//          'field_name' => 'duration',
//    ),
    array('label'      => 'Text Ad Credits',
          'field_name' => 'text_ad_credits',
    ),
    array('label'      => 'Max. Ads',
          'field_name' => 'max_ads',
    ),
//    array('label'      => 'Banner Credits',
//          'field_name' => 'banner_credits',
//    ),
    array('label'      => 'Price',
          'field_name' => 'price',
          'format' => 'currency',
    ),
);

