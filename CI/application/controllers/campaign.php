<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Campaign extends MY_Controller {
    public function __construct() {
        parent::__construct();

        //Loading The Required Models
        $this->load->model('campaign_model', 'Campaign');
    }

    public function index() {
        show_404();
    }

    public function click_thru($id, $type) {
        $ad = $this->Campaign->clickAd($type, $id, $this->userId);

        if (!$ad) show_404();

        $url = $ad->target_url;
        redirect($url);
    }

    public function view($table, $id) {
        $this->data->ad = $this->Campaign->getAd($id, $table);

        if (!$this->data->ad) show_404();

        $this->data->ad->type = $table;

        $userId              = $this->profile ? $this->profile->id : 0;
        $this->data->isOwner = $userId == $this->data->ad->user_id;

        $clicksViews = $this->Campaign->getClicksViews($id, $table);

        $this->data->stats = array(
            'url'    => "campaign/ad_stats/{$table}/$id",
            'views'  => $clicksViews['views'],
            'clicks' => $clicksViews['clicks'],
            'name'   => 'Stats'
        );

        if ($this->ajax) {
            echo $this->loadPartialView("campaign/{$table}_details");
        } else {

            $this->addJavascript(asset('amcharts/amcharts.js'));
            $this->addJavascript(asset('amcharts/serial.js'));
            $this->addJavascript(asset('amcharts/responsive.js'));
            $this->addJavascript(asset('amcharts/themes/light.js'));
            $this->addJavascript(asset('bootstrap/js/ad_chart.js'));
            $this->addStylesheet(asset('bootstrap/member/css/amchart.css'));

            $this->data->content = $this->loadPartialView("campaign/{$table}_details");
            $this->setLayout('layout/member/shell');
            $this->loadView('layout/default', 'Campaign Stats');
        }
    }

    public function text_ad_views($id = NULL) {
        $this->load->model('campaign_model', 'Campaign');

        $items = $this->Campaign->countTextAdViewsByDay($this->profile->id, $id);
        echo json_encode(array_values($items));
    }

    public function text_ad_clicks($id = NULL) {
        $this->load->model('campaign_model', 'Campaign');

        $items = $this->Campaign->countTextAdClicksByDay($this->profile->id, $id);
        if ($items) echo json_encode(array_values($items));
    }

    public function banner_views($id = NULL) {
        $this->load->model('campaign_model', 'Campaign');

        $items = $this->Campaign->countBannerViewsByDay($id);
        if ($items) echo json_encode(array_values($items));
    }

    public function ad_stats($table, $id) {
        $this->load->model('campaign_model', 'Campaign');

        $views = $this->Campaign->countAdViewsByDay($id, $table);

        $items = array();
        foreach ($views as $v) {
            $d              = date(DEFAULT_DATE_FORMAT, $v->day);
            $items[$v->day] = array(
                'Date'   => $d,
                'Views'  => $v->c,
                'Clicks' => 0
            );
        }

//        if (count($items) < 7) {
//            $start = strtotime("-7 days", date('Y-m-d 00:00:00'));
//            for ($i = 0; $i < 7; $i++) {
//            }
//        }
//
        $clicks = $this->Campaign->countAdClicksByDay($id, $table);

        foreach ($clicks as $c) {
            $items[$c->day]['Clicks'] = $c->c;
        }

        $stats = array();
        foreach ($items as $i) {
            $stats[] = $i;
        }
        if ($stats) echo json_encode(array_values($stats));
    }
}