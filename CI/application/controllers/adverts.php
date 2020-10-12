<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adverts extends MY_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->isGuest) {
            if ($this->ajax) {
                show_error("Please ".anchor(site_url('login'), 'log in')." for access.");
                exit(0);
            } else {
                redirect();
            }
        }

        $this->output->enable_profiler(PROFILER_SETTING);

        $this->load->model('campaign_model', 'Campaign');
    }

    public function index() {
        $this->bootstrap();
    }

    /********
     * @name bootstrap
     * @param string $page
     *
     * @puropse main entry point for back office pages
     */

    public function bootstrap($page = 'dashboard') {

        if (!method_exists($this, $page)) {
            $page = 'dashboard';
        }

        $html = $this->$page();

        if ($this->ajax) {
            $state = array(
                'html'      => $html,
                'pageTitle' => $this->data->page_title,
                'url'       => SITE_ADDRESS.'ads/'.$page.''
            );
            echo json_encode($state);
            return;
        }

        $this->addJavascript(asset('scripts/sortable.js'));
        $this->addJavascript(asset('scripts/replace.js'));
        $this->addJavascript(asset('scripts/getList.js'));
        $this->addJavascript(asset('scripts/tabs.js'));

        $this->data->content = & $html;
        $this->layout        = 'layout/member/shell';
        $this->loadView('layout/default', $this->data->page_title);
    }

 /**********
  *
  * DASHBOARD
  *
  */
    public function dashboard() {
        $this->data->page_title = 'Advertising';

        return $this->loadPartialView('ads/index');
    }

    /***********
     *
     * TEXT ADS
     *
     */

    public function text_ads() {

        $this->data->page_title = 'Text Ads';
        $_GET['user_id']        = $this->userId;
        $this->_path            = 'member/';

        //$this->data->placementList = $this->renderList('ad_placement');

        $this->data->maxAds          = $this->Account->getMaxAds($this->profile->account_level);
        $this->data->adCredits       = $this->User->getData($this->userId, array('text_ad_credits'))->text_ad_credits;
        $this->data->textAdCount     = $this->Campaign->countAds($this->userId, 'text_ad');
        $this->data->textAds         = $this->loadPartialView('ads/text_ads');

        $this->addJavascript(asset('amcharts/amcharts.js'));
        $this->addJavascript(asset('amcharts/serial.js'));
        $this->addJavascript(asset('amcharts/responsive.js'));
        $this->addJavascript(asset('amcharts/themes/light.js'));
        $this->addJavascript(asset('bootstrap/js/ad_chart.js'));
        $this->addStylesheet('/layout/member/assets/css/amchart.css');

        return $this->loadPartialView('ads/index');
    }



public function banner_ads() {

        $this->data->page_title = 'Banner Ads';
        $_GET['user_id']        = $this->userId;
        $this->_path            = 'member/';

        //$this->data->placementList = $this->renderList('ad_placement');

        $this->data->maxAds          = $this->Account->getMaxAds($this->profile->account_level);
        $this->data->adCredits       = $this->User->getData($this->userId, array('text_ad_credits'))->text_ad_credits;
        $this->data->bannerAdCount     = $this->Campaign->countAds($this->userId, 'banner');
        $this->data->textAds         = $this->loadPartialView('ads/banner_ad');

        $this->addJavascript(asset('amcharts/amcharts.js'));
        $this->addJavascript(asset('amcharts/serial.js'));
        $this->addJavascript(asset('amcharts/responsive.js'));
        $this->addJavascript(asset('amcharts/themes/light.js'));
        $this->addJavascript(asset('bootstrap/js/ad_chart.js'));
        $this->addStylesheet('/layout/member/assets/css/amchart.css');

        return $this->loadPartialView('ads/banner_ads');
    }


    public function delete_banner_ad($id) {

        $ad = $this->Campaign->getAd($id,'banner');
        if ($ad->user_id == $this->profile->id) {
            $this->Campaign->deleteBannerAd($id);
            if (($diff = $ad->credits - $ad->impressions) > 0) {
                $this->User->update($ad->user_id, array('text_ad_credits' => $this->profile->text_ad_credits + $diff));
                $this->data->userData->text_ad_credits += $diff;
            }
            $result = array(
                'replace' => array(
                    'textAdCreditTotal' => number_format($this->data->userData->text_ad_credits)
                )
            );
        } else {
            // hack attempt
            $result = array('error' => 'Invalid access.');
        }
        echo json_encode($result);
    }


    public function delete_text_ad($id) {

        $ad = $this->Campaign->getTextAd($id);
        if ($ad->user_id == $this->profile->id) {
            $this->Campaign->deleteTextAd($id);
            if (($diff = $ad->credits - $ad->impressions) > 0) {
                $this->User->update($ad->user_id, array('text_ad_credits' => $this->profile->text_ad_credits + $diff));
                $this->data->userData->text_ad_credits += $diff;
            }
            $result = array(
                'replace' => array(
                    'textAdCreditTotal' => number_format($this->data->userData->text_ad_credits)
                )
            );
        } else {
            // hack attempt
            $result = array('error' => 'Invalid access.');
        }
        echo json_encode($result);
    }

    public function assign_credits() {

        $post = $this->input->post();

        if (isset($post['credits'])) {
            $sum   = 0;
            $table = $this->input->post('table');

            $result = array();
            foreach ($post['credits'] as $adId => $numCredits) {
                if (is_numeric($numCredits)) {

                    $sum += intval($numCredits);

                    if ($numCredits < 0) {
                        $ad = $this->Campaign->getAd($adId, $table);

                        if ($ad->user_id != $this->profile->id) {

                            // hack attempt
                            echo json_encode(array('error' => 'Invalid access.'));
                            return;
                        }
                        if (($ad->credits - $ad->impressions) < -$numCredits) {
                            if (empty($result)) {
                                $result['errorElements'] = array();
                            }
                            $result['errorElements']['credits-'.$adId] = '*too many';
                        }
                    }
                } elseif ($numCredits != '') {

                    if (empty($result)) {
                        $result['errorElements'] = array();
                    }

                    $result['errorElements']['credits-'.$adId] = '*invalid';
                }
            }

            if (!empty($result)) {
                echo json_encode($result);
                return;
            }

            $field = $table.'_credits';

            if ($sum > $this->profile->$field) {
                echo json_encode(array('error' => "You don't have that many credits to assign"));
                return;
            }

            $net = -$sum; // if total is neg, credits will be added.

            $this->User->update($this->userId, array($field => $this->profile->$field + $net));

            $this->data->userData->$field += $net;

            foreach ($post['credits'] as $id => $credits) {

                if ($credits != '') {
                    $this->Campaign->addCredits($id, $credits, $table);
                }
            }

            $_GET['user_id'] = $this->userId;
            $this->_path     = 'member/';

            if ($table == 'banner') {
                $this->data->bannerAdList = $this->renderList('user_banner_ads');
                $result                   = array(
                    'html' => $this->loadPartialView('ads/banners')
                );
            } else {
                $this->data->textAdList = $this->renderList('user_text_ads');
                $result                 = array(
                    'html' => $this->loadPartialView('ads/text_ads')
                );
            }
        } else {
            $result = array('error' => 'Invalid entry.');
        }

        echo json_encode($result);
    }

}
