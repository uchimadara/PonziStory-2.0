<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_pending_invites']['table'] = 'invite';
$config['list_pending_invites']['order'] = 'date';
$config['list_pending_invites']['where'] = "invite.referral_user_id IS NULL AND date > ".(now() - INVITE_EXPIRATION*CACHE_ONE_HOUR);
$config['list_pending_invites']['join'] = array(
    array('users', 'users.id = invite.user_id')
);
$config['list_pending_invites']['table_class'] = 'rwd-table';
$config['list_pending_invites']['keyfields'] = array('first_name', 'last_name', 'email', 'username');

$config['list_pending_invites']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
    ),
    array('label'      => 'Member',
          'field_name' => 'username',
          'table_name' => 'users',
          'href' => SITE_ADDRESS.'admin/user/%s',
        'href_key' => 'user_id'
    ),
    array('label'      => 'First Name',
          'field_name' => 'first_name',
    ),
    array('label'      => 'Last Name',
          'field_name' => 'last_name',
    ),
    array('label'      => 'Email',
          'field_name' => 'email',
    ),
    array('label'      => 'Date',
          'field_name' => 'date',
          'format' => 'datetime',
    ),
    array('label'       => '',
          'field_name'  => 'id',
          'format'      => 'icon',
          'icon'        => 'fa fa-trash-o',
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/users/delete_invite/%d', 'Confirm delete invite.');",
          'onclick_key' => 'id',
          'title'       => 'Delete this invite',
          'align'       => 'center',
    ),

);

