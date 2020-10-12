<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_history']['table']       = 'transaction';
$config['list_history']['order']       = 'created';
$config['list_history']['table_class'] = 'table';
$config['list_history']['keyfields']   = array('identifier');

$config['list_history']['fields'] = array(
    array('label'      => 'ID',
          'width'      => '8%',
          'field_name' => 'id',
        'format' => "pad",
        'pad_length' => 4,
        'pad_char' => '0',
          'onclick'     => "popup('".base_url()."cashier/transaction/%s');",
          'onclick_key' => 'id',
          'title'       => 'View Details',
    ),
    array('label'      => 'Date/Time',
          'width'      => '20%',
          'field_name' => 'created',
          'format'     => 'datetime'
    ),
    array('label'      => 'Transaction',
          'width'      => '12%',
          'field_name' => 'type',
        'format'    => 'wordify'
    ),
    array('label'      => 'Item',
          'width'      => '12%',
          'field_name' => 'item_code',
          'format'     => 'wordify'
    ),
    array('label'      => 'Method',
          'width'      => '12%',
          'field_name' => 'method',
        'format' => 'icon',
        'class' => 'ppIcon'
    ),
    array('label'      => 'Status',
          'width'      => '8%',
          'field_name' => 'status',
    ),
    array('label'      => 'Total',
          'width'      => '12%',
          'field_name' => 'gross_amount',
        'format' => 'currency',
        'align' => 'right'
    ),

);



