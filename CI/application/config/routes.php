<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'main';
$route['404_override']       = '';
$route['index'] = 'main/bootstrap';
$route['testimonials'] = 'main/testimonials';
$route['news'] = 'news';


// user
$route['register'] = 'user/register';
$route['login']    = 'user/login';
$route['logout']   = 'user/logout';
$route['forgot']   = 'user/forgot_password';
$route['blackpage'] = 'main/blackpage';
$route['migration'] = 'main/migration';
$route['turing_test.jpg'] = 'turing/test_image';
$route['turing_test/(:any).jpg'] = 'turing/test_image';

$route['activate/(:num)/([a-f0-9\-]{36})'] = 'user/activate/$1/$2';
$route['reset_password/([a-f0-9]{40})']  = 'user/reset_password/$1';

$route['ref/([\w\-]+)']                             = 'main/referral/$1';

$route['back_office']            = 'member/bootstrap';
$route['back_office/(\w+)'] = 'member/bootstrap/$1';
$route['back_office/(\w+)/(:num)'] = 'member/$1/$2';
$route['back_office/approve/(:num)'] = 'member/approve/$1/$2';
$route['back_office/refPurge/(:num)/(:num)'] = 'member/refPurge/$1/$2';
$route['back_office/reject/(:num)'] = 'member/reject/$1/$2';
$route['back_office/rejectPost/(:num)'] = 'member/rejectPost/$1/$2';
//$route['back_office/pay_subscription/(:num)'] = 'member/pay_subscription/$1';
$route['back_office/order_success/(:num)'] = 'member/bootstrap/order_success';

// Ads
$route['ads']       = 'adverts/bootstrap';
$route['ads/(\w+)'] = 'adverts/bootstrap/$1';

// Profile updates
$route['member/emailsettings']   = 'user/email_settings';
$route['member/change_password'] = 'user/change_password';
$route['member/change_email']    = 'user/change_email';
$route['member/change_country']  = 'user/change_country';
$route['member/change_secret']   = 'user/change_secret';

$route['member/change_phone']    = 'user/change_phone';
$route['member/change_address']    = 'user/change_address';
$route['member/change_names']    = 'user/change_names';

$route['change_email/([a-f0-9]{40})/(:any)/(:num)']    = 'user/change_email/$1/$2/$3';

// Support
$route['support']                      = 'support';
$route['support/([a-f0-9]{13})']       = 'support/view/$1';
$route['support/add']                  = 'support/add';
$route['support/reply/([a-f0-9]{13})'] = 'support/reply/$1';

// admin
$route['admin']        = 'adminpanel/admin/bootstrap';
$route['admin/viewList/(:any)'] = 'adminpanel/admin/viewList/$1';
$route['admin/getList/(:any)'] = 'adminpanel/admin/getList/$1';
$route['admin/form/(\w+)'] = 'adminpanel/admin/viewForm/$1';
$route['admin/form/(\w+)/(:num)/(:num)'] = 'adminpanel/admin/viewForm/$1/$2/$3';
$route['admin/form/(\w+)/(:num)'] = 'adminpanel/admin/viewForm/$1/$2';
$route['admin/user/(:num)'] = 'adminpanel/users/detail/$1';
$route['admin/email_user/(:num)'] = 'adminpanel/mass_email/email_user/$1';
$route['admin/(:any)'] = 'adminpanel/admin/bootstrap/$1';
$route['adminpanel/merger'] = 'adminpanel/merger';
$route['adminpanel/merger/(:num)/(:num)/(:num)/(:num)'] = 'adminpanel/merger/index/$1/$2/$3/$4';
$route['adminpanel/merger/(:num)/(:num)/(:num)/(:num)/(:any)'] = 'adminpanel/merger/index/$1/$2/$3/$4/$5';
// support
$route['support/getList/(:any)'] = 'adminpanel/support/getList/$1';

//Cashier
$route['cashout'] = 'cashier/cashout';
$route['confirm_order/(\w+)'] = 'cashier/confirm_order/$1';
$route['process_order/(\w+)'] = 'cashier/process_order/$1';
$route['cancel_order'] = 'cashier/cancel_order';
$route['member/cashier']   = 'cashier';
$route['cashier/purchase'] = 'cashier/add_funds';
$route['member/account/([a-z]{2})'] = 'cashier/account/$1';

// Pages Static
$route['page/(:any)'] = 'main/view_page/$1';
$route['blog'] = 'main/blog';
$route['teams'] = 'teams/index';
$route['disclaimer'] = 'disclaimer/index';
$route['back_office/teams/add'] = 'teams/addTeam';
//$route['blog/(:any)'] = 'main/blog/$1';
$route['article/(:any)'] = 'blog/index/$1';

$route['purchase_cancel'] = 'callback/cancel';
$route['purchase_confirm'] = 'callback/success';

// campaigns
$route['tclick/(:num)'] = 'campaign/click_thru/$1/text_ad';
$route['bclick/(:num)'] = 'campaign/click_thru/$1/banner';

$route['upgrade'] = 'member/account_upgrade';
$route['back_office/upgrade/(:num)'] = 'member/upgrade/$1';
$route['confirm_upgrade.html'] = 'purchase/confirm_upgrade';

/* End of file routes.php */
/* Location: ./application/config/routes.php */