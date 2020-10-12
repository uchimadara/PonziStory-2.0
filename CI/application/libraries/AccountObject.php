<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AccountObject
{
    public function __construct($input = null)
    {
        if ($input)
        {
            /**
             * The idea behind this is that if the input is a string, it should
             * be an object therefore needs to be decoded to produce an output.
             * If the input is an array it should be treated as input and used to create
             * the object to be saved in the DB...
            **/
            $data = is_array($input) ? $input : unserialize(base64_decode($input));

            foreach ($data as $k=>$v)
                $this->$k = stripcslashes($v);
        }
    }

    // This function will automatically return a base64 encoded string when the object is cast as a string
    // It is actually part of the PHP Magic methods (http://php.net/manual/en/language.oop5.magic.php)
    public function __toString()
    {
        $serialized = serialize($this);
        return base64_encode($serialized);
    }
}

class WesternUnion extends AccountObject
{
    public $first_name = null;
    public $last_name  = null;
    public $city       = null;
    public $region     = null;
    public $zip        = null;
    public $country    = null;

    // Just a function to return a nice looking title otherwise it is difficult to display
    public function getTitle()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

class WesternUnionDetails extends AccountObject
{
    public $city          = null;
    public $region        = null;
    public $zip           = null;
    public $country       = null;
    public $mtcn          = null;
    public $transfer_date = null;
    public $currency      = 'USD';
    public $amount        = null; // Real Amount sent, in the currency specified above
}

class BankWire extends AccountObject
{
    public $bank_name      = null;
    public $bank_address   = null;
    public $bank_city      = null;
    public $bank_country   = null;
    public $fullname       = null;
    public $address        = null;
    public $city           = null;
    public $country        = null;
    public $account_number = null;
    public $bic_swift      = null;
    public $iban           = null;
    public $info           = null;

    public function getTitle()
    {
        return $this->fullname;
    }
}

class BankWireDetails extends AccountObject
{
    public $memo     = null;
    public $info     = null;
    public $currency = 'USD';
    public $amount   = null; // Real Amount sent, in the currency specified above
}