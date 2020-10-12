<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getRewards($accountLevel) {
        return $this->db->from('product')
                ->where('purchase_item_code <=', $accountLevel)
                ->where('enabled', 1)
                ->where('deleted', 0)
                ->get()->result();
    }

    public function get($id) {
        return $this->db->from('product')
                ->where('id', $id)
                ->where('enabled', 1)
                ->where('deleted', 0)
                ->get()->row();
    }
}