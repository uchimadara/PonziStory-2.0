<?php

/**
* 
*/
class webtasks extends My_Controller
{
	public function index()
	{

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

		$tasks['websitename'] = $websitenameee;

		$tasks['username'] = $username;
		
		$this->load->model('webtaskmodel','webmodel');

		$this->form_validation->set_rules('textTitle','Title','required');

		if ($this->form_validation->run()) {

			$taskTitle = $this->input->post('textTitle');

			$taskdescription = $this->input->post('textDesp');

			$insertData = array(
				'title'=>$taskTitle,
				'description'=>$taskdescription
				);

			$this->webmodel->insertTask($insertData);

			$insertTasks['message'] = '1';

		}

		$getalltasks = $this->webmodel->GetAllTaskss();

		$tasks['alltasks'] = $getalltasks;
 
		$this->load->view('insertwebtasks',['tasks'=>$tasks]);
	}

	public function edittasks()
	{

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

		$edit['websitename'] = $websitenameee;

		$edit['username'] = $username;

		$this->form_validation->set_rules('idfortaskedit','ID','required');

		$this->load->model('webtaskmodel','webmodel');

		if ($this->form_validation->run()) {
			# code...

			$id = $this->input->post('idfortaskedit');

			$gettaskbyid = $this->webmodel->getpostbyid($id);

			foreach ($gettaskbyid as $getbytaskid) {
				# code...
				$title = $getbytaskid->title;

				$desp = $getbytaskid->description;

				$id = $getbytaskid->id;
			}

			$edit['title'] = $title;

			$edit['desp'] = $desp;

			$edit['id'] = $id;

		}

		$this->load->view('edittasks',['edit'=>$edit]);
	}
	
	public function edited()
	{
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

		$tasks['websitename'] = $websitenameee;

		$tasks['username'] = $username;

		$this->form_validation->set_rules('id','ID','required');

		$this->load->model('webtaskmodel','webmodel');

		if ($this->form_validation->run()) {
			# code...
			$id = $this->input->post('id');

			$title = $this->input->post('textTitle');

			$desp = $this->input->post('textDesp');

			$updateinfo = array(
				'title'=>$title,
				'description'=>$desp
				);

			$this->webmodel->edittask($id, $updateinfo);

		}

		$getalltasks = $this->webmodel->GetAllTaskss();

		$tasks['alltasks'] = $getalltasks;
 
		$this->load->view('insertwebtasks',['tasks'=>$tasks]);
	}

