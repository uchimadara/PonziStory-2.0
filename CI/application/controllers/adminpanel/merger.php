<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include('admin.php');

class Merger extends Admin {

    public function __construct() {
        parent::__construct(TRUE);
        if ($this->ion_auth->is_support()){
            echo "You are not permitted to view this page";
            exit;
        }

        $this->layoutData['title'] = 'Merger';
        $this->load->model('Settings_model', 'Settings');
        $this->layout = 'layout/admin/shell';
    }

    public function index($ghAmt=0,$phAmt=0,$ghLimit=10,$phLimit=15,$recom=NULL) {
        //var_dump($p);
        $now    = date('Y-m-d');
        $this->load->model('ph_model','PhModel');
        $this->load->model('gh_model','GhModel');

        $this->data->phMerge = $this->PhModel->getPh4Merge($phAmt,$phLimit,$recom);
        $this->data->allPH = $this->PhModel->getAllPHstats();
        $this->data->allPHToday = $this->PhModel->getAllPHstatsToday($now);
        $this->data->allPHSum = $this->PhModel->getAllSumPHstats();
        $this->data->allPHSumToday = $this->PhModel->getAllSumPHstatsToday($now);


        $this->data->GhMerge = $this->GhModel->getGh4Merge($ghAmt,$ghLimit);
        $this->data->BonusMerge = $this->GhModel->getBonus4Merge();
        $this->data->allBonus = $this->GhModel->getAllBonusstats();
        $this->data->allGH = $this->GhModel->getAllGHstats();
        $this->data->allGHToday = $this->GhModel->getAllGHstatsToday($now);
        $this->data->allGHSum = $this->GhModel->getAllSumGHstats();
        $this->data->allGHSumToday = $this->GhModel->getAllSumGHstatsToday($now);



        $this->data->page_title = "Merger";
        $this->data->content = $this->loadPartialView('admin/merger/index');

        $this->addJavascript(asset('bootstrap/js/datetimepicker.min.js'));
        $this->addStyleSheet(asset('bootstrap/css/datetimepicker.min.css'));

        $this->loadView('layout/default', 'Admin Merger');
    }

    public function ph_phones(){

        $m = $this->User->getCurrentPhphones();
        foreach ($m as $n){
            echo $n->phone.",";
        }
    }

    private function mergesms($phone,$username){
        $message =   urlencode(" Dear ".$username.",You have been merged on tradermoni.net with few hours to go. kindly log into your dashboard to verify. Thanks");

        $jj = "https://smartsmssolutions.com/api/?message=".$message."&to=".$phone."&sender=tradermoni&type=0&routing=3&token=";
        // $kk =   "http://www.50kobo.com/tools/geturl/Sms.php?username=isaacmomoh2000@gmail.com&password=big50kobo&sender=tradermoni&message=".$message."&recipients=".$phone."";
        $m =  file_get_contents($jj);
        $s =  json_decode($m);
        return $s;
    }

