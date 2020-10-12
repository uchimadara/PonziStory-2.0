<?php

/**
* 
*/
class changeadprice extends CI_Controller
{
	public function index()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('changeprice','Change Price','required');

		$this->load->model('changepricemodel','changeprice');

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
			$prize = $prizee->amount;
		}
		
		$this->load->view('changepriceview',['prize'=>$prize]);
	}
}

?>