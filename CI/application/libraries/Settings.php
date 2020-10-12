<?php
/****
 * Class Settings
 *
 * A clever way to pull constants out of the database. Put this in config/autoload.php
 *
 */
class Settings {
  
    var $row;
    var $row_count;
  
    public function __construct() {
      
        $CI =& get_instance();
        $query = $CI->db->get('settings')->result();
    
        foreach($query as $r){
            $key=$r->name;
            $val=$r->value;
            if($key == "site_ssl"){
                if($val == "1")
                    define('SITE_SSL',true);
                else
                    define('SITE_SSL',false);
            } else {
                define(strtoupper($key),$val);
            }
        }       
    }
}
