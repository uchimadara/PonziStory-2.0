<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_mass_email'] = array(
    'table'  => 'blaster_queue',
    'fields' => array(
        array(
            'label'      => 'From Name',
            'field_name' => 'from_name',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '100',
            'size'       => '35',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required|',
        ),
        array(
            'label'      => 'From Email',
            'field_name' => 'from_email',
            'type'       => 'email',
            'value'      => FROM_EMAIL,
            'maxlength'  => '100',
            'size'       => '35',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required|valid_email',
        ),
        array(
            'label'      => 'Subject',
            'field_name' => 'subject',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '100',
            'size'       => '35',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required|',
        ),
        array(
            'label'       => 'Email Type',
            'field_name'  => 'email_options',
            'type'        => 'select',
            'select_list' => 'mass_email_setting_list',
            'rule'        => 'trim|',
            'blank_first' => FALSE,
            'value' => EMAIL_ALL,
        ),

//        array(
//            'label'       => 'Active',
//            'field_name'  => 'active',
//            'type'        => 'select',
//            'value' => 1,
//            'select_list' => 'yes_no_int',
//            'rule'        => 'trim|',
//            'blank_first' => FALSE,
//        ),
//        array(
//            'label'       => 'Account Level',
//            'field_name'  => 'account_level',
//            'type'        => 'select',
//            'select_list' => 'account_level_list',
//            'rule'        => 'trim|',
//        ),
        array(
            'label'      => 'Date to send',
            'field_name' => 'send_date',
            'type'       => 'date',
            'value' => now(),
        ),
        array(
            'label'      => 'Time to send',
            'field_name' => 'send_time',
            'type'       => 'time',
            'value'      => now(),
        ),
//        array(
//            'label'      => 'Send only to users from [country]',
//            'field_name' => 'country',
//            'type'       => 'select',
//            'select_list' => 'country_list',
//            'rule'        => 'trim|',
//        ),
//        array(
//            'label'        => 'Email Template',
//            'field_name'   => 'template',
//            'type'        => 'select',
//            'select_list' => 'email_template_list',
//            'rule'        => 'trim|',
//            'value' => 'default',
//            'blank_first' => FALSE,
//
//        ),
        array(
            'label'      => 'Message',
            'field_name' => 'body',
            'type'       => 'html',
            'value'      => '',
            'rows'       => '45',
            'cols'       => '80',
            'required'   => TRUE,
            'rule'       => 'xss_clean|required',
        ),

    )
);
