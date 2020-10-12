<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_failed_transactions']['table']       = 'deposit_fail';
$config['list_failed_transactions']['order']       = 'created';
$config['list_failed_transactions']['sort_dir'] = 'asc';
$config['list_failed_transactions']['table_class'] = 'table';
$config['list_failed_transactions']['keyfields']   = array('id', 'ip', 'data');

$config['list_failed_transactions']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
        'width' => '2%'
    ),
    array('label'      => 'Created',
          'width'      => '20%',
          'field_name' => 'created',
          'format'     => 'datetime'
    ),
    array('label'      => 'Method',
          'width'      => '8%',
          'field_name' => 'method',
    ),
    array('label'      => 'IP',
          'width'      => '5%',
          'field_name' => 'ip',
    ),
    array('label'      => 'Reason',
          'width'      => '15%',
          'field_name' => 'reason',
    ),
    array('label'       => '',
          'width'       => '2%',
          'field_name'  => 'id',
          'format'      => 'image',
          'src'         => asset('images/icons/edit.png'),
          'href'     => base_url().'adminpanel/cashier/failed_deposit/%d',
          'href_key' => 'id',
          'title'       => 'Edit Transaction',
          'align'       => 'center'
    ),
);
