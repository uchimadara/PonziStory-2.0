<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_ad_placement']['table'] = 'ad_placement';
$config['list_ad_placement']['order'] = 'id';
$config['list_ad_placement']['table_class'] = 'table';
$config['list_ad_placement']['fields'] = array(
    
    array('label' => 'ID',
        'width' => '4%',
        'field_name' => 'id',
        'hidden' => TRUE,
    ),
    array('label'      => 'Group',
          'width'      => '18%',
          'field_name' => 'group',
    ),
    array('label' => 'Position',
        'width' => '18%',
        'field_name' => 'position',
    ),
    array('label' => 'Cost',
        'width' => '4%',
        'field_name' => 'cost',
    ),
    array('label' => 'Member Max',
        'width' => '8%',
        'field_name' => 'member_max',
    ),
    array('label' => 'Ads Limit',
        'width' => '8%',
        'field_name' => 'ads_limit',
    ),
    array('label' => 'Type',
        'width' => '8%',
        'field_name' => 'type',
    ),
    array('label' => 'Size',
        'width' => '18%',
        'field_name' => 'size',
    ),
    array('label'       => 'Edit',
          'width'       => '5%',
          'field_name'  => 'id',
          'format'      => 'image',
          'src'         => asset('images/icons/edit.png'),
          'href'     => base_url().'admin/form/ad_placement/%s',
          'href_key' => 'id',
          'title'       => 'Edit',
          'align'       => 'center'
    ),
    array('label'       => 'Delete',
          'width'       => '5%',
          'field_name'  => 'id',
          'format'      => 'image',
          'src'         => asset('images/icons/delete.png'),
          'onclick'     => "removeRow(this, '".base_url()."admin/delete_placement/%d', 'Confirm delete ad placement.');",
          'onclick_key' => 'id',
          'title'       => 'Delete',
          'align'       => 'center'
    ),

);
