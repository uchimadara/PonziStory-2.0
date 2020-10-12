<?php
/*************************
 * DB_LIST - PRODUCE A USER-SORTABLE, PAGED LISTING OF ANY QUERY DEFINED
 *
 */

class SupportTicket extends rmsList {


    public function __construct ($listName, $uri, $sort_dir = 'asc', $order = NULL) {

        parent::__construct($listName, $uri, $sort_dir, $order);
    }

    public function getPartial($page, $perPage, $userId = null, $status = null) {

        $this->_page = $page;
        $this->_per_page = $perPage;

        $start = ($this->_page - 1) * $this->_per_page;

        if ($userId)
            $this->db->where('user_id', $userId);

        if ($status)
            $this->db->where('status', $status);

        $this->_total = $this->db->from('support_ticket')
                        ->count_all_results();

        // More Harry Potter SQL
        if ($userId)
            $this->_where .= ' AND st.user_id = '. $userId;

        if ($status)
            $this->_where .= ' AND st.status = '.$status;

        $this->_listing = $this->db->query("SELECT st.*, m.responder_id, m.username, mc.num_msg
              FROM support_ticket st
              LEFT JOIN (SELECT stm.*, u.id AS responder_id, u.username
                           FROM support_ticket_message stm
                      LEFT JOIN users u
                             ON u.id = stm.user_id
                       ORDER BY stm.id DESC) m
                ON st.id = m.ticket_id
                LEFT JOIN (SELECT ticket_id, count(*) num_msg
                           FROM support_ticket_message stmc
                           GROUP BY ticket_id) mc
                ON st.id = mc.ticket_id
             WHERE 1 = 1
                 {$this->_where}
          GROUP BY st.id
          ORDER BY {$this->_order} {$this->_sort_dir}
             LIMIT $start, $perPage")->result();
    }

}

?>