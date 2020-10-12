<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_balance'] = array(
    'table'  => 'user_payment_method',
    'fields' => array(
        array(
            'label'      => 'Pay Processor',
            'field_name' => 'payment_code',
            'type'       => 'select',
            'select_list'  => 'payment_code_list',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'Account',
            'field_name' => 'account',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '50',
            'size'       => '25',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|',
        ),
        array(
            'label'      => 'Balance',
            'field_name' => 'balance',
            'type'       => 'float',
            'value'      => '',
            'maxlength'  => '12',
            'size'       => '12',
            'tip' => 'Negative number will decrease balance.',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
        array(
            'label'      => 'Reason',
            'field_name' => 'message',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '255',
            'size'       => '25',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
    )
);
