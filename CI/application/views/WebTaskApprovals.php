<!DOCTYPE html>
<html>
<head>
<title><?php echo $approval['websitename']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/animate.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/style.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles/admin/style.css'); ?>">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href="<?php echo base_url('assets/bootstrap/css/style.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap/css/icons.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap/css/calendar.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap/css/form.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap/css/generics.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap/css/common.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap/css/footer.css'); ?>" rel="stylesheet" media="screen">
<link href="<?php echo base_url('assets/bootstrap/css/datepicker.css'); ?>" rel="stylesheet" media="screen">
<link href="<?php echo base_url('assets/styles/modal.css'); ?>" rel="stylesheet" media="screen">
<link rel="stylesheet" href="<?php echo base_url('assets/styles/admin/style.css'); ?>" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo base_url('custom/css/flipclock.css'); ?>" type="text/css" media="all" />
</head>
<body id="skin-blotter">




<header id="header" class="media">
    <a href="" id="menu-toggle"></a>
    <a id="logo" class="logo pull-left" href="/">
     <?php echo $approval['websitename']; ?>
    </a>

    <div class="media-body">
        <div class="media" id="top-menu">

            <h4 class="page-title"></h4>
        </div>
    </div>

</header>

<div class="clearfix"></div>

<section id="main" class="p-relative" role="main">

    <!-- Sidebar -->
    <aside id="sidebar">

        <!-- Sidbar Widgets -->
        <div class="side-widgets overflow">
            <!-- Profile Menu -->
            <div class="text-center s-widget m-b-15" id="profile-menu">

                <!-- PROFILE MENU -->

                <h2 class="username tile"><?php echo $approval['username']; ?></h2>

            </div>
            <div class="s-widget m-b-25">
                <h2 class="tile-title">
                    SITE INFO
                </h2>

                <div class="s-widget-body">
                    <div id="ta-feed">
                        <div class="textAdArea">
                            <table class="table fs14">
