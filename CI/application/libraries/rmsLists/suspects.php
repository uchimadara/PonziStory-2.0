<?php
/*************************
 * RMS_LIST EXTENDER
 *
 */
class Suspects extends RMSList {

    function getPartial($page, $perPage) {

        $this->_page = $page;
        $this->_per_page = $perPage;

        $this->db->select("count(id) c", FALSE);
        $this->db->from('user_payment_method');
//        $this->db->where('active', 1);
//        $this->db->where('id > ', 1);

        $this->_total = $this->db->get()->row()->c;

        $start = ($page - 1)*$perPage;

        $this->db->select("user_id,username, account,method_name,first_name,last_name,phone, note,count(account) acc_count", FALSE);
        $this->db->from('user_payment_method up');
        $this->db->join('users u', 'u.id = up.user_id');
        $this->db->where('active', 1);
        $this->db->where('visible', 1);
//        $this->db->where('u2.active', 1);
//        $this->db->where('u1.id > ', 1);
        $this->db->group_by("u.id");
        $this->db->order_by('acc_count', 'DESC');
        $this->db->limit($perPage, $start);

        $this->_listing = $this->db->get()->result_array();

        return $this;
    }

}