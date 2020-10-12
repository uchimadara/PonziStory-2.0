<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_user'] = array(
    'table'  => 'users',
    'fields' => array(
        array(
            'label'        => 'Created',
            'field_name'   => 'created_on',
            'type'         => 'datetime',
            'display_only' => TRUE
        ),
        array(
            'label'        => 'Last Login',
            'field_name'   => 'last_login',
            'type'         => 'datetime',
            'display_only' => TRUE
        ),
        array(
            'label'        => 'Upline:',
            'field_name'   => 'referrer_id',
            'type'         => 'select',
            'select_list'  => 'user_list',
            'display_only' => TRUE,
        ),
        array(
            'label'        => 'Orig. Sponsor:',
            'field_name'   => 'sponsor_id',
            'type'         => 'select',
            'select_list'  => 'user_list',
            'display_only' => TRUE,
        ),
        array(
            'label'      => 'Account Level',
            'field_name' => 'account_level',
            'type'       => 'int',
            'display_only' => TRUE,
        ),
        array(
            'label'      => 'Username',
            'field_name' => 'username',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '100',
            'size'       => '35',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required|callback_user_check',
        ),
        array(
            'label'      => 'Email',
            'field_name' => 'email',
            'type'       => 'email',
            'value'      => '',
            'maxlength'  => '100',
            'size'       => '35',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required|valid_email',
//            'rule'       => 'trim|xss_clean|required|valid_email|callback_email_check',
        ),
        array(
            'label'      => 'First Name',
            'field_name' => 'first_name',
            'type'       => 'text',
            'maxlength' => '50',
        ),
        array(
            'label'      => 'Last Name',
            'field_name' => 'last_name',
            'type'       => 'text',
            'maxlength' => '50',
        ),
        array(
            'label'      => 'Text Ad Credits',
            'field_name' => 'text_ad_credits',
            'type'       => 'int',
            'maxlength'  => '7',
        ),

        array(
            'label'      => 'Account Expires',
            'field_name' => 'account_expires',
            'type'       => 'datetime',
            'value'      => ''
        ),
        array(
            'label'      => 'Phone Number',
            'field_name' => 'phone',
            'type'       => 'text',
            'maxlength' => '25',
        ),
        array(
            'label'      => 'Address',
            'field_name' => 'address',
            'type'       => 'text',
            'maxlength' => '255',
        ),
        array(
            'label'      => 'City',
            'field_name' => 'city',
            'type'       => 'text',
            'maxlength' => '100',
        ),
        array(
            'label'      => 'State',
            'field_name' => 'state',
            'type'       => 'text',
            'maxlength' => '50',
        ),
        array(
            'label'      => 'Country',
            'field_name' => 'country_id',
            'type'       => 'select',
            'select_list' => 'country_list'
        ),
        array(
            'label'      => 'Reason/Possible Reason for Ban/lock',
            'field_name' => 'reason',
            'type'       => 'text',
            'maxlength' => '50',
        ),
        array(
            'label'       => 'Active',
            'field_name'  => 'active',
            'type'        => 'checkbox',
            'select_list' => 'yes_no_int',
        ),
        array(
            'label'       => 'Banned',
            'field_name'  => 'banned',
            'type'        => 'checkbox',
            'select_list' => 'yes_no_int',
        ),
        array(
            'label'       => 'Locked',
            'field_name'  => 'locked',
            'type'        => 'checkbox',
            'select_list' => 'yes_no_int',
        ),
    )
);