</table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

       <ul class="list-unstyled side-menu">
            <li class="active">
                        <a class="sa-side-home" href="<?php echo base_url('admin'); ?>">
                            <span class="menu-item">Dashboard</span>
                        </a>
                    </li><li class="dropdown">
                    <a class="dropdown-link sa-side-widget" href="">
                        <span class="menu-item">Site config <span class="pull-right"><i class="fa fa-angle-right" aria-hidden="true"></i></span></span>
                    </a>
                    <ul class="list-unstyled menu-item dropdown-menu"><li><a href="<?php echo base_url('admin/viewList/page'); ?>">Pages</a></li><li><a href="<?php echo base_url('adminpanel/admin_menu'); ?>">CMS Menu</a></li><li><a href="<?php echo base_url('admin/viewList/currency'); ?>">Currencies</a></li></ul>
                </li><li class="dropdown">
                    <a class="dropdown-link sa-side-user" href="">
                        <span class="menu-item">Members <span class="pull-right"><i class="fa fa-angle-right" aria-hidden="true"></i></span></span>
                    </a>
                    <ul class="list-unstyled menu-item dropdown-menu"><li><a href="<?php echo base_url('admin/viewList/users'); ?>">User List</a></li><li><a href="<?php echo base_url('admin/viewList/pending_invites'); ?>">Pending Invites</a></li><li><a href="<?php echo base_url('admin/form/mass_email'); ?>">Mass Email</a></li><li><a href="<?php echo base_url('admin/viewList/locked_members'); ?>">Locked Members</a></li><li><a href="<?php echo base_url('admin/viewList/products')?>">Rewards</a></li><li><a href="<?php echo base_url('admin/viewList/wallets'); ?>">Wallets</a></li></ul>
                </li><li class="dropdown">
                    <a class="dropdown-link sa-side-spam" href="">
                        <span class="menu-item">Support <span class="pull-right"><i class="fa fa-angle-right" aria-hidden="true"></i></span></span>
                    </a>
                    <ul class="list-unstyled menu-item dropdown-menu"><li><a href="<?php echo base_url('admin/support'); ?>">Open Tickets</a></li><li><a href="<?php echo base_url('admin/viewList/closed_tickets'); ?>">Closed Tickets</a></li></ul>
                </li><li>
                        <a class="sa-side-folder" href="<?php echo base_url('adminpanel/admin_settings'); ?>">
                            <span class="menu-item">Settings</span>
                        </a>
                    </li><li>
                        <a class="sa-side-news" href="h<?php echo base_url('admin/viewList/news'); ?>">
                            <span class="menu-item">News & Updates</span>
                        </a>
                    </li><li class="dropdown">
                    <a class="dropdown-link sa-side-cashier" href="">
                        <span class="menu-item">Money <span class="pull-right"><i class="fa fa-angle-right" aria-hidden="true"></i></span></span>
                    </a>
                    <ul class="list-unstyled menu-item dropdown-menu"><li><a href="<?php echo base_url('admin/viewList/purchase_items'); ?>">Upgrades</a></li><li><a href="<?php echo base_url('admin/viewList/pending_payments'); ?>">Pending Payments</a></li><li><a href="<?php echo base_url('admin/viewList/rejected_payments'); ?>">Rejected Payments</a></li><li><a href="<?php echo base_url('admin/viewList/payments'); ?>">Completed Payments</a></li></ul>
                </li><li>
                        <a class="" href="<?php echo base_url('back_office/testimonials'); ?>">
                            <span class="menu-item">Testimonials</span>
                        </a>
                    </li>
                    <li>
                        <a class="sa-side-user" href="<?php echo base_url('adminpanel/changeadprice'); ?>">
                            <span class="menu-item">Change Ad Account Price</span>
                        </a>
                    </li>
                    <li>
                        <a class="sa-side-user" href="<?php echo base_url('adminpanel/addrp'); ?>">
                            <span class="menu-item">Add Royalty Positions</span>
                        </a>
                    </li>
                    <li>
                        <a class="sa-side-user" href="<?php echo base_url('adminpanel/AdminWebTasks'); ?>">
                            <span class="menu-item">Ad Pack Details</span>
                        </a>
                    </li>
                    <li>
                        <a class="sa-side-user" href="<?php echo base_url('adminpanel/webtasks'); ?>">
                            <span class="menu-item">Web Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a class="sa-side-user" href="<?php echo base_url('adminpanel/webtasks/WebTasksForApproval'); ?>">
                            <span class="menu-item">Web Tasks Approval</span>
                        </a>
                    </li>
                     <li>
                        <a class="sa-side-user" href="<?php echo base_url('aadminpanel/dividends'); ?>">
                            <span class="menu-item">Add Dividends</span>
                        </a>
                    </li>
        </ul>
    </aside>

    <!-- Content -->
    <section id="content" class="container">


        <div id="widgetArea" class="block-area" style="min-height:650px;    margin-bottom: 20px;">
            <div class="row">

                <div class="col-md-12">
                    
                    <table class="table table-striped">
                        
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Task Title</th>
                                <th>Task Id</th>
                                <th>Proof Url</th>
                                <th>Status</th>
                                <th>Action</th>
                            <tr>
                        </thead>

                        <tbody>
                            <tbody>

                             <?php foreach ($approval['alldata'] as $alldata) { ?>




                                <?php $idoftask = $alldata->id;
                                    $user_id = $alldata->user_id;
                                    $idfortask = $alldata->id;

                                    $statuss = $alldata->status;
                                 ?>
                                <tr>
                                    <td><?php echo $alldata->user_name; ?></td>
                                    <td><?php echo $alldata->task_title; ?></td>
                                    <td><?php echo $alldata->task_id; ?></td>
                                    <td><?php echo $alldata->url_for_proof; ?></td>
                                    <td><?php echo $alldata->status; ?></td>
                                    <td>

                                    <?php if($statuss === 'pending'): ?>
                                        <?php echo form_open('adminpanel/webtasks/WebTasksForApproval'); ?>
                                            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                                            <input type="hidden" name="username" value="<?php echo $alldata->user_name; ?>">
                                            <input type="hidden" name="id" value="<?php echo  $idfortask; ?>">
                                            <input type="submit" name="reject" value="reject" class="btn btn-sm btn-danger">
                                            <input type="submit" name="approval" value="approve" class="btn btn-sm btn-success">
                                        <?php echo form_close(); ?>
                                    <?php else: ?>
                                     Action Submitted
                                    <?php endif; ?>

                                    </td>
                                </tr>
                                <?php
                                }
                                 ?>
                            </tbody>
                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    </section>

    <!-- Older IE Message -->
    <!--[if lt IE 9]>
    <div class="ie-block">
        <h1 class="Ops">Oops!</h1>

        <p>You are using an outdated version of Internet Explorer, upgrade to any of the following web browser in order to access the maximum functionality of this website. </p>
        <ul class="browsers">
            <li>
                <a href="https://www.google.com/intl/en/chrome/browser/">
                    <img src="<?=asset('bootstrap/img/browsers/chrome.png')?>" alt="">

                    <div>Google Chrome</div>
                </a>
            </li>
            <li>
                <a href="http://www.mozilla.org/en-US/firefox/new/">
                    <img src="<?=asset('bootstrap/img/browsers/firefox.png')?>" alt="">

                    <div>Mozilla Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com/computer/windows">
                    <img src="<?=asset('bootstrap/img/browsers/opera.png')?>" alt="">

                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="http://safari.en.softonic.com/">
                    <img src="<?=asset('bootstrap/img/browsers/safari.png')?>" alt="">

                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/downloads/ie-10/worldwide-languages">
                    <img src="<?=asset('bootstrap/img/browsers/ie.png')?>" alt="">

                    <div>Internet Explorer(New)</div>
                </a>
            </li>
        </ul>
        <p>Upgrade your browser for a Safer and Faster web experience. <br/>Thank you for your patience...</p>
    </div>
    <![endif]-->
