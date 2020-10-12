<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include(APPPATH.'core/MY_AjaxController.php');

class Screenshot extends MY_AjaxController {

    public function __construct() {
        parent::__construct();

        $this->requireLogon();
    }

    public function index() {
        show_error('nothing here');
    }


    public function image_upload() {
        $config = array(
            'upload_path'   => FCPATH.'uploads/',
            'allowed_types' => 'gif|jpg|png',
            'max_size'      => 1024*2,
            'encrypt_name'  => TRUE
        );

        $config['max_width']  = 250;
        $config['max_height'] = 250;

        $this->load->library('upload', $config);

        $data = NULL;
        if (!$this->upload->do_upload('screenshot_file')) {
            $data = array(
                'error' => $this->upload->display_errors('', '')
            );
            @unlink($_FILES['screenshot']['tmp_name']);
        } else {
            $image = $this->upload->data();

            list($width, $height, $type, $attr) = @getimagesize($image['full_path']);

            // Validate image type
            $types = array(IMAGETYPE_JPEG => '.jpg', IMAGETYPE_GIF => '.gif', IMAGETYPE_PNG => '.png');
            if (!array_key_exists($type, $types)) {
                $data['error'] = '* Incorrect type - use gif, jpg, or png only.';
                echo json_encode($data);
                return;
            } else {
                if (putObject('uploads/'.$image['file_name'], $image['full_path'], $image['file_type'])) {
                    $data = array(
                        'success'    => 'success',
                        'file'       => $image['file_name'],
                        'screenshot' => screenshot($image['file_name'])
                    );

                    $data = $data + $image;
                } else {
                    $data = array(
                        'error' => 'Could not upload - please try again'
                    );
                }
            }

            //@unlink($image['full_path']);
        }

        echo json_encode($data);
    }

    public function test_image() {
        $data = array(
            'success' => 0,
            'error'   => 0
        );
        if (($imgFile = $this->input->post('imageURL'))) {

            if (preg_match('((http|https):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=‌​%&amp;/~\+#])?)', $imgFile)) {

                if (list($width, $height, $type, $attr) = @getimagesize($imgFile)) {
                    $types = array(IMAGETYPE_JPEG => '.jpg', IMAGETYPE_GIF => '.gif', IMAGETYPE_PNG => '.png');
                    if (!array_key_exists($type, $types)) {
                        $data['error'] = '* Incorrect type - use gif, jpg, or png only.';
                        echo json_encode($data);
                        return;
                    }

                    //$mime = image_type_to_mime_type($type);

                    $screenshot = md5(uniqid(mt_rand())).$types[$type];
                    $path       = FCPATH.'uploads/';

                    copy($imgFile, $path.$screenshot);

                    $data = array(
                        'error'      => 0,
                        'success'    => 'success',
                        'file'       => $screenshot,
                        'screenshot' => screenshot($screenshot)
                    );

                } else {
                    $data = array(
                        'error' => 'invalid image.'
                    );
                }
            } else {
                $data = array(
                    'error' => 'invalid URL'
                );
            }
        } else {
            $data = array(
                'error' => 'invalid input'
            );
        }

        echo json_encode($data);
        return;
    }

    public function cancel_image() {
        if ($file = $this->input->post('screenshot')) {
            @unlink(FCPATH.'uploads/'.$file);
        }
    }
}
