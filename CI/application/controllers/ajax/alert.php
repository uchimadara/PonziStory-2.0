<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include(APPPATH.'core/MY_AjaxController.php');

class Alert extends MY_AjaxController {

    public function __construct() {
        parent::__construct();

        $this->requireLogon();
    }

    public function index() {
        show_error('nothing here');
    }


    public function get($ts) {

        $this->load->model('payment_model', 'Payment');
        $payments = $this->Payment->getPendingReceived($this->userId);

        $c = count($payments);
        $msg = '';
        if ($c > 0) {
            $msg = anchor('/back_office/approve_payments', $c . ' Pending '.pluralise(' Confirmation', $c));
        }

        $data = array(
            'alert_count' => $c,
            'alert_message' => $msg
        );
        echo (json_encode($data));
    }
}
