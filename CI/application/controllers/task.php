<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Task extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->enable_profiler(FALSE);
    }

    public function fix_refs() {
        $this->load->model('referral_model', 'Referral');
        $this->load->model('user_model', 'User');

        $users = $this->User->getActive();

        foreach ($users as $u) {

            $sponsorId = $u->referrer_id;
            $level     = 1;

            while ($sponsorId > 0 && $level <= 10) {

                $amt = $this->Referral->getPaid($u->id, $sponsorId);

                $this->Referral->addEarnings($sponsorId, $u->id, $level, ($amt ? $amt : 0));

                $sponsorId = $this->User->getData($sponsorId, array('referrer_id'))->referrer_id;

                $level++;
            }
        }
    }

    public function traverse() {

        $this->tree(1, 1);
    }

    private function tree($userId, $level) {
        $dots = '';
        for ($i = 0; $i < $level; $i++) $dots .= '.';

        $user = $this->db->from('users')->where('id', $userId)->get()->row();

        log_message('debug', $dots.$user->username.'['.$user->id.']');

        $users = $this->db->from('users')->where('referrer_id', $userId)->get()->result();
        foreach ($users as $u) {
            $this->tree($u->id, $level+1);
        }

    }

    public function email_test() {
        if ($_POST) {

            $fromName  = FROM_NAME;
            $fromEmail = FROM_EMAIL;
            $to = $_POST['email'];
            $subject = 'Test email';
            $body = 'Testing 1, 2, 3...';

            echo "sending email to $to from < $fromName > $fromEmail <br/><br/>";

            $headers = 'MIME-Version: 1.0'.PHP_EOL.
                    'Content-type: text/html; charset=iso-8859-1'.PHP_EOL.
                    'From: <'.$fromName.'> '.$fromEmail.PHP_EOL.
                    'Reply-To: '.$fromEmail.PHP_EOL.
                    'X-Mailer: PHP/'.phpversion();

            echo 'headers = '.nl2br(htmlentities($headers));

            $result = mail($to, $subject, $body, $headers);

            echo '<br/><br/>result = '.$result.'<br/><br/>';


        }

        $this->load->view('user/test_email');

    }

    public function invite_test() {


        if ($_POST) {

            print_r($_POST);
            $this->load->helper('guid');

            $userData                    = $this->input->post();
            $userData['activation_code'] = create_guid();
            $userData['account_expires'] = now() + (INVITE_EXPIRATION*CACHE_ONE_HOUR);

            $fromName  = FROM_NAME;
            $fromEmail = FROM_EMAIL;
            $to        = $_POST['email'];
            $subject   = 'Test invite';
            
            $email_content = $this->load->view('emails/user/invite', $userData, TRUE);
            $body = $this->load->view("emails/basic_template", compact('email_content'), TRUE);


            echo "sending email to $to from < $fromName > $fromEmail <br/><br/>";

            $this->load->library('phpmailer');

            $mailer = new PHPMailer();
            
            //log_message('debug', 'send_smtp_email- to:'.$to.' subject: '.$subject);

            try {
                $mailer->ClearAllRecipients();
                $mailer->AddAddress($to);
                $mailer->IsSMTP();
                $mailer->Host     = SMTP_SERVER; //$config->item('smtp_host');
                $mailer->SMTPAuth = TRUE;
                $mailer->Port     = SMTP_PORT; //$config->item('smtp_port');
                $mailer->Username = SMTP_USERNAME; //$config->item('smtp_username');
                $mailer->Password = SMTP_PASSWORD; //$config->item('smtp_password');
                $mailer->SetFrom($fromEmail, $fromName);
                $mailer->IsHTML(TRUE);
                $mailer->Subject = $subject;
                $mailer->Body    = $body;
                $mailer->SMTPDebug = TRUE;
                $mailer->Send();
                $msg = 'success';
            } catch (phpmailerException $e) {
                echo 'ERROR!<br/>';
                echo $msg = $e->errorMessage(); //Pretty error messages from PHPMailer
            } catch (Exception $e) {
                echo 'ERROR!<br/>';
                echo $msg = $e->getMessage(); //Boring error messages from anything else!
            }

            echo $msg;
        }

        $this->load->view('user/test_invite');
    }
}