    public function bonus(){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('ph_model','PhModel');
        $this->load->model('gh_model','GhModel');

        //  echo ($_POST['amount'][1]) ."-". ($_POST['amount2'][1]);
        //echo ($_POST['amount'][0]) - ($_POST['amount2'][0]);

        if(isset($_POST['check2']) && (isset($_POST['check']))){


            if(!empty(($_POST['check']) && ($_POST['check2']) )) {

                $count1 = count($_POST['check']);
                $count2 = count($_POST['check2']);

                if($count1 != $count2){
                    exit("Number of PH not same as GH");
                }


                $expired = now() + 108000; // plus 30 hours

                $count =   count($_POST['check']);
//
                for($i=0;$i<$count;$i++) {
                  $array1 = explode(",",$_POST['check'][$i]);
                    $array2 = explode(",",$_POST['check2'][$i]);
                    print_r($array1[1]);
                    echo "<br/>";
                    print_r($array2[1]);
                    $ghd = $this->GhModel->getGh4MergeSingleBonus($array1[4]);
                    $phd = $this->PhModel->getPh4MergeSingle($array2[3]);

                    $ghamt = (int)$ghd->rem_amount;
                    $phamt = (int)$phd->rem_amount;
                    $guid = (int)$array1[4];
                    $puid = (int)$array2[3];
                    $mid = $ghd->method_id;
                    
                     //  if ($_POST['amount'][$i] < $_POST['amount2'][$i]){

                    if ($ghamt < $phamt){
                        //var_dump($_POST);

                        exit("GH amount is less than PH amount");

                    }

                    if ($guid == $puid){
                        exit("USer canot be merged to himself");
                    }

                    //next update ph table after merge
                    //next to check if it already existed
                    $pch = $this->Payment->checkPaymentExist($puid,$guid,NULL,NULL);
                    if($pch > 0 ){
                        echo "Record Already Existed";
                    }
                    else{
                        $phValues =  $this->PhModel->getZaPh($puid);
                        $ghValues =  $this->GhModel->getZaGhBonus($guid);
                        // var_dump($ghValues->rem_amount);
                        $gha = (int)$ghValues->rem_amount;
                        $ghId = $ghValues->id;
                        $pha = (int)$phValues->rem_amount;
                        $phId = $phValues->id;
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

                        $pId = $this->Payment->add($puid,$guid,$amt,now(),$expired,0,0,NULL,NULL,NULL,1,$mid,$phId,$ghId);

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
                            $this->mergesms($payment->payer_phone,$payment->payer);
                            $this->mergesms($payment->payee_phone,$payment->payee);
                            // $this->load->model('email_model', 'EmailQueue');
                            //  $this->EmailQueue->store($payment->payee_email, '['.SITE_NAME.'] Merge Order', 'emails/cashier/payment_merge_payee', compact('payee', 'payment'),1);
                            //  $this->EmailQueue->store($payment->payer_email, '['.SITE_NAME.'] Merge Order', 'emails/cashier/payment_merge_payer', compact('payer', 'payment'),1);

                        }


                        //echo $_POST['check'][$i] ." | ".$_POST['amount'][$i]." <br> ".$_POST['check2'][$i]." | ".$_POST['amount2'][$i];

                    }
                }
                $this->session->set_flashdata('success', 'Successfully Merged.');
                redirect('adminpanel/merger');


            }

        }
    }

    public function merge(){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('ph_model','PhModel');
        $this->load->model('gh_model','GhModel');

      //  echo ($_POST['amount'][1]) ."-". ($_POST['amount2'][1]);
       //echo ($_POST['amount'][0]) - ($_POST['amount2'][0]);

        if(isset($_POST['check2']) && (isset($_POST['check']))){


            if(!empty(($_POST['check']) && ($_POST['check2']) )) {

                $count1 = count($_POST['check']);
                $count2 = count($_POST['check2']);

                if($count1 != $count2){
                    exit("Number of PH not same as GH");
                }


                $expired = now() + 108000; // plus 30 hours

              $count =   count($_POST['check']);
//
              for($i=0;$i<$count;$i++) {
//                  if($_POST['check'][$i] === $_POST['check2'][$i]){
//                      exit("Cannot Merge User to himself");
//                  }
//                  var_dump($_POST['amount'][$i]);
//                  var_dump($_POST['amount2'][$i]);
//                  var_dump($_POST['method_id'][$i]);
                 $ghd = $this->GhModel->getGh4MergeSingle($_POST['check'][$i]);
                 $phd = $this->PhModel->getPh4MergeSingle($_POST['check2'][$i]);
                //  if ($_POST['amount'][$i] < $_POST['amount2'][$i]){

                  if ($ghd->rem_amount < $phd->rem_amount){
                      //var_dump($_POST);

                      exit("GH amount is less than PH amount");

                  }

                  if ($_POST['check'][$i] == $_POST['check2'][$i]){
                      exit("USer canot be merged to himself");
                  }

                  //next update ph table after merge
                  //next to check if it already existed
                  $pch = $this->Payment->checkPaymentExist($_POST['check2'][$i],$_POST['check'][$i],NULL,NULL);
                  if($pch > 0 ){
                      echo "Record Already Existed";
                  }
                  else{
                      $phValues =  $this->PhModel->getZaPh($_POST['check2'][$i]);
                      $ghValues =  $this->GhModel->getZaGh($_POST['check'][$i]);
                      // var_dump($ghValues->rem_amount);
                      $gha = (int)$ghValues->rem_amount;
                      $ghId = $ghValues->id;
                      $pha = (int)$phValues->rem_amount;
                      $phId = $phValues->id;
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

                  $pId = $this->Payment->add($_POST['check2'][$i],$_POST['check'][$i],$amt,now(),$expired,0,0,NULL,NULL,NULL,1,$ghd->method_id,$phId,$ghId);

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
                    $this->mergesms($payment->payer_phone,$payment->payer);
                    $this->mergesms($payment->payee_phone,$payment->payee);
                   // $this->load->model('email_model', 'EmailQueue');
                  //  $this->EmailQueue->store($payment->payee_email, '['.SITE_NAME.'] Merge Order', 'emails/cashier/payment_merge_payee', compact('payee', 'payment'),1);
                  //  $this->EmailQueue->store($payment->payer_email, '['.SITE_NAME.'] Merge Order', 'emails/cashier/payment_merge_payer', compact('payer', 'payment'),1);


                    $this->session->set_flashdata('success', 'Successfully Merged.');
                    redirect('adminpanel/merger');
                }
                else{ echo "Payment error";}

                  //echo $_POST['check'][$i] ." | ".$_POST['amount'][$i]." <br> ".$_POST['check2'][$i]." | ".$_POST['amount2'][$i];

              }
              }


            }

        }
    }

    public function bulkMerge(){
        $this->load->model('payment_model', 'Payment');
        $this->load->model('ph_model','PhModel');
        $this->load->model('gh_model','GhModel');

        //  echo ($_POST['amount'][1]) ."-". ($_POST['amount2'][1]);
        //echo ($_POST['amount'][0]) - ($_POST['amount2'][0]);

        if(isset($_POST['check2']) && (isset($_POST['check']))){


            if(!empty(($_POST['check']) && ($_POST['check2']) )) {

                $count1 = count($_POST['check']);
                $count2 = count($_POST['check2']);

                if($count1 != $count2){
                    exit("Number of PH not same as GH");
                }


                $expired = now() + 108000; // plus 30 hours

                $count =   count($_POST['check']);
//
                for($i=0;$i<$count;$i++) {
                   $array1 = explode(",",$_POST['check'][$i]);
                    $array2 = explode(",",$_POST['check2'][$i]);
                    print_r($array1[1]);
                    echo "<br/>";
                    echo "-----";
                    print_r($array2[1]);
                    $ghd = $this->GhModel->getGh4MergeSingle($array1[4]);
                    $phd = $this->PhModel->getPh4MergeSingle($array2[3]);
                    //  if ($_POST['amount'][$i] < $_POST['amount2'][$i]){

                    $ghamt = (int)$ghd->rem_amount;
                    $phamt = (int)$phd->rem_amount;
                    $guid = (int)$array1[4];
                    $puid = (int)$array2[3];
                    $mid = $ghd->method_id;
//                    if ($ghamt < $phamt){
//                        //var_dump($_POST);
//
//                        exit("GH amount is less than PH amount");
//
//                    }

                    if ($guid == $puid){
                        exit("USer cannot be merged to himself");
                    }

                    //next update ph table after merge
                    //next to check if it already existed
                    $pch = $this->Payment->checkPaymentExist($puid,$guid,NULL,NULL);
                    if($pch > 0 ){
                        echo "Record Already Existed";
                    }
                    else{
                        $phValues =  $this->PhModel->getZaPh($puid);
                        $ghValues =  $this->GhModel->getZaGh($guid);
                        // var_dump($ghValues->rem_amount);
                        $gha = (int)$ghValues->rem_amount;
                        $ghId = $ghValues->id;
                        $pha = (int)$phValues->rem_amount;
                        $phId = $phValues->id;
                        //var_dump($gha);
                        if ($gha > $pha) {
                            $rem = $gha - $pha;
                            $amt = $pha;
                        }elseif ($gha == $pha){
                            $rem = 0;
                            $amt = $pha;
                        }

                        elseif ($gha < $pha){
                            $Nrem = $pha - $gha;
                            $rem = $Nrem * -1;
                            $amt = $gha;
                        }
                        else{
                            $rem = 1;
                            //meaning there is an error here
                        }

                        $pId = $this->Payment->add($puid,$guid,$amt,now(),$expired,0,0,NULL,NULL,NULL,1,$mid,$phId,$ghId);

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

                            elseif ($rem < 0){
                                //update ph record to merged
                                if(empty(!$phId)){
                                    $this->PhModel->updatePHmerge($phId, array(
                                        'status' => 1,
                                        'rem_amount' => abs($rem)
                                    )); }
                                //update GH record rem_amount
                                $this->GhModel->updateGHmerge($ghId, array(
                                    'status' => 2,
                                    'rem_amount' => 0
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
                            $this->mergesms($payment->payer_phone,$payment->payer);
                            $this->mergesms($payment->payee_phone,$payment->payee);
                             $this->load->model('email_model', 'EmailQueue');
                              $this->EmailQueue->store($payment->payee_email, '['.SITE_NAME.'] Merge Order', 'emails/cashier/payment_merge_payee', compact('payee', 'payment'),1);
                              $this->EmailQueue->store($payment->payer_email, '['.SITE_NAME.'] Merge Order', 'emails/cashier/payment_merge_payer', compact('payer', 'payment'),1);



                        }
                        else{ echo "Payment error";}

                        //echo $_POST['check'][$i] ." | ".$_POST['amount'][$i]." <br> ".$_POST['check2'][$i]." | ".$_POST['amount2'][$i];

                    }
                }
                $this->session->set_flashdata('success', 'Successfully Merged.');
                redirect('/adminpanel/merger');

            }
            else{
                var_dump($_POST['check2']);
                var_dump($_POST['check']);
                echo "2";
            }

        }
        else {
            var_dump($_POST['check2']);
            var_dump($_POST['check']);

        }
    }

    public function insertGHManual(){
        $this->load->model('gh_model','GhModel');
        $this->load->model('ph_model','PhModel');
        $this->load->model('payment_model','Payment');
        $mm = $this->User->getByUsername( $this->input->post('username'),array('id'));
        if($mm->id){

            $payeeWallet = $this->Payment->getWallet($mm->id);

            if($this->input->post('submit')){

                $data = array(
                    "user_id" => $mm->id,
                    "username" => $this->input->post('username'),
                    "amount" => $this->input->post('amount'),
                    "rem_amount" => $this->input->post('amount'),
                    "type"   => "GH",
                    "method_id"   => $payeeWallet->id,
                    "status" => "1",
                    "date_added" => date('Y-m-d'),
                    "date_of_gh" => date('Y-m-d')

                );
                $m =  $this->GhModel->create($data);
                if ($m) {
                    $this->session->set_flashdata('success', 'Successfully Submitted.');
                    redirect('adminpanel/merger');
                }



            }else {
                $this->session->set_flashdata('error', 'Validation error: Select one of the Options');
                redirect('adminpanel/merger');
            }

        }
    }

    public function add() {
        if ($this->ajax) {
            $data = NULL;
            if ($this->form_validation->run('admin/add_settings')) {
                $data = array(
                    'name' => $this->input->post('name'),
                    'module' => $this->input->post('module'),
                    'description' => $this->input->post('description'),
                    'format' => $this->input->post('format'),
                    'label' => $this->input->post('label'),
                    'value' => $this->input->post('value')
                );

                if ($this->Settings->add($data)) {
                    $data = array(
                        'html' => '<div align="center"><h2>Your data has been successfully saved.</h2></div>'
                    );
                } else {
                    $data = array(
                        'error' => renderErrors(array(
                            'ERROR:' => 'Something wrong happened'
                        ))
                    );
                }
            } else {
                $data = array(
                    'error' => renderErrors($this->form_validation->error_array())
                );
            }

            echo json_encode($data);
        } else {
            $this->data->page_title = "Admin Add Setting";
            $this->data->widgets = $this->loadPartialView('admin/settings/add');
            $this->loadView('member/shell', 'Admin Add Setting');
        }
    }

    public function update($id) {
        if ($this->ajax) {
            $data = NULL;

            $setting = $this->Settings->getSetting($id);
            switch ($setting->format) {
                case 'date':
                case 'datetime':
                    $val = strtotime($this->input->post('value'));
                    break;

                case 'yes_no_int':
                    $val = $this->input->post('value') ? '1' : '0';
                    break;

                default:
                    $val = $this->input->post('value');
            }
            $data = array('value' => $val);

            if ($this->Settings->update($id, $data)) {
                $data = array('success' => 'Your data has been successfully saved.<script>$(function() {
                    setTimeout(function() {
                        $("div.alert").hide(\'blind\', {}, 500)
                    }, 3000);
                });</script>');
            } else {
                $data = array('error' => renderErrors(array('ERROR:' => 'Something wrong happened')));
            }
            echo json_encode($data);
        } else {
            $this->data->setting = $this->Settings->getSetting($id);
            $this->data->page_title = "Admin Add Setting";
            $this->data->content = $this->loadPartialView('admin/settings/update');
            $this->loadView('layout/default', 'Admin Update Setting');
        }
    }

    public function ref_comm() {

        $this->load->model('referral_model', 'Referral');
        $this->data->maxLevels = 5;

        if ($_POST) {
            $post = $this->input->post();
            foreach ($post['id'] as $id) {
                $data = array(
                    'sorting' => $post['sorting'][$id],
                );
                $levels = array();
                for ($i = 1; $i <= $this->data->maxLevels; $i++) {
                    $levels[$i] = $post["level$i"][$id];
                }
                $data['levels'] = json_encode($levels);
                $this->Referral->updateCommission($id, $data);
            }
        }

        $this->data->refComm = $this->Referral->getCommissionTable();


        $this->layoutData['title'] = 'Settings - Referral Commissions';

        $this->data->page_title = "Admin Ref Comm";
        $this->data->widgets = $this->loadPartialView('admin/settings/ref_comm');
        $this->loadView('member/shell', 'Admin Ref Comm');
    }

    public function add_ref_comm() {

        $this->load->model('referral_model', 'Referral');
        $data = array('error' => renderErrors(array('ERROR:' => 'Something wrong happened')));
        if ($_POST) {
            $post = $this->input->post();
            $this->Referral->addCommission($post);

            $data = array('html' => '<div align="center"><h2>Your data has been successfully saved.</h2></div>');
        }

        echo json_encode($data);
    }

}
