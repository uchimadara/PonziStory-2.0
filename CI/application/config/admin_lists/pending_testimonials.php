<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DEFAULT USER LIST DEFINITIONS

$config['list_pending_testimonials']['table'] = 'testimonial';
$config['list_pending_testimonials']['order'] = 'date';
$config['list_pending_testimonials']['where'] = "testimonial.status ='Pending'";
$config['list_pending_testimonials']['join'] = array(
    array('users', 'users.id = testimonial.user_id')
);
$config['list_pending_testimonials']['table_class'] = 'rwd-table';
$config['list_pending_testimonials']['keyfields'] = array('username');

$config['list_pending_testimonials']['fields'] = array(
    array('label'      => 'id',
          'field_name' => 'id',
    ),
    array('label'      => 'id',
          'field_name' => 'user_id',
        'hidden' => true,
    ),
    array('label'      => 'User',
          'field_name' => 'username',
          'table_name' => 'users',
          'href' => SITE_ADDRESS.'admin/user/%s',
        'href_key' => 'user_id'
    ),
    array('label'      => 'Created On',
          'field_name' => 'date',
          'format'     => 'datetime'
    ),
    array('label'      => 'Content',
          'field_name' => 'content',
    ),
    array('label'      => 'Image',
          'field_name' => 'screenshot',
          'format'     => 'image',
          'src'        => SITE_ADDRESS.'uploads/%s',
          'img_key'    => 'screenshot',
          'img_width'  => '50px',
          'img_height' => '50px',
          'popup'      => TRUE
    ),
    array('label'       => '',
          'field_name'  => 'id',
          'format' => 'icon',
          'icon'   => 'fa fa-check green',
          'onclick'     => "removeRow(this, '".base_url()."adminpanel/admin/testimonial_update/%d/1', 'Confirm approve testimonial.');",
          'onclick_key' => 'id',
          'title'       => 'Approve this testimonial',
          'align'       => 'center',
    ),
    array('label'       => '',
          'field_name'  => 'id',
          'format' => 'icon',
          'icon'   => 'fa fa-ban red',
          'onclick'     => "promptRemove(this, '".base_url()."adminpanel/admin/testimonial_update/%d/0', 'Enter reason for rejecting.');",
          'onclick_key' => 'id',
          'title'       => 'Reject this testimonial',
          'align'       => 'center',
    ),

);