</section>
<!-- Footer -->
<div id="footer">
    <div id="footerBottom">
        <div class="copy floatLeft">Landwhale Enterprises, Inc. © 2016 All Right Reserved</div>
        <div class="serverTime floatRight">Server time : <strong>02-Nov-2016 10:21:31</strong></div>
        <div class="clear"></div>
    </div>
</div>
<!-- End Footer -->

<!-- Modal Default -->
<div id="modal" class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-hidden="true">
    <div id="modalDialog" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div id="modalBody" class="modal-body">
                <div class="loading"></div>
            </div>

        </div>
    </div>
</div>

<!-- Javascript Libraries -->
<script src="<?php echo base_url('assets/bootstrap/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/jquery-ui.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/jquery.easing.1.3.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/toggler.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/scroll.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/datepicker.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/autosize.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/sparkline.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/ad_sparkline.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/tinymce/tinymce.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/functions.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/tinynav.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/getList.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/tooltipsy.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/sortable.js'); ?>"></script>


<script type="text/javascript" src="http://www.cryptocogent.com/assets/scripts/admin/admin.js"></script><script type="text/javascript" src="http://www.cryptocogent.com/assets/scripts/searchList.js"></script><script type="text/javascript">var mim = {
   baseUrl: 'http://www.cryptocogent.com/',
   assetPath: '/assets/',
   isGuest: false,
   isActive: false,
   launchtime: 0,
   alertInterval: 10000,
   alertCount: 0,
   teAlert: 'bell'
};
var currentDateTime = 10;</script><script type="text/javascript" src="http://www.cryptocogent.com/custom/js/flipclock.min.js"></script><script type="text/javascript" src="http://www.cryptocogent.com/custom/js/my_flipclock.js"></script>
<script src="<?php echo base_url('assets/scripts/forms.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/modal.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/getList.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/tooltipsy.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/sortable.js'); ?>"></script>






</body>
</html>