<?php
/*************************
 * RMS_LIST EXTENDER
 *
 */
class Suspectsnum extends RMSList {

    function getPartial($page, $perPage) {

        $this->_page = $page;
        $this->_per_page = $perPage;

        $this->db->select("count(id) c", FALSE);
        $this->db->from('users');
//        $this->db->where('active', 1);
//        $this->db->where('id > ', 1);

        $this->_total = $this->db->get()->row()->c;

        $start = ($page - 1)*$perPage;

        $this->db->select("id,username,first_name,last_name,phone,count(phone) p_count", FALSE);
        $this->db->from('users u');
       $this->db->where('active', 1);
        $this->db->where('visible', 1);
//        $this->db->where('u1.id > ', 1);
        $this->db->group_by("phone");
        $this->db->order_by('p_count', 'DESC');
        $this->db->limit($perPage, $start);

        $this->_listing = $this->db->get()->result_array();

        return $this;
    }

}