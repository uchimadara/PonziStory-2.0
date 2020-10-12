<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_click_stats']['table']       = 'reflink_clicks';
$config['list_click_stats']['order']       = 'came_from';
$config['list_click_stats']['table_class'] = 'rwd-table';
$config['list_click_stats']['keyfields']   = array('came_from');

$config['list_click_stats']['fields'] = array(
    array('label'      => 'Came From',
          'field_name' => 'came_from',
    ),
    array('label'      => 'Clicked',
          'field_name' => 'click_count',
    ),
    array('label'      => 'Signed Up',
          'field_name' => 'user_count',
    ),
);



