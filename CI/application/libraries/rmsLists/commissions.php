<?php
/*************************
 * RMS_LIST EXTENDER
 *
 */
class Commissions extends RMSList {

    function getPartial($page, $perPage) {

        $this->_page     = $page;
        $this->_per_page = $perPage;

        $this->db->select("count(*) c", FALSE)
                ->from('transaction')
                ->join('users', 'users.id = transaction.user_id')
                ->join('purchase_order p', 'p.id = transaction.reference_id')
                ->join('users u1', 'u1.id = p.user_id')
                ->where('transaction.status', 'ok')
                ->where($this->_where, NULL, FALSE);


        $this->_total = $this->db->get()->row()->c;

        $start = ($page - 1)*$perPage;

        $this->db->select("transaction.id, transaction.user_id,transaction.created, users.username, transaction.item_code, p.description, transaction.gross_amount, u1.username purchaser, u1.id purchaser_id", FALSE)
         ->from('transaction')
         ->join('users', 'users.id = transaction.user_id')
         ->join('purchase_order p', 'p.id = transaction.reference_id')
         ->join('users u1', 'u1.id = p.user_id')
         ->where('transaction.status', 'ok')
         ->where($this->_where, NULL, FALSE)
         ->order_by($this->_order, $this->_sort_dir)
         ->limit($perPage, $start);

        $this->_listing = $this->db->get()->result_array();

        return $this;
    }
}