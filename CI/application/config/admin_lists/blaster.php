<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_blaster']['table']       = 'blaster_queue';
$config['list_blaster']['order']       = 'time_to_send';
$config['list_blaster']['table_class'] = 'table';
$config['list_blaster']['keyfields']   = array('subject');


$config['list_blaster']['fields'] = array(
    array('label'      => 'Sent',
          'width'      => '140px',
          'field_name' => 'time_to_send',
          'format'     => 'datetime',
    ),
    array('label'      => 'Subject',
          'width'      => '60px',
          'field_name' => 'subject',
    ),
    array('label'      => 'Total',
          'width'      => '60px',
          'field_name' => 'sent_total',
    ),
    array('label'      => 'Viewed',
          'width'      => '60px',
          'field_name' => 'viewed_total',
    ),
    array('label'      => '% Viewed',
          'width'      => '90px',
          'field_name' => 'viewed_percent',
    ),
    array('label'      => 'Clicked',
          'width'      => '60px',
          'field_name' => 'clicked_thru_total',
    ),
    array('label'      => 'Clicked %',
          'width'      => '90px',
          'field_name' => 'clicked_thru_percent',
          'nosort'     => TRUE,
    ),
);