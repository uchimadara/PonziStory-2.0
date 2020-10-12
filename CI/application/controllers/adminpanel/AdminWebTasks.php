<?php

/**
* 
*/
class AdminWebTasks extends My_Controller
{
	public function index()
	{

		$this->load->model('webtaskmodel','wtmodel');

		$this->form_validation->set_rules('selectPlans','Select Plans','required');

		$profileInfo = $this->profile;

		$this->load->model('websitenamemodel','namemodel');

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$WebTask['username'] = $username;

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$WebTask['websitename'] = $websitenameee;

		if ($this->form_validation->run()) {
			# code...

			$plans = $this->input->post('selectPlans');

			if ($plans === 'Select Plans To See Details') {
			# code...
			$WebTask['message'] = '1';

			}
			else{


				$WebTask['message'] = '2';

				$plandata = $this->wtmodel->plan_details($plans);

				foreach ($plandata as $pd) {
					# code...
					$plandata_id = $pd->id;

					$plandata_name = $pd->plan_name;

					$plandata_price_per_share = $pd->price_per_share;

					$plandata_share_per_user = $pd->share_per_user;

					$plandata_banner_ads = $pd->banner_ads;

					$plandata_text_ads = $pd->text_ads;

					$plandata_daily_percentage = $pd->daily_percentage;

					$plandata_max_return = $pd->max_return;
				}

				$WebTask['plandata_id'] = $plandata_id;

				$WebTask['plandata_name'] = $plandata_name;

				$WebTask['plandata_price_per_share'] = $plandata_price_per_share;

				$WebTask['plandata_share_per_user'] = $plandata_share_per_user;

				$WebTask['plandata_banner_ads'] = $plandata_banner_ads;

				$WebTask['plandata_text_ads'] = $plandata_text_ads;

				$WebTask['plandata_daily_percentage'] = $plandata_daily_percentage;

				$WebTask['plandata_max_return'] = $plandata_max_return;

			}

		}

		$this->load->view('WebTaskPlansView',['WebTask'=>$WebTask]);
	}

	public function EditPlans()
	{

		$this->form_validation->set_rules('planname','Plan name','required');

		$profileInfo = $this->profile;

		$this->load->model('websitenamemodel','namemodel');

		foreach ($profileInfo as $profileinformation) {
			# code...

			$username = $profileInfo->username;

			$userid = $profileInfo->id;

			$email = $profileInfo->email;

		}

		$EditPlans['username'] = $username;

		$websitename = $this->namemodel->websitename();

		foreach ($websitename as $webname) {
			# code...
			$websitenameee = $webname->value;
		}

		$EditPlans['websitename'] = $websitenameee;

		if ($this->form_validation->run()) {
			# code...
			$plan_name = $this->input->post('planname');
			$per_price_share_price = $this->input->post('price_per_share_show');
			$Share_Per_User_Can_Buy = $this->input->post('Share_Per_User_Can_Buy');
			$Banner_adss = $this->input->post('Banner_Ads');
			$Text_Adss = $this->input->post('Text_Ads');
			$DailyPercentage = $this->input->post('Daily_Percentagee');
			$Max_Return_Percentage = $this->input->post('Max_Return_Percentage');
			
			$EditPlans['planname'] = $plan_name;
			
			$EditPlans['price_per_sharee'] = $per_price_share_price;

			$EditPlans['Share_Per_User_Can_Buy'] = $Share_Per_User_Can_Buy;

			$EditPlans['banner_ads'] = $Banner_adss;

			$EditPlans['Text_Ads'] = $Text_Adss;

			$EditPlans['Daily_Prctnge'] = $DailyPercentage;

			$EditPlans['Max_Return_Percentage'] = $Max_Return_Percentage;


		}



		$this->load->view('WebTaskPlansView',['EditPlans'=>$EditPlans]);
	}
	public function EditDetailss()
	{
		$this->form_validation->set_rules('plan_namee','Plan name','required');

		$this->load->model('webtaskmodel','wtmodel');

		if($this->form_validation->run())
		{
			$planname = $this->input->post('plan_namee');

			$newPricepershare = $this->input->post('PerSharePrice');

			$ShareUserCanBuy = $this->input->post('SharesUserCanBuy');

			$BannerAdsRewarded = $this->input->post('BannerAdsRewarded');

			$TextAdsRewarded = $this->input->post('TextAdsRewarded');

			$DailyPercentagee = $this->input->post('DailyPercentagee');

			$Max_Return_Percentage = $this->input->post('Max_Return_Percentage');

			

			$EditInfo = array(
				'price_per_share'=>$newPricepershare,
				'share_per_user'=>$ShareUserCanBuy,
				'banner_ads'=>$BannerAdsRewarded,
				'text_ads'=>$TextAdsRewarded,
				'daily_percentage'=>$DailyPercentagee,
				'max_return'=>$Max_Return_Percentage
				);

			$this->wtmodel->EditDetails($planname, $EditInfo);


		}

		$this->load->view('WebTaskPlansView');
	}

}