<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['form_cms_slider']['table'] = 'cms_slider';
$config['form_cms_slider']['fields'] = array(
    array('label'      => 'Position',
          'field_name' => 'position',
          'type'       => 'int',
          'value'      => '',
          'maxlength'  => '255',
          'rule'       => 'trim|xss_clean|required',
    ),
    array('label' => 'Image',
        'field_name' => 'image',
        'type' => 'text',
        'value' => '',
        'maxlength' => '255',
        'rule' => 'trim|xss_clean|required',
    ),
    array('label' => 'Headline',
        'field_name' => 'headline',
        'type' => 'text',
        'value' => '',
        'maxlength' => '255',
        'rule' => 'trim|xss_clean',
    ),
    array('label' => 'Tagline',
        'field_name' => 'tagline',
        'type' => 'textarea',
        'value' => '',
        'rows' => '5',
        'cols' => '80',
        'rule' => 'trim|xss_clean',
        'class' => 'form_control'
    ),
);
?>
