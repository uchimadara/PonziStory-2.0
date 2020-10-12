<?php
/*************************
 * RMS_LIST EXTENDER
 *
 */
class Closed_tickets extends RMSList {

	function getPartial($page, $perPage) {

        $this->_page     = $page;
        $this->_per_page = $perPage;
        $this->set_where($this->_ci->input->get_post());
        $this->_where .= " AND status = 'closed'";
        $this->_total = $this->db->from('support_ticket st')
                            ->where($this->_where, NULL, FALSE)
                            ->count_all_results();

        $start = ($page - 1)*$perPage;

        $this->_listing = $this->db->query("SELECT st.*, ifnull(username, st.email) op
              FROM support_ticket st
              LEFT JOIN users u ON u.id = st.user_id

             WHERE {$this->_where}
          GROUP BY st.id
          ORDER BY {$this->_order} {$this->_sort_dir}
             LIMIT $start, $perPage")->result_array();

        foreach ($this->_listing as &$l) {
            $l['num_msg'] = $this->db->where('ticket_id', $l['id'])
                            ->from('support_ticket_message')
                            ->count_all_results();
        }

        return $this;
	}

}