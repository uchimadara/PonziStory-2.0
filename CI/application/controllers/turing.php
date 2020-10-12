<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Turing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function reset_turing() {
        $this->load->helper('guid');
        $this->session->unset_userdata(array('rand1', 'rand2'));
        $rand1 = rand(1, 10);
        $rand2 = rand(1, 10);
        $this->session->set_userdata(array('rand1' => $rand1, 'rand2' => $rand2));

        echo '<img src="'.SITE_ADDRESS.'turing_test/'.create_guid().'.jpg" class="turingTest" height="40" width="100"/>';
    }

    public function test_image() {

        $im = imagecreatefromjpeg(assetFilePath().'images/turing_bg.jpg');

        $color = ImageColorAllocateAlpha($im, 191, 241, 240, 45);
        imagecolortransparent($im, $color);

        $center = 50;

        $turing = $this->session->userdata('rand1').' + '.$this->session->userdata('rand2');

        $start_y   = 35;
        $font_size = 24;
        $angle     = 0;
        $font      = assetFilePath().'fonts/arialbd.ttf';

        do {
            $font_size -= 2;
            $start_y -= 1;
            $dimensions = imagettfbbox($font_size, $angle, $font, $turing);
            $start_x    = $center - ($dimensions[2]/2);
        } while ($start_x < 8);

        Imagettftext($im, $font_size, $angle, $start_x, $start_y, $color, $font, $turing);

        //Creates the jpeg image and sends it to the browser
        //100 is the jpeg quality percentage
        header('Content-Type: image/jpeg');
        Imagejpeg($im, NULL, 100);
        ImageDestroy($im);
    }

}