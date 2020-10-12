<?php

/**
* 
*/
class changepricemodel extends CI_Model
{
	public function index($updatePrice)
	{
		$this->db->from('ad_price')
				->where('id','1')
				->where('name','adPrice')
				->set($updatePrice)
				->update();
	}
	
}

?>