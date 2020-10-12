<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['list_cms_page']['table'] = 'cms_page';
$config['list_cms_page']['order'] = 'id';
$config['list_cms_page']['table_class'] = 'table';
$config['list_cms_page']['fields'] = array(
array('label'      => 'Id',
                                            'width'      => '4%',
                                            'field_name' => 'id',
                                      ),
array('label'      => 'Name',
                                            'width'      => '8%',
                                            'field_name' => 'name',
                                      ),
array('label'      => 'Content',
                                            'width'      => '14%',
                                            'field_name' => 'content',
                                      ),
array('label'      => 'Updated',
                                            'width'      => '14%',
                                            'field_name' => 'updated',
                                      ),
array('label'      => 'Cms Menu Id',
                                            'width'      => '22%',
                                            'field_name' => 'cms_menu_id',
                                      ),
);
?>
