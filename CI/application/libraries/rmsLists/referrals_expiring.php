<?php

/* * ***********************
 * RMS_LIST EXTENDER
 *
 */

class Referrals_expiring extends RMSList {

    function getPartial($page, $perPage) {

        $this->_page = $page;
        $this->_per_page = $perPage;
        $start = ($page - 1) * $perPage;
        $userId = $this->_ci->input->get('user_id');


        $this->_listing = $this->db->query("
        select ref.id, ifnull(nullif(concat_ws(' ', ref.first_name, ref.last_name), ' '), ref.username) member, ref.email, ref.phone, e.expires, r.level, p.price from
        referrals r
        join users u on u.id = r.user_id
        join users ref on ref.id = r.referee_id
        join expiration e on e.user_id = r.referee_id
        join purchase_item p on p.id = e.upgrade_Id
        where r.user_id = $userId
        and r.level = p.code
        order by  {$this->_order} {$this->_sort_dir}
        LIMIT $start, $perPage ")->result();

        $this->_total = count($this->_listing);
        return $this;
    }

}
