<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support extends MY_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->load->model('support_model', 'Ticket');
        $this->addStyleSheet(asset('styles/support.css'));

        //$this->output->enable_profiler(PROFILER_SETTING);
    }

    public function index()
    {

        $this->addJavascript(asset('bootstrap/js/autosize.min.js'));
        $this->addJavascript(asset('scripts/forms.js'));
        // $this->addStyleSheet(asset('bootstrap/css/form.css'));
        $this->data->page_title = SITE_NAME.' Support';
        $this->data->userId = $this->userId;
        $this->load->model('gh_model', 'GH');




        $this->data->valid = $this->GH->getUserGHstats($this->profile->id);

        if ($this->isGuest) {

            $this->session->set_flashdata('error', 'You must be logged in to write to support');
            redirect('login');
        } else {

            $this->layout = 'layout/member/shell';
            $this->layoutData['member_page'] = 'Support';

            $this->data->openTickets   = $this->Ticket->getSummary($this->data->userId, FALSE, 'open');
            $this->data->closedTickets = $this->Ticket->getSummary($this->data->userId, FALSE, 'closed');

            $this->loadView('member/support', 'Support');
        }

    }

    public function add()
    {
        if ($this->ajax)
        {
            if ($this->input->post())
            {
                $data = NULL;
                $supportRules = $this->userId > 0 ? 'member_support' : 'support';

                if ($this->form_validation->run($supportRules))
                {
                    $this->data->code = uniqid();

                    $post = $this->input->post();
                    $ticketData = array(
                        'code'    => $this->data->code,
                        'category' => $post['category'],
                        'priority' => $post['priority'],
                        'subject' => $post['subject']
                    );

                    $messageData = array(
                        'message' => $post['message']
                    );

                    if ($this->userId > 0)
                    {
                        $ticketData['user_id']  = $this->profile->id;
                        $messageData['user_id'] = $this->profile->id;
                    }
                    else $ticketData['email'] = $post['email'];

                    if ($this->Ticket->store($messageData, $ticketData) === TRUE)
                    {
                        $this->load->model('email_model', 'EmailQueue');

                        $email = $this->userId > 0 ? $this->profile->email : $post['email'];

                        // Send the email to the guest so he/she can refer back to it
                        $ticketUrl = SITE_ADDRESS.'support/' . $this->data->code . '';
                        $this->EmailQueue->store($email, 'Support Ticket [' . $ticketData['code'].']', 'emails/support/ticket_created.php' , compact('ticketUrl'));

                        if (defined('SUPPORT_NOTIFY') && SUPPORT_NOTIFY != '') {
                            $data = array(
                                'user'      => ($this->userId > 0) ? $this->profile->username : 'guest',
                                'ticketUrl' => $ticketUrl
                            );
                            $this->EmailQueue->store(SUPPORT_NOTIFY, 'New Support Ticket ['.$ticketData['code'].']', 'emails/support/ticket_notify', $data);
                        }

                        if ($this->profile)
                        {
                            $data = array(
                                'success'  => 'success',
                                'redirect' => array(
                                    'url' => site_url('/support/' . $this->data->code . '')
                                )
                            );
                        }
                        else
                        {
                            $data = array(
                                'success' => 'success',
                                'html'    => $this->loadPartialView('support/created')
                            );
                        }
                    }
                    else
                    {
                        $data = array(
                            'error' => 'Your message was not sent'
                        );
                    }
                }
                else
                {
                    $data = array(
                        'errorElements' => $this->form_validation->error_array()
                    );
                }

                echo json_encode($data);
            }
            else
            {
                $isGuest = $this->isGuest;
                echo $this->loadPartialView('support/partial/add', compact('isGuest'));
            }

            return;
        }

        show_404();
    }

    public function reply($code)
    {

        if ($this->ajax)
        {
            $ticket = $this->Ticket->get($code);
            if (!$ticket)
            {
                $this->session->set_flashdata('error', 'There is no support ticket with that code');
                redirect(site_url('support'), 'refresh');
            }

            if ($this->form_validation->run('support_reply')) // Defined in form_validation
            {
                $post = $this->input->post();

                $messageData = array(
                    'ticket_id' => $ticket['ticket']->id,
                    'message'   => $post['message'],
                );

                if ($this->profile)
                    $messageData['user_id'] = $this->profile->id;


                $ticketData = NULL;
                if (isset ($post['status']))
                {
                    $n =  $this->User->getUserGroup($this->profile->id);
                    $gid = (int)$n->group_id;
                    if($gid < 6) {
                        $ticketData = array(
                            'status' => $post['status'],
                            'support_id' => $this->profile->id

                        );
                    }else{
                        $ticketData = array(
                            'status' => $post['status'],

                        );
                    }
                }

                if ($this->Ticket->store($messageData, $ticketData) === TRUE)
                {
                    if ($this->isAdmin) {
                        $notificationEmail = $ticket['ticket']->email;
                        if (!$notificationEmail) {
                            //If user, no mail stored in ticket, so, take email from user
                            $user = $this->User->getData($ticket['ticket']->user_id);
                            $notificationEmail = $user->email;
                        }

                        $ticketUrl = SITE_ADDRESS.'support/'.$code.'';

                        $this->load->model('email_model', 'EmailQueue');
                        if ($this->EmailQueue->store($notificationEmail, 'Support Ticket Reply ['.$code.']', 'emails/support/ticket_reply', compact('ticketUrl')))
                            $this->session->set_flashdata('success', 'Message sent to '.$notificationEmail);
                    } else {
                        $this->session->set_flashdata('success', 'We have received your message and you can expect a response within 24 hours.');
                    }

                    $data = array(
                        'success'  => 'success',
                        'redirect' => array(
                            'url' => site_url('support/' . $code . '')
                        )
                    );
                }
                else
                {
                    $data = array(
                        'error' => 'Your message was not sent'
                    );
                }
            }
            else
            {
                $data = array(
                    'errorElements' => array(
                        'message' => form_error('message')
                    )
                );
            }

            echo json_encode($data);
            return;
        }

        show_404();
    }

    public function view($code)
    {

        $ticketData = $this->Ticket->get($code);

        if (!$this->isAdmin) {
            if (!$ticketData || ($ticketData['ticket']->user_id && $this->isGuest) || ($ticketData['ticket']->email && !$this->isGuest)) {
                $this->session->set_flashdata('error', 'There is no support ticket with that code');
                redirect(site_url('support'), 'refresh');
            }
        }

        $this->data->ticket   = $ticketData['ticket'];
        $this->data->messages = $ticketData['messages'];

        $this->Ticket->read($code);

        $this->addJavascript(asset('bootstrap/js/autosize.min.js'));
        $this->addJavascript(asset('scripts/forms.js'));

        $this->layout = 'layout/member/shell';
        $this->data->title = 'Support Ticket '.$code;
        $this->loadView('support/view', $this->data->title);
    }
}