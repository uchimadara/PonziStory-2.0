<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['form_news']['table'] = 'news';
$config['form_news']['fields'] = array(
    array('label' => 'Title',
        'field_name' => 'title',
        'type' => 'text',
        'value' => '',
        'maxlength' => '255',
        'rule' => 'trim|xss_clean|required',
    ),
    array('label' => 'Image Url',
        'field_name' => 'image',
        'type' => 'text',
        'value' => '',
        'maxlength' => '255',
        'rule' => 'trim|xss_clean',
    ),
    array('label' => 'Content',
        'field_name' => 'content',
        'type' => 'html',
        'value' => '',
        'maxlength' => '',
        'rule' => 'trim|xss_clean|required',
    ),
    array('label' => 'Date',
        'field_name' => 'date',
        'type' => 'hidden',
        'value' => now()+172800,
        'maxlength' => '11',
        'rule' => 'trim|xss_clean|required',
    ),
);
?>
