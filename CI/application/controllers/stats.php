<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stats extends CI_Controller {
    public function __construct() {
        parent::__construct();

        if (!$this->input->is_ajax_request()) {
            echo "Please " . anchor(site_url('login'), 'log in') . " for access.";
            exit(0);
        }

        $this->profile = $this->ion_auth->user()->row();
        $this->load->driver('cache', array(
            'adapter' => CACHE_METHOD_PRIMARY,
            'backup'  => CACHE_METHOD_SECONDARY
        ));
    }

    public function index() {
        show_404();
    }

    public function member_listings() {
        $this->load->model('monitor_model', 'Monitor');

        $clicks = $this->Monitor->countListingsByDay($this->profile->id);
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function listing_clicks($listingId = NULL) {
        $this->load->model('monitor_model', 'Monitor');

        $clicks = $this->Monitor->countClicksByDay($this->profile->id, $listingId);
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function listing_views($listingId = NULL) {
        $this->load->model('monitor_model', 'Monitor');

        $clicks = $this->Monitor->countViewsByDay($this->profile->id, $listingId);
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function site_clicks() {
        $this->load->model('referral_model', 'Referral');

        $clicks = $this->Referral->countClicksByDay();
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function reflink_clicks() {
        $this->load->model('referral_model', 'Referral');

        $clicks = $this->Referral->countClicksByDay($this->profile->id);
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function referral_signups() {
        $this->load->model('referral_model', 'Referral');

        $clicks = $this->Referral->countSignupsByDay($this->profile->id);
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function member_registrations() {
        $this->load->model('user_model', 'User');

        $clicks = $this->User->countRegistrationsByDay();
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function member_upgrades() {
        $this->load->model('my_account_model', 'Account');

        $clicks = $this->Account->countUpgradesByDay();
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function ad_purchases() {
        $this->load->model('my_account_model', 'Account');

        $clicks = $this->Account->sumAdPurchasesByDay();
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function tokens_earned() {
        $this->load->model('user_model', 'User');

        $clicks = $this->User->countTokensEarnedByDay();
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function site_listings() {
        $this->load->model('monitor_model', 'Monitor');

        $clicks = $this->Monitor->countListingsByDay();
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function member_signups() {
        $this->load->model('referral_model', 'Referral');

        $clicks = $this->Referral->countSignupsByDay();
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function site_listing_clicks() {
        $this->load->model('monitor_model', 'Portfolio');

        $clicks = $this->Portfolio->countClicksByDay();
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function site_listing_views() {
        $this->load->model('monitor_model', 'Portfolio');

        $clicks = $this->Portfolio->countViewsByDay();
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function text_ads() {
        $this->load->model('campaign_model', 'Campaign');

        $clicks = $this->Campaign->countTextAdsByDay($this->profile->id);
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function text_ad_views() {
        $this->load->model('campaign_model', 'Campaign');

        $clicks = $this->Campaign->countTextAdViewsByDay($this->profile->id);
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }

    public function text_ad_clicks() {
        $this->load->model('campaign_model', 'Campaign');

        $clicks = $this->Campaign->countTextAdClicksByDay($this->profile->id);
        $c      = array();
        foreach ($clicks as $click)
            $c[] = $click->c;

        echo json_encode($c);
    }
}