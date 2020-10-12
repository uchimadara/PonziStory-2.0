<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class textAds extends Widget {
    function run($count = 4, $userId, $userLevel) {

        $this->load->model('campaign_model', 'Campaign');
        $this->load->model('user_model', 'User');

        $ci = get_instance();

        //if ($placement = $this->Campaign->getPlacement('text', $group, $position, 'text')) {
        $adverts = $this->Campaign->getAds('text_ad', 'text', $count, $userId);

        $html = '';
        if ($adverts) {
            foreach ($adverts as $advert) {
                $html .= $ci->load->view('ads/text_ad', compact('advert'), true);
                //$this->render('text_ad', compact('advert'));
            }
        }

//        if (($c = count($adverts)) < $count) {
//            for ($i = $c; $i < $count; $i++) {
//                $this->render('default_text_ad');
//            }
//        }
        //}
        return $html;
    }
}