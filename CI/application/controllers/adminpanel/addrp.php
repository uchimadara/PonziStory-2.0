<?php


/**
* 
*/
class addrp extends My_Controller
{
	public function index($huserid, $totalrp)
	{

		$amounttoadd = '';

		$this->load->model('addrpmodel','addrp');

		$this->form_validation->set_rules('username_search','Username Search','required');

		$profileInfo = $this->profile;

		$this->load->model('websitenamemodel','namemodel');

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$add_rp['username'] = $username;

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$add_rp['websitename'] = $websitenameee;

		if ($this->form_validation->run()) {
			# code...
			$usernamesearch = $this->input->post('username_search');

			$id = $this->addrp->index($usernamesearch);

			foreach ($id as $ids) {
				# code...
				$user_id = $ids->id;

				$add_rp['user_id'] = $user_id;
			}

			if (!empty($user_id)) {
				# code...
				$add_rp['username_search'] = 'found';


				$getinfoofuser = $this->addrp->getInfo($user_id);

				foreach ($getinfoofuser as $infoouser) {
					# code...
					$current_rp = $infoouser->royalty_positions;
				}

				$add_rp['current_rpos'] = $current_rp;

				$add_rp['username_rp'] = $usernamesearch;



			}
			else
			{	
				$add_rp['username_search'] = 'not_found';
			}
			
		}

		$this->load->view('add_royalty_position',['add_rp'=>$add_rp]);
	}
	public function add_royalty_pos()
	{

		$this->form_validation->set_rules('add_rp_amount','Rp Amount','required');

		$profileInfo = $this->profile;

		$this->load->model('websitenamemodel','namemodel');

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$message['username'] = $username;

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$message['websitename'] = $websitenameee;

		if ($this->form_validation->run()) {

			$username = $this->input->post('hiddenusername');

			$current_rpp = $this->input->post('hidden_rp');

			$add_rpp = $this->input->post('add_rp_amount');

			$user_id = $this->input->post('hidden_userid');

			$total_rpp = $current_rpp + $add_rpp;

			
			$this->load->model('addrpmodel','rpmod');

			$totaltoupdate = array(
				'royalty_positions'=>$total_rpp
				);

			$this->rpmod->updateRp($user_id, $totaltoupdate);

			$message['added_notif'] = 'added';

		}

		$this->load->view('add_royalty_position',['message'=>$message]);



	}

}

?>