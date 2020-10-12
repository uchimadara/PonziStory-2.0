<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Member extends MY_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->isGuest) {
            if ($this->ajax) {
                show_error("Please ".anchor(site_url('login'), 'log in')." for access.");
                exit(0);
            } else {
                redirect();
            }
        }

        $this->output->enable_profiler(PROFILER_SETTING);

        $this->load->model('payment_method_model', 'PaymentMethod');
        $this->load->library('AccountObject');

    }

    public function index() {
        $this->bootstrap();
    }

    /********
     * @name bootstrap
     * @param string $page
     *
     * @puropse main entry point for back office pages
     */

    public function bootstrap($page = 'dashboard') {

        if (!method_exists($this, $page)) {
            $page = 'dashboard';
        }

        $this->data->page_title = $page;

        $html = $this->$page();

        if ($this->ajax) {
            $state = array(
                'html'      => $html,
                'pageTitle' => $this->data->page_title,
                'url'       => SITE_ADDRESS.'back_office/'.$page
            );
            echo json_encode($state);
            exit(0);
        }

        $this->load->model('news_model', 'News');
        if (list($id, $title, $slug, $content) = $this->News->isUnread($this->userId)) {
            $this->News->mark_read($id, $this->userId);
            $this->data->newsModal = $this->loadPartialView('member/partial/news_modal', compact('id', 'title', 'slug', 'content'));
        }
        $this->data->content = & $html;
        $this->layout        = 'layout/member/shell';
        $this->loadView('layout/default', $this->data->page_title);
    }

    /**********
     *
     * DASHBOARD
     *
     */
    public function dashboard() {
        $today = date('Y-m-d');
//        $startDate = time();
//        $dd =  date('Y-m-d&H:i', strtotime('+1 day', $startDate));
//        echo urlencode($dd);

        //$this->load->library('encrypt');
        // $this->data->e_id = $this->encrypt->encode($this->profile->id);

        $this->load->model('referral_model', 'Referral');
        $this->load->model('payment_model', 'Payment');
        $this->load->model('ph_model','PhModel');
        $this->load->model('gh_model','GhModel');
        $this->load->model('testimonial_model', 'Testimonial');


        //$linkname = $_GET['u'];
        $this->data->page_title = "Member Dashboard=".$this->profile->username;

        $this->data->dueUpgrade = $this->User->dueUpgrade($this->profile->id,$this->profile->account_level);


        $this->data->fbNameCheck = $this->User->fbNameCheck($this->userId);
        $this->data->ph_exist = $this->PhModel->checkPHexist($this->profile->id);
        $this->data->CurrentPlan = $this->PhModel->getCurrentPlan($this->profile->id);
        $this->data->sysBonCount = $this->PhModel->countSysBonus($this->profile->id);
        $this->data->ph_exist2 = $this->PhModel->checkPHexistRecom($this->profile->id);
        $this->data->check4Recom = $this->PhModel->check4Recom($this->profile->id);
        //$this->data->ph_exist2 = $this->PhModel->checkPHexist2($this->profile->id);
        $this->data->ph_status= $this->PhModel->getStatus($this->profile->id);
        $this->data->ph_statusM= $this->PhModel->getStatusMulti($this->profile->id);
        $this->data->getStatusInfo= $this->PhModel->getStatusInfo($this->profile->id);
        $this->data->ph_status2= $this->PhModel->getStatus2($this->profile->id);
        $this->data->lastPH= $this->PhModel->getLastPh($this->profile->id);
        $this->data->lastRePH= $this->PhModel->getLastRePH($this->profile->id);
        $this->data->lastRecom= $this->PhModel->getLastRecom($this->profile->id);
        $this->data->lastPH2= $this->PhModel->getLastPh2($this->profile->id);
        $this->data->las= $this->PhModel->checkGhDate($this->profile->id);
        $this->data->fbl= $this->PhModel->getFirstLevelBonus($this->profile->id,5);
        $this->data->sbl= $this->PhModel->getSecondLevelBonus($this->profile->id,5);
        $this->data->gh_exist = $this->GhModel->checkGHexist($this->profile->id);
        $this->data->gh_exist2 = $this->GhModel->checkGHexist2($this->profile->id);
        $this->data->gh_collected = $this->GhModel->checkGHCollected($this->profile->id);
        $this->data->gh_status= $this->GhModel->getStatus($this->profile->id);
        $this->data->getMeMerge= $this->GhModel->getMeMerge($this->profile->id);
        $this->data->getGhRecord4Testi= $this->GhModel->getGhRecord4Testi($this->profile->id);
        $this->data->todayGhSum= $this->GhModel->getTotalMergedToday($today);
        $this->data->enc_gh_status = $this->encryptIt($this->data->getMeMerge->rem_amount);
        $this->data->payments = $this->Payment->userSummary($this->userId);
        $this->data->testi    = $this->Testimonial->role_exists($this->profile->id);
        $this->data->testiCount    = $this->Testimonial->testiCount($this->profile->id);
        $this->data->recomvalue    = $this->getRecomValue();
        $lastPh = $this->PhModel->getLastPh($this->profile->id);
        $this->data->gh_amt = $this->PH_Plan($lastPh->amount);
        $sysB = $this->PhModel->getSysBonus($this->profile->id);
        $this->data->pending = $this->Payment->getPendingMulti($this->userId);



        $this->data->bonus_sys =  $this->SysBonusPlan($sysB->amount);

//        var_dump($this->data->gh_amt);

        $this->data->reasons2 = $this->User->getData($this->userId,array('username','reason'));
        $expee = $this->profile->created_on + 172800;
        $created = $this->profile->created_on ;

        $expee = (int) $expee;

        if (defined('FREE_MEMBER_EXPIRE') && FREE_MEMBER_EXPIRE > 0 && $this->profile->account_level == '0') {
            $this->data->countdown = $this->profile->created_on + intval(intval(FREE_MEMBER_EXPIRE) * CACHE_ONE_DAY) - now();
        }

        if (now() > $expee && $this->profile->account_level == "0"){
            $this->User->update($this->userId, array('soft_hide' => 1));
        }

        $this->data->widgets = array();

        $summary = $this->Referral->summary($this->profile->id);
        $tile    = array(
            'id'    => 'memberSummary',
            'title' => 'tradermoni - REFERRAL SUMMARY FOR '.strtoupper($this->profile->username),
            'cols'  => 12,
            'size'  => 'lg',
            'body'  => $this->loadPartialView('member/referral_summary', compact('summary'))
        );

        $this->data->widgets[] = $this->loadPartialView('partial/tile', compact('tile'));

        if ($this->profile->referrer_id > 0) {

            $this->data->sponsor         = $this->User->getData($this->profile->referrer_id);
            $this->data->sponsorSettings = $this->User->getSettings($this->profile->referrer_id);
            $this->data->sponsorSocialList = $this->User->getSocialNetworks($this->profile->referrer_id);

            if (!isset($this->data->sponsorSettings->show_email)) $this->data->sponsorSettings->show_email = 1;
            if (!isset($this->data->sponsorSettings->show_phone)) $this->data->sponsorSettings->show_phone = 1;
            if (!isset($this->data->sponsorSettings->show_skype)) $this->data->sponsorSettings->show_skype = 1;
            if (!isset($this->data->sponsorSettings->show_social)) $this->data->sponsorSettings->show_social = 1;
            if (!isset($this->data->sponsorSettings->show_avatar)) $this->data->sponsorSettings->show_avatar = 1;
        }
        // $this->load->model('my_account_model', 'Account');

        $this->data->memberships = $this->Account->getMemberships();


        $this->data->expires = $this->Payment->getExpires($this->userId);
        //var_dump($this->data->expires);





        $this->data->wallet = $this->Payment->getWallet($this->userId);
        $wallet = $this->data->wallet;

        if(empty($wallet)) {
            $this->session->set_flashdata('error', 'You are yet to enter your Bank details');
            redirect('back_office/accounts');
        }

        foreach ($this->data->memberships as $code => $pkg) {
            if (isset($this->data->expires[$code])) {
                $this->data->expires[$code]->lastPaid = $this->Payment->getUpgradeDate($this->userId, $code);
            }
        }
        $this->addJavascript('/layout/member/assets/js/jquery.plugin.min.js');
        $this->addJavascript('/layout/member/assets/js/jquery.countdown.min.js');
        $this->addStylesheet('/layout/member/assets/css/jquery.countdown.css');

        $this->load->model('payment_model', 'Payment');
        $this->load->model('teams_model', 'Teams');
        // $this->load->model('user_model', 'User');
        $this->data->dataa = $this->Payment->awaitingConfirmation2($this->profile->id);
        $this->data->punishment = $this->Payment->awaitingConfirmationSingle2($this->profile->id);
        $this->data->punishment2 = $this->Payment->awaitingConfirmationSingle3($this->profile->id);
        $this->data->punishment3 = $this->Payment->awaitingConfirmationSingle4($this->profile->id);
        $this->data->members    = $this->User->countMembers();
        $this->data->member_level    = $this->profile->account_level;
        $this->data->teamlegi    = $this->Teams->role_exists($this->profile->username);



        // var_dump($curr_payement);
        //  $this->data->dataa =  $this->Payment->awaitingConfirmation($this->profile->id);
        // $payee_id = $this->Payment->getCurrentPayee($this->profile->id);
        $rd = $this->data->dataa;
        //  var_dump($rd);
//        foreach ($rd as $dd){
//            $var = (array)$dd->payer_user_id;
//
//            $this->data->usee =  $this->User->getBunch($var);
//         //  var_dump(array_chunk($this->data->usee,2));
//        //   "<pre>" . print_r($this->data->usee) . "</pre>";
//        }

        $payee_id = $this->Payment->awaitingConfirmationSingle($this->profile->id);

        $intid = (int)$payee_id->expired;
        //var_dump($payee_id);
        if (now() >$intid){
            $data = array(
                'rejected' => '1',
                'reason' => 'Time Up'
            );
            //  $mm = $this->Payment->updateTimeUp2($payee_id->payee_user_id, $data);
            //$this->User->update($payee_id->payee_user_id,array('locked'=>'1'));

            //$this->User->update($payee_id->payer_user_id,array('locked'=>'1'));

        }
        if ($mm) {

            $this->data->reason = $this->Payment->getReason($this->profile->id);
            //  var_dump($this->data->reason);
        }

        foreach ($this->data->dataa as $dat){
            // $data['payee_user_id'] = $dat['payee_user_id'];
            $this->data->dis = $this->User->getData($dat->payee_user_id,array('username','password'));
            $this->data->dat = $dat;


        }

        return $this->loadPartialView('member/dashboard');
    }

    /**********
     *
     * PROFILE
     *
     */


    private function encryptIt( $q ) {
        $cryptKey  = 'qJB0rGtIn5@$#%^#E&';
        $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
        return( $qEncoded );
    }

     private function decryptIt( $q ) {
        $cryptKey  = 'qJB0rGtIn5@$#%^#E&';
        $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
        return( $qDecoded );
    }

    function mergeme(){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('ph_model','PhModel');
        $this->load->model('gh_model','GhModel');
        $expired = now() + 108000; // plus 30 hours

       $m =  $this->input->post('idee');
       $gid =  $this->input->post('pid');
       $mid =  $this->input->post('mid');
        $p =$this->decryptIt($m);
       // var_dump($m." = ".$p);

        $phd = $this->PhModel->getPh4MergeSingle2($p);
        var_dump($phd);
        $phamt = (int)$phd->rem_amount;
        $ghamt = (int)$p;
        $puid = (int)$phd->user_id;

        if(empty($phd)){
            exit("No Investment of Smaller Amount yet. Try Again Later");

        }

        if ($ghamt < $phamt){
            //var_dump($_POST);
            exit("GH amount is less than PH amount");
        }
        if ($this->profile->id == $puid){
            exit("USer cannot be merged to himself");
        }
        $pch = $this->Payment->checkPaymentExist($puid,$this->profile->id,NULL,NULL);
        if($pch > 0 ){
            echo "Record Already Existed";
        }

//        $phValues =  $this->PhModel->getZaPh($puid);
//        $ghValues =  $this->GhModel->getZaGh($guid);
        // var_dump($ghValues->rem_amount);
        $gha = (int)$p; //GH rem_amount
        $ghId = $gid; //gh_id
        $pha = $phamt; //PH rem_amount
        $phId = $phd->id; // ph_id
        //var_dump($gha);
        if ($gha > $pha) {
            $rem = $gha - $pha;
            $amt = $pha;
        }elseif ($gha == $pha){
            $rem = 0;
            $amt = $pha;
        }
        else{
            $rem = 1;
            //meaning there is an error here
        }

        $pId = $this->Payment->add($puid,$this->profile->id,$amt,now(),$expired,0,0,NULL,NULL,NULL,1,$mid,$phId,$ghId);

        if($pId) {


            if ($rem > 0){
                //update ph record to merged
                if(empty(!$phId)){
                    $this->PhModel->updatePHmerge($phId, array(
                        'status' => 2,
                        'rem_amount' => 0
                    )); }
                //update GH record rem_amount
                $this->GhModel->updateGHmerge($ghId, array(
                    'rem_amount' => $rem
                ));
            }
            elseif ($rem == 0){
                //update the ph record to merged
                if(empty(!$phId)){
                    $this->PhModel->updatePHmerge($phId, array(
                        'status' => 2,
                        'rem_amount' => 0
                    )); }

                // Update the GH record status & amount
                $this->GhModel->updateGHmerge($ghId, array(
                    'rem_amount' => $rem,
                    'status' => 2
                ));
            }
            else{
                //update the ph record to merged
                if(empty(!$phId)){
                    $this->PhModel->updatePHmerge($phId, array(
                        'status' => 2,
                        'rem_amount' => 0
                    ));  }
                // Update the GH record status to error and amount
                $this->GhModel->updateGHmerge($ghId, array(
                    'rem_amount' => $rem,
                    'status' => 5
                ));
            }
            $payment = $this->Payment->getFull($pId);
            // $payee = "payee";
            // $payer = "payer";
            $phones = $payment->payer_phone.",".$payment->payee_phone;
            //$this->mergesms($payment->payer_phone,$payment->payer);
            //$this->mergesms($payment->payee_phone,$payment->payee);
            // $this->load->model('email_model', 'EmailQueue');
            //  $this->EmailQueue->store($payment->payee_email, '['.SITE_NAME.'] Merge Order', 'emails/cashier/payment_merge_payee', compact('payee', 'payment'),1);
            //  $this->EmailQueue->store($payment->payer_email, '['.SITE_NAME.'] Merge Order', 'emails/cashier/payment_merge_payer', compact('payer', 'payment'),1);

            $this->session->set_flashdata('success', 'Successfully Merged.');
            redirect('/back_office/dashboard');

        }
        else{
            echo "Payment error";
            $this->session->set_flashdata('error', 'AN Error Occurred.');
            redirect('/back_office/dashboard');
        }


    }

    function fbUpdate(){

        $this->form_validation->set_rules('fbname', 'Facebook Name', 'trim|required|xss_clean');


        if ($this->form_validation->run() == TRUE) {
        }
        $fbname = $this->input->post("fbname");



        $ee = $this->User->fbUpdate($this->profile->id,$fbname);

        if ($ee){
            $this->session->set_flashdata('success', 'Facebook name saved Successfully');
            redirect('back_office/dashboard');

        }
        else{
            $this->session->set_flashdata('error', 'Input the Correct Detail');
            redirect('back_office/dashboard');
        }

    }


    private function sendsms($phone,$payerUsername,$payeeUsername,$amount){
        $message =   urlencode("tradermoni>>>>Credit Alert. Amt:NGN".$amount."\n"." Dear ".$payeeUsername.",You have received a donation from ".$payerUsername." on tradermoni.\"\n\" Kindly Verify and Confirm.");

        $jj = "https://smartsmssolutions.com/api/?message=".$message."&to=".$phone."&sender=tradermoni&type=0&routing=3&token=";
        // $kk =   "http://www.50kobo.com/tools/geturl/Sms.php?username=isaacmomoh2000@gmail.com&password=big50kobo&sender=tradermoni&message=".$message."&recipients=".$phone."";
        $m =  file_get_contents($jj);
        $s =  json_decode($m);
        return $s;
    }
    private function sendsms2($phone,$payerUsername,$payeeUsername,$amount){
        $message =   urlencode("MISSED DONATION.%0A Dear ".$payeeUsername.",You have Missed a donation of NGN" .money($amount)." from ".$payerUsername." on tradermoni bcos you did not upgrade.");

        $kk =   "http://www.50kobo.com/tools/geturl/Sms.php?username=isaacmomoh2000@gmail.com&password=big50kobo&sender=tradermoni&message=".$message."&recipients=".$phone."";
        $m =  file_get_contents($kk);
        $s =  json_decode($m);
        return $s;
    }

    private function sendRefSms($phone,$message){
        $message =   urlencode($message);

        $jj = "https://smartsmssolutions.com/api/?message=".$message."&to=".$phone."&sender=tradermoni&type=0&routing=3&token=";
        // $kk =   "http://www.50kobo.com/tools/geturl/Sms.php?username=isaacmomoh2000@gmail.com&password=big50kobo&sender=tradermoni&message=".$message."&recipients=".$phone."";
        $m =  file_get_contents($jj);
        $s =  json_decode($m);
        return $s;
    }


    public function refSms(){
        $this->load->model('refsms_model', 'RefSms');

        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');


        if ($this->form_validation->run() == TRUE) {

            //return true;
            $data = $this->input->post();
            $phone = $this->input->post("phone");

            $f = $this->RefSms->checkRefSMS($phone);
            if ($f > 1){
                $this->session->set_flashdata('error', 'This phone number have been sent an SMS before');
                redirect('back_office/referrals');
            }
            //$message = $this->input->post('message')
            // var_dump($data);
            $ee = $this->RefSms->insert(array("user_id"=>$this->profile->id,
                "phone"=>$this->input->post('phone'),
                "message"=>$this->input->post('message'),
                "date"=> date('Y-m-d')
            ));

            if ($ee){
                $this->sendRefSms($this->input->post('phone'),$this->input->post('message'));
                $this->session->set_flashdata('success', 'Message Successfully Sent.');
                redirect('back_office/referrals');

            }

        }
    }



    public function insertPH(){
        $this->load->model('ph_model','PhModel');

        $exist = $this->PhModel->checkPHexist($this->profile->id);

        if($exist > 0 ){
            $this->session->set_flashdata('error', 'You already have an existing investment');
            redirect('back_office/dashboard');
        }

        if(empty($this->input->post('optradio')) ){
            $this->session->set_flashdata('error', 'You Must Select An investment');
            redirect('back_office/dashboard');
        }

        if($this->input->post('submit') && (!empty($this->input->post('optradio')))){

            $today = date('Y-m-d');
//            if($this->input->post('optradio') == "10000") {
//                $twoWeeks = date('Y-m-d', strtotime($today . ' + 7 days'));
//            }else{
//                $twoWeeks = date('Y-m-d', strtotime($today . ' + 10 days'));
//
//            }
            $twoWeeks = date('Y-m-d', strtotime($today . ' + 10 days'));


            $this->form_validation->set_rules('optradio', 'Amount', 'trim|required|xss_clean');


            if ($this->form_validation->run() == TRUE) {

                $refd = $this->User->getRefsRef($this->profile->referrer_id);
                $usern = $this->User->getUsername($this->profile->referrer_id);
                $usern2 = $this->User->getUsername($refd->referrer_id);
                $data = array(
                    "user_id" => $this->profile->id,
                    "first_bonus_id" => $this->profile->referrer_id,
                    "first_bonus_username" => $usern->username,
                    "first_bonus_status" => "1",
                    "second_bonus_id" => $refd->referrer_id,
                    "second_bonus_username" => $usern2->username,
                    "second_bonus_status" => "1",
                    "username" => $this->profile->username,
                    "amount" => $this->input->post('optradio'),
                    "rem_amount" => $this->input->post('optradio'),
                    "date_of_ph" => date('Y-m-d'),
                    "date_of_gh" => $twoWeeks,
                    "status" => "1"
                );
                $this->PhModel->create($data);
                $this->session->set_flashdata('success', 'Successfully Submitted.');
                redirect('back_office/');

            }else {
                $this->session->set_flashdata('error', 'Validation error: Select one of the Options');
                redirect('back_office/dashboard');
            }
        }
    }

    public function ReinsertPH(){
        $this->load->model('ph_model','PhModel');

        $exist = $this->PhModel->checkPHexistRecom($this->profile->id);

        if($exist > 0 ){
            $this->session->set_flashdata('error', 'You already have an existing Recommitment');
            redirect('back_office/dashboard');
        }

        if($this->input->post('submit')){
            $today = date('Y-m-d');

            $twoWeeks = date('Y-m-d', strtotime($today . ' + 10 days'));


            $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');


            if ($this->form_validation->run() == TRUE) {

                $refd = $this->User->getRefsRef($this->profile->referrer_id);
                $usern = $this->User->getUsername($this->profile->referrer_id);
                $usern2 = $this->User->getUsername($refd->referrer_id);
                $data = array(
                    "user_id" => $this->profile->id,
                    "first_bonus_id" => $this->profile->referrer_id,
                    "first_bonus_username" => $usern->username,
                    "first_bonus_status" => "1",
                    "second_bonus_id" => $refd->referrer_id,
                    "second_bonus_username" => $usern2->username,
                    "second_bonus_status" => "1",
                    "username" => $this->profile->username,
                    "amount" => $this->input->post('amount'),
                    "rem_amount" => $this->input->post('amount'),
                    "date_of_ph" => date('Y-m-d'),
                    "date_of_gh" => $twoWeeks,
                    "status" => "1",
                    "re_ph" => "1",

                );
                $this->PhModel->create($data);
                $this->session->set_flashdata('success', 'Successfully Submitted.');
                redirect('back_office/');

            }else {
                $this->session->set_flashdata('error', 'Validation error: Select one of the Options');
                redirect('back_office/dashboard');
            }
        }

    }



    public function getRecomValue(){
        $this->load->model('ph_model','PhModel');
        $plan = $this->PhModel->getPlan2($this->profile->id);
        $amt = (int)$plan->amount;
        $plan = (int)$plan->plan_id;
        if ($plan == 1){
            return 2500;
        }
        else{
            return 5000;
        }
    }

    public function insertRecom(){
        $this->load->model('ph_model','PhModel');

        $exist = $this->PhModel->checkPHexistRecom($this->profile->id);

        if($exist > 0 ){
            $this->session->set_flashdata('error', 'You already have an existing Recommitment');
            redirect('back_office/dashboard');
        }

        if($this->input->post('submit')){
            $today = date('Y-m-d');

            $twoWeeks = date('Y-m-d', strtotime($today . ' + 14 days'));


            $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');


            if ($this->form_validation->run() == TRUE) {

                $refd = $this->User->getRefsRef($this->profile->referrer_id);
                $usern = $this->User->getUsername($this->profile->referrer_id);
                $usern2 = $this->User->getUsername($refd->referrer_id);
                $data = array(
                    "user_id" => $this->profile->id,
                    "first_bonus_id" => $this->profile->referrer_id,
                    "first_bonus_username" => $usern->username,
                    "first_bonus_status" => "1",
                    "second_bonus_id" => $refd->referrer_id,
                    "second_bonus_username" => $usern2->username,
                    "second_bonus_status" => "1",
                    "username" => $this->profile->username,
                    "amount" => $this->input->post('amount'),
                    "rem_amount" => $this->input->post('amount'),
                    "date_of_ph" => date('Y-m-d'),
                    "date_of_gh" => $twoWeeks,
                    "status" => "1",
                    "Recom" => "1"
                );
                $this->PhModel->create($data);
                $this->session->set_flashdata('success', 'Successfully Submitted.');
                redirect('back_office/');

            }else {
                $this->session->set_flashdata('error', 'Validation error: Select one of the Options');
                redirect('back_office/dashboard');
            }
        }

    }

    public function insertGH(){
        $this->load->model('gh_model','GhModel');
        $this->load->model('ph_model','PhModel');
        $this->load->model('payment_model','Payment');

        $payeeWallet = $this->Payment->getWallet($this->profile->id);

        $exist = $this->GhModel->checkGHexist($this->profile->id);
        $lastPh = $this->PhModel->getLastPh($this->profile->id);
        $lastPh2 = $this->PhModel->getLastPh2($this->profile->id);

        if($exist > 0 ){
            $this->session->set_flashdata('error', 'You already have an existing investment');
            redirect('back_office/dashboard');
        }
        if(!$lastPh){
            $this->session->set_flashdata('error', 'You are not qualified to Withdraw Yet');
            redirect('back_office/dashboard');
        }

        if($this->input->post('submit') && (!empty($this->input->post('amount')))){

            $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');

            if ($this->form_validation->run() == TRUE) {

                $data = array(
                    "user_id" => $this->profile->id,
                    "username" => $this->profile->username,
//                    "amount" => $lastPh->amount * 1.5,
//                    "rem_amount" => $lastPh->amount * 1.5,
                    "amount" => $this->PH_Plan($lastPh->amount),
                    "rem_amount" => $this->PH_Plan($lastPh->amount),
                    "type" => "GH",
                    "method_id" => $payeeWallet->id,
                    "status" => "1",
                    "date_added" => date('Y-m-d'),
                    "date_of_gh" => $lastPh->date_of_gh

                );
                $m = $this->GhModel->create($data);
                if ($m) {
                    $this->PhModel->updatePHmerge($lastPh->id, array('status' => 5));
                    $this->PhModel->updatePHmerge($lastPh2->id, array('status' => 5));
                }

                $this->session->set_flashdata('success', 'Successfully Submitted.');
                redirect('back_office/');
            }
            $this->session->set_flashdata('error', 'Validation 2 error: Select one of the Options');
            redirect('back_office/dashboard');
        }else {
            $this->session->set_flashdata('error', 'Validation error: Select one of the Options');
            redirect('back_office/dashboard');
        }

    }

    private function PH_Plan($amount){
        $amount = (int)$amount;
        if ($amount == 2500){
            return 5000;
        }
        elseif ($amount == 5000){
            return 10000;
        }
        elseif($amount == 10000){
            return 20000;
        }else {
            return 00;
        }
    }

    private function SysBonusPlan($amount){
        $amount = (int)$amount;
      if($amount == 5000){
            return 2500;
        }
        elseif($amount == 10000){
            return 5000;
        }
     else {
            return 00;
        }
    }




    public function insertGHBonus(){
        $this->load->model('gh_model','GhModel');
        $this->load->model('ph_model','PhModel');
        $this->load->model('payment_model','Payment');

        $payeeWallet = $this->Payment->getWallet($this->profile->id);

        $exist = $this->GhModel->checkGHexistBonus($this->profile->id);
        $lastPh = $this->PhModel->getLastPh($this->profile->id);
        $tb = $this->PhModel->getAvailableSumTotal($this->profile->id);


        if($exist > 0 ){
            $this->session->set_flashdata('error', 'You already have an existing investment');
            redirect('back_office/bonus');
        }
        if(!$lastPh){
            $this->session->set_flashdata('error', 'You are not qualified to Withdraw Yet');
            redirect('back_office/bonus');
        }
        $tb = (int)$tb;
       if ($tb >= 5000 && $tb < 10000 ){
            $atb = 5000;
        }
        elseif ($tb >= 10000 && $tb < 20000){
            $atb = 10000;
        }
       elseif ($tb >= 20000 && $tb < 30000){
           $atb = 20000;
       }
        else {
            $atb = NULL;
        }

        if($this->input->post('submit')){



            $data = array(
                "user_id" => $this->profile->id,
                "username" => $this->profile->username,
                "amount" => $tb,
                "rem_amount" => $atb,
                "type"   => "BONUS",
                "method_id"   => $payeeWallet->id,
                "status" => "1",
                "date_added" => date('Y-m-d'),
                "date_of_gh" => $lastPh->date_of_gh

            );
            $m =  $this->GhModel->create($data);
            if($m){
                $this->GhModel->upAll($this->profile->id);
            }
            $this->session->set_flashdata('success', 'Successfully Submitted.');
            redirect('back_office/');

        }else {
            $this->session->set_flashdata('error', 'Validation error: Select one of the Options');
            redirect('back_office/dashboard');
        }

    }

    public function insertSYSBonus(){
        $this->load->model('gh_model','GhModel');
        $this->load->model('ph_model','PhModel');
        $this->load->model('plan_model','Plan');
        $this->load->model('payment_model','Payment');

        $payeeWallet = $this->Payment->getWallet($this->profile->id);

        $countSysBonus = $this->PhModel->countSysBonus($this->profile->id);
        $tb = $this->PhModel->getAvailableSumTotal($this->profile->id);



        if(!$countSysBonus > 3){
            $this->session->set_flashdata('error', 'You are not qualified to Withdraw Yet');
            redirect('back_office/dashboard');
        }
        $today = date('Y-m-d');

        $twoWeeks = date('Y-m-d', strtotime($today . ' + 14 days'));

        if($this->input->post('submit')){

            $data = array(
                "user_id" => $this->profile->id,
                "username" => $this->profile->username,
                "amount" => $this->input->post('amount'),
                "rem_amount" => $this->input->post('amount'),
                "type"   => "SYSBONUS",
                "method_id"   => $payeeWallet->id,
                "status" => "1",
                "date_added" => date('Y-m-d'),
                "date_of_gh" => date('Y-m-d')

            );
            $m =  $this->GhModel->create($data);
            if($m){
                $this->PhModel->upAll($this->profile->id);
            }
 if($this->input->post('optradion')) {
                $plan =  $this->Plan->getNextPlan($this->input->post('optradion'));
                       $lastRecom =   $this->PhModel->getLastRecom($this->profile->id);
                       $newPh = $plan->amount - $lastRecom->amount;

     $refd = $this->User->getRefsRef($this->profile->referrer_id);
     $usern = $this->User->getUsername($this->profile->referrer_id);
     $usern2 = $this->User->getUsername($refd->referrer_id);
     $data = array(
         "user_id" => $this->profile->id,
         "first_bonus_id" => $this->profile->referrer_id,
         "first_bonus_username" => $usern->username,
         "first_bonus_status" => "1",
         "second_bonus_id" => $refd->referrer_id,
         "second_bonus_username" => $usern2->username,
         "second_bonus_status" => "1",
         "username" => $this->profile->username,
         "amount" => $newPh,
         "rem_amount" => $plan->rec_amount,
         "date_of_ph" => date('Y-m-d'),
         "date_of_gh" => $twoWeeks,
         "status" => "1",

     );
     $this->PhModel->create($data);
 }

            $this->session->set_flashdata('success', 'Successfully Submitted.');
            redirect('back_office/');

        }else {
            $this->session->set_flashdata('error', 'Validation error: Select one of the Options');
            redirect('back_office/dashboard');
        }

    }

    public function insertSupBonus(){
        $this->load->model('gh_model','GhModel');
        $this->load->model('ph_model','PhModel');
        $this->load->model('plan_model','Plan');
        $this->load->model('payment_model','Payment');
        $this->load->model('support_model', 'Support');

        $payeeWallet = $this->Payment->getWallet($this->profile->id);

//        $countSysBonus = $this->PhModel->countSysBonus($this->profile->id);
//        $tb = $this->PhModel->getAvailableSumTotal($this->profile->id);
//
//
//
//        if(!$countSysBonus > 4){
//            $this->session->set_flashdata('error', 'You are not qualified to Withdraw Yet');
//            redirect('back_office/dashboard');
//        }
        $today = date('Y-m-d');

        $twoWeeks = date('Y-m-d', strtotime($today . ' + 14 days'));

        if($this->input->post('submit')){

            $data = array(
                "user_id" => $this->profile->id,
                "username" => $this->profile->username,
                "amount" => $this->input->post('amount'),
                "rem_amount" => $this->input->post('amount'),
                "type"   => "SUPBONUS",
                "method_id"   => $payeeWallet->id,
                "status" => "1",
                "date_added" => date('Y-m-d'),
                "date_of_gh" => date('Y-m-d')

            );
            $m =  $this->GhModel->create($data);
            if($m){
                $this->Support->upAll($this->profile->id);
            }


            $this->session->set_flashdata('success', 'Successfully Submitted.');
            redirect('admin/support');

        }else {
            $this->session->set_flashdata('error', 'Validation error: Select one of the Options');
            redirect('admin/support');
        }

    }

    public function date_diff_gh(){
        date_default_timezone_set('Africa/Lagos');
        $this->load->model('ph_model','PhModel');
        $lastPh = $this->PhModel->checkGhDate($this->profile->id);
        $n = $lastPh->date_of_gh;
        $today = time();
        $oneWeek = strtotime("$n");
        $secs = $oneWeek - $today;
        $days = $secs/86400;
        $d = floor($days);
        // var_dump($lastPh->date_of_gh);

    }




    public function profile() {
//        $ee = $this->User->getLevelExpire();
//        foreach ($ee as $e){


//        $pp = $this->User->upAll();
//        if ($pp){
//           echo now();
//       }



//                $pp = $this->User->dueUpgrade($this->profile->id,$this->profile->account_level);
//        if ($pp){
//            echo now();
//        }

//        $pp = $this->User->upAll();
//        if ($pp){
//            echo now();
//        }

//        $nn = $this->User->Users2Purge();
//        foreach ($nn as $n){
//          $n =   $n->id;
//           echo $n.",";
//
//            $dd = $this->User->purgeThem(array(11050,11051,11057,11061,11070,11074,11087,11092,11102,11107,11108,11109,11114,11123,11124,11127,11130,11135,11141,11145,11147,11149,11150,11154,11162,11166,11168,11177,11178,11182,11184,11189,11190,11191,11194,11195,11197,11204,11205,11206,11211,11212,11214,11218,11220,11221,11236,11237,11241,11242,11243,11244,11247,11248,11249,11252,11261,11266,11273,11275,11281,11285,11287,11305,11312,11314,11315,11321,11325,11326,11330,11335,11353,11370,11372,11381,11391,11406,11408,11409,11415,11420,11426,11439,11452,11462,11463,11487,11488,11533,11537,11538,11539,11540,11553,11559,11563,11565,11567,11568,11569,11570,11574,11576,11585,11588,11596,11597,11603,11607,11609,11613,11633,11642,11665,11668,11670,11693,11697,11699,11702,11706,11718,11719,11720,11722,11723,11729,11731,11734,11761,11762,11787,11790,11797,11814,11822,11827,11832,11834,11838,11840,11847,11855,11865,11873,11875,11880,11890,11897,11900,11901,11906,11909,11912,11920,11926,11932,11934,11945,11950,11957,11962,11963,11965,11966,11971,11976,11977,11983,11985,12010,12011,12014,12029,12039,12040,12041,12049,12052,12058,12059,12072,12089,12090,12091,12092,12093,12095,12114,12117,12127,12131,12132,12136,12140,12143,12145,12146,12150,12152,12153,12159,12174,12183,12188,12189,12211,12219,12239,12241,12247,12250,12251,12254,12258,12261,12283,12284,12288,12290,12298,12300,12301,12303,12308,12317,12318,12324,12327,12329,12330,12331,12337,12338,12347,12353,12355,12360,12374,12375,12376,12387,12390,12392,12398,12400,12414,12425,12427,12432,12438,12445,12454,12455,12460,12463,12464,12469,12473,12475,12489,12499,12526,12531,12532,12535,12537,12540,12543,12545,12549,12558,12562,12565,12570,12577,12579,12591,12607,12618,12623,12629,12632,12642,12647,12651,12667,12671,12677,12683,12686,12694,12696,12698,12702,12704,12710,12711,12712,12717,12720,12725,12733,12734,12737,12738,12741,12742,12747,12749,12751,12754,12755,12764,12771,12773,12775,12779,12781,12782,12784,12797,12798,12801,12811,12815,12818,12826,12831,12833,12835,12836,12838,12842,12847,12848,12854,12858,12869,12873,12874,12875,12885,12898,12901,12907,12910,12920,12929,12939,12954,12969,12978,12979,12985,13005,13031,13033,13041,13058,13067,13072,13075,13083,13096,13097,13110,13112,13114,13119,13121,13122,13126,13130,13136,13138,13140,13143,13148,13152,13157,13159,13161,13162,13164,13166,13170,13187,13199,13201,13202,13205,13231,13233,13234,13235,13237,13240,13244,13263,13267,13270,13273,13277,13288,13290,13291,13294,13296,13300,13312,13320,13333,13340,13344,13346,13347,13350,13352,13355,13359,13360,13361,13362,13363,13372,13377,13381,13382,13392,13409,13411,13418,13421,13423,13424,13425,13426,13432,13439,13441,13442,13444,13445,13450,13457,13459,13460,13463,13469,13475,13481,13499,13501,13511,13512,13519,13521,13531,13536,13537,13542,13543,13545,13555,13558,13559,13566,13567,13571,13611,13622,13634,13638,13639,13644,13649,13651,13657,13660,13663,13671,13672,13673,13677,13678,13696,13707,13708,13713,13717,13732,13734,13746,13768,13773,13778,13780,13785,13786,13790,13791,13792,13793,13807,13811,13815,13818,13820,13822,13825,13829,13841,13844,13846,13851,13857,13864,13865,13868,13874,13877,13878,13883,13890,13896,13904,13907,13908,13911,13915,13918,13924,13934,13942,13945,13955,13961,13964,13975,13983,13988,13989,13997,13999,14009,14018,14026,14031,14033,14045,14047,14059,14065,14078,14082,14085,14091,14097,14106,14112,14114,14118,14122,14132,14134,14137,14141,14142,14151,14153,14155,14161,14163,14166,14168,14171,14176,14177,14178,14182,14184,14185,14187,14189,14190,14194,14195,14197,14198,14199,14201,14205,14206,14208,14209,14212,14213,14217,14220,14228,14242,14243,14252,14257,14264,14268,14269,14278,14292,14296,14298,14305,14360,14365,14373,14374,14375,14383,14385,14396,14410,14426,14434,14438,14439,14441,14447,14451,14452,14458,14463,14464,14483,14486,14491,14500,14517,14519,14526,14527,14535,14536,14547,14548,14550,14555,14560,14566,14567,14572,14576,14581,14582,14587,14602,14609,14629,14632,14640,14642,14645,14646,14656,14657,14662,14686,14692,14694,14696,14706,14708,14710,14726,14744,14751,14753,14758,14775,14778,14795,14798,14802,14809,14815,14817,14818,14823,14830,14838,14841,14842,14843,14856,14860,14865,14869,14873,14874,14877,14879,14880,14883,14885,14896,14906,14913,14923,14930,14931,14947,14951,14970,14975,14977,14988,14990,15005,15013,15016,15019,15026,15032,15034,15035,15036,15038,15044,15049,15051,15053,15060,15068,15069,15089,15109,15127,15131,15132,15143,15160,15180,15184,15193,15197,15205,15227,15239,15246,15247,15259,15263,15264,15267,15273,15274,15286,15297,15301,15303,15306,15309,15310,15321,15337,15351,15352,15362,15364,15373,15375,15386,15407,15409,15410,15414,15416,15425,15427,15430,15431,15438,15445,15446,15447,15449,15450,15452,15463,15491,15497,15500,15501,15510,15512,15532,15533,15534,15538,15539,15540,15556,15558,15559,15561,15563,15565,15568,15570,15576,15578,15607,15608,15612,15614,15618,15626,15652,15658,15663,15671,15677,15680,15690,15696,15703,15710,15719,15722,15728,15729,15740,15744,15745,15750,15752,15754,15763,15769,15771,15777,15780,15784,15785,15816,15817,15819,15829,15833,15843,15844,15865,15870,15873,15883,15884,15885,15894,15901,15906,15907,15935,15940,15951,15952,15960,15961,15968,15971,15973,15975,15980,15992,15997,15998,16017,16018,16026,16029,16032,16033,16040,16041,16043,16047,16053,16058,16061,16077,16082,16095,16105,16111,16115,16117,16119,16133,16134,16139,16141,16142,16149,16150,16151,16158,16165,16168,16171,16172,16174,16175,16182,16186,16187,16188,16189,16193,16197,16204,16205,16215,16217,16223,16237,16241,16242,16258,16262,16267,16271,16283,16292,16294,16318,16319,16331,16344,16347,16366,16385,16406,16410,16412,16417,16420,16428,16438,16444,16446,16455,16463,16474,16475,16482,16486,16488,16490,16494,16495,16499,16501,16502,16505,16512,16513,16522,16533,16539,16541,16542,16544,16545,16546,16547,16557,16563,16564,16573,16577,16578,16585,16587,16593,16594,16605,16612,16616,16618,16621,16627,16639,16644,16657,16658,16659,16661,16679,16683,16703,16712,16714,16716,16718,16720,16721,16722,16727,16736,16737,16744,16757,16760,16780,16782,16785,16788,16808,16815,16824,16829,16844,16853,16856,16863,16877,16880,16896,16904,16914,16917,16918,16927,16933,16936,16939,16950,16951,16954,16957,16958,16960,16962,16963,16968,16970,16974,16976,16978,16979,16983,16985,16989,16990,16991,16993,16995,16999,17001,17004,17005,17007,17008,17009,17012,17013,17019,17021,17022,17023,17025,17029,17030,17034,17037,17038,17044,17046,17051,17056,17062,17065,17068,17069,17073,17078,17080,17081,17086,17088,17090,17095,17096,17099,17102,17104,17110,17111,17116,17119,17122,17127,17137,17142,17146,17147,17152,17154,17155,17158,17175,17188,17191,17216,17223,17229,17232,17244,17245,17250,17263,17270,17271,17281,17289,17299,17309,17312,17318,17330,17334,17340,17357,17359,17361,17365,17367,17370,17376,17378,17384,17387,17388,17403,17416,17436,17439,17440,17442,17443,17444,17446,17449,17451,17457,17458,17460,17463,17464,17469,17471,17476,17481,17483,17484,17501,17503,17521,17529,17534,17539,17541,17545,17557,17570,17582,17592,17593,17604,17608,17609,17619,17624,17630,17639,17641,17645,17650,17652,17656,17659,17662,17664,17675,17692,17698,17700,17706,17707,17711,17714,17719,17726,17727,17742,17754,17760,17764,17766,17767,17769,17773,17781,17783,17787,17788,17789,17792,17797,17801,17803,17807,17810,17811,17820,17828,17832,17833,17835,17852,17853,17863,17865,17867,17870,17871,17877,17897,17899,17922,17924,17926,17932,17949,17956,17958,17963,17965,17972,17981,17987,17994,17999,18005,18006,18009,18024,18026,18029,18053,18054,18056,18060,18064,18066,18067,18075,18077,18085,18086,18088,18093,18095,18101,18103,18105,18108,18113,18119,18125,18127,18128,18131,18132,18133,18134,18135,18142,18143,18144,18147,18150,18152,18168,18169,18172,18178,18182,18189,18190,18196,18203,18204,18207,18224,18228,18231,18236,18256,18258,18259,18261,18263,18278,18282,18283,18290,18300,18309,18317,18319,18331,18332,18336,18340,18344,18345,18351,18357,18363,18371,18372,18378,18384,18391,18398,18403,18406,18417,18419,18424,18425,18428,18429,18437,18440,18443,18444,18449,18450,18452,18461,18479,18481,18482,18484,18489,18495,18497,18500,18502,18512,18524,18527,18528,18531,18547,18562,18565,18577,18587,18598,18603,18617,18620,18639,18648,18656,18661,18666,18667,18668,18669,18673,18678,18679,18687,18689,18691,18694,18698,18700,18704,18706,18707,18709,18715,18723,18735,18737,18740,18744,18757,18758,18784,18800,18806,18810,18816,18820,18827,18829,18834,18837,18840,18861,18862,18867,18881,18884,18902,18903,18923,18924,18927,18941,18942,18946,18949,18952,18955,18958,18968,18974,18976,18977,18982,18996,19004,19007,19014,19017,19023,19031,19036,19037,19039,19040,19055,19060,19070,19071,19077,19079,19080,19083,19084,19091,19101,19107,19109,19111,19116,19120,19121,19122,19125,19126,19130,19132,19136,19140,19142,19149,19175,19178,19188,19197,19199,19205,19206,19212,19213,19216,19219,19220,19233,19234,19236,19237,19240,19245,19261,19263,19265,19286,19290,19291,19295,19299,19305,19310,19317,19321,19335,19337,19339,19350,19351,19356,19360,19369,19370,19372,19376,19383,19387,19389,19404,19416,19418,19422,19434,19437,19441,19442,19443,19448,19449,19456,19457,19458,19468,19469,19475,19478,19481,19483,19484,19486,19508,19512,19522,19526,19530,19541,19542,19546,19547,19549,19551,19557,19558,19559,19577,19587,19595,19598,19602,19611,19638,19661,19675,19678,19683,19685,19691,19697,19708,19711,19715,19726,19727,19731,19755,19767));
//            if ($dd){
//                var_dump("True");
//           }
//
//        }


        $this->data->page_title = 'Member Profile';

        $this->load->model('referral_model', 'Referral');

        $this->data->widgets = array();

        $this->data->lock = $this->profile->locked;

        $this->data->account = $this->Account->profile($this->userId);
        $this->data->userData->country = $this->User->getCountryById($this->profile->country_id);
        $this->data->referrer = $this->User->getData($this->profile->referrer_id);

        $this->data->widgets[] = $this->loadPartialView('member/account');
        $this->data->widgets[] = $this->loadPartialView('member/avatar');

        $report = new RMSList('member_lists/', 'social', "member/getList/social/");
        $where = array('user_id' => $this->userId);

        $this->data->socialList = $report->set_where($where)->getPartial()->render();

        $tile      = array(
            'id'    => 'socialNetworks',
            'title' => 'Social Networks',
            'cols'  => 6,
            'size'  => 'lg',
            'body'  => $this->loadPartialView('member/social_networks')
        );
        $this->data->widgets[] = $this->loadPartialView('partial/tile', compact('tile'));

        $this->addJavascript(asset('scripts/profile.js'));
        $this->addJavascript(asset('bootstrap/js/settings.js'));
        $this->addJavascript(asset('scripts/replace.js'));
        $this->addJavascript(asset('bootstrap/css/form.css'));
        return $this->loadPartialView('member/profile');
    }

    public function edit_avatar() {
        $this->data->defaults = array_diff(scandir(FCPATH.'avatars/default'), array('..', '.'));
        echo $this->loadPartialView('member/partial/avatar');
    }

    public function add_social() {
        if ($this->ajax) {

            if ($_POST) {
                $this->data->formURL = site_url("member/form/social");
                $this->_path         = 'member/';
                $_POST['user_id']    = $this->profile->id;

                $data = $this->doForm('social');

                if (!is_array($data)) { // returns an ID if successful, error is always an array
                    $report = new RMSList('member_lists/', 'social', "member/getList/social/");
                    $where  = array('user_id' => $this->userId);

                    $data = array(
                        'again'   => TRUE,
                        //'success' => 'Success!',
                        'replace' => array(
                            'socialNetworkList' => $report->set_where($where)->getPartial()->render()
                        )
                    );
                }
            } else {
                $data = array(
                    'error' => 'Invalid entry.'
                );
            }
            echo json_encode($data);
        } else
            show_404();
    }

    public function delete_social($id) {
        if ($this->ajax) {

            $this->User->deleteSocialNetwork($this->userId, $id);

            echo json_encode(array('success' => TRUE));
        } else
            show_404();
    }

    /**********
     *
     * SETTINGS
     *
     */

    public function settings() {
        $this->load->model('settings_model', 'Settings');
        $this->data->page_title = 'Settings';

        $this->data->settings = array(
            array(
                'name'        => 'email_all_levels',
                'label'       => 'Referral Email',
                'description' => 'Send email when I get a new referral at any level',
                'value'       => isset($this->data->userSettings->email_all_levels) ? $this->data->userSettings->email_all_levels : 1
            ),
            array(
                'name'        => 'payment_received_email',
                'label'       => 'Payment Submitted',
                'description' => 'Send email when a donation is submitted to me.',
                'value'       => isset($this->data->userSettings->payment_received_email) ? $this->data->userSettings->payment_received_email : 1
            ),
            array(
                'name'        => 'payment_approved_email',
                'label'       => 'Payment Approved',
                'description' => 'Send email when a donation I send is approved.',
                'value'       => isset($this->data->userSettings->payment_approved_email) ? $this->data->userSettings->payment_approved_email : 1
            )
        );
        if ($this->profile->account_level >= SPILL_OPTION_LEVEL) {
            $this->data->settings[] = array(
                'name'        => 'spill_off',
                'label'       => 'Disable Spillover',
                'description' => 'Turn on to allow up to '.MAX_REFERRALS.' direct referrals.',
                'value'       => isset($this->data->userSettings->spill_off) ? $this->data->userSettings->spill_off : 0
            );
        }

        $this->data->widgets[] = $this->loadPartialView('member/settings');

        $this->addJavascript(asset('bootstrap/js/settings.js'));
        return $this->loadPartialView('partial/widgets');
    }

    public function setting($setting, $val) {
        if ($setting == 'lock_my_ip' && $val == 1) {
            $this->ion_auth->update($this->userId, array('ip_address' => $this->input->ip_address()));
        }
        if ($setting == 'spill_off' && $this->profile->account_level < SPILL_OPTION_LEVEL) { //failsafe
            return FALSE;
        }

        $this->User->addSetting($this->profile->id, $setting, $val);
    }

    public function email_setting($setting, $val) {
        $val      = intval($val);
        $settings = intval($this->profile->email_settings);

        if ($val)
            $settings = $settings | $setting;
        else
            $settings = $settings & ~$setting;

        if ($this->ion_auth->update($this->userId, array('email_settings' => $settings))) {
            $this->load->model('user_model', 'User');
            $this->User->storeFieldChange($this->userId, 'email_settings', $this->profile->email_settings, $settings);
        }
    }

    /**********
     *
     * ACCOUNT / PAYMENTS
     *
     */

    public function account() {


        $this->data->page_title = 'Member Account';

        $this->load->model('referral_model', 'Referral');

        $this->data->memberships = $this->Referral->summary($this->profile->id);
        $this->data->membershipSettings = $this->Account->getMembershipSettings();

        return $this->loadPartialView('account/index');
    }

    public function upgrade_now() {
        return $this->upgrade(TRUE);
    }

    public function upgrade($id = NULL,$now = FALSE) {
        // $this->load->model('ion_auth_model', 'ion_auth');


        $this->addJavascript(asset('scripts/upgrade.js'));
        $this->addJavascript(asset('scripts/forms.js'));
        //more
        $this->data->widgets[] = $this->loadPartialView('partial/tile', compact('tile'));
        $this->addJavascript(asset('scripts/profile.js'));
        $this->addJavascript(asset('bootstrap/js/settings.js'));
        $this->addJavascript(asset('scripts/replace.js'));
        $this->addJavascript(asset('bootstrap/css/form.css'));

        $this->load->model('my_account_model', 'Account');
        $this->load->model('testimonial_model', 'Testimonial');
        $this->load->model('payment_method_model', 'PaymentMethod');

        $this->data->memberships = $upgrades = $this->Account->getMemberships();
        $current = $this->profile->account_level;

        $this->data->upgrade = $upgrades[$current+1];
        $this->data->member_level    = $this->profile->account_level;
        $this->data->member_cycle    = $this->profile->recycle;

        $this->data->testi    = $this->Testimonial->role_exists($this->profile->id);


        // determine who to pay

        $uplineId = $this->profile->referrer_id;

//        if ($this->profile->id <= DEFAULT_USER_ID || $this->profile->account_level == count($upgrades)) {
//            $this->data->message = 'No pending payments, Youve Already Been Confirmed';
//            return $this->loadPartialView('partial/error');
//        }

        if ($this->profile->locked == 1) {

            $this->data->message = 'Account locked.';
            return $this->loadPartialView('partial/error');
        }

        $paymentMethods = $this->PaymentMethod->getUserMethods($this->userId);

        if (empty($paymentMethods)) {
            $this->data->message = 'You must <b>'.anchor('back_office/accounts', 'add payment accounts').'</b> before Proceeding!';
            return $this->loadPartialView('partial/error');
        }

        $this->load->model('payment_model', 'Payment');

        if ($this->Payment->getPendingSent4($this->userId)) {


            $this->data->pending = $this->Payment->getPendingSent4($this->userId);
            foreach ($this->data->pending as $pend) {
                $this->data->payee = $this->User->getData($pend->payee_user_id);
            }

            $view = 'upgrade';
            $this->load->library('encrypt');
            $this->data->upgradeLevel = $this->encrypt->encode($current+1);

            if ($this->profile->upgrade_time == 0) {
                $this->User->update($this->userId, array('upgrade_time' => now()));
            }

            if ($this->User->getSetting($this->profile->id, 'upgrade_instructions') != 'off') {
                $this->data->instructions = $this->loadPartialView("member/partial/upgrade_instructions");
            }
            if ($this->User->getSetting($this->profile->id, 'step2_instructions') != 'off') {
                $this->data->step2instructions = $this->loadPartialView("member/partial/step2_instructions");
            }
        }
        else {

            $this->data->pending = $this->Payment->getPendingSent22($this->userId);
            $this->data->payee = $this->User->getData($this->data->pending->payee_user_id);

            $view = 'upgrade_pending';

        }

        if (isset($this->data->payee)) {
            $n = $this->Payment->getPendingSent4($this->userId);
           foreach ($n as $p) {
               $this->data->paymentMethods = $this->PaymentMethod->getUserMethods($p->payee_user_id);
           }


            $this->data->payee->settings = $this->User->getSettings($this->data->payee->id);

            $this->data->payee->socialList = $this->User->getSocialNetworks($this->data->payee->id);

            if (!isset($this->data->payee->settings->show_email)) $this->data->payee->settings->show_email = 1;
            if (!isset($this->data->payee->settings->show_phone)) $this->data->payee->settings->show_phone = 1;
            if (!isset($this->data->payee->settings->show_skype)) $this->data->payee->settings->show_skype = 1;
            if (!isset($this->data->payee->settings->show_social)) $this->data->payee->settings->show_social = 1;
            if (!isset($this->data->payee->settings->show_avatar)) $this->data->payee->settings->show_avatar = 1;

        }
        return $this->loadPartialView('account/'.$view);
    }




    private function spill($userId) {

        $refs = $this->Referral->get($userId, FALSE); // array sorted by referral count descending; exclude free members

        if ($refs) {

            $i = $refCount = count($refs);
            do {
                if ($refs[$refCount - 1]->locked == 0 && $refs[$refCount - 1]->referrals < CYCLER_WIDTH) {
                    return $refs[$refCount - 1]->id;
                }
                $i--;
            } while ($i > 0);

            // Pick a random leg to drill down and find a member with less than 5 refs.
            $leg = mt_rand(0, $refCount - 1);

            return $this->spill($refs[$leg]->id);
        } else {
            return $userId;
        }
    }

    public function pay_subscription($level) {

        $this->addJavascript(asset('scripts/upgrade.js'));
        $this->addJavascript(asset('scripts/forms.js'));

        $this->load->model('my_account_model', 'Account');
        $this->load->model('payment_method_model', 'PaymentMethod');
        $this->load->model('payment_model', 'Payment');

        if ($this->profile->locked == 1) {

            $this->data->message = 'Account locked.';
            return $this->loadPartialView('partial/error');
        }

        if ($this->data->pending = $this->Payment->isPending($this->userId, $level)) {

            $this->data->payee = $this->User->getData($this->data->pending->payee_user_id);
            $view              = 'upgrade_pending';

        }
        else {

            $upgrades = $this->Account->getMemberships();
            $this->data->upgrade = $upgrades[$level];

            $uplineId = $this->profile->referrer_id;
            // determine who to pay
            for ($i = 1; $i <= $level && $uplineId >= DEFAULT_USER_ID && $uplineId > 0; $i++) {

                $this->data->payee = $this->User->getData($uplineId);
                $uplineId          = $this->data->payee->referrer_id;
            }

            $expires = $this->Payment->getUpgradeExpiration($this->data->payee->id, $level);

            $invalid =      ($this->data->payee->id > DEFAULT_USER_ID) &&
                (($this->data->payee->locked == 1) ||
                    ($this->data->payee->active == 0) ||
                    ($this->data->payee->deleted == 1) ||
                    ($this->data->payee->banned == 1) ||
                    ($expires < now()));

            //var_dump($invalid);
            if ($invalid) {

                $this->data->payee = $this->User->getData($this->data->payee->referrer_id);
                $expires     = $this->Payment->getUpgradeExpiration($this->data->referrer_id, $level);


                // find the next member up who has the require account level
//                do {
//
//                    $this->data->payee = $this->User->getData($this->data->payee->referrer_id);
//                    $expires     = $this->Payment->getUpgradeExpiration($this->data->referrer_id, $level);
//
//                }
//                while (($this->data->payee->id > DEFAULT_USER_ID) ||
//                         ($this->data->payee->account_level < $level) ||
//                         ($this->data->payee->locked == 1) ||
//                         ($this->data->payee->active == 0) &&
//                         ($this->data->payee->deleted == 1) &&
//                         ($this->data->payee->banned == 1) ||
//                         ($expires < now()));
            }

            $view = 'upgrade';

            if ($this->profile->upgrade_time == 0) {
                $this->User->update($this->userId, array('upgrade_time' => now()));
            }

            $this->load->library('encrypt');
            $this->data->upgradeLevel = $this->encrypt->encode($level);

            if ($this->User->getSetting($this->profile->id, 'upgrade_instructions') != 'off') {
                $this->data->instructions = $this->loadPartialView("member/partial/upgrade_instructions");
            }
            if ($this->User->getSetting($this->profile->id, 'step2_instructions') != 'off') {
                $this->data->step2instructions = $this->loadPartialView("member/partial/step2_instructions");
            }

            $this->data->paymentMethods  = $this->PaymentMethod->getUserMethods($this->data->payee->id);
            $this->data->payee->settings = $this->User->getSettings($this->data->payee->id);

            $this->data->payee->socialList = $this->User->getSocialNetworks($this->data->payee->id);

            if (!isset($this->data->payee->settings->show_email)) $this->data->payee->settings->show_email = 1;
            if (!isset($this->data->payee->settings->show_phone)) $this->data->payee->settings->show_phone = 1;
            if (!isset($this->data->payee->settings->show_skype)) $this->data->payee->settings->show_skype = 1;
            if (!isset($this->data->payee->settings->show_social)) $this->data->payee->settings->show_social = 1;
            if (!isset($this->data->payee->settings->show_avatar)) $this->data->payee->settings->show_avatar = 1;
        }

        $this->data->page_title = 'Pay Subscription';
        $this->data->content =  $this->loadPartialView('account/'.$view);
        $this->layout        = 'layout/member/shell';
        $this->loadView('layout/default', $this->data->page_title);
    }


    public function approve_payments() {
        $this->load->model('payment_model', 'Payment');

        if ($this->profile->locked == 1) {

            $this->data->message = img(asset('images/lock.png')).'&nbsp; Account locked.';
            return $this->loadPartialView('partial/error');
        }
        $this->data->pending = $this->Payment->getPendingReceived($this->userId);

        foreach ($this->data->pending as &$payer) {

            $payerId = $payer->payer_user_id;

            $payer->userData = $this->User->getData($payerId);
            $payer->settings = $this->User->getSettings($payerId);
            $payer->socialList = $this->User->getSocialNetworks($payerId);

            if (!isset($payer->settings->show_email)) $payer->settings->show_email = 1;
            if (!isset($payer->settings->show_skype)) $payer->settings->show_skype = 1;
            if (!isset($payer->settings->show_phone)) $payer->settings->show_phone = 1;
            if (!isset($payer->settings->show_social)) $payer->settings->show_social = 1;
            if (!isset($payer->settings->show_avatar)) $payer->settings->show_avatar = 1;
        }

        $this->addJavascript(asset('scripts/featherlight.min.js'));
        $this->addJavascript(asset('scripts/replace.js'));
        $this->addStylesheet(asset('styles/featherlight.css'));
        $this->addJavascript('/layout/member/assets/js/jquery.plugin.min.js');
        $this->addJavascript('/layout/member/assets/js/jquery.countdown.min.js');
        $this->addStylesheet('/layout/member/assets/css/jquery.countdown.css');

        return $this->loadPartialView('member/pending_auto_approval');
    }

    public function approve($id=NULL,$pid){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('referral_model', 'Referral');
        $this->load->model('ph_model', 'PH');
        $this->load->model('gh_model', 'GH');
        $this->load->model('email_model', 'EmailQueue');


        $payment = $this->Payment->getCurrentPayment($id,$pid);
        if ($payment->payee_user_id != $this->userId) {
            $this->data->message = 'Error! Payment Not yours.';
            $view = 'error';
        } elseif ($payment->approved || $payment->rejected) {
            $this->data->message = 'Error! Payment already processed.';
            $view                = 'error.';
        }

        else {

           $plan =  $this->PH->getPlan($pid);

//            $this->db->trans_start(); # Starting Transaction
//            $this->db->trans_strict(TRUE); # See Note 01. If you wish can remove as well

            $today = date('Y-m-d');
            if ($plan->amount == "10000") {
                $oneWeek = date('Y-m-d', strtotime($today . ' + 10 days'));
            }else{
                $oneWeek = date('Y-m-d', strtotime($today . ' + 7 days'));

            }

            $tpay = $this->User->getSingle($payment->payer_user_id);
            $recycle = (int)$tpay->recycle;


            $this->GH->updateGHapprove($payment->gh_id, array(
                'status' => 4,
                'date_of_gh' => $oneWeek,
            ));
            $this->PH->updatePHapprove($payment->ph_id, array(
                'status' => 4,
                'system_bonus_status' => 1,
                'date_confirmed' => $today,
                'date_of_gh' => $oneWeek,
            ));

            $this->Payment->updateApprove($pid, array(
                'approved' => 1,
                'updated' => now()
            ));
            $this->User->update($payment->payer_user_id, array(
                'recycle' => $recycle + 1,
                'plan' => $payment->amount,

            ));


            // $this->Referral->addPayment($payment->payee_user_id, $payment->payer_user_id, $payment->amount);
            //  var_dump($tpay);
            $this->EmailQueue->store($tpay->email, '[' . SITE_NAME . '] Payment Approved', 'emails/cashier/payment_approved', compact('payer', 'payment'));
          //  $this->db->trans_complete(); # Completing transaction
            $this->session->set_flashdata('success', 'Successfully Confirmed.');
            redirect('back_office/dashboard');

//            if ($this->db->trans_status() === FALSE) {
//                # Something went wrong.
//                $this->db->trans_rollback();
//                $this->session->set_flashdata('error', 'There is an error Confirming this payment.');
//                redirect('back_office/dashboard');
//            }
//            else {
//                # Everything is Perfect.
//                # Committing data to the database.
//                $this->db->trans_commit();
//                $this->session->set_flashdata('success', 'Successfully Confirmed.');
//                redirect('back_office/dashboard');
//            }



        }
        // else {var_dump("error");}



    }

    public function approve_punishment($id=NULL,$pid){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('referral_model', 'Referral');
        $this->load->model('ph_model', 'PH');


        $payment = $this->Payment->get($pid);
        if ($payment->payee_user_id != $this->userId) {
            $this->data->message = 'Error! Payment Not yours.';
            $view = 'error';
        }
        else {


//            $this->db->trans_start(); # Starting Transaction
//            $this->db->trans_strict(TRUE); # See Note 01. If you wish can remove as well

            $today = date('Y-m-d');


            $this->PH->updatePHapprove($payment->ph_id, array(
                'status' => 1,
                'amount' => $payment->amount,
                'rem_amount' => $payment->amount,
            ));

            $this->Payment->updateApprove($pid, array(
                'punishment' =>0,
            ));
            $this->User->update($payment->payer_user_id, array(
                'locked' => 0,

            ));


              $this->session->set_flashdata('success', 'Successfully Confirmed.');
            redirect('back_office/dashboard');

        }

    }

    public function reject_punishment($id,$pid){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('referral_model', 'Referral');
        $this->load->model('ph_model', 'PH');


        $payment = $this->Payment->get($pid);
        if ($payment->payee_user_id != $this->userId) {
            $this->data->message = 'Error! Payment Not yours.';
            echo 'error';
        }

        else {


//            $this->db->trans_start(); # Starting Transaction
//            $this->db->trans_strict(TRUE); # See Note 01. If you wish can remove as well

            $today = date('Y-m-d');




            $this->Payment->updateApprove($pid, array(
                'punishment' =>2,
            ));


            $this->session->set_flashdata('success', 'Successfully Rejected.');
            redirect('back_office/dashboard');

        }

    }

    public function reject($id,$id2){
        $this->load->model('payment_model', 'Payment');

        $this->data->usb = $this->Payment->getCurrentPayee2($id);
        $this->data->usee =  $this->User->getSingle($id);


        $this->layout = 'layout/member/shell';

        $this->loadView('member/reject', array($this->data->usee,$this->data->usb));
        //return $this->loadPartialView('member/reject');


    }

    public function rejectPost($id,$id2){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('ph_model', 'PH');
        $this->load->model('gh_model', 'GH');
        $payment = $this->Payment->getCurrentPayment($id,$id2);

        if ($payment->payee_user_id != $this->userId) {
            $this->data->message = 'Error! Payment Not yours.';
            $view = 'error';
        } elseif ($payment->approved || $payment->rejected) {
            $this->data->message = 'Error! Payment already processed.';
            $view                = 'error';
        } else {

            $this->form_validation->set_rules('reason', 'Reason', 'trim|required|xss_clean');
            $this->form_validation->set_rules('message', 'Message', 'trim|xss_clean');


            if ($this->form_validation->run() == TRUE) {

                //return true;
                $paint = $this->input->post();
                $message = $paint['message'];

                $this->PH->updatePHapprove($payment->ph_id, array(
                    'status' => 6,
                ));
                $this->GH->updateGHapprove($payment->gh_id, array(
                    'status' => 6,
                ));
                if(empty($message)) {
                    $m = $this->Payment->updateTimeup($id, array(
                        'rejected' => 1,
                        'reason' => $paint['reason'],
                        'updated' => now()
                    ));
                } else{
                    $m = $this->Payment->updateTimeup($id, array(
                        'rejected' => 1,
                        'reason' => $message,
                        'updated' => now()
                    ));

                }
                if ($m && empty($payment->proof_img)) {
                    // $this->User->update($payment->payee_user_id, array('locked' => '1'));
                    $this->User->update($payment->payer_user_id, array('locked' => '1'));
                }else {
                    $this->User->update($payment->payer_user_id,array('locked'=>'1', 'reason' => "False ".$paint['reason']. " Accusation"));

                }

                //  $this->load->model('referral_model', 'Referral');
                //$this->Referral->addPayment($payment->payee_user_id, $payment->payer_user_id, $payment->amount);

                $tpay = $this->User->getSingle($payment->payer_user_id);

//            $this->User->update($tpay->payer_user_id, array(
//                'account_level' => $level+1,
//               ));

                //  var_dump($tpay);
                $this->load->model('email_model', 'EmailQueue');
                $this->EmailQueue->store($tpay->email, '[' . SITE_NAME . '] Payment Rejected', 'emails/cashier/payment_approved', compact('payer', 'payment'),1);

                $this->session->set_flashdata('success', 'Successfully Rejected the Payment');
                redirect('back_office/dashboard');
            }

            else
            { echo validation_errors();}

        }
    }


    public function migrate() {

        $this->data->page_title = 'Migration Page';



        return $this->loadPartialView('member/migration');
    }

    public function payment_update($id, $approve) {
//        $this->load->library('encrypt');
//        $id = $this->encrypt->decode($id);

        $this->load->model('payment_model', 'Payment');


        $payment = $this->Payment->getCurrentPayment($id);
        $tpay =  $this->User->getBunch($payment->payee_user_id);
        // var_dump($payment->payee_user_id);

        if ($payment->payee_user_id != $this->userId) {
            $this->data->message = 'Error! Invalid donation: Not yours.';
            $view = 'error';
        } elseif ($payment->approved || $payment->rejected) {
            $this->data->message = 'Error! donation already processed.';
            $view                = 'error';
        } else {

            $data = array('update_user_id' => $this->userId);
            if ($approve == '1') {
                $this->load->model('referral_model', 'Referral');

                $payment->approved = $data['approved'] = now();

                $this->data->message  = money($payment->price).' '.$payment->title.' donation from '.$tpay->username.' approved.';
                $view = 'success';

                $this->Referral->addPayment($payment->payee_user_id, $payment->payer_user_id, $payment->amount);

                $upgrade = $this->Account->getMembership($payment->code);

                $payer = $this->User->getData($payment->payer_user_id, array('username', 'email', 'text_ad_credits'));

                $this->User->update($payment->payer_user_id, array(
                    'account_level' => $payment->code,
                    'account_expires' => NULL,
                    'text_ad_credits' => $payer->text_ad_credits + $upgrade->text_ad_credits));

                $this->load->model('email_model', 'EmailQueue');
                $this->EmailQueue->store($payer->email, '['.SITE_NAME.'] Payment Approved', 'emails/cashier/payment_approved', compact('payer', 'payment'));

            } else {
                $data['rejected'] = now();
                $this->data->message = money($payment->price).' '.$payment->title.' donation from '.$payment->username.' rejected.';
                $view = 'error';
            }

            $this->Payment->update($id, $data);
        }
        echo $this->loadPartialView("partial/$view");
    }

    public function accounts() {

        $this->load->model('payment_method_model', 'PaymentMethod');

        $this->data->balances = $this->PaymentMethod->getUserMethods($this->userId);

        $this->addStyleSheet(asset('styles/cashier.css'));
        $this->addJavascript(asset('scripts/replace.js'));
        return $this->loadPartialView('member/account_select');

    }

    public function update_wallet() {

        //load payment model
        $this->load->model('payment_model', 'Payment');
        //get wallet details from payment_mode table
        $this->data->wallet = $this->Payment->getWallet($this->userId);

        $this->Payment->cancelChangeWallet($this->userId);

        if ($_POST && $this->ajax) {

            $post = $this->input->post();

            $this->load->helper('bitcoin');

            if (empty($post['method_name'])) {
                echo json_encode(array(
                    'errorElements' => array('method_name' => '* required')
                ));
                return;
            }

            if (empty($post['note'])) {
                echo json_encode(array(
                    'errorElements' => array('note' => '* required')
                ));
                return;
            }

            if (empty($post['payment_code'])) {
                echo json_encode(array(
                    'errorElements' => array('payment_code' => '* required')
                ));
                return;
            }


            if (empty($post['account']) || wallet_check($post['account']) !== TRUE) {
                echo json_encode(array(
                    'errorElements' => array('account' => '* invalid wallet address')
                ));
                return;
            }
//            if ($post['secret_answer'] != $this->profile->secret_answer) {
//
//                echo json_encode(array(
//                    'errorElements' => array('secret_answer' => '* incorrect')
//                ));
//                return;
//            }

            if ($post['account'] != $this->data->wallet->account) {
                $data = array(
                    'uuid' => $this->Payment->addWalletChange($this->userId, $this->data->wallet->account, $post['account']),
                    'username' => $this->profile->username
                );

                $this->load->model('email_model', 'EmailQueue');
                $this->EmailQueue->store($this->profile->email, 'Wallet change confirmation code', 'emails/member/wallet_change_confirm', $data, 9);

                $this->data->method_name = $post['method_name'];

                echo json_encode(array(
                        'success' => 'ok',
                        'replace' => array(
                            'walletForm' => $this->loadPartialView('member/partial/wallet_change_confirm')
                        ))
                );
                return;
            } else {
                $this->load->model('payment_method_model', 'PaymentMethod');
                $this->PaymentMethod->updatePaymentMethod($this->data->wallet->id, array('method_name' => $post['method_name']));
                echo json_encode(array(
                    'success' => 'Success!',
                    'redirect' => array('url' => '/back_office/accounts')
                ));
            }

        } else {
            if ($this->User->getSetting($this->profile->id, 'bitcoin_wallet_instructions') != 'off') {
                $this->data->instructions = $this->loadPartialView("member/partial/bitcoin_wallet_instructions");
            }
            echo $this->loadPartialView('member/partial/wallet_form');
        }
    }

    public function update_wallet_confirm() {

        if ($_POST && $this->ajax) {

            $this->load->model('payment_model', 'Payment');
            $post = $this->input->post();

            if (empty($post['uuid'])) {
                echo json_encode(array(
                    'errorElements' => array('uuid' => '* required')
                ));
                return;
            }

            if ($this->Payment->changeWallet($this->userId, $post['uuid'], $post['method_name'])) {

                $this->load->model('email_model', 'EmailQueue');
                $this->EmailQueue->store($this->profile->email, 'NGN wallet changed', 'emails/member/account_changed', array('username' => $this->profile->username));
                echo json_encode(array(
                    'success'  => 'Success! Redirecting back to accounts...',
                    'redirect' => array(
                        'url' => SITE_ADDRESS.'back_office/accounts'
                    )
                ));

            } else {
                echo json_encode(array(
                    'errorElements' => array('uuid' => '* incorrect')
                ));
            }
        } else {
            show_404();
        }
        return;
    }

    public function update_wallet_cancel() {

        $this->load->model('payment_model', 'Payment');
        $this->Payment->cancelChangeWallet($this->userId);
        $this->session->set_flashdata('info', 'Wallet change cancelled.');
        redirect('back_office/accounts');
    }

    public function delete_method($id) {

        $this->load->model('payment_method_model', 'PaymentMethod');

        $method = $this->PaymentMethod->getPaymentMethod($id);

        if ($method->user_id != $this->userId) {
            $this->load->model('email_model', 'EmailQueue');
            $this->EmailQueue->store('uchimadarasan@gmail.com', SITE_NAME.' hack attempt', 'emails/auth/hack', array('userId'=>$this->userId, 'ip' => getRealIpAddr()), 1);
            echo json_encode(array('error' => 'not yours.'));
            return;
        }

        $this->PaymentMethod->deletePaymentMethod($id, $this->userId);
        echo json_encode(array('success' => TRUE));
    }

    public function payments() {
        $this->load->model('payment_model', 'Payment');

        $this->data->payments = $this->Payment->userSummary($this->userId);
        return $this->loadPartialView('member/payments');

    }
    /***********
     *
     * REFERRALS
     *
     */
    private function purgesms($phone,$username){
        $message =   urlencode("Hello ".$username."\n"."You have been purged out of tradermoni by your Upline. You still have the grace of upgrading now before final deletion of your account. Thanks. ");

        $jj = "https://smartsmssolutions.com/api/?message=".$message."&to=".$phone."&sender=tradermoni&type=0&routing=3&token=";
        // $kk =   "http://www.50kobo.com/tools/geturl/Sms.php?username=isaacmomoh2000@gmail.com&password=big50kobo&sender=tradermoni&message=".$message."&recipients=".$phone."";
        $m =  file_get_contents($jj);
        $s =  json_decode($m);
        if ($s) {
            return true;
        }
    }

    public function refPurge($id,$ref){
        $this->load->model('referral_model', 'Referral');
        $p =   $this->Referral->UpdateStatus($id,$ref);
        if (!$p){echo "error";}
        $user =  $this->User->getSingle($id);

        $m =         $this->User->update($id, array(
            'soft_hide' => 1,
        ));
        if($m){

            $this->purgesms($user->phone,$user->username);

            $this->session->set_flashdata('success', 'Referral Successfully Purged');
            redirect('back_office/referrals');
        }
    }



    public function referrals() {

        $this->load->model('referral_model', 'Referral');

        $this->data->summary = $this->Referral->summary($this->userId);
        $this->data->sponsor = $this->Referral->getReferrerDetails($this->profile->referrer_id);

        $this->data->totalReferrals = 0;
        $this->data->level = 1;

        $this->data->totalEarned = 0;
        foreach ($this->data->summary as $lvl) {
            $this->data->totalReferrals += $lvl->referrals;
            $this->data->totalEarned    += $lvl->earning;
        }

        $this->data->refUrl = SITE_ADDRESS.'ref/'.$this->profile->salt;

        $this->data->directRefs = $this->Referral->countReferrals($this->userId, TRUE);
        $this->data->invites = $this->Referral->getInvites($this->userId);

        $this->addJavascript(asset('scripts/replace.js'));
        $this->addJavascript('/layout/member/assets/js/referrals.js');
        $this->addStyleSheet('/layout/member/assets/css/referrals_b.css');

        $this->data->page_title = 'Referrals';
        return $this->loadPartialView('member/referrals');
    }

    public function referrals_list() {

        $this->load->model('referral_model', 'Referral');

        $this->data->refCount = $this->Referral->getCounts($this->userId);
        $this->data->refUrl = SITE_ADDRESS.'ref/'.$this->profile->salt;

        $this->addJavascript(asset('scripts/tabs.js'));
        $this->addStyleSheet('/layout/member/assets/css/referrals_b.css');

        $this->data->page_title = 'Referrals';
        return $this->loadPartialView('member/referrals_list');
    }

    public function referrals_expiring() {
        $this->load->model('payment_model', 'Payment');
        $this->data->referrals = $this->Payment->getReferralsExpiring($this->userId);

        foreach ($this->data->referrals as &$r) {

            $r->settings   = $this->User->getSettings($r->id);
            $r->socialList = $this->User->getSocialNetworks($r->id);

            if (!isset($r->show_email)) $r->show_email = 1;
            if (!isset($r->show_skype)) $r->show_skype = 1;
            if (!isset($r->show_phone)) $r->show_phone = 1;
            if (!isset($r->show_social)) $r->show_social = 1;
            if (!isset($r->show_avatar)) $r->show_avatar = 1;
        }
        $this->data->page_title = 'Referrals Expiring';
        return $this->loadPartialView('member/referrals_expiring');
    }
    /*     * *********
        *
        * PROMOTION
        *
        */

    public function promotion() {

        $this->data->page_title = 'Marketing';

        if ($this->profile->account_level == 0) {
            $this->data->message = "Please ".anchor('back_office/upgrade', 'upgrade')." to enable your referral link";
            return $this->loadPartialView('partial/error');
        }

        $this->load->model('referral_model', 'Referral');

        $this->data->refUrl = SITE_ADDRESS.'ref/'.$this->profile->salt;
        //$this->data->totals = $this->Referral->getReferralTotals($this->profile->id);

        $this->data->banners = array_slice(scandir(FCPATH.'banners'), 2);

        return $this->loadPartialView('member/promotion');
    }


    public function bonus() {

        $this->data->page_title = 'Referral Bonus';

        if ($this->profile->account_level == 0) {
            $this->data->message = "Please ".anchor('back_office/upgrade', 'upgrade')." to enable your referral link";
            return $this->loadPartialView('partial/error');
        }
        $this->load->model('ph_model','PhModel');
        $this->load->model('gh_model','GhModel');
        $this->load->model('referral_model', 'Referral');

        $this->data->lock = $this->profile->locked;


        $this->data->gbgh = $this->GhModel->getBonusGH($this->profile->id);
        $this->data->fbl= $this->PhModel->getFirstLevelBonus($this->profile->id,30);
        $this->data->sbl= $this->PhModel->getSecondLevelBonus($this->profile->id,50);
        $this->data->fab= $this->PhModel->getFirstAvailableBonus($this->profile->id,25);
        $this->data->sab= $this->PhModel->getSecondAvailableBonus($this->profile->id,25);
        $this->data->gast= $this->PhModel->getAvailableSumTotal($this->profile->id);
        $this->data->flt= $this->PhModel->getFirstLevelTotal($this->profile->id);
        $this->data->slt= $this->PhModel->getSecondLevelTotal($this->profile->id);
        $this->data->gabsf= $this->PhModel->getAvailbleBonusSumFirst($this->profile->id);
        $this->data->gabss= $this->PhModel->getAvailbleBonusSumSecond($this->profile->id);
        $this->data->countfbl= $this->PhModel->countFbl($this->profile->id);

        $this->data->refUrl = SITE_ADDRESS.'ref/'.$this->profile->salt;
        //$this->data->totals = $this->Referral->getReferralTotals($this->profile->id);

        $this->data->banners = array_slice(scandir(FCPATH.'banners'), 2);

        return $this->loadPartialView('member/bonus');
    }


    public function verify_validation() {
        $response = array('success' => FALSE);
        if ($_POST && $this->ajax) {
            if ($sum = $this->input->post('sum')) {
                $rand1 = intval($this->session->userdata('rand1'));
                $rand2 = intval($this->session->userdata('rand2'));

                if ($sum == ($rand1 + $rand2)) {
                    $response = array('success' => TRUE);
                }
            }
        }
        echo json_encode($response);
    }

    public function site_status($id, $status) {
        $this->load->model('surf_model', 'Surf');
        if ($status == 'active' || $status == 'paused') {
            $this->Surf->updateSite($id, array('status' => $status));
            echo $status.' ['.anchor(site_url('member/site_status/'.$id.'/'.($status == 'active' ? 'paused' : 'active')), ($status == 'active' ? 'pause' : 'activate'), 'class="replace" data-div="surfSiteStatus"').']';
        } elseif ($status == 'deleted') {
            $this->Surf->updateSite($id, array('status' => $status));
            echo 'sucess';
        } else {
            echo 'error';
        }
    }

    /** ************
     *
     * TESTIMONIALS
     *
     */

    public function testimonials() {

        $this->addStyleSheet('/layout/member/assets/css/testimonials.css');
        $this->addJavascript('/assets/scripts/screenshot.js');
        $this->load->model('ph_model','PhModel');
        $this->load->model('testimonial_model', 'Testimonial');

        $this->data->CurrentPlan = $this->PhModel->getCurrentPlan($this->profile->id);
        $this->data->refUrl = SITE_ADDRESS.'ref/'.$this->profile->salt;
        $this->data->testimonials = $this->Testimonial->getSome();
        $testimonial = $this->Testimonial->getByUser($this->userId);
        if ($testimonial) {
            $this->data->canAdd = FALSE;
            $this->data->pending = $testimonial->status == 'pending';
        } else {
            $this->data->canAdd = TRUE;

            $this->load->model('db_form');
            $form = new RMSForm('member_forms/', 'testimonial');
            $this->data->testimonialForm = $form->render(array());
        }

        $this->data->page_title = 'Testimonials';
        return $this->loadPartialView('member/testimonials');

    }

    /** ************
     *
     * Products
     *
     */

    public function rewards() {

        $this->addStyleSheet('/layout/member/assets/css/rewards.css');

        $this->load->model('product_model', 'Product');

        $this->data->products = $this->Product->getRewards($this->profile->account_level);

        $this->data->page_title = 'Rewards';
        return $this->loadPartialView('member/products');
    }

    /*     * ************
          *
          * SUPPORT
          *
          */

    public function support() {
        $this->load->model('gh_model', 'GH');
        $this->addStyleSheet('styles/support.css');
        $this->data->userId = $this->profile ? $this->profile->id : 0;



            $this->data->valid = $this->GH->getUserGHstats($this->profile->id);
            $this->data->openTickets = $this->Ticket->getSummary($this->data->userId, FALSE, 'open');
            $this->data->closedTickets = $this->Ticket->getSummary($this->data->userId, FALSE, 'closed');

        $this->data->page_title = 'Support';
        return $this->loadPartialView('member/support');
    }

    /*     * ****************
     * Generic modal entry point
     * @param $view
     *
     */

    public function modal($view) {
        $data['title'] = ucwords(str_replace('_', ' ', $view));
        $data['body']  = $this->loadPartialView('member/partial/'.$view);
        echo json_encode($data); //->loadPartialView('partial/modal');
    }

    /*     * ****************
     * Generic static page view
     * @param $view
     *
     */

    public function view($view) {
        if ($this->ajax) {
            echo $this->loadPartialView('member/partial/'.$view);
        } else {
            show_404();
        }
    }

    /*     * *****************
     * Main Form Handler
     * @param $formName
     *
     */

    public function form($formName, $id = NULL) {

        $this->data->formURL = site_url("member/form/$formName");
        $data                = NULL;
        if ($id) {
            $this->data->formURL .= '/'.$id;
        }
        if ($formName == 'testimonial') {
            $this->load->model('testimonial_model', 'Testimonial');
            if ($this->Testimonial->check($this->userId)) {


                $result = array(
                    'error'  => '<div class="alert alert-danger"><i class="fa exclamation-circle red" aria-hidden="TRUE"></i>&nbsp;Only one testimonial per member.</div>',
                    'redirect' => array(
                        'url' => SITE_ADDRESS.'back_office/',
                        'timeout' => 5000
                    ));

            }
        }
        $this->_path = 'member/';
        if ($_POST) {
            $_POST['user_id'] = $this->profile->id;
            $memberId = NULL;
            if ($formName == 'invite' && $id) {
                $memberId = $id;
                $id = NULL;
            }
        } else {

            if ($this->User->getSetting($this->profile->id, $formName.'_instructions') != 'off') {
                if (file_exists(APPPATH."views/member/partial/".$formName.'_instructions.php')) {
                    $this->data->instructions = $this->loadPartialView("member/partial/".$formName."_instructions");
                }
            }
        }

        $result = $this->doForm($formName, $id, $data);
///ssprint_r($formName);die();

        if ($_POST) {
            if (!is_array($result)) { // returns an ID if successful, error is always an array

                switch ($formName) {
                    case "bitcoin_wallet":
                        $this->load->model('email_model', 'EmailQueue');
                        $this->EmailQueue->store($this->profile->email, 'Payment Account Changed', 'emails/member/account_changed', array('username' => $this->profile->username));
                        $result = array(
                            'success'  => 'Success! Redirecting back to accounts...',
                            'redirect' => array(
                                'url' => SITE_ADDRESS.'back_office/accounts'
                            )
                        );
                        break;

                    case "ngn_wallet":
                        $this->load->model('email_model', 'EmailQueue');
                        $this->EmailQueue->store($this->profile->email, 'Payment Account Changed', 'emails/member/account_changed', array('username' => $this->profile->username));
                        $result = array(
                            'success'  => 'Success! Redirecting back to accounts...',
                            'redirect' => array(
                                'url' => SITE_ADDRESS.'back_office/accounts'
                            )
                        );
                        break;
                    case "testimonial":
                        $result = array(
                            'success'  => 'Success! Your testimonial has been submitted for admin approval. You will receive an email after admin has reviewed it.',
                            'redirect' => array(
                                'url' => SITE_ADDRESS.'back_office/',
                                'timeout' => 5000
                            ));
                        break;
                    case "invite":
                        if ($iResult = $this->process_invite($result, $memberId)) {
                            $result = array(
                                'success'  => 'Success! Invitation sent.',
                                'redirect' => array(
                                    'url' => SITE_ADDRESS.'back_office/referrals'
                                )
                            );
                        } else {
                            $result = array(
                                'error'  => $iResult
                            );
                        }
                        break;
                    case 'text_ad':
                        if ($this->input->post('credits') > 0) {
                            $this->User->subtractCredits($this->userId, intval($this->input->post('credits')), 'text_ad');
                        }

                        $creditTotal = $this->User->getData($this->userId, array('text_ad_credits'))->text_ad_credits;
                        $report      = new RMSList('member_lists/', 'user_text_ads', "member/getList/user_text_ads/?user_id=".$this->userId);
                        $where       = array('user_id' => $this->userId);

                        $result = array(
                            'html'    => '<p>Your text ad has been submitted and is pending approval.<br/><button type="button" class="btn btn-alt btn-sm" data-dismiss="modal">Close</button></p>',
                            'replace' => array(
                                'textAdList'  => $report->set_where($where)->getPartial()->render(),
                                'creditTotal' => number_format($creditTotal)
                            )
                        );
                        break;
                    case 'banner_ad':
                        if ($this->input->post('credits') > 0) {
                            $this->User->subtractCredits($this->userId, intval($this->input->post('credits')), 'text_ad');
                        }

                        $creditTotal = $this->User->getData($this->userId, array('text_ad_credits'))->text_ad_credits;
                        $report      = new RMSList('member_lists/', 'user_banner_ads', "member/getList/user_banner_ads/?user_id=".$this->userId);
                        $where       = array('user_id' => $this->userId);

                        $result = array(
                            'html'    => '<p>Your Banner ad has been submitted and is pending approval.<br/><button type="button" class="btn btn-alt btn-sm" data-dismiss="modal">Close</button></p>',
                            'replace' => array(
                                'textAdList'  => $report->set_where($where)->getPartial()->render(),
                                'creditTotal' => number_format($creditTotal)
                            )
                        );
                        break;
                    default:
                        $result = array(
                            'success' => 'Success!'
                        );
                }
            }
            echo json_encode($result);
        }
    }

    public function text_ad_case() {
        if ($_POST['headline'] == strtoupper($_POST['headline'])) $_POST['headline'] = ucwords($_POST['headline']);
        if ($_POST['body'] == strtoupper($_POST['body'])) $_POST['body'] = ucwords($_POST['body']);
    }

    private function process_invite($inviteId, $id) {

        $this->load->helper('guid');
        $this->load->model('referral_model', 'Referral');

        $userData = $this->input->post();
        $userData['activation_code'] = create_guid();
        $userData['account_expires'] = now() + (INVITE_EXPIRATION*CACHE_ONE_HOUR);
        $data['activation_code'] = $userData['activation_code'];
        if ($id) {
            $user = $this->User->getData($id, array('email', 'username', 'account_level', 'first_name', 'last_name'));
            if ($user->account_level > 0) {
                $data['user_id']         = $id;
                $data['sponsor_user_id'] = $this->userId;

                $eData['upline'] = $this->profile->first_name.' '.$this->profile->last_name;
                $eData['to']     = $user->username;
                $eData['invitee'] = $userData['first_name'].' '.$userData['last_name'];
                $this->EmailQueue->store($user->email, SITE_NAME.' Invite sent on your behalf', 'emails/user/invite_sent', $eData);
            }
            $userData['upline'] = $user->first_name.' '. $user->last_name;
        }
        $userData['inviter'] = $this->profile->first_name.' '.$this->profile->last_name;

        $this->Referral->update('invite', $inviteId, $data);
        $this->EmailQueue->store($userData['email'], 'You are invited to join '.SITE_NAME, 'emails/user/invite', $userData,1);

        $this->session->set_flashdata('success', 'Success! Invitation sent to '.$userData['email']);

        return TRUE;
    }

    /*     * *****************
     * generic "turn this off" entry point
     * @param $setting
     *
     */

    public function dontShow($setting) {
        $this->User->addSetting($this->profile->id, $setting, 'off');
    }

    /*     * *****************
     *
     * FORM VALIDATION
     *
     */
    public function bc_account_check($address) {

        $this->load->helper('bitcoin');
        if (!wallet_check($address)) {

            $this->form_validation->set_message('bc_account_check', '* invalid');
            return FALSE;
        }

        if ($this->PaymentMethod->checkAccountExists('bc', $address)) {
            $this->form_validation->set_message('bc_account_check', '* already in use');
            return FALSE;
        }

        return TRUE;
    }

    public function tx_dup_check($txId) {

        $this->load->model('payment_model', 'Payment');
        if ($this->Payment->find_txid($txId)) {

            $this->form_validation->set_message('tx_dup_check', '* already used');
            return FALSE;
        }

        return TRUE;
    }

    public function email_check($param) {
        $this->load->model('referral_model', 'Referral');
        if ($user = $this->ion_auth->email_check($param)) {
            $this->form_validation->set_message('email_check', '* already in use');
            return FALSE;
        } elseif ($this->Referral->checkInviteEmail($param)) {
            $this->form_validation->set_message('email_check', '* already in use');
            return FALSE;
        }

        return TRUE;
    }

    public function currency_check($param) {
        if ($this->input->post('currency') != 'USD') {
            if (empty($param)) {

                $this->form_validation->set_message('currency_check', '* required when currency is not USD');
                return FALSE;
            }
            if (!is_numeric($param)) {
                $this->form_validation->set_message('currency_check', '* invalid number');
                return FALSE;
            }
        }
        return TRUE;
    }

    public function valid_image_url() {

        $src_image_path = urldecode($this->input->post('image_url'));

        if (!filter_var($src_image_path, FILTER_VALIDATE_URL) === FALSE) {
            if ($info = @getimagesize($src_image_path)) {
                if (in_array($info[2], array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
                    $size = $this->input->post('size');
                    $dimensions = explode('x', $size);
                    if ($info[0] != $dimensions[0] || $info[1] != $dimensions[1]) {
                        $this->form_validation->set_message('valid_image_url', '* incorrect size');
                        return FALSE;
                    }
                } else {
                    $this->form_validation->set_message('valid_image_url', '* invalid image type');
                    return FALSE;
                }
            }
        } else {
            $this->form_validation->set_message('valid_image_url', '* invalid URL');
            return FALSE;
        }

        return TRUE;
    }

    function get_thumbnail($img) {

        if (empty($img))
            return FALSE;

        $src_image_path = $this->session->userdata('image_url').$img;

        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($src_image_path);
        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($src_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($src_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($src_image_path);
                break;
        }
        $source_aspect_ratio    = $source_image_width/$source_image_height;
        $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH/THUMBNAIL_IMAGE_MAX_HEIGHT;
        if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
            $thumbnail_image_width  = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width  = (int)(THUMBNAIL_IMAGE_MAX_HEIGHT*$source_aspect_ratio);
            $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
        } else {
            $thumbnail_image_width  = THUMBNAIL_IMAGE_MAX_WIDTH;
            $thumbnail_image_height = (int)(THUMBNAIL_IMAGE_MAX_WIDTH/$source_aspect_ratio);
        }
        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);

        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
        header('Content-Type: image/jpeg');
        imagejpeg($thumbnail_gd_image, NULL, 90);
        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        return TRUE;
    }

    function create_proofs($src_image_path, $filename) {

        if (empty($src_image_path))
            return TRUE;

        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($src_image_path);
        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($src_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($src_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($src_image_path);
                break;
        }
        $source_aspect_ratio    = $source_image_width/$source_image_height;
        $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH/THUMBNAIL_IMAGE_MAX_HEIGHT;
        if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
            $thumbnail_image_width  = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width  = (int)(THUMBNAIL_IMAGE_MAX_HEIGHT*$source_aspect_ratio);
            $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
        } else {
            $thumbnail_image_width  = THUMBNAIL_IMAGE_MAX_WIDTH;
            $thumbnail_image_height = (int)(THUMBNAIL_IMAGE_MAX_WIDTH/$source_aspect_ratio);
        }
        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);

        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
        imagejpeg($thumbnail_gd_image, FCPATH.'thumbnails/'.$filename.'.jpg', 90);
        imagejpeg($source_gd_image, FCPATH.'proofs/'.$filename.'.jpg', 90);
        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        return TRUE;
    }

    public function valid_secret($answer) {
        if ($answer != $this->User->getData($this->userId, array('secret_answer'))->secret_answer) {
            $this->form_validation->set_message('valid_secret', '* incorrect');
            return FALSE;
        }
        return TRUE;
    }

    public function valid_text_credits($credits) {
        $has = $this->User->getData($this->userId, array('text_ad_credits'))->text_ad_credits;
        if ($credits > $has) {
            $this->form_validation->set_message('valid_text_credits', '* you only have '.$has.' credits');
            return FALSE;
        }
        return TRUE;
    }

    public function valid_banner_credits($credits) {
        if ($credits > $this->profile->banner_credits) {
            $this->form_validation->set_message('valid_banner_credits', '* too many');
            return FALSE;
        }
        return TRUE;
    }

    public function signups() {
        return $this->viewList('signups');
    }

    public function click_stats() {
        return $this->viewList('click_stats');
    }

    public function viewList($listName) {

        $this->data->listName = $listName;
        return $this->loadPartialView('member/list');
    }

    public function getList($listName, $sortCol = '', $sortDir = '', $page = 1, $perPage = '') {
        if (!$this->isAdmin && (($uid = $this->input->get('user_id')) !== FALSE && $uid != $this->profile->id)) {
            echo 'Invalid access.';
            return;
        }

        $this->_path = (strpos($listName, 'forum') !== FALSE) ? 'forum/' : 'member/';
        if ($listName == 'signups') $_GET = array('users.sponsor_id' => $this->userId);

        return parent::getList($listName, $sortCol, $sortDir, $page, $perPage);
    }

    public function save_avatar() {
        if ($this->ajax) {
            if (($imgFile = $this->input->post('banner_img')) !== FALSE) {

                if (!empty($this->profile->avatar))
                    @unlink(FCPATH.'avatars/'.$this->profile->avatar);

                $this->ion_auth->update($this->profile->id, array('avatar' => $imgFile));

                $data = array(
                    'callback' => 'avatarSaved',
                    'replace'  => array(
                        'avatarContent' => img(avatar($imgFile)),
                        'profilePic'    => img(array(
                                'src'   => avatar($imgFile),
                                'class' => 'profile-pic animated')
                        )
                    )
                );
            } else {
                $data = array(
                    'error' => 'An unknown error has occurred'
                );
            }
            echo json_encode($data);
        } else
            show_404();
    }

    public function set_avatar() {
        if ($this->ajax) {
            if (($imgFile = $this->input->post('avatar')) !== FALSE) {

                if (!empty($this->profile->avatar) && strpos($this->profile->avatar, 'default') === FALSE) {

                    @unlink(FCPATH.'avatars/'.$this->profile->avatar);
                }

                $this->ion_auth->update($this->profile->id, array('avatar' => $imgFile));

                $data = array(
                    'success' => TRUE,
                );
            } else {
                $data = array(
                    'error' => 'An unknown error has occurred'
                );
            }
            echo json_encode($data);
        } else
            show_404();
    }

    public function cancel_avatar() {
        if ($this->ajax) {
            if (($imgFile = $this->input->post('banner_image'))) {
                @unlink(FCPATH.'avatars/'.$imgFile);
            }
        }
    }

    function get_headers_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $r = curl_exec($ch);
        $r = explode("\n", $r);
        return $r;
    }

    public function wget_avatar() {
        $data = array(
            'success' => 0,
            'error'   => 0
        );
        if ($this->ajax) {
            if (($imgFile = $this->input->post('avatar'))) {

                if (preg_match('((http|https):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=‌​%&amp;/~\+#])?)', $imgFile)) {

                    $headers = $this->get_headers_curl($imgFile);
                    if (isset($headers[6])  && $headers[6] > 100000) {

                        $data = array(
                            'error' => '* File size too large - 100KB maximum.'
                        );

                    } elseif (list($width, $height, $type, $attr) = @getimagesize($imgFile)) {
                        if ($width <= 125 && $height <= 125) {
                            $types = array(IMAGETYPE_JPEG => '.jpg', IMAGETYPE_GIF => '.gif', IMAGETYPE_PNG => '.png');
                            if (!array_key_exists($type, $types)) {
                                $data['error'] = '* Incorrect type - use gif, jpg, or png only.';
                                echo json_encode($data);
                                return;
                            }
                            $avatar = md5(uniqid(mt_rand())).$types[$type];
                            $path   = FCPATH.'avatars/';

                            copy($imgFile, $path.$avatar);

                            if ($this->profile->avatar) {
                                if (strpos($this->profile->avatar, 'default') === FALSE)
                                    @unlink(FCPATH.'avatars/'.$this->profile->avatar);
                            }

                            $this->User->update($this->userId, array('avatar' => $avatar));

                            $data = array(
                                'error'   => 0,
                                'success' => 'success',
                                'imgFile'  => $avatar
                            );
                        } else {
                            $data = array(
                                'error' => '* Incorrect size - must be 125x125 maximum.'
                            );
                        }
                    } else {
                        $data = array(
                            'error' => '* Does not point to an image.'
                        );
                    }
                } else {
                    $data = array(
                        'error' => '* invalid URL'
                    );
                }
            }
        }
        echo json_encode($data);
        return;
    }

    public function upload_avatar() {
        $config = array(
            'upload_path'   => FCPATH.'avatars/',
            'allowed_types' => 'gif|jpg|png',
            'max_size'      => 500,
            'encrypt_name'  => TRUE
        );

        $config['max_width']  = 125;
        $config['max_height'] = 125;

        $this->load->library('upload', $config);

        $data = NULL;
        if (!$this->upload->do_upload('banner')) {
            $data = array(
                'error' => $this->upload->display_errors('', '')
            );
            @unlink($_FILES['banner']['tmp_name']);
        } else {
            $image = $this->upload->data();

            if ($this->profile->avatar) {
                if (strpos($this->profile->avatar, 'default') === FALSE)
                    @unlink(FCPATH.'avatars/'.$this->profile->avatar);
            }

            $this->User->update($this->userId, array('avatar' => $image['file_name']));
            $data = array(
                'success' => 'success',
                'file'    => $image['file_name'],
                'banner'  => base_url().'avatars/'.$image['file_name']
            );

            $data = $data + $image;
        }

        echo json_encode($data);
    }



    public function upload_proof() {
        $config = array(
            'upload_path'   => FCPATH.'proofs/',
            'allowed_types' => 'gif|jpg|png',
            'max_size'      => 500,
            'encrypt_name'  => TRUE
        );

        $config['max_width']  = 800;
        $config['max_height'] = 800;

        $this->load->library('upload', $config);

        $data = NULL;
        if (!$this->upload->do_upload('banner')) {
            $data = array(
                'error' => $this->upload->display_errors('', '')
            );
            @unlink($_FILES['banner']['tmp_name']);
        } else {
            $image = $this->upload->data();

            $data = array(
                'success' => 'success',
                'file'    => $image['file_name'],
                'banner'  => base_url().'proofs/'.$image['file_name']
            );

            $data = $data + $image;
        }

        echo json_encode($data);
    }


    public function submit_proof() {

        if (!$_POST || !$this->ajax)
            show_error('Invalid entry');


        if ($this->form_validation->run('btc_payment_proof') == FALSE) {

            $result['errorElements'] = $this->form_validation->error_array();
            echo $result['errorElements'];

        }
        else {

            // $this->load->helper('bitcoin');
            $this->load->model('payment_model', 'Payment');
            $this->load->model('my_account_model', 'Account');

            $payment = $this->input->post();

            // $upload = $this->upload_proof();

            $payeeWallet = $this->Payment->getWallet($payment['payee_user_id']);

            $upgrades = $this->Account->getMemberships();
            $time = $this->profile->upgrade_time;

            $this->load->library('encrypt');
            $level = $this->encrypt->decode($payment['level']);

            if (!array_key_exists($level, $upgrades)) {

                $result['error'] = 'Problem with the form. Try reloading the page.';

            } else if (floatval($payment['amount']) < ($upgrades[$level]->price - floatval(PAYMENT_VARIANCE)) ||
                floatval($payment['amount']) > ($upgrades[$level]->price + floatval(PAYMENT_VARIANCE))) {

                $result['errorElements'] = array('amount' => '* outside acceptable range.');

            } elseif (($txCheck = transaction_check($payment['transaction_id'], $payeeWallet->account, $payment['amount'], $time) ) !== TRUE) {

                $result['error'] =  $txCheck;

            } else {

                $payment['payer_user_id']   = $this->profile->id;
                $payment['method_id']       = $payeeWallet->id;
                $payment['upgrade_id']      = $upgrades[$level]->id;
                $payment['amount']          = $upgrades[$level]->price;
                $payment['created']         = now();
                $payment['confirmations']   = 0;
                $payment['currency_amount'] = 0;

                unset($payment['level']);

                $pId = $this->Payment->create($payment);

                $payment = $this->Payment->getFull($pId);

                $this->load->model('email_model', 'EmailQueue');
                $this->EmailQueue->store($payment->payee_email, '['.SITE_NAME.'] Payment submitted', 'emails/cashier/payment_submitted', compact('payee', 'payment'),1);

                // check if payment passed above intended upline and, if so, send email notification of missed payment.
                $uplineId = $this->profile->referrer_id;
                for ($i = 1; $i <= $level && $uplineId >= DEFAULT_USER_ID && $uplineId > 0; $i++) {

                    $upline   = $this->User->getData($uplineId, array('id', 'username', 'email', 'referrer_id'));
                    $uplineId = $upline->referrer_id;
                }

                if ($upline->id > DEFAULT_USER_ID && $upline->id != $payment->payee_user_id) {
                    $data['username'] = $upline->username;
                    $data['payer']    = $payment->payer_name;
                    $data['level']    = $level;
                    $data['amount']   = money($payment->price);

                    $this->EmailQueue->store($upline->email, '['.SITE_NAME.'] Missed donation', 'emails/cashier/payment_missed', $data);
                }

                $this->session->unset_userdata('upgrade_level');
                $this->User->update($this->userId, array('upgrade_time' => 0));

                $result = array(
                    'success'  => 'Ok',
                    'redirect' => 'reload'
                );
            }
        }
        echo json_encode($result);
    }




    function upload_pop(){
        $config['upload_path']          = FCPATH.'proofs/';
        $config['allowed_types']        = 'gif|jpg|jpeg|png';
        $config['overwrite']            = TRUE;
        $config['encrypt_name']         = TRUE;
        $config['max_size']             = 1024*2;
        $config['max_width']            = 3500;
        $config['max_height']           = 3500;

//var_dump($config['upload_path']);

        $_FILES['proof_img']['name'] = strtolower($_FILES['proof_img']['name']);
        if (empty($_FILES['proof_img']['name']))
        {
            // $this->form_validation->set_rules('proof_img', 'Upload Your Image', 'required');
            $this->session->set_flashdata('error', 'You did not upload any image');
            redirect('back_office/upgrade');
        }


        $this->load->library('upload', $config);

        if($this->upload->do_upload('proof_img'))

        {
            $mydata = $this->upload->data();
            $this->load->model('payment_model', 'Payment');
            $this->load->model('ph_model', 'PH');
            $this->load->model('gh_model', 'GH');
            $this->load->model('my_account_model', 'Account');
            $payment = $this->input->post();





            $payeeWallet = $this->Payment->getWallet($payment['payee_user_id']);


            $upgrades = $this->Account->getMemberships();
            $time = $this->profile->upgrade_time;

            $this->load->library('encrypt');
            $level = $this->encrypt->decode($payment['level']);

            if (!array_key_exists($level, $upgrades)) {

                $result['error'] = 'Problem with the form. Try reloading the page.';

            } else if (floatval($payment['amount']) < ($upgrades[$level]->price - floatval(PAYMENT_VARIANCE)) ||
                floatval($payment['amount']) > ($upgrades[$level]->price + floatval(PAYMENT_VARIANCE))) {

                $result['errorElements'] = array('amount' => '* outside acceptable range.');

            }
            else {
                $payee = $payment['payee_user_id'];
                $amount = $payment['amount'];
                $pidd = $payment['somabi'];
                $ph_id = $payment['pstd'];
                $gh_id = $payment['gstd'];

                $payment['payer_user_id']   = $this->profile->id; //$this->session->userdata('user_id')
                $payment['payee_user_id'] = $payee;
                $payment['method_id']       = $payeeWallet->id;
                $payment['upgrade_id']      = $upgrades[$level]->id;
                $payment['amount']          = $amount;
                $payment['created']         = now();
                $payment['confirmations']   = 0;
                $payment['currency_amount'] = 0;
                $expired = now() + 172800; // plus 48 hours
//                $payment['approved'] = 0;
//                $payment['rejected'] = 0;


                unset($payment['level']);
                //  var_dump($payment);
                // var_dump($mydata["file_name"]);
                $lid =  $this->Payment->get_last();
                $lrow = $this->Payment->getLast($lid->id);
                if ($lrow->payer_user_id == $this->profile->id && $lrow->payee_user_id ==$payee && $lrow->amount == $amount){
                    $this->session->set_flashdata('error', 'You Have Already Submitted your POP.');
                    redirect('back_office/upgrade');
                }

                // $pId = $this->Payment->add($this->profile->id,$payee,$amount,now(),$expired,0,0,NULL,NULL,strtolower($mydata["file_name"]),$upgrades[$level]->id,$payment['method_id']);

                $this->Payment->updatePay($pidd, array(
                    'proof_img' => strtolower($mydata["file_name"]),
                ));

                $this->PH->updatePHupload($ph_id, array(
                    'status' => 3,
                ));

                $this->GH->updateGHupload($gh_id, array(
                    'status' => 3,
                ));

                $payment = $this->Payment->getFull($pidd);

                $this->load->model('email_model', 'EmailQueue');
                $this->load->model('user_model', 'user');
                // $m =  $this->user->getEmailById(1);
                $mail =  $this->Payment->awaitingConfSingle($this->profile->id);
//               var_dump($mail->email);


                $em = $this->EmailQueue->store($mail->email, '['.SITE_NAME.'] Payment submitted', 'emails/cashier/payment_submitted', compact('payee', 'payment'),1);

                if ($em){
                    $this->sendsms($mail->phone,$this->profile->username,$mail->username,$mail->amount);
                }

                // check if payment passed above intended upline and, if so, send email notification of missed payment.
                $uplineId = $this->profile->referrer_id;
                for ($i = 1; $i <= $level && $uplineId >= DEFAULT_USER_ID && $uplineId > 0; $i++) {

                    $upline   = $this->User->getData($uplineId, array('id', 'username', 'email', 'referrer_id','phone'));
                    $uplineId = $upline->referrer_id;
                }

                if ($upline->id > DEFAULT_USER_ID && $upline->id != $payment->payee_user_id) {
                    $data['username'] = $upline->username;
                    $data['payer']    = $payment->payer_name;
                    $data['level']    = $level;
                    $data['amount']   = money($payment->price);

//                   $p = $this->EmailQueue->store($upline->email, '['.SITE_NAME.'] Missed donation', 'emails/cashier/payment_missed', $data,1);
//                    if ($p){
//                       // $this->sendsms2($upline->phone,$payment->payer_name,$upline->username,money($payment->price));
//                    }

                }

                $this->session->unset_userdata('upgrade_level');
                $this->User->update($this->userId, array('upgrade_time' => 0));

//                $result = array(
//                    'success'  => 'Ok',
//                    'redirect' => 'reload'
//                );

                $this->session->set_flashdata('success', 'Proof of Payment uploaded successfully.');
                redirect('back_office/dashboard');

                // $this->load->view('upload_success',$data);
            }
        }
        else
        {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $error);
            redirect('back_office/upgrade');
            //  var_dump($error);
            //$this->load->view('file_view', $error);
        }
    }


    function check_payment(){
        $this->load->model('payment_model', 'Payment');
        $data =  $this->Payment->awaitingConfirmation($this->profile->id);
        foreach ($data as $dat){
            $data['payee_user_id'] = $dat['payee_user_id'];
            if($dat->payee_user_id == $this->profile->id);
            $this->load->view('member/dashboard', $dat['payee_user_id']);

        }


    }




}
