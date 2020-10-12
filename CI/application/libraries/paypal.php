<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package CodeIgniter
 * @author Romaldy Minaya
 * @copyright Copyright (c) 2011, PROTOS.
 * @license GLP
 * @since Version 1.0
 * @version 1.0
 */
// ------------------------------------------------------------------------
/**
 * Paypal Class
 *
 * @package CodeIgniter
 * @subpackage Libraries
 * @category Payment process
 * @author Romaldy Minaya
 *
 * // ------------------------------------------------------------------------
 * Documentation
 * This class let you make the payment procces based on paypal API,
 * effortless and easy.
 *1)Use the same documentation about the vars from paypal page. http://bit.ly/j4wRR
 *2)Customize the payment procces as you desire.
 *3)Build with love.
 * Implementation
 *1)Copy this code in your controller's function
 * $config['business'] = 'demo@demo.com';
 * $config['cpp_header_image'] = ''; //Image header url [750 pixels wide by 90 pixels high]
 * $config['return'] = 'sucess.php';
 * $config['cancel_return'] = 'shopping.php';
 * $config['notify_url'] = 'process_payment.php'; //IPN Post
 * $config['production'] = TRUE; //Its false by default and will use sandbox
 * $config['discount_rate_cart'] = 20; //This means 20% discount
 * $config["invoice"] = '843843'; //The invoice id
 * $this->load->library('paypal',$config);
 * #$this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional]);
 * $this->paypal->add('T-shirt',2.99,6); //First item
 * $this->paypal->add('Pants',40); //Second item
 * $this->paypal->add('Blowse',10,10,'B-199-26'); //Third item with code
 * $this->paypal->pay(); //Proccess the payment
 * The notify url is where paypal will POST the information of the payment so you
 * can save that POST directly into your DB and analize as you want.
 * With $config["invoice"] is how you identify a bill and you can compare,save or update
 * that value later on your DB.
 * For test porpuses i do recommend to save the entire POST into your DB and analize if
 * its working according to your needs before putting it in production mode. EX.
 * $received_post = print_r($this->input->post(),TRUE);
 * //Save that variable and analize.
 * Note: html reference page http://bit.ly/j4wRR
 */
class Paypal {
    var $config = Array();
    var $production_url = 'https://www.paypal.com/cgi-bin/webscr?';
    var $sandbox_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
    var $item = 1;

    /**
     * Constructor
     *
     * @param string
     * @return void
     */
    public function __construct($props = array()) {
        $this->__initialize($props);
        log_message('debug', "Paypal Class Initialized");
    }

// --------------------------------------------------------------------
    /**
     * initialize Paypal preferences
     *
     * @access public
     * @param array
     * @return bool
     */
    function __initialize($props = array()) {
#Account information
        $config["business"]   = ''; //Account email or id
        $config["cmd"]        = '_cart'; //Do not modify
        $config["production"] = FALSE;
#Custom variable here we send the billing code-->
        $config["custom"]  = '';
        $config["invoice"] = ''; //Code to identify the bill
#API Configuration-->
        $config["upload"]        = '1'; //Do not modify
        $config["currency_code"] = 'USD'; //http://bit.ly/anciiH
        $config["disp_tot"]      = 'Y';
#Page Layout -->
        $config["cpp_header_image"]      = ''; //Image header url [750 pixels wide by 90 pixels high]
        $config["cpp_cart_border_color"] = '000'; //The HTML hex code for your principal identifying color
        $config["no_note"]               = 1; //[0,1] 0 show, 1 hide
#Payment Page Information -->
        $config["return"]        = ''; //The URL to which PayPal redirects buyersÃ¢â‚¬â„¢ browser after they complete their payments.
        $config["cancel_return"] = ''; //Specify a URL on your website that displays a Ã¢â‚¬Å“Payment CanceledÃ¢â‚¬Â page.
        $config["notify_url"]    = ''; //The URL to which PayPal posts information about the payment (IPN)
        $config["rm"]            = '2'; //Leave this to get payment information
        $config["lc"]            = 'EN'; //Languaje [EN,ES]
#Shipping and Misc Information -->
        $config["shipping"]             = '';
        $config["shipping2"]            = '';
        $config["handling"]             = '';
        $config["tax"]                  = '';
        $config["discount_amount_cart"] = ''; //Discount amount [9.99]
        $config["discount_rate_cart"]   = ''; //Discount percentage [15]
#Customer Information -->
        $config["first_name"]    = '';
        $config["last_name"]     = '';
        $config["address1"]      = '';
        $config["address2"]      = '';
        $config["city"]          = '';
        $config["state"]         = '';
        $config["zip"]           = '';
        $config["email"]         = '';
        $config["night_phone_a"] = '';
        $config["night_phone_b"] = '';
        $config["night_phone_c"] = '';
        /*
        * Convert array elements into class variables
        */
        if (count($props) > 0) {
            foreach ($props as $key => $val) {
                $config[$key] = $val;
            }
        }
        $this->config = $config;
    }

// --------------------------------------------------------------------
    /**
     * Perform payment process
     *
     * @access public
     * @param array
     * @return void
     */
    function pay() {
#Convert the array to url encode variables
        $vars = http_build_query($this->config);
        if ($this->config['production'] == TRUE) {
            header('LOCATION:'.$this->production_url.$vars);
        } else {
            header('LOCATION:'.$this->sandbox_url.$vars);
        }
    }

// --------------------------------------------------------------------
    /**
     * Add a product to the list
     *
     * @access public
     * @param array
     * @return void
     */
    function add($item_name = '', $item_amount = NULL, $item_qty = NULL, $item_number = NULL) {
        $this->config['item_name_'.$this->item]   = $item_name;
        $this->config['amount_'.$this->item]      = $item_amount;
        $this->config['quantity_'.$this->item]    = $item_qty;
        $this->config['item_number_'.$this->item] = $item_number;
        $this->item++;
    }

}
// END Paypal Class
/* End of file Paypal.php */
/* Location: ./application/libraries/Paypal.php */