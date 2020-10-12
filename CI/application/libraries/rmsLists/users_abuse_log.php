<?php

/* * ***********************
 * RMS_LIST EXTENDER
 *
 */

class Users_abuse_log extends RMSList {

    function getPartial($page, $perPage) {
        $this->_page     = $page;
        $this->_per_page = $perPage;

        $this->_ci->load->model('user_model', 'User');
        $sql = $this->_ci->User->find_same_login_ip($page, $perPage);
        $this->_listing = array();
        $this->_results = $sql['result'];
        $this->_total = $sql['count'];
        $this->_get_listing();

        return $this;
    }

}
