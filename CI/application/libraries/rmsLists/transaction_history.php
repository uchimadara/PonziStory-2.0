<?php
/*************************
 * RMS_LIST EXTENDER
 *
 */
class Transaction_history extends RMSList {

	function getPartial($page, $perPage) {

        $this->_page = $page;
        $this->_per_page = $perPage;

        $this->_total = $this->db->select("count(*) c", FALSE)
         ->from('transaction t')
         ->where('status', 'ok')
         ->where($this->_where, NULL, FALSE)
         ->get()->row()->c;

        $start = ($page - 1)*$perPage;

        $this->db->select("t.id, t.created, u.username, t.item_code, p.description, t.gross_amount, u1.username purchaser", FALSE);
        $this->db->from('transaction t');
        $this->db->join('users u', 'u.id = t.user_id');
        $this->db->join('purchase_order p', 'p.id = t.reference_id');
        $this->db->join('users u1', 'u1.id = p.user_id');
        $this->db->where('t.status', 'ok');
        $this->db->where($this->_where, NULL, FALSE);
        $this->db->order_by($this->_order, $this->_sort_dir);
        $this->db->limit($perPage, $start);

        $this->_listing = $this->db->get()->result_array();

        return $this;
	}

}