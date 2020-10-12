<?php


/**
* 
*/
class WebsiteNameModel extends MY_Model
{
	public function websitename()
	{
		$data = $this->db->select('value')
				->from('settings')
				->where('label','Website Name')
				->get();

				return $data->result();
	}
}

?>