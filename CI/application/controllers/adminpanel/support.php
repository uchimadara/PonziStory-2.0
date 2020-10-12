<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('admin.php');

class Support extends MY_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->isGuest) {
            show_error('Not logged in. '.anchor(site_url('login'), 'Login'));
        } else if (!$this->isAdmin && !in_array('support', $this->userGroups)) {

            show_error('Unauthorized access.');
        }

        $this->addStyleSheet(asset('styles/admin/style.css'));
        $this->addJavascript(asset('scripts/admin/admin.js'));
        $this->addJavascript(asset('scripts/searchList.js'));

        $this->load->model('support_model', 'Support');

        $this->output->enable_profiler(PROFILER_SETTING);

        $this->data->openTickets = $this->Support->countOpenTickets();

        $this->layout = 'layout/admin/shell';
    }

    public function getList($listName, $sortCol = '', $sortDir = '', $page = 1, $perPage = '') {
        $this->_path = 'support/';
        parent::getList($listName, $sortCol, $sortDir, $page, $perPage);
    }

    /**
     * In Admin, when clicking on 'Support Ticket' it shows the menu for
     * admin to check what it has to show, with a summary of not answered
     * tickets. It shows all the pages related to info of tickets
     *
     * @param string $type It can be 'guest' or 'member'
     * @param string $status It can be 'opened' or 'closed'
     */
    public function index($type = NULL, $status = NULL, $page = 1)
    {
        switch ($type)
        {
            case NULL:

                $this->data->ticketcount = $this->Support->countTickets();
                $this->data->Supcount = $this->Support->countSupportWOrks($this->profile->id);
               // var_dump($this->data->Supcount);
                $this->data->guestTicketCount = $this->Support->getCount(NULL, TRUE);
                $this->data->memberTicketCount = $this->Support->getCount(NULL, FALSE);

                $this->layoutData['title'] = 'Support Tickets';
                $this->loadView('admin/support/tickets', 'All Tickets');
                break;

            default:
                $this->data->type   = $type;
                $this->data->status = $status;
                $this->data->page   = $page;
                $this->data->count   = $this->Support->getCount(NULL, $type == 'guest', $status);
                $this->data->tickets = $this->Support->getSummary(NULL, $type == 'guest', $status, $page);
                $this->layoutData['title'] = anchor ('adminpanel/support', 'Support Tickets') . ' - ' . UCWords(pluralise($type, 2)) . ' Tickets';
                $this->loadView('admin/support/index', ucwords($type)." Support Tickets");
        }
    }

    /**
     * It shows View to answer support's ticket, and sent the answered via mail
     * or answering the user in the support zone
     *
     * @param string $type It can be 'member' or 'guest'
     * @param string $code Code generated on recording a ticket
     * @author Alex
     */
    public function view($type, $code)
    {
        $this->data->ticket = $this->Support->get($code);

        if (!$this->data->ticket)
            show_404();

        if (!$this->ajax)
        {
            $this->layoutData['title'] = anchor ('adminpanel/support', 'Support Tickets') . ' - Ticket #' . $this->data->ticket['ticket']->id;
            $this->loadView('admin/support/view', 'Support Ticket '.$code);
        }
        elseif ($_POST)
        {
            $data = NULL;

            //TODO: Validation
            if( $this->form_validation->run('support_reply') === TRUE)
            {
                $post = $this->input->post();

                if (isset($post['status']))
                {
                    $ticketData = array(
                        'status' => $post['status']
                    );
                }

                $messageData = array(
                    'ticket_id' => $ticket['ticket']->id,
                    'user_id'   => $this->ion_auth->select('id')->user()->row()->id,
                    'message'   => $post['message']
                );

                if ($this->Support->store($messageData, $ticketData) === TRUE)
                {
                    $notificationEmail = $ticket['ticket']->email;
                    if (!$notificationEmail) //If user, no mail stored in ticket, so, take email from user
                        $notificationEmail = $this->ion_auth->select('email')->user($ticket['ticket']->user_id)->row()->email;

                    $ticketUrl = SITE_ADDRESS.'support/' . $code . '';

                    if ($this->EmailQueue->store($notificationEmail, 'Support Ticket #' . $ticket['ticket']->id . ' - New Reply!', 'emails/support/ticket_reply', compact('ticketUrl')))
                    {
                        $data = array(
                            'success'  => 'success',
                            'html'     => '<strong>Successfully sent reply!</strong>',
                            'redirect' => array(
                                'url'     => site_url('adminpanel/support/index/' . $type . '/open'),
                                'timeout' => 1000
                            )
                        );
                    }
                    else
                    {
                        $data = array(
                            'error'  => 'ERROR Sending the reply email!'
                        );
                    }
                }
                else
                {
                    $data = array(
                        'error'  => 'ERROR storing message'
                    );
                }
            }
            else
            {
                $data = array(
                    'error' => renderErrors($this->form_validation->error_array())
                );
            }

            echo json_encode($data);
        } else {
            show_error('invalid entry');
        }
    }

    public function create()
    {
        if (!$this->ajax)
        {
            $this->layoutData['title'] = anchor ('adminpanel/support', 'Support Tickets') . ' - Create New Ticket';
            $this->loadView('admin/support/create', 'create ticket');
        }
        else
        {
            $data = NULL;

            if ($this->form_validation->run('admin_support') === TRUE)
            {
                $post   = $this->input->post();
                $code   = uniqid();
                $userId = $this->Support->findUserId($post['username']);

                if ($userId)
                {
                    $ticketData = array(
                        'code'    => $code,
                        'subject' => $post['subject'],
                        'user_id' => $userId,
                        'status'  => 'open'
                    );

                    $messageData = array(
                        'user_id' => $userId,
                        'message' => $post['message']
                    );

                    if ($this->Support->store($messageData, $ticketData) === TRUE)
                    {
                        $data = array(
                            'success'  => 'success',
                            'html'     => '<strong>Successfully created ticket!</strong>',
                            'redirect' => array(
                                'url'     => site_url('adminpanel/support/index/member/open'),
                                'timeout' => 1000
                            )
                        );
                    }
                    else
                    {
                        $data = array(
                            'error' => 'ERROR storing message'
                        );
                    }
                }
                else
                {
                    $data = array(
                        'error' => "Cannot find user '" . $post['username'] . "'"
                    );
                }
            }
            else
            {
                $data = array(
                    'error' => renderErrors($this->form_validation->error_array())
                );
            }

            echo json_encode($data);
        }
    }
}