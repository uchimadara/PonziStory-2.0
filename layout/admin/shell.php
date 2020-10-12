<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <meta name="format-detection" content="telephone=no">
    <meta charset="UTF-8">



    <title>{page_title}</title>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="/assets/bootstrap/css/style.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/icons.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/calendar.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/form.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/generics.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/common.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/footer.css" rel="stylesheet" media="screen">
    <link href="/assets/bootstrap/css/datepicker.css" rel="stylesheet" media="screen">
    <link href="/assets/styles/modal.css" rel="stylesheet" media="screen">
    {css}

</head>
<body id="skin-blotter">

{flash_message}

<header id="header" class="media">
    <a href="" id="menu-toggle"></a>
    <a id="logo" class="logo pull-left" href="/">
        {SITE_NAME}
    </a>

    <div class="media-body">
        <div class="media" id="top-menu">

            <h4 class="page-title">{title}</h4>
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


                <h2 class="username tile">{username}</h2>

            </div>
            <div class="s-widget m-b-25">
                <h2 class="tile-title">
                    SITE INFO
                </h2>

                <div class="s-widget-body">
                    <div id="ta-feed">
                        <div class="textAdArea">
                            {stats}
                        </div>

                    </div>
                </div>
                <tr style="font-size: 8px">
                    <td style="font-size: 8px">SMS Remaining:</td><td style="font-size: 8px">  {sms_balance}
                    </td>
                </tr>
            </div>

        </div>

        <ul class="list-unstyled side-menu">
            {menu}
        </ul>
    </aside>

    <!-- Content -->
    <section id="content" class="container">


        <div id="widgetArea" class="block-area" style="min-height:650px;    margin-bottom: 20px;">
                <div class="row">{content}</div>
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
        <div class="copy floatLeft">Landwhale Enterprises, Inc. © {year} All Right Reserved</div>
        <div class="serverTime floatRight">Server time : <strong>{server_time}</strong></div>
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
<script src="/assets/bootstrap/js/jquery.min.js"></script>
<script src="/assets/bootstrap/js/jquery-ui.min.js"></script>
<script src="/assets/bootstrap/js/jquery.easing.1.3.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/bootstrap/js/toggler.min.js"></script>
<script src="/assets/bootstrap/js/scroll.min.js"></script>
<script src="/assets/bootstrap/js/datepicker.js"></script>
<script src="/assets/bootstrap/js/autosize.min.js"></script>
<script src="/assets/bootstrap/js/sparkline.min.js"></script>
<script src="/assets/bootstrap/js/ad_sparkline.js"></script>
<script src="/assets/scripts/tinymce/tinymce.min.js"></script>
<script src="/assets/bootstrap/js/functions.js"></script>
<script src="/assets/scripts/tinynav.min.js"></script>
<script src="/assets/scripts/getList.js"></script>
<script src="/assets/scripts/tooltipsy.min.js"></script>
<script src="/assets/scripts/sortable.js"></script>


{js}
<script src="/assets/scripts/forms.js"></script>
<script src="/assets/scripts/modal.js"></script>
<script src="/assets/scripts/getList.js"></script>
<script src="/assets/scripts/tooltipsy.min.js"></script>
<script src="/assets/scripts/sortable.js"></script>


</body>
</html>
