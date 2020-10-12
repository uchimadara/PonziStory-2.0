<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('admin.php');

class Adverts extends Admin
{
    public function __construct()
    {
        parent::__construct(true);

        $this->layoutData['title'] = 'Adverts';
        $this->load->model('campaign_model', 'Campaign');
    }

    public function index()
    {
        parent::viewList('pending_text_ads');
    }
    public function approve($table, $id) {
        $ad = $this->Campaign->getAd($id, $table);
        $this->Campaign->updateAd($id, $table, array('status' => 'approved'));

        $user = $this->User->getData($ad->user_id);
        $this->EmailQueue->store($user->email, wordify($table).' Approved', "emails/campaign/text_ad_approval", array('name' => $ad->name, 'username' => $user->username));

        echo json_encode(array('success' => 'success'));
    }

    public function reject($table, $id) {
        $ad = $this->Campaign->getAd($id, $table);
        $this->Campaign->updateAd($id, $table, array('status' => 'rejected'));
        $this->User->addCredits($ad->user_id, $ad->credits, 'sc_ad');

        $user = $this->User->getData($ad->user_id);
        $this->EmailQueue->store($user->email, wordify($table).' Rejected', "emails/campaign/text_ad_rejected", array('name' => $ad->name, 'username' => $user->username));

        echo json_encode(array('success' => 'success'));
    }

    public function page_campaigns($page = 1, $perPage = 20) {

        $count = $this->Campaign->getCount();
        $data = $this->Campaign->getAllCampaigns($page, $perPage);

        $paging   = generatePagination(site_url('adminpanel/adverts/page_campaigns'), $count, $page, $perPage, true);
        $hasPages = $count > $perPage;
        $campaigns  = $this->loadPartialView('admin/adverts/partial/campaigns', compact('data', 'paging', 'hasPages'));

        echo $campaigns;
    }

    public function edit($id)
    {
        $campaignData = $this->Campaign->get($id);
        $error = null;

        if ($post = $this->input->post())
        {
            $data = array();

            if ($_FILES && $_FILES['banner']['name'] != '')
            {
                $config = array(
                    'upload_path'   => FCPATH.'campaign/' . $campaignData->type . '/',
                    'allowed_types' => 'gif|jpg|png',
                    'max_size'      => 1024 * 2,
                    'encrypt_name'  => true
                );

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('banner'))
                {
                    @unlink($_FILES['banner']['tmp_name']);
                }
                else
                {
                    $image = $this->upload->data();
                    $data['image'] = $image['file_name'];
                }
            }

            if ($campaignData->name != $post['name'])
                $data['name'] = $post['name'];

            if ($campaignData->target_url != $post['url'])
                $data['target_url'] = $post['url'];

            if (count($data))
            {
                $this->Campaign->update($id, $data);

                $this->session->set_flashdata('success', 'Campaign updated');
                redirect('adminpanel/adverts');
            }
            else $error = 'Nothing to update';
        }

        $this->layoutData['title'] = 'Adverts';
        $this->loadView('admin/adverts/edit', 'Edit Campaign', compact('campaignData', 'error'));
    }

    public function prices()
    {
        $currentPrice     = $this->Campaign->getPrice('fixed');
        $impressionValues = $this->Campaign->getImpressionValues('fixed');
        $error = null;

        if ($post = $this->input->post())
        {
            $updated = false;
            $price = $post['price'];
            if ($price != $currentPrice)
            {
                if (is_numeric($price))
                {
                    $this->Campaign->addPrice('fixed', $price);

                    $this->session->set_flashdata('success', 'Price Changed');
                    $updated = true;
                }
                else $error = 'This price is not a proper number';
            }

            $impValue = json_encode($post['impression_value']);
            if ($impValue != $impressionValues)
            {
                $this->Campaign->addImpressionValues('fixed', $impValue);

                $this->session->set_flashdata('success', 'Impression Values Changed');
                $updated = true;
            }

            if ($updated)
                redirect('adminpanel/adverts');
        }

        $impressionValues = json_decode($impressionValues, true);

        $this->layoutData['title'] = 'Adverts';
        $this->loadView('admin/adverts/prices', 'Campaign Prices', compact('currentPrice', 'impressionValues', 'error'));
    }
}