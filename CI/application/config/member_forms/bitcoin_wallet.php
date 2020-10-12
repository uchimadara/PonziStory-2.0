<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_bitcoin_wallet'] = array(
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
            'label'      => 'Bank Name',
            'field_name' => 'note',
            'type'       => 'text',
            'maxlength' => 255,
            'value'      => '',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),



        array(
            'label'      => 'Account Name',
            'field_name' => 'method_name',
            'type'       => 'text',
            'maxlength' => 255,
            'value'      => '',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),

        array(
            'label'      => 'Account Number',
            'field_name' => 'account',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '255',
            'rule'       => 'trim|xss_clean|required',

//            'size'       => '40',
//            'required'   => TRUE,
//            'rule'       => 'trim|xss_clean|required|callback_bc_account_check',
//            'tip'        => 'Enter the bitcoin wallet address where you want to receive donations.'
        ),

        array(
            'label'      => 'Account type',
            'field_name' => 'payment_code',
            'type'       => 'text',
            'value'      => 'Savings',
            'required'   => TRUE,
            'placeholder' => 'Savings or current',
            'rule'       => 'trim|xss_clean|required',
        ),

//        array(
//            'label'      => 'Secret Question',
//            'field_name' => 'secret_answer',
//            'type'       => 'secret',
//            'value'      => '',
//            'rows'       => '5',
//            'cols'       => '80',
//            'rule'       => 'trim|xss_clean|callback_valid_secret',
//            'class'      => 'form-control',
//            'tip'        => 'Enter your secret answer.'
//        ),
    )
);
