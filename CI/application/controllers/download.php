<?php

class Download extends MY_Controller {

	function __construct()
	{
    parent::__construct();
        if ($this->isGuest) {
            if ($this->ajax) {
                echo "Please ".anchor(site_url('login.html'), 'log in')." for access.";
                return;
            } else {
                show_error('unauthorized access');
            }
        }
    }

	function reward($id) {

        $this->load->model('product_model', 'Product');
        $reward = $this->Product->get($id);

        if ($reward->purchase_item_code > $this->profile->account_level) {
            $this->session->set_flashdata('error', 'Invalid reward access: Upgrade level not high enough.');
            redirect('back_office');
        }

        if ($reward->enabled  == 0) {
            $this->session->set_flashdata('error', 'Invalid reward access: Reward not enabled.');
            redirect('back_office');
        }

        $filename = str_replace('%20', ' ', $reward->file);
		
		$local_location = DOCPATH.$filename;
		
		if(!file_exists( $local_location )) {
			echo ("Invalid File Reference: ".$local_location);
			return;
		
		} else {

			if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])) {
				// cn: bug 7870 IE cannot handle MBCS in filenames gracefully. set $name var to filename
				$name = str_replace("+", "_", $filename);
				//$name = translate_charset($name, 'UTF-8', $locale->getOutboundEmailCharset());
			} else {
				// ff 1.5+
				$name = mb_encode_mimeheader($filename, 'UTF-8', 'Q');
			}
	
			header('Content-Description: File Transfer');
			header('Content-Type: application/force-download');
			header("Content-Disposition: attachment; filename=\"".$name."\";");
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($local_location));	
	
			set_time_limit(0);
			
			@ob_end_clean();
			ob_start();
		//	if(filesize($local_location) < 2097152) {
		//		readfile($download_location);
		//	} else {
			$this->readfile_chunked($local_location);
		//	}
			@ob_flush();
		}
	}

    function readfile_chunked($filename, $retbytes = TRUE) {
        $chunksize = 1*(1024*1024); // how many bytes per chunk
        $buffer    = '';
        $cnt       = 0;

        $handle = fopen($filename, 'rb');
        if ($handle === FALSE) {
            return FALSE;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, $chunksize);
            echo $buffer;
            ob_flush();
            flush();
            set_time_limit(0);

            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }
        return $status;
    }
}