<?php
/*************************
 * RMS_LIST EXTENDER
 *
 */
class Click_stats extends RMSList {

	function getPartial($page, $perPage) {

        $this->_page     = $page;
        $this->_per_page = $perPage;
        $userData = $this->_ci->ion_auth->user()->row();

        $this->_results = $this->db->select("came_from, count(*) click_count, SUM( referral_user_id IS NOT NULL )  user_count", FALSE)
                ->from('reflink_clicks')
                ->where("user_id", $userData->id)
                ->group_by("came_from")
                ->order_by($this->_order, $this->_sort_dir)
                ->get()->result_array();

        $this->_total = count($this->_results);
        $this->_get_listing();

        return $this;
	}

}