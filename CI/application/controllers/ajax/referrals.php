<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include(APPPATH.'core/MY_AjaxController.php');

class Referrals extends MY_AjaxController {

    public function __construct() {
        parent::__construct();

        $this->load->model('referral_model', 'Referral');
        $this->requireLogon();
    }

    public function summary() {
        $this->data->referrals = $this->Referral->countReferrals($this->userId, TRUE);
        $this->data->activeReferrals     = $this->Referral->countReferrals($this->userId, TRUE);

        $this->data->totalReferrals = 0;
        foreach ($this->data->referrals as $ref) {
            $this->data->totalReferrals += $ref->count;
        }

        $this->data->refStats = array(
            'Reflink Clicks'   => array(
                'url'   => 'stats/reflink_clicks',
                'count' => $this->Referral->countClicks($this->userId)
            ),
            'Referral Signups' => array(
                'url'   => 'stats/referral_signups',
                'count' => $this->data->totalReferrals
            ),
        );

        echo $this->loadPartialView('member/tabs/referral_summary');

        return;
    }

    public function search() {

        $terms = $this->input->post('terms');
        $list = $this->Referral->getSearchList($this->userId);

        $matches = array();

        foreach ($list as $id => $str) {
            if (stripos($str, $terms) !== FALSE)
                $matches[] = $id;
        }

        if (!empty($matches)) {
            $this->data->referrals = $this->Referral->get($matches);

            foreach ($this->data->referrals as &$ref) {
                if ($this->profile->referrer_id > 0) {

                    $ref->settings   = $this->User->getSettings($ref->id);
                    $ref->socialList = $this->User->getSocialNetworks($ref->id);

                    if (!$ref->settings) $ref->settings = new stdClass();

                    if (!isset($ref->settings->show_email)) $ref->settings->show_email = 1;
                    if (!isset($ref->settings->show_skype)) $ref->settings->show_skype = 1;
                    if (!isset($ref->settings->show_phone)) $ref->settings->show_phone = 1;
                    if (!isset($ref->settings->show_social)) $ref->settings->show_social = 1;
                    if (!isset($ref->settings->show_avatar)) $ref->settings->show_avatar = 1;
                }
            }

            echo json_encode(array(
                'total' => count($matches).pluralise(' Search Result', count($matches)),
                'html' => $this->loadPartialView('member/partial/referrals')
            ));
        } else {
            echo json_encode(array(
                'total' => '0 Search Results',
                'html' => 'No results'
            ));
        }

    }

    public function get_list($userId, $level) {
        $this->load->model('referral_model', 'Referral');
        $this->data->referrals = $this->Referral->get($userId);
        $this->data->level     = $level;
        $this->data->levelNum = spellNumber($level);

        foreach ($this->data->referrals as &$ref) {
            if ($this->profile->referrer_id > 0) {

                $ref->settings   = $this->User->getSettings($ref->id);
                $ref->socialList = $this->User->getSocialNetworks($ref->id);

                if (!$ref->settings) $ref->settings = new stdClass();

                if (!isset($ref->settings->show_email)) $ref->settings->show_email = 1;
                if (!isset($ref->settings->show_skype)) $ref->settings->show_skype = 1;
                if (!isset($ref->settings->show_phone)) $ref->settings->show_phone = 1;
                if (!isset($ref->settings->show_social)) $ref->settings->show_social = 1;
                if (!isset($ref->settings->show_avatar)) $ref->settings->show_avatar = 1;
            }
        }

        echo $this->loadPartialView('member/partial/referrals');
    }

    public function get_level($level) {
        $this->load->model('referral_model', 'Referral');
        $this->data->referrals = $this->Referral->getReferralList($this->userId, $level);
        $this->data->level     = $level;
        $this->data->levelNum  = ''; //spellNumber($level);

        foreach ($this->data->referrals as &$ref) {

                $ref->settings   = $this->User->getSettings($ref->id);
                $ref->socialList = $this->User->getSocialNetworks($ref->id);

                if (!$ref->settings) $ref->settings = new stdClass();

                if (!isset($ref->settings->show_email)) $ref->settings->show_email = 1;
                if (!isset($ref->settings->show_skype)) $ref->settings->show_skype = 1;
                if (!isset($ref->settings->show_phone)) $ref->settings->show_phone = 1;
                if (!isset($ref->settings->show_social)) $ref->settings->show_social = 1;
                if (!isset($ref->settings->show_avatar)) $ref->settings->show_avatar = 1;
        }

        echo $this->loadPartialView('member/partial/referrals');
    }
}