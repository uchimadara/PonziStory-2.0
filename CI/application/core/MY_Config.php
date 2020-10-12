<?php
class MY_Config extends CI_Config {
    public function __construct() {
        parent::__construct();
    }

    function site_url($uri = '') {
        if ($uri == '') {
            return SITE_ADDRESS;
        }

        if ($this->item('enable_query_strings') == FALSE) {
            $suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
            return SITE_ADDRESS.$this->slash_item('index_page').$this->_uri_string($uri).$suffix;
        } else {
            return SITE_ADDRESS.$this->item('index_page').'?'.$this->_uri_string($uri);
        }
    }
}