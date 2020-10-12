<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_donation_method'] = array(
    'table'  => 'user_payment_method',
    'fields' => array(
//        array(
//            'label'       => 'Payment Method',
//            'field_name'  => 'donation_code',
//            'type'        => 'select',
//            'select_list' => 'donation_code_list',
//            'rule'        => 'trim|xss_clean|required|',
//        ),
        array(
            'label'      => 'Payment Method',
            'field_name' => 'method_name',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '255',
            'size'       => '35',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
            'tip'        => 'Enter the name of the payment processor or bank.'
        ),
        array(
            'label'      => 'Account',
            'field_name' => 'account',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '255',
            'size'       => '40',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
            'tip'        => 'Enter the payment account username, email or account number.'
        ),
        array(
            'label'      => 'Note',
            'field_name' => 'note',
            'type'       => 'textarea',
            'value'      => '',
            'rows'       => '5',
            'cols'       => '80',
            'rule'       => 'trim|xss_clean',
            'class'      => 'form-control',
            'tip'        => 'Optional: Enter any payment instructions specific to this payment method.'
        ),
        array(
            'label'      => 'Secret Question',
            'field_name' => 'secret_answer',
            'type'       => 'secret',
            'value'      => '',
            'rows'       => '5',
            'cols'       => '80',
            'rule'       => 'trim|xss_clean|callback_valid_secret',
            'class'      => 'form-control',
            'tip'        => 'Enter your secret answer.'
        ),
    )
);
