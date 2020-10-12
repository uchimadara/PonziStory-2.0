<?php

/**
* 
*/
class AdminWebTasks extends CI_Controller
{
	public function index()
	{

		$this->load->model('webtaskmodel','wtmodel');

		$this->form_validation->set_rules('selectPlans','Select Plans','required');

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

				$WebTask['plandata_share_per_user'] = $plandata_price_per_share;

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
		
			$planname = $this->input->post('plan_namee');

			$newPricepershare = $this->input->post('PerSharePrice');

			$ShareUserCanBuy = $this->input->post('SharesUserCanBuy');

			$BannerAdsRewarded = $this->input->post('BannerAdsRewarded');

			$TextAdsRewarded = $this->input->post('TextAdsRewarded');

			$DailyPercentagee = $this->input->post('DailyPercentagee');

			$Max_Return_Percentage = $this->input->post('Max_Return_Percentage');

			echo $planname;

			echo $newPricepershare;

			echo $ShareUserCanBuy;

			echo $BannerAdsRewarded;

			echo $TextAdsRewarded;

			echo $DailyPercentagee;

			echo $Max_Return_Percentage;

		$this->load->view('WebTaskPlansView');
	}

}