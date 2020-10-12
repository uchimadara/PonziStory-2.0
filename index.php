<?php

function getRealIpAddr() {

    $ip = !empty($_SERVER['HTTP_CF_CONNECTING_IP']) ? htmlspecialchars((string)$_SERVER['HTTP_CF_CONNECTING_IP']) : FALSE;
    if (!$ip)
        $ip = !empty($_SERVER['HTTP_CLIENT_IP']) ? htmlspecialchars((string)$_SERVER['HTTP_CLIENT_IP']) : FALSE;

    if (!$ip)
        $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? htmlspecialchars((string)$_SERVER['HTTP_X_FORWARDED_FOR']) : FALSE;

    if (!$ip)
        $ip = !empty($_SERVER['REMOTE_ADDR']) ? htmlspecialchars((string)$_SERVER['REMOTE_ADDR']) : '0.0.0.0';

    // Hack because some of the IPs seems to be on the format: xxx.xxx.xxx.xxx, xxx.xxx.xxx.xxx
    $idx = strpos($ip, ',');
    if ($idx !== FALSE)
        $ip = substr($ip, 0, $idx);

    return $ip;
}

define('SITE', 'tdm.nghelpers.com');

//$ip = getRealIpAddr();
//
//if ($_SERVER['HTTP_HOST'] != 'localhost') {
//    if (in_array($ip, array('0.0.0.0', '138.185.76.42', '169.0.223.160')) === FALSE)
//        die("<br/><br/><strong>".SITE." is down for maintenance. We'll be back shortly. Thank you for your patience.<br/><br/>");
//}

//date_default_timezone_set('UTC');

/*
 * ---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 * ---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     local
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */

if (!isset($_SERVER['HTTP_HOST'])) { // command line
    define('PROFILER_SETTING', FALSE);
    define('CLI', TRUE);
    $dir = dirname(__FILE__);
    if (strpos($dir, 'C:') !== FALSE) {
        define('ENVIRONMENT', 'local');
        $_SERVER['HTTP_HOST'] = 'localhost';
    } elseif (strpos($dir, 'dev') !== FALSE) {
        define('ENVIRONMENT', 'testing');
        $_SERVER['HTTP_HOST'] = 'dev.'.SITE;
    } else {
        define('ENVIRONMENT', 'production');
        $_SERVER['HTTP_HOST'] = SITE;
    }
} else {

    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define('ENVIRONMENT', 'local');
        define('PROFILER_SETTING', FALSE);
    } else if (strpos($_SERVER['HTTP_HOST'], 'dev.') !== FALSE) {
        define('ENVIRONMENT', 'testing');
        define('PROFILER_SETTING', FALSE);
    } else {
        define('ENVIRONMENT', 'production');
        define('PROFILER_SETTING', FALSE);
    }
}

/*
 * ---------------------------------------------------------------
 * ERROR REPORTING
 * ---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */

if (defined('ENVIRONMENT')) {
    switch (ENVIRONMENT) {
        case 'local':
            error_reporting(E_ALL ^ E_NOTICE);
            ini_set('display_errors', 1);
            break;

        case 'testing':
            error_reporting(E_ALL ^ E_NOTICE);
            ini_set('display_errors', 1);
            break;
        case 'production':
            error_reporting(0);
            ini_set('display_errors', 1);
            break;

        default:
            exit('The application environment is not set correctly.');
    }
}
/*
 * ---------------------------------------------------------------
 * SECURE FOLDER NAME
 * ---------------------------------------------------------------
 *
 * This variable must contain the name of your "secure" folder.
 * Ensure this directory is not web accessible.
 *
 */
$secure_path = '..';
if (realpath($secure_path) !== FALSE) {
    $secure_path = realpath($secure_path).'/';
    define('SECUREPATH', str_replace("\\", "/", $secure_path));
}

/*
 * ---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 * ---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
$system_path = 'CI/system';

/*
 * ---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 * ---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
$application_folder = 'CI/application';
/*
 * ---------------------------------------------------------------
 * Document FOLDER NAME
 * ---------------------------------------------------------------
 *
 *
 */
$document_folder = realpath('../documents');
/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here.  For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT:  If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller.  Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 *
 */
// The directory name, relative to the "controllers" folder.  Leave blank
// if your controller is not in a sub-folder within the "controllers" folder
// $routing['directory'] = '';
// The controller class file name.  Example:  Mycontroller
// $routing['controller'] = '';
// The controller function you wish to be called.
// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
// $assign_to_config['name_of_config_item'] = 'value of config item';
// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

// Set the current directory correctly for CLI requests
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

if (realpath($system_path) !== FALSE) {
    $system_path = realpath($system_path).'/';
}

// ensure there's a trailing slash
$system_path = rtrim($system_path, '/').'/';

// Is the system path correct?
if (!is_dir($system_path)) {
    exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
// this global constant is deprecated.
define('EXT', '.php');

// Path to the system folder
define('BASEPATH', str_replace("\\", "/", $system_path));

// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));

// Name of the "system folder"
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


// The path to the "application" folder
if (is_dir($application_folder)) {
    define('APPPATH', $application_folder.'/');
} else {
    if (!is_dir(BASEPATH.$application_folder.'/')) {
        exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
    }

    define('APPPATH', BASEPATH.$application_folder.'/');
}
if (is_dir($document_folder)) {
    define('DOCPATH', $document_folder.'/');
}

/*
 * --------------------------------------------------------------------
 * LOAD COMPOSER LIBS
@include_once './vendor/autoload.php';
*
--------------------------------------------------------------------
 *
 */

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */
require_once BASEPATH.'core/CodeIgniter.php';

/* End of file index.php */
/* Location: ./index.php */