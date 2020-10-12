<?
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function create_guid(){
  $microTime = microtime();
  list($a_dec, $a_sec) = explode(" ", $microTime);

  $dec_hex = sprintf("%x", $a_dec* 1000000);
  $sec_hex = sprintf("%x", $a_sec);

  ensure_length($dec_hex, 5);
  ensure_length($sec_hex, 6);

  $guid = "";
  $guid .= $dec_hex;
  $guid .= create_guid_section(3);
  $guid .= '-';
  $guid .= create_guid_section(4);
  $guid .= '-';
  $guid .= create_guid_section(4);
  $guid .= '-';
  $guid .= create_guid_section(4);
  $guid .= '-';
  $guid .= $sec_hex;
  $guid .= create_guid_section(6);

  return $guid;
}
function create_guid_section($characters)
{
  $return = "";
  for($i=0; $i<$characters; $i++)
  {
    $return .= sprintf("%x", mt_rand(0,15));
  }
  return $return;
}

function ensure_length(&$string, $length)
{
  $strlen = strlen($string);
  if($strlen < $length)
  {
    $string = str_pad($string,$length,"0");
  }
  else if($strlen > $length)
  {
    $string = substr($string, 0, $length);
  }
}

function create_random_code($c) {
    $chars = array();
    for ($i = 65; $i <= 90; $i++) $chars[] = chr($i); // A-Z
    for ($i = 48; $i <= 57; $i++) $chars[] = chr($i); // 0-9
    for ($i = 97; $i <= 122; $i++) $chars[] = chr($i); // a-z

    $r = '';
    $m = count($chars) - 1;
    for ($i = 0; $i < $c; $i++) {
        $idx = mt_rand(0, $m);
        $r .= $chars[$idx];
    }
    return $r;
}
