<?php
if (!defined( "BASEPATH")) exit ('No direct script access allowed');
/**
 * SITE PATHS
 */
if (SITE_SSL)define( "SITE_PROTO" ,'http');
else define( "SITE_PROTO" ,'http');
define( "SITE_PROTO" ,'http');
define( "SITE_ADDRESS" ,SITE_PROTO
                        . '://'
                        . $_SERVER['HTTP_HOST']
                        . '/');
define( "SITE_DOMAIN" ,$_SERVER['HTTP_HOST']
                       . '/');
define( "BASE_URL" ,SITE_ADDRESS);
define( "CDN_URL" ,SITE_ADDRESS);
define( "ASSET_VERSION" ,'');
/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define( "FILE_READ_MODE" ,0644);
define( "FILE_WRITE_MODE" ,0666);
define( "DIR_READ_MODE" ,0755);
define( "DIR_WRITE_MODE" ,0777);
/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
define( "FOPEN_READ" ,'rb');
define( "FOPEN_READ_WRITE" ,'r+b');
define( "FOPEN_WRITE_CREATE_DESTRUCTIVE" ,'wb'); // truncates existing file data, use with care
define( "FOPEN_READ_WRITE_CREATE_DESTRUCTIVE" ,'w+b'); // truncates existing file data, use with care
define( "FOPEN_WRITE_CREATE" ,'ab');
define( "FOPEN_READ_WRITE_CREATE" ,'a+b');
define( "FOPEN_WRITE_CREATE_STRICT" ,'xb');
define( "FOPEN_READ_WRITE_CREATE_STRICT" ,'x+b');
// GENERAL PURPOSE
define( "DEFAULT_DATE_FORMAT" ,'d-M-Y');
define( "DEFAULT_DATETIME_FORMAT" ,'d-M-Y H:i:s');
define( "DEFAULT_TIME_FORMAT" ,'H:i:s');
define( "MYSQL_DATE_FORMAT" ,'Y-m-d');
define( "MYSQL_DATETIME_FORMAT" ,'Y-m-d H:i:s');
// PAGING
define( "DEFAULT_ITEMS_PER_PAGE" ,20);
define( "ITEMS_PER_PAGE_MIN" ,10);
define( "ITEMS_PER_PAGE_MAX" ,100);
define( "PER_PAGE_INTERVAL" ,10);
/* CACHE CONTROL */
define( "CACHE_FIVE_SECONDS" ,5);
define( "CACHE_THIRTY_SECONDS" ,30);
define( "CACHE_FIVE_MINUTES" ,300);
define( "CACHE_ONE_HOUR" ,3600);
define( "CACHE_ONE_DAY" ,86400);
define( "CACHE_METHOD_PRIMARY" ,'file');
define( "CACHE_METHOD_SECONDARY" ,'file');
define( "KEY_PREFIX" ,'');
define( "EMAIL_ALL" ,2047);
define( "THUMBNAIL_IMAGE_MAX_WIDTH" ,160);
define( "THUMBNAIL_IMAGE_MAX_HEIGHT" ,160);
define( "SITE_START_DATE" ,'2014-11-30 23:59:59');
/* End of file constants.php */
/* Location: ./application/config/constants.php */
