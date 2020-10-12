<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_ad_placement']['table']       = 'ad_placement';
$config['list_ad_placement']['order']       = 'group, type, size';
$config['list_ad_placement']['table_class'] = 'table';
$config['list_ad_placement']['paging'] = false;
$config['list_ad_placement']['fields']      = array(

    array('label'      => 'ID',
          'width'      => '4%',
          'field_name' => 'id',
          'hidden'     => TRUE,
    ),
    array('label'      => 'Area',
          'width'      => '18%',
          'field_name' => 'group',
    ),
    array('label'      => 'Position',
          'width'      => '18%',
          'field_name' => 'position',
    ),
    array('label'      => 'Size',
          'width'      => '18%',
          'field_name' => 'size',
    ),
    array('label'      => 'Credits per view',
          'width'      => '4%',
          'field_name' => 'cost',
    ),
    array('label'      => 'Max',
          'width'      => '8%',
          'field_name' => 'member_max',
    ),

);
