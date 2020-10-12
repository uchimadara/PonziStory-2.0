<?php

/* * ***********************
 * RMS_LIST EXTENDER
 *
 */

class User_groups extends RMSList {

    function getPartial($page, $perPage) {

        $this->_page = $page;
        $this->_per_page = $perPage;
        $start = ($page - 1) * $perPage;
        $user_id = $this->_ci->input->get('user_id');
        if ($this->_ci->uri->segment(1) == 'admin') {
           
            $this->_listing = array();
            $query = $this->db->get('groups');
            foreach($query->result() as $g){
                $g->user_id = $user_id;
                $g->group_id = $this->db->where('user_id', $user_id)
                                        ->where('group_id', $g->id)
                                        ->get('users_groups')->row()->group_id;

                $this->_listing[] = $g;
            }
        }else{
            show_404();
        }

        $this->_total = $query->num_rows();
        return $this;
    }

}
