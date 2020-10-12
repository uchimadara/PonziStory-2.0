<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_rejected_payments']['table']       = 'payment';
$config['list_rejected_payments']['order']       = 'payment.created';
$config['list_rejected_payments']['sort_dir']    = 'desc';
$config['list_rejected_payments']['table_class'] = 'rwd-table';
$config['list_rejected_payments']['where'] = 'payment.approved IS NULL AND payment.rejected IS NOT NULL';
//$config['list_rejected_payments']['where'] =  'payee.visible = 1';
$config['list_rejected_payments']['keyfields'] = array('payer.username', 'payee.username', 'transaction_id', 'method_name', 'reason');

$config['list_rejected_payments']['join']        = array(
    array('users payee', 'payee.id = payment.payee_user_id'),
    array('users payer', 'payer.id = payment.payer_user_id'),
    array('user_payment_method m', 'm.id = payment.method_id'),
    array('purchase_item i', 'i.id = payment.upgrade_id')
);

$config['list_rejected_payments']['fields'] = array(
    array('label'      => '',
          'field_name' => 'id',
          'hidden'      => TRUE,
        'table_name' => 'payee',
        'alias' => 'payee_id'
    ),
    array('label'      => '',
          'field_name' => 'id',
          'hidden'     => TRUE,
          'table_name' => 'payer',
          'alias'      => 'payer_id'
    ),

    array(
        'label'       => 'PID',
        'field_name'  => 'id',
        'type'        => 'checkbox',
        'format' => 'checkbox',
        'value' => 'payer_id',
        'form_action' => SITE_ADDRESS."adminpanel/admin/requeueBunch/%d/",
        'form_key' => 'payer_id',
    ),
    array('label'      => 'Date',
          'field_name' => 'created',
          'table_name' => 'payment',
          'format'     => 'date'
    ),
    array('label'      => 'From Member',
          'field_name' => 'username',
          'table_name' => 'payer',
            'href'     => base_url().'admin/user/%s',
          'href_key' => 'payer_id',
          'alias'    => 'payer_username'
    ),
    array('label'      => 'To Member',
          'field_name' => 'username',
          'table_name' => 'payee',
          'href'     => base_url().'admin/user/%s',
          'href_key' => 'payee_id',
          'alias'    => 'payee_username'
    ),
    array('label'      => 'Stage',
          'field_name' => 'code',
          'table_name' => 'i'
    ),
    array('label'      => 'Payment Method',
          'field_name' => 'method_name',
          'table_name' => 'm'
    ),
   
    array('label'      => 'Amount',
          'field_name' => 'amount',
          'format'     => 'currency'
    ),

    array('label'      => 'Reason',
        'field_name' => 'reason',

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

    array('label'       => '',
        'field_name'  => 'id',
        'format'      => 'icon',
        'icon'        => 'fa fa-check green',
        'onclick'     => "removeRow(this, '".base_url()."adminpanel/admin/payment_update/%d/1/1', 'Confirm approve payment.');",
        'onclick_key' => 'id',
        'title'       => 'Approve this payment',
        'align'       => 'center',
    ),

    array('label'       => '',
        'field_name'  => 'id',
        'format'      => 'icon',
        'icon'        => 'fa fa-refresh green',
        'onclick'     => "removeRow(this, '".base_url()."adminpanel/admin/requeue/%d/1/1', 'Confirm ReQueue PH.');",
        'onclick_key' => 'id',
        'title'       => 'ReQueue PH',
        'align'       => 'center',
    ),

    array('label'       => '',
        'field_name'  => 'id',
        'format'      => 'icon',
        'icon'        => 'fa fa-refresh red',
        'onclick'     => "removeRow(this, '".base_url()."adminpanel/admin/requeue/%d/2/1', 'Confirm ReQueue GH.');",
        'onclick_key' => 'id',
        'title'       => 'ReQueue GH',
        'align'       => 'center',
    ),

    array('label'       => '',
        'field_name'  => 'id',
        'format'      => 'icon',
        'icon'        => 'fa fa-exchange blue',
        'onclick'     => "removeRow(this, '".base_url()."adminpanel/admin/remergethis/%d/1', 'Confirm Remerge This.');",
        'onclick_key' => 'id',
        'title'       => 'Remerge this',
        'align'       => 'center',
    ),

    array('label'      => '',
          'field_name' => 'id',
          'table_name' => 'payment',
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

    array('label'       => '',
        'field_name'  => 'id',
        'format'      => 'icon',
        'icon'        => 'fa fa-refresh red',
        'onclick'     => "removeRow(this, '".base_url()."adminpanel/admin/requeueBunch/%d/2/1', 'Confirm ReQueue GH BUNCH.');",
        'onclick_key' => 'id',
        'title'       => 'ReQueue GH Bunch',
        'align'       => 'center',
    ),


);
