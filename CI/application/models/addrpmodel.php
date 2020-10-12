<?php

/**
* 
*/
class addrpmodel extends CI_Model
{
	public function index($username)
	{
		$data = $this->db->select('id')
				->from('users')
				->where('username',$username)
				->get();

				return $data->result();
	}
	public function getInfo($user_id)
	{
		$data = $this->db->select('royalty_positions')
				->from('user_payment_method')
				->where('user_id', $user_id)
				->get();

				return $data->result();
	}
	public function updateRp($userid, $updaterp)
	{
		$this->db->from('user_payment_method')
		->where('user_id',$userid)
		->set($updaterp)
		->update();
	}
}

?>