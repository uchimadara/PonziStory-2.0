<?php

/* * ***********************
 * RMS_LIST EXTENDER
 *
 */

class User_Balances extends RMSList {

    function getPartial($page, $perPage) {

        $this->_page     = $page;
        $this->_per_page = $perPage;

        $this->set_where($this->_ci->input->get_post());

        $this->_total = $this->db
                ->query("SELECT COUNT(*) c FROM users")
                ->row(0)->c;

        $start = ($page - 1)*$perPage;

        $sql = "
        select username, u.id,st.balance st_bal, pm.balance pm_bal, pz.balance pz_bal, pp.balance pp_bal, eb.balance eb_bal, bc.balance bc_bal,
        ifnull(st.balance, 0) + ifnull(pm.balance, 0) + ifnull(pz.balance, 0) + ifnull(pp.balance, 0) + ifnull(eb.balance, 0) + ifnull(bc.balance, 0) total_bal,
        u.balance user_bal,
        if(u.balance = (ifnull(st.balance, 0) + ifnull(pm.balance, 0) + ifnull(pz.balance, 0) + ifnull(pp.balance, 0) + ifnull(eb.balance, 0) + ifnull(bc.balance, 0)), 'OK', u.balance - (ifnull(st.balance, 0) + ifnull(pm.balance, 0) + ifnull(pz.balance, 0) + ifnull(pp.balance, 0) + ifnull(eb.balance, 0) + ifnull(bc.balance, 0))) status
        from users u
        left join user_payment_method pm on pm.user_id = u.id and pm.payment_code = 'pm'
        left join user_payment_method pz on pz.user_id = u.id and pz.payment_code = 'pz'
        left join user_payment_method pp on pp.user_id = u.id and pp.payment_code = 'pp'
        left join user_payment_method eb on eb.user_id = u.id and eb.payment_code = 'eb'
        left join user_payment_method bc on bc.user_id = u.id and bc.payment_code = 'bc'
        left join user_payment_method st on st.user_id = u.id and st.payment_code = 'st'
        where {$this->_where}
        order by {$this->_order} {$this->_sort_dir}
        limit $start, $perPage
        ";

        $this->_listing = $this->db->query($sql)->result_array();

        return $this;
    }

}
