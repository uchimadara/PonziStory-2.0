<?php

/* * ***********************
 * RMS_LIST EXTENDER
 *
 */

class Admin_user_login_ads extends RMSList {

    function getPartial($page, $perPage) {

        $this->_page = $page;
        $this->_per_page = $perPage;
        $start = ($page - 1) * $perPage;
        $user_id = $this->_ci->input->get('user_id');


        $query = $this->db->select('cl.id AS id, cl.name AS name, cl.target_url AS target_url, '
                        . 'SUM(cl.amount) AS paid, SUM(if(cl.status = \'outbid\', cl.amount ,0)) AS refund', FALSE)
                ->join('purchase_order po', 'po.id = cl.order_id')
                ->where('po.user_id', $user_id)
                ->group_by('cl.target_url')
                ->order_by($this->_order, $this->_sort_dir)
                ->limit($perPage, $start)
                ->get('campaign_login_ad cl');

        $this->_listing = $query->result();

        $this->_total = $query->num_rows();
        return $this;
    }

}
