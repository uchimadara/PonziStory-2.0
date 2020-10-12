<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// FORM FIELDS FOR USERS ADMIN FORM
$date = date_create();
$config['form_testimonial'] = array(
    'table'  => 'testimonial',
    'fields' => array(
        array(
            'label'      => 'Date',
            'field_name' => 'date',
            'type'       => 'hidden',
            'value'      => date_timestamp_get($date),
        ),
        array(
            'label'      => 'Image',
            'field_name' => 'screenshot',
            'type'       => 'ci_view',
            'view_file'  => 'member/partial/upload_image',
        ),
        array(
            'label'      => 'Your words (up to 5000 characters)',
            'field_name' => 'content',
            'type'       => 'textarea',
            'class' => 'form-control',
            'value'      => '',
            'maxlength'  => '5096',
            'rows'       => '7',
            'required'   => TRUE,
            'rule'       => 'trim|xss_clean|required',
        ),
    )
);
