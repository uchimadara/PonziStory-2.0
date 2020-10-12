<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_user_tickets']['table'] = 'support_ticket';
$config['list_user_tickets']['order'] = 'updated';
$config['list_user_tickets']['table_class'] = 'rwd-table';
$config['list_user_tickets']['keyfields'] = array('subject', 'message');

$config['list_user_tickets']['fields'] = array(
    array('label'      => 'Ticket ID',
          'field_name' => 'code',
          'format'     => 'text',
          'href'       => base_url().'support/%s',
          'href_key'   => 'code',
    ),
    array('label'      => 'Username',
          'field_name' => 'username',
          'href'       => SITE_ADDRESS.'admin/user/%s',
          'href_key'   => 'user_id'
    ),
    array('label'      => 'Subject',
          'field_name' => 'subject',
    ),
    array('label'      => 'Priority',
          'field_name' => 'priority',
    ),
    array('label'      => 'Category',
          'field_name' => 'category',
          'format' => 'select',
          'select_list' => 'support_category_list'
    ),
    array('label'      => 'Created',
          'field_name' => 'created',
          'format'       => 'datetime',
    ),
    array('label'      => 'updated',
          'field_name' => 'updated',
          'format' => 'datetime',
    ),
    array('label'      => 'Replies',
          'field_name' => 'num_msg',
          'format'     => 'int'
    ),
);

