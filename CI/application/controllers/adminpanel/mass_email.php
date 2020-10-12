<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include('admin.php');

class Mass_email extends Admin {
    public function __construct() {
        parent::__construct(TRUE);

        $this->layoutData['title'] = 'Mass Emailer';
    }

    public function index() {
        $formName = 'mass_email';
        if (!$this->ajax) {

            $this->data->formURL    = site_url("adminpanel/admin/form/$formName");
            $this->data->page_title = wordify($formName);
            $this->data->content    = $this->loadPartialView('admin/partial/form');
            $this->layout = 'layout/admin/shell';
            $this->loadView('layout/default', ' Admin');

        } elseif ($_POST) {

            $send_time = $this->input->post('send_date').' ';
            if ($_POST['send_meridian'] == '12' && intval($_POST['send_hour']) < 12) {
                $send_time .= 12 + intval($_POST['send_hour']);
            } else {
                $send_time .= ($_POST['send_hour'] == 12) ? '00' : $_POST['send_hour'];
            }

            $_POST['time_to_send'] = strtotime($send_time.':00:00');

            $result = $this->doForm($formName);

            if (!is_array($result)) { // returns an ID if successful, error is always an array
                $this->session->set_flashdata('success', 'Email queued to send at '.$send_time);
                $result = array(
                    'success' => 'success',
                    'redirect' => array(
                        'url' => SITE_ADDRESS.'admin'
                    )
                );
            }

            echo json_encode($result);
        }
    }

    function email_user($id) {
        $this->data->user = $user = $this->ion_auth->where('id', $id)
                                           ->select('id, username, activation_code, email, email_settings, balance')
                                           ->users()
                                           ->row();
        if ($_POST) {

            if ($this->form_validation->run('admin/email_user') === TRUE) {
                $post                 = $this->input->post();
                $fromName    = $post['from_name'];
                $fromEmail   = $post['from_email'];
                $subject              = $post['subject'];
                $body                 = $post['message'];

                $find = array(
                    '/\[USERNAME\]/',
                    '/\[EMAIL\]/',
                    '/\[REF_LINK\]/',
                    '/\[ID\]/',
                    '/\[ACTIVATION_CODE\]/',
                );

                $replace = array(
                    $user->username,
                    $user->email,
                    site_url('ref/'.$user->username),
                    $user->id,
                    $user->activation_code
                );

                $subject = preg_replace($find, $replace, $subject);
                $body    = preg_replace($find, $replace, $body);

                $this->EmailQueue->add($user->email, $subject, $fromName, $fromEmail, $body);

                $data = array(
                    'success' => 'success',
                    'replace' => array(
                        'blasterForm' => "Successfully sent message to ".$this->data->user->username." &lt;".$this->data->user->email."&gt;<br/>"
                    )
                );
            } else {
                $data = array(
                    'error' => renderErrors($this->form_validation->error_array())
                );
            }
            echo json_encode($data);
        } else {
            $this->data->content = $this->loadPartialView('admin/mass_email/single');
            //$this->layout        = 'shell';
            $this->loadView('layout/default', 'Admin');
        }
    }
}