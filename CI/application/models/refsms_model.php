<?php

class Refsms_model extends MY_Model
{


    public function __construct()
    {
        parent::__construct();
    }


    function insert($data)
    {
        $this->db->insert('refsms',$data);
        return $this->db->insert_id();
    }


    public function checkRefSMS($phone) {
        return $this->db->from('refsms')
            ->where('phone', $phone)
            ->get()->num_rows();
    }



}