<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['list_email_templates']['table'] = 'email_templates';
$config['list_email_templates']['order'] = 'id';
$config['list_email_templates']['table_class'] = 'table';
$config['list_email_templates']['fields'] = array(
    array('label' => 'Id',
        'width' => '4%',
        'field_name' => 'id',
    ),
    array('label' => 'Name',
        'field_name' => 'name',
        'href' => base_url() . 'admin/form/email_templates/%s',
        'href_key' => 'id',
    ),
    array('label'      => 'Category',
          'field_name' => 'category',
    )
);
