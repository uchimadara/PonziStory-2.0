<?php
/*************************
 * RMS_LIST EXTENDER
 *
 */
class Referrers extends RMSList {

	function getPartial($page, $perPage) {

        $this->_page = $page;
        $this->_per_page = $perPage;

        $this->db->select("count(distinct referrer_id) c", FALSE);
        $this->db->from('users');
        $this->db->where('active', 1);
        $this->db->where('id > ', 1);

        $this->_total = $this->db->get()->row()->c;

        $start = ($page - 1)*$perPage;

        $this->db->select("u1.id, u1.username, count(*) ref_count", FALSE);
        $this->db->from('users u2');
        $this->db->join('users u1', 'u1.id = u2.referrer_id');
        $this->db->where('u2.active', 1);
        $this->db->where('u1.id > ', 1);
        $this->db->group_by("u1.id");
        $this->db->order_by($this->_order, $this->_sort_dir);
        $this->db->limit($perPage, $start);

        $this->_listing = $this->db->get()->result_array();

        return $this;
	}

}