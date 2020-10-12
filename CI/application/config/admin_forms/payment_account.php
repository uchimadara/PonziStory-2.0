<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_payment_account'] = array(
    'table'  => 'payment_method_account',
    'fields' => array(

        array(
            'label'      => 'Name',
            'field_name' => 'name',
            'type'       => 'text',
            'value'      => '',
            'maxlength'  => '50',
            'size'       => '25',

            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),

        array(
            'label'      => 'Details',
            'field_name' => 'details',
            'type'       => 'text',
        ),
        array(
            'label'      => 'Extra field 1',
            'field_name' => 'extra_field_1',
            'type'       => 'text',
        ),
        array(
            'label'      => 'Extra field 2',
            'field_name' => 'extra_field_2',
            'type'       => 'text',
        ),

        array(
            'label'      => 'Min deposit',
            'field_name' => 'min_deposit',
            'type'       => 'int',
        ),
        array(
            'label'      => 'Max deposit',
            'field_name' => 'max_deposit',
            'type'       => 'int',
        ),
        array(
            'label'      => 'Min cashout',
            'field_name' => 'min_cashout',
            'type'       => 'int',
        ),
        array(
            'label'      => 'Max cashout',
            'field_name' => 'max_cashout',
            'type'       => 'int',
        ),


        array(
            'label'       => 'Enabled',
            'field_name'  => 'enabled',
            'type'        => 'select',
            'select_list' => 'yes_no_int',
            'rule'        => 'trim|required|',
            'required'    => TRUE
        ),

    )
);

