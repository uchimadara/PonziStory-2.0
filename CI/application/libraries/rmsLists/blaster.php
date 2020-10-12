<?php
/*************************
 * RMS_LIST EXTENDER
 *
 */
class Blaster extends RMSList {

	function getPartial($page, $perPage) {
        $this->_page     = $page;
        $this->_per_page = $perPage;

        $this->db->select("blaster_queue.id,
                          time_to_send, subject, count(*) sent_total,
                          sum(viewed IS NOT NULL) viewed_total,
                          sum(clicked_thru IS NOT NULL) clicked_thru_total", FALSE);
        $this->db->from($this->_table);

        if ($this->_order == '' || $this->_order == 'id')
            $this->_order = " sent ";
        elseif ($this->_order == 'viewed_percent')
            $this->_order = 'viewed_total';
        elseif ($this->_order == 'clicked_thru_percent')
            $this->_order = 'clicked_thru_total';

        $this->db->group_by('subject');

        $q            = $this->db->get();
        $this->_total = $q->num_rows();


        $this->db->select("blaster_queue.id,
                          time_to_send, subject, count(*) sent_total,
                          sum(viewed IS NOT NULL) viewed_total,
                          sum(clicked_thru IS NOT NULL) clicked_thru_total", FALSE);
        $this->db->from($this->_table);

        if ($this->_order == '' || $this->_order == 'id')
            $this->_order = " sent ";
        elseif ($this->_order == 'viewed_percent')
            $this->_order = 'viewed_total';
        elseif ($this->_order == 'clicked_thru_percent')
            $this->_order = 'clicked_thru_total';

        $this->db->group_by('subject');
        $this->db->order_by($this->_order, $this->_sort_dir);

        $start = ($page - 1)*$perPage;
        $this->db->limit($perPage, $start);

        $this->_listing = $this->db->get()->result_array();
        
        foreach ($this->_listing as &$l) {
            $l['clicked_thru_percent'] = round($l['clicked_thru_total']/$l['sent_total']*100, 2);
            $l['viewed_percent']       = round($l['viewed_total']/$l['sent_total']*100, 2);
        }
        return $this;
	}

}