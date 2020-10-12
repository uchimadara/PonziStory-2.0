<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_invite'] = array(
    'table'  => 'invite',
    'fields' => array(
        array(
            'label'      => 'Date',
            'field_name' => 'date',
            'type'       => 'hidden',
            'value'      => now(),
        ),
//        array(
//            'label'      => 'Username',
//            'field_name' => 'username',
//            'type'       => 'text',
//            'value'      => '',
//            'maxlength'  => '50',
//            'size'       => '35',
//            'required'   => TRUE,
//            'rule'       => 'trim|xss_clean|required',
//        ),
        array(
            'label'      => 'First Name',
            'field_name' => 'first_name',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '50',
            'size'       => '35',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'Last Name',
            'field_name' => 'last_name',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '50',
            'size'       => '35',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'Email',
            'field_name' => 'email',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '255',
            'size'       => '40',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required|valid_email|callback_email_check',
        ),
    )
);
