<?php

/**
* 
*/
class changeadprice extends MY_Controller
{
	public function index()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('changeprice','Change Price','required');

		$this->load->model('changepricemodel','changeprice');

		$this->load->model('websitenamemodel','namemodel');

		$profileInfo = $this->profile;

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$prize['username'] = $username;

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$prize['websitename'] = $websitenameee;

		if($this->form_validation->run()){

			$newPrice = $this->input->post('changeprice');

			$newadpackprice = array(
				'amount'=>$newPrice
				);

			$this->changeprice->index($newadpackprice);
		}




		$this->load->model('buyerandsellermodel','prizemodel');

		$prizeofonepack = $this->prizemodel->getadpackprice();

		foreach ($prizeofonepack as $prizee) {
			# code...
			$prize['price'] = $prizee->amount;
		}
		
		$this->load->view('changepriceview',['prize'=>$prize]);
	}
}

?>