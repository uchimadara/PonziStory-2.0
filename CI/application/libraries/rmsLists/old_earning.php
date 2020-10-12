<?php
/*************************
 * RMS_LIST EXTENDER
 *
 */
class Earning extends RMSList {

	function getPartial($page, $perPage) {

        $this->_page     = $page;
        $this->_per_page = $perPage;
        $this->set_where($this->_ci->input->get_post());

        $this->_total = $this->db->select('count(distinct user_id) c', FALSE)
                        ->from('transaction')
                ->like('item_code', 'position')
                ->where($this->_where, NULL, FALSE)
                            ->get()->row()->c;

        $start = ($page - 1)*$perPage;

        $sql = "select u.username, ifnull(a.cycled, 0) cycled, ifnull(b.cleared, 0) cleared, ifnull(a.cycled, 0) + ifnull(b.cleared, 0) as total
                from users u
                LEFT join (select user_id, sum(gross_amount) cycled from transaction t where item_code = 'cycled position' group by user_id) a ON a.user_id = u.id
                LEFT join (select user_id, sum(gross_amount) cleared from transaction t where item_code = 'cleared position' group by user_id) b ON b.user_id = u.id
                where cleared > 0 or cycled > 0
                AND {$this->_where}
                ORDER BY {$this->_order}  {$this->_sort_dir}
                LIMIT $start, $perPage ";

        $this->_listing = $this->db->query($sql)->result_array();

       return $this;
	}

}