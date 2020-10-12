<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_closed_tickets']['table'] = 'support_ticket';
$config['list_closed_tickets']['order'] = 'updated';
$config['list_closed_tickets']['table_class'] = 'table';
$config['list_closed_tickets']['keyfields'] = array('code', 'subject');

$config['list_closed_tickets']['fields'] = array(
    array('label'      => 'Ticket ID',
          'width'      => '5%',
          'field_name' => 'code',
          'format'     => 'text',
          'href'       => base_url().'support/%s',
          'href_key'   => 'code',
    ),
    array('label'      => 'OP',
          'width'      => '10%',
          'field_name' => 'op',
    ),
    array('label'      => 'Subject',
          'width'      => '25%',
          'field_name' => 'subject',
    ),
    array('label'      => 'Priority',
          'width'      => '5%',
          'field_name' => 'priority',
    ),
    array('label'      => 'Category',
          'width'      => '10%',
          'field_name' => 'category',
    ),
    array('label'      => 'Created',
          'width'      => '15%',
          'field_name' => 'created',
          'format'       => 'datetime',
    ),
    array('label'      => 'updated',
          'width'      => '15%',
          'field_name' => 'updated',
          'format' => 'datetime',
    ),
    array('label'      => 'Replies',
          'width'      => '5%',
          'field_name' => 'num_msg',
          'format'     => 'int'
    ),
);

