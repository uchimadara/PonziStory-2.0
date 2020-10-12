<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_payments']['table']       = 'payment';
$config['list_payments']['order']       = 'payment.created';
$config['list_payments']['sort_dir']    = 'desc';
$config['list_payments']['table_class'] = 'rwd-table';
$config['list_payments']['where'] = 'payment.approved IS NOT NULL';
$config['list_payments']['where'] = 'payee.visible = 1'; 
$config['list_payments']['keyfields'] = array('payer.username', 'payee.username', 'transaction_id', 'method_name');

$config['list_payments']['join']        = array(
    array('users payee', 'payee.id = payment.payee_user_id'),
    array('users payer', 'payer.id = payment.payer_user_id'),
    array('user_payment_method m', 'm.id = payment.method_id'),
    array('purchase_item i', 'i.id = payment.upgrade_id')
);

$config['list_payments']['fields'] = array(
    array('label'      => '',
          'field_name' => 'id',
          'hidden'     => TRUE,
          'table_name' => 'payee',
          'alias'      => 'payee_id'
    ),
    array('label'      => '',
          'field_name' => 'id',
          'hidden'     => TRUE,
          'table_name' => 'payer',
          'alias'      => 'payer_id'
    ),
    array('label'      => 'Date',
          'field_name' => 'created',
          'table_name' => 'payment',
          'format'     => 'datetime'
    ),
    array('label'      => 'From Member',
          'field_name' => 'username',
          'table_name' => 'payer',
          'href'       => base_url().'admin/user/%s',
          'href_key'   => 'payer_id',
          'alias'      => 'payer_username'
    ),
    array('label'      => 'To Member',
          'field_name' => 'username',
          'table_name' => 'payee',
          'href'       => base_url().'admin/user/%s',
          'href_key'   => 'payee_id',
          'alias'      => 'payee_username'
    ),
    array('label'      => 'Stage',
          'field_name' => 'code',
          'table_name' => 'i'
    ),
    array('label'      => 'Payment Method',
          'field_name' => 'method_name',
          'table_name' => 'm'
    ),
    array('label'      => 'Transaction ID',
          'field_name' => 'transaction_id'
    ),
    array('label'      => 'Amount',
          'field_name' => 'amount',
          'format'     => 'currency'
    ),
    array('label'      => 'Screenshot',
          'field_name' => 'proof_img',
          'format'     => 'image',
          'src'        => SITE_ADDRESS.'proofs/%s',
          'img_key'    => 'proof_img',
          'img_width'  => '40px',
          'img_height' => '20px',
          'popup'      => TRUE
    ),
    array('label'      => '',
          'field_name' => 'id',
          'format'     => 'icon',
          'icon'       => 'fa fa-pencil-square-o',
          'href'       => base_url()."adminpanel/users/edit_payment/%d",
          'href_key'   => 'id',
          'title'      => 'Edit payment',
          'align'      => 'center',
          'class'      => 'popup'
    ),

    array('label'       => '',
        'field_name'  => 'id',
        'format'      => 'icon',
        'icon'        => 'fa fa-trash-o',
        'onclick'     => "removeRow(this, '".base_url()."adminpanel/admin/delete_item/payment/%d', 'Confirm delete payment.');",
        'onclick_key' => 'id',
        'title'       => 'Delete this payment',
        'align'       => 'center',
    ),

);
