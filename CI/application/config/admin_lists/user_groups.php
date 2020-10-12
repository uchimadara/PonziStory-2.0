<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_user_groups']['table'] = 'groups';
$config['list_user_groups']['table_class'] = 'table table-bordered';
$config['list_user_groups']['keyfields'] = array('name');
$config['list_user_groups']['order'] = 'id';

$config['list_user_groups']['fields'] = array(

    array('label' => 'Name',
        'field_name' => 'description',
        'nosort' => true,
        'width' => '150px'
    ),
    array('label' => 'Access',
        'field_name' => 'group_id',
        'format' => 'checkbox_ajax',
        'nosort' => true,
        'form_action' => SITE_ADDRESS."adminpanel/users/change_group/%d",
        'form_key' => 'user_id',
    )
);