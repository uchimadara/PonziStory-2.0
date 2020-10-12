<?php
use Mailgun\Mailgun;

class Email_model extends MY_Model
{
    private $mailer = NULL;

    public function __construct()
    {
        parent::__construct();
    }

    public function store($email, $subject, $template='test', $tplData = array(), $importance = 0)
    {
        if ($importance > 0 && ENVIRONMENT == 'production') { // send immediately

            $body = $this->load->view($template, $tplData, TRUE);
            $this->send_smtp_email(
                $email,
                FROM_NAME,
                FROM_EMAIL,
                $subject,
                $this->skin($body)
            );
        } else {
            $data = array(
                'email'      => $email,
                'subject'    => $subject,
                'template'   => $template,
                'data'       => json_encode($tplData),
                'importance' => $importance,
                'date'       => $this->now,
                'body' => ''
            );

            $this->db->insert('email_queue', $data);
        }

        return TRUE;
    }

    public function add($to, $subject, $fromName, $fromEmail, $body, $importance = 0) {
        if ($importance > 0) {
            $this->send_smtp_email(
                $to,
                $fromName,
                $this->config->item('noreply_email', 'ion_auth'),
                $subject,
                $this->skin($body)
            );
            $this->updateCount(1);
        } else {
            $data = array(
                'email'      => $to,
                'subject'    => $subject,
                'body'       => $body,
                'importance' => $importance,
                'date'       => $this->now
            );

            $this->db->insert('email_queue', $data);
        }

        return TRUE;
    }

    public function storeBlast($data) {
        $this->db->insert('blaster_queue', $data);
    }

    public function subset($limit = 50)
    {
        return $this->db->from('email_queue')
            ->where('error', 0)
            ->order_by('id')
            ->order_by('importance', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    function getBlasterQueue($userCount) {

        $sql = "SELECT * FROM blaster_queue WHERE sent < $userCount AND time_to_send <= '".$this->now."' AND completed IS NULL ORDER BY created";
        $r   = $this->db->query($sql)->result();
        return $r;
    }

    function getBlast($id) {

        $sql = "SELECT * FROM blaster_queue WHERE id = '$id'";
        $r   = $this->db->query($sql)->result();

        if (empty($r)) return FALSE;

        return $r[0];
    }

    function blasterSent($ids) {
        $this->db->where('id IN ('.implode(",", $ids).')', NULL, FALSE)
            ->update('blaster_queue', array('sent' => $this->now));
    }

    function updateBlasterQueue($id, $countSent, $lastUserId, $completed) {

        $complete = ($completed) ? ', completed = '.$completed : '';

        $sql = "UPDATE blaster_queue SET sent = $countSent, last_user_id = $lastUserId $complete WHERE id = $id";
        $this->db->query($sql);

        $this->updateCount($countSent);

    }

    public function updateCount($c) {

        $month = strtotime(date('Y-m-01 00:00:00', $this->now));
        $this->db->query("INSERT INTO email_counter (month, sent) VALUES ($month, $c)
                            ON DUPLICATE KEY UPDATE sent = sent + $c");

    }

    function purgeBlasterQueue($ids) {

        $id_string = "'".implode("','", $ids)."'";
        $sql       = "DELETE FROM blaster_queue WHERE id IN ($id_string)";

        $this->db->query($sql);
    }

    public function markError($id)
    {
        $this->db->where('id', $id)
            ->set('error', 1)
            ->update('email_queue');

        return ($this->db->affected_rows() == 1);
    }

    public function delete($id)
    {
        $this->db->where('id', $id)
            ->delete('email_queue');

        return ($this->db->affected_rows() == 1);
    }

    public function skin($body, $data=array(), $template = 'basic') {

        $content = $this->load->view("emails/{$template}_template", $data, TRUE);

        return str_replace('[email_content]', $body, $content);
    }

    public function init_mailer() {
        if (EMAIL_METHOD == 'mailgun') {
            $this->mailer = new Mailgun(MAILGUN_API_KEY);
        } elseif (EMAIL_METHOD == 'smtp') {
            $this->load->library('phpmailer');
            $this->mailer = new PHPMailer();
        } else { //native

        }
    }
    public function send_smtp_email($email, $from_name, $from_email, $subject, $body) {
        if (is_null($this->mailer)) $this->init_mailer();

        $method = 'send_'.EMAIL_METHOD;
        $this->$method($email, $from_name, $from_email, $subject, $body);
    }

    public function send_native($email, $from_name, $from_email, $subject, $body) {

        log_message('debug', 'send_native_email- to:'.$email.' subject: '.$subject);


        $headers = 'MIME-Version: 1.0'.PHP_EOL.
                'Content-type: text/html; charset=iso-8859-1'.PHP_EOL.
                'From: '.$from_name.' <'.$from_email.'>'.PHP_EOL.
                'Reply-To: '.$from_email.PHP_EOL.
                'X-Mailer: PHP/'.phpversion();

        mail($email, $subject, $body, $headers);

        return TRUE;
    }

    public function send_mailgun($email, $from_name, $from_email, $subject, $body) {
        log_message('debug', 'send_mailgun_email- to:'.$email.' subject: '.$subject);

        $rep = $this->mailer->sendMessage(MAILGUN_DOMAIN, array('from' => $from_name.' <'.$from_email.'>',
            'to' => $email,
            'subject' => $subject,
            'html' => $body));
        $msg = $rep->http_response_body->message;
        log_message('debug', 'EMAIL SENT via Mailgun: '.$msg);

        return TRUE;
    }

    public function send_smtp($email, $from_name, $from_email, $subject, $body){
        $msg = 'success';
        log_message('debug', 'send_smtp_email- to:'.$email.' subject: '.$subject);

        try {
            $this->mailer->ClearAllRecipients();
            $this->mailer->AddAddress($email);
            $this->mailer->IsSMTP();
            $this->mailer->Host     = SMTP_SERVER; //$this->config->item('smtp_host');
            $this->mailer->SMTPAuth = TRUE;
            $this->mailer->SMTPSecure = 'ssl';
            $this->mailer->Port     = SMTP_PORT; //$this->config->item('smtp_port');
            $this->mailer->Username = SMTP_USERNAME; //$this->config->item('smtp_username');
            $this->mailer->Password = SMTP_PASSWORD; //$this->config->item('smtp_password');
            $this->mailer->SetFrom($from_email, $from_name);
            $this->mailer->IsHTML(TRUE);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
           // $this->mailer->SMTPDebug = TRUE;
            $this->mailer->Send();
        } catch (phpmailerException $e) {
            $msg = $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            $msg = $e->getMessage(); //Boring error messages from anything else!
        }

        // mail($email, $subject, $body, $headers);
        log_message('debug', 'send_smtp_email EMAIL SENT via phpMailer: '.$msg);

        return TRUE;
    }
}