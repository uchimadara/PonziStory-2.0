<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM

$config['form_payment_item'] = array(
    'table'  => 'payment_method',
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
            'label'      => 'Sorting',
            'field_name' => 'sorting',
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

