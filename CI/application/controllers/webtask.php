<?php

/**
* 
*/
class webtask extends MY_CONTROLLER
{
	public function index()
	{
		$this->load->model('webtaskmodel','webtask');

		$profileInfo = $this->profile;

		$showtaskbutton = false;

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$this->load->model('WebsiteNameModel','namemodel');

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$webtasks['websitename'] = $websitenameee;

		$webtasks['username'] = $username;


		$UserBalanceDetails = $this->webtask->getBalanceDetails($userid);

		foreach ($UserBalanceDetails as $baldetails) {
			# code...\

			$userbalancedetailss = $baldetails->amount_balance;

			$BronzeShares = $baldetails->bronze_shares;

			$SilverShares = $baldetails->silver_shares;

			$GoldShares = $baldetails->gold_shares;
		}

		$webtasks['amount_balance'] = $userbalancedetailss;

		$webtasksss = $this->webtask->getWebTasks();

		$webtasks['admin_webtasks'] = $webtasksss;

		$checkpendingtasks = $this->webtask->getlistoftaskss($username);

		$CurrentDateAndTime = date('Y-m-d H:i:s');

		$webtasks['CurrentDateAndTime'] = $CurrentDateAndTime;

		foreach ($checkpendingtasks as $oldtaskss) {
			# code...
			$timeoflasttask = $oldtaskss->dateandtime;

			$taskstatus = $oldtaskss->status;	
		}

		$webtasks['timeoftaskinserted'] = $timeoflasttask;

		$newtime = strtotime($timeoflasttask) + (24 * 60 * 60);

		$newtimee = date('Y-m-d H:i:s', $newtime);

		$webtasks['timetoendtask'] = $newtimee;

		


		if (($newtimee < $CurrentDateAndTime) || ($taskstatus === 'Rejected')) {
			# code...
			$webtasks['timepending'] = '2';
		}
		else
		{
			$webtasks['timepending'] = '1';
		}
		



		$this->load->view('webtasks_clientview',['webtasks'=>$webtasks]);
	}

	public function description()
	{

		$this->load->model('webtaskmodel','webtask');

		$profileInfo = $this->profile;

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$this->load->model('WebsiteNameModel','namemodel');

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$webtask_desp['websitename'] = $websitenameee;

		$webtask_desp['username'] = $username;


		$UserBalanceDetails = $this->webtask->getBalanceDetails($userid);

		foreach ($UserBalanceDetails as $baldetails) {
			# code...\

			$userbalancedetailss = $baldetails->amount_balance;

			$BronzeShares = $baldetails->bronze_shares;

			$SilverShares = $baldetails->silver_shares;

			$GoldShares = $baldetails->gold_shares;
		}

		$webtask_desp['amount_balance'] = $userbalancedetailss;

		$this->form_validation->set_rules('idfortask','Id','required');

		if ($this->form_validation->run()) {
			# code...

			$idfortask = $this->input->post('idfortask');

			$details = $this->webtask->getDetailsForTasks($idfortask);

			foreach ($details as $detail) {
				# code...
				$idfortask = $detail->id;

				$title = $detail->title;

				$description = $detail->description;
			}

			$webtask_desp['id'] = $idfortask;

			$webtask_desp['title'] = $title;

			$webtask_desp['desp'] = $description;

		}



		$this->load->view('webtask_description',['webtask_desp'=>$webtask_desp]);



	}


	public function proof_submission()
	{

		$this->load->model('webtaskmodel','webtask');

		$profileInfo = $this->profile;

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$this->load->model('WebsiteNameModel','namemodel');

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$description['websitename'] = $websitenameee;

		$UserBalanceDetails = $this->webtask->getBalanceDetails($userid);

		foreach ($UserBalanceDetails as $baldetails) {
			# code...\

			$userbalancedetailss = $baldetails->amount_balance;

			$BronzeShares = $baldetails->bronze_shares;

			$SilverShares = $baldetails->silver_shares;

			$GoldShares = $baldetails->gold_shares;
		}

		$description['username'] = $username;

		$description['amount_balance'] = $userbalancedetailss;

		$this->form_validation->set_rules('id','ID','required');

		if ($this->form_validation->run()) {
			# code...
			$idfortask = $this->input->post('id');

			$titlefortask = $this->input->post('title');

			$urlforsubmission = $this->input->post('urlforsubmission');

			$dateandtime = date("Y-m-d H:i:s");

			$insertDetails = array(
				'user_name'=>$username,
				'user_id'=>$userid,
				'task_id'=>$idfortask,
				'task_title'=>$titlefortask,
				'url_for_proof'=>$urlforsubmission,
				'dateandtime'=>$dateandtime,
				'status'=>'pending'
				);

			$description['confirmation_msg'] = '1';

			$this->webtask->insertforapproval($insertDetails);

		}


		$this->load->view('webtask_description',['description'=>$description]);

	}


}



 ?>