<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class menu extends Widget {

    private $_counter = 0;
    private $_frontend = FALSE;
    private $_isGuest = FALSE;

    function __construct($config = array()) {
    }


    public function run($place = 'guest', $isGuest) {
        $this->_frontend = $place == 'guest';
        $this->_isGuest = $isGuest;
        $data            = '';

        foreach ($this->_get_items($place, 0) as $mp) {
            if ($this->_has_children($mp->id) > 0) {
                $data .= $this->_item_dropdown_start($mp);
                foreach ($this->_get_items($place, $mp->id) as $cm) {
                    $data .= $this->_menu_item($cm);
                }
                $data .= $this->_item_dropdown_end();
            } else {
                $data .= $this->_item_single($mp);
            }
        }

        return $data;
    }

    public function _item_dropdown_start($data) {
        return '<li class="dropdown">
                    <a class="dropdown-link '.$data->icon.'" href="">
                        <span class="menu-item">'.$data->name.' <span class="pull-right"><i class="fa fa-angle-right" aria-hidden="true"></i></span></span>
                    </a>
                    <ul class="list-unstyled menu-item dropdown-menu">';
    }

    public function _item_dropdown_end() {
        return '</ul>
                </li>';
    }

    public function _url($url) {
        if (strpos($url, 'http') === FALSE) {
            return SITE_ADDRESS.$url;
        }
        return $url;
    }

    public function _menu_item($data) {
        $this->checkData($data);
        return '<li><a href="'.$this->_url($data->url).'">'.$data->name.'</a></li>';
    }

    public function _has_children($id) {
        return $this->db->where('parent_id', $id)->order_by('position', 'asc')->get('cms_menu')->num_rows();
    }

    public function _get_items($place, $id) {
        return $this->db->where('place', $place)->where('parent_id', $id)->order_by('position', 'asc')->get('cms_menu')
                ->result();
    }

    public function _item_single($data) {
        $this->checkData($data);
        if ($this->_frontend == TRUE) {
            return '<li><a class="'.$data->icon.'" href="'.$this->_url($data->url).'">'.$data->name.'</a></li>';
        } else {
            $this->_counter++;
            return '<li'.($this->_counter == 1 ? ' class="active"' : '').'>
                        <a class="'.$data->icon.'" href="'.$this->_url($data->url).'">
                            <span class="menu-item">'.$data->name.'</span>
                        </a>
                    </li>';
        }
    }

    private function checkData(& $data) {
        if (!$this->_isGuest) {
            if ($data->url == 'login') {
                $data->name = 'Back Office';
                $data->url  = 'back_office';
            } elseif ($data->url == 'register') {
                $data->name = 'Sign Out';
                $data->url  = 'logout';
            }
        }
    }
}