	public function WebTasksForApproval()
	{
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

		$approval['websitename'] = $websitenameee;

		$approval['username'] = $username;

		
		$this->load->model('webtaskmodel','webmodel');
		
		$approvaldata = $this->webmodel->getWebtasksforapproval();

		$approval['alldata'] = $approvaldata;

		$getbronzedailypercentage = $this->webmodel->GetBronzeSharePrice();

		foreach ($getbronzedailypercentage as $bronzedetails) {
			# code...
			$daily_percentage_bronze = $bronzedetails->daily_percentage;

			$price_of_bronze = $bronzedetails->price_per_share;
		}

		$getsilverdailypercentage = $this->webmodel->GetSilverSharePrice();

		foreach ($getsilverdailypercentage as $silverdetails) {
			# code...
			$daily_percentage_silver = $silverdetails->daily_percentage;

			$price_of_silver = $silverdetails->price_per_share;
		}

		$getgolddailypercentage = $this->webmodel->GetGoldSharePrice();

		foreach ($getgolddailypercentage as $goldetails) {
			# code...
			$daily_percentage_gold = $goldetails->daily_percentage;

			$price_of_gold = $goldetails->price_per_share;
		}


		$this->form_validation->set_rules('user_id','User id','required');

		if ($this->form_validation->run()) {
			# code...

			$user_id = $this->input->post('user_id');

			$reject = $this->input->post('reject');

			$approve = $this->input->post('approval');

			$id = $this->input->post('id');

			$user_name_task = $this->input->post('username');


			$emailtask = $this->webmodel->getEmail($username);

			foreach ($emailtask as $task) {
			
				$emaill = $task->email;
			}

			echo "Email".$emaill;
			exit;	

			if ($approve === 'approve') {
				# code...

				$updateStatus = array(
					'status'=>'Approved'
					);
				
				$this->webmodel->updateStatusOnTaskApproval($id, $updateStatus);

				$getMaxPercentageOfSilver = $this->webmodel->GetMaxPercentageSilver($user_name_task);

				foreach ($getMaxPercentageOfSilver as $silvermaxperr) {
					# code...

					$idtoupdate = $silvermaxperr->id;

					$max_percentage_silver_user = $silvermaxperr->max_percentage;

					$total_percentage = $max_percentage_silver_user + $daily_percentage_silver;

					$total_percentage_to_update = array(
						'max_percentage'=>$total_percentage
						);

					$this->webmodel->updatemaxpercentage($idtoupdate, $total_percentage_to_update);


					$getAmountBalance = $this->webmodel->getBalanceDetails($user_id);

					foreach ($getAmountBalance as $amount) {
						# code...
						$amountbalance = $amount->amount_balance;
					}


					$amounttoupdate = ($price_of_silver / $daily_percentage_silver) * 100;


					$amounttoupdatetodatabase = $amountbalance + $amounttoupdate;

					$amounttoupdatetodatabasee = array(
						'amount_balance'=>$amounttoupdatetodatabase
						);

					$this->webmodel->updateAmount($user_id, $amounttoupdatetodatabasee);


				}

				$getMaxPercentageOfBronze = $this->webmodel->GetMaxPercentageBronze($user_name_task);

				foreach ($getMaxPercentageOfBronze as $bronzemaxperr) {
					# code...


					$idtoupdate = $bronzemaxperr->id;

					$max_percentage_bronze_user = $bronzemaxperr->max_percentage;

					$total_percentage = $max_percentage_bronze_user + $daily_percentage_bronze;

					$total_percentage_to_update = array(
						'max_percentage'=>$total_percentage
						);

					$this->webmodel->updatemaxpercentage($idtoupdate, $total_percentage_to_update);

					$getAmountBalance = $this->webmodel->getBalanceDetails($user_id);

					foreach ($getAmountBalance as $amount) {
						# code...
						$amountbalance = $amount->amount_balance;
					}

					$amounttoupdate = ($price_of_bronze / $daily_percentage_bronze) * 100;

					$amounttoupdatetodatabase = $amountbalance + $amounttoupdate;

					$amounttoupdatetodatabasee = array(
						'amount_balance'=>$amounttoupdatetodatabase
						);

					$this->webmodel->updateAmount($user_id, $amounttoupdatetodatabasee);

				}

				$getMaxPercentageOfGold = $this->webmodel->GetMaxPercentageGold($user_name_task);

				foreach ($getMaxPercentageOfGold as $goldmax) {
					# code...


					$idtoupdate = $goldmax->id;

					$max_percentage_gold_user = $goldmax->max_percentage;

					$total_percentage = $max_percentage_gold_user + $daily_percentage_gold;

					$total_percentage_to_update = array(
						'max_percentage'=>$total_percentage
						);

					$this->webmodel->updatemaxpercentage($idtoupdate, $total_percentage_to_update);

					$getAmountBalance = $this->webmodel->getBalanceDetails($user_id);

					foreach ($getAmountBalance as $amount) {
						# code...
						$amountbalance = $amount->amount_balance;
					}

					$amounttoupdate = ($price_of_gold /  $daily_percentage_gold ) * 100;

					$amounttoupdatetodatabase = $amountbalance + $amounttoupdate;

					$amounttoupdatetodatabasee = array(
						'amount_balance'=>$amounttoupdatetodatabase
						);

					$this->webmodel->updateAmount($user_id, $amounttoupdatetodatabasee);


				}

				



				#redirect('/adminpanel/webtasks/WebTasksForApproval');



			}
			else
			{

				$updateStatus = array(
					'status'=>'Rejected'
					);
				
				$this->webmodel->updateStatusOnTaskApproval($id, $updateStatus);

			}

		}

		$this->load->view('WebTaskApprovals',['approval'=>$approval]);


	}

}

?>