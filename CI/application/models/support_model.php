<?php
class Support_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function store($message, &$ticket = NULL)
    {
        // Set some default values for the ticket
        $ticket['updated'] = $this->now;
        $ticket['status']  = isset($ticket['status']) ? $ticket['status'] : 'open'; // If not explicitly closed, assume open

        // and the message
        $message['created'] = $this->now;
        $message['user_ip'] = ip2long($this->input->ip_address());
        $message['ip_address'] = $this->input->ip_address();

        if (!isset($message['ticket_id']))
        {
            // If the message does not contain a ticket ID -> new ticket
            $ticket['created'] = $this->now;

            $this->db->insert('support_ticket', form_prep($ticket));
            $ticket['id'] = $message['ticket_id'] = $this->db->insert_id();
        }
        else
        {
            // Update the ticket with the latest date or status
            $this->db->where('id', $message['ticket_id'])
                ->update('support_ticket', $ticket);
        }

        $this->db->insert('support_ticket_message', form_prep($message));

        return TRUE;
    }

    public function get($code, $userId = NULL)
    {
        if ($userId)
            $this->db->where('user_id', $userId);

        $ticket = $this->db->select('u.username, st.id, st.code, st.user_id, st.email, c.name category,
              st.priority, st.subject, st.status, st.created, st.updated, st.last_read')
            ->from('support_ticket st')
                ->join('support_category c', 'c.id = st.category')
                ->join('users u', 'u.id = st.user_id', 'left')
            ->where('st.code', $code)
            ->get()
            ->row();

        if ($ticket)
        {
            // Hocus Pocus SQL
            $messages = $this->db->query("SELECT stm.id, u.username, stm.message, stm.created, g.name lvl_name, g.description lvl_description
                     FROM support_ticket_message stm
                LEFT JOIN users u ON u.id = stm.user_id
                LEFT JOIN groups g ON g.id = (SELECT MIN(group_id) FROM users_groups WHERE user_id = u.id)
                    WHERE stm.ticket_id = " . $ticket->id . "
                 ORDER BY stm.created")->result();

            return array(
                'ticket'   => $ticket,
                'messages' => $messages
            );
        }

        return FALSE;
    }

    public function countOpenTickets() {
        return $this->db->from('support_ticket')
                        ->where('status', 'open')
                        ->count_all_results();
    }

    public function countSupportWOrks($user) {
        return $this->db->from('support_ticket')
            ->where('support_id', $user)
            ->where('support_status',1)
            ->where('status','closed')
            ->get()->num_rows();
    }

    public function upAll($id){
        // $cd = (int) $cd;

        $this->db->query("UPDATE support_ticket SET support_status = 2 WHERE support_id = $id ");
        return TRUE;
    }

    public function getCount($userId = NULL, $guest = FALSE, $status = 'open')
    {
        if ($userId)
            $this->db->where('user_id', $userId);
        else if ($guest)
            $this->db->where('user_id IS NULL');
        else $this->db->where('user_id IS NOT NULL');

        return $this->db->from('support_ticket')
            ->where('status', $status)
            ->count_all_results();
    }

    public function getSummary($userId = NULL, $guest = FALSE, $status = 'open', $page = 1, $perPage=30)
    {
        $start = ($page - 1) * $perPage;

        $whereUser   = $userId !== NULL ? "AND st.user_id = $userId" : '';
        $whereStatus = $status ? "AND st.status = '$status'" : '';
        $whereType   = $guest  ? 'AND st.user_id IS NULL' : 'AND st.user_id IS NOT NULL';

        // More Harry Potter SQL
        $r = $this->db->query("SELECT st.id, st.code, st.user_id, st.email, cat.name category,
              st.priority, st.subject, st.status, st.created, st.updated, st.last_read, m.responder_id, m.username
              FROM support_ticket st
              JOIN support_category cat on cat.id = st.category
              LEFT JOIN (SELECT stm.*, u.id AS responder_id, u.username
                           FROM support_ticket_message stm
                      LEFT JOIN users u
                             ON u.id = stm.user_id
                       ORDER BY stm.id DESC) m
                ON st.id = m.ticket_id
             WHERE 1 = 1
                 $whereUser
                 $whereStatus
                 $whereType
          GROUP BY st.id
          ORDER BY st.updated DESC
             LIMIT $start, $perPage")->result();

        return $r;
    }

    /*Counting Replies messages*/
    public function countReplies($ticketId)
    {
        return $this->db->where('ticket_id', $ticketId)
            ->from('support_ticket_message')
            ->count_all_results();
    }

    /*Count Tickets*/
    public function countTickets()
    {
        $res = NULL;

        /*Count all guest Tickets*/
        $res['guest'] = $this->db->where('user_id', NULL)
            ->from('support_ticket')
            ->count_all_results();

        /*Count all  members Tickets*/
        $res['members'] = $this->db->where('user_id IS NOT NULL')
            ->from('support_ticket')
            ->count_all_results();

        /*Count all  guest Tickets from today*/
        $res['guest_today'] = $this->db->where('user_id', NULL)
            ->where('created >', $this->now - 60 * 60 * 24)
            ->from('support_ticket')
            ->count_all_results();

        /*Count all  members Tickets from today*/
        $res['members_today'] = $this->db->where('user_id IS NOT NULL')
            ->where('created >', $this->now - 60 * 60 * 24)
            ->from('support_ticket')
            ->count_all_results();

        /*Count all opened  Tickets  */
        $res['all_open_tickets'] = $this->db->where('status', 'open')
            ->from('support_ticket')
            ->count_all_results();

        return $res;
    }

    public function read($code)
    {
        return $this->db->set('last_read', $this->now)
            ->where('code', $code)
            ->update('support_ticket');
    }

    public function countUnread($userId)
    {
        return $this->db->from('support_ticket')
            ->where('user_id', $userId)
            ->where('updated > last_read')
            ->count_all_results();
    }

    public function findUserId($username)
    {
        $user = $this->db->select('id')
            ->from('users')
            ->where('username', $username)
            ->get()
            ->row();

        if ($user)
            return $user->id;

        return FALSE;
    }
}