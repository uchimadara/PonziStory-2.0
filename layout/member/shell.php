<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-112068952-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-112068952-1');
    </script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
    <title>{page_title}</title>

    <link href="/assets/images/favicon.png" rel="shortcut icon" type="image/png">


    <!--    <link href="/assets/bootstrap/css/non-responsive.css" rel="stylesheet">-->
    <link href="/layout/member/assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/animate.min.css" rel="stylesheet">
    {css}
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="/assets/bootstrap/css/calendar.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/generics.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/common.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/member.css" rel="stylesheet" media="screen">
    <link href="/assets/bootstrap/css/footer.css" rel="stylesheet" media="screen">
    <link href="/assets/bootstrap/css/datepicker.css" rel="stylesheet" media="screen">
    <link href="/assets/styles/modal.css" rel="stylesheet" media="screen">


    <link href="/layout/member/assets/css/main.css" rel="stylesheet">
    <link href="/layout/member/assets/css/custom.css" rel="stylesheet">
    <link href="/layout/member/assets/css/sponsor.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="/layout/member/js/html5shiv.js"></script>
    <script src="/layout/member/js/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<style>
    .blink_text {

        animation:2s blinker linear infinite;
        -webkit-animation:2s blinker linear infinite;
        -moz-animation:2s blinker linear infinite;

        color: red;
    }

    @-moz-keyframes blinker {
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }

    @-webkit-keyframes blinker {
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }

    @keyframes blinker {
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }
</style>

{flash_message}

<header>

    <div class="floatLeft p-l-15" id="logotype" style="font-size: 30px>
        <a href="/" title="{SITE_NAME}" style="color:white;">{SITE_NAME}</a>
    </div>

    <div class="member" style="font-size: 20px">
        Welcome {username}

    </div>
    <div class="statistics" style="font-size: 20px">
        {stats}
    </div>

    <div id="notify">
        <i class="fa fa-bell-o" id="alertBell"></i>
        <span id="notify-msg"></span>
    </div>

<!--    <div class="pull-right" style="padding-top:3px; margin-right:5px;">-->
<!--        <div id="google_translate_element"></div>-->
<!--        <script type="text/javascript">-->
<!--            function googleTranslateElementInit() {-->
<!--                new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');-->
<!--            }-->
<!--        </script>-->
<!--    </div>-->
    <div class="container">
        <div class="row">
            <div class="col-lg-6" style="margin-right: 200px;text-align: center;">
                <span class="blink_text"> <a href="#" data-toggle="tooltip" style="background-color: white;line-height: 40px;font-size: 14.5px;font-weight: bold" title="Use your spare cash only to participate here. don't let greed make u open multiple accounts, it's a capital offense">SPARE CASH ONLY||MULTIPLE ACCOUNT A CAPITAL OFFENSE </a></span>
            </div>
    </div>
    </div>
    <div class="clear"></div>
</header>

<div class="container-fluid" id="content">
    <div class="row">
        <div class="col-sm-2" id="left">
            <h3 class="menu-header">Member tools</h3>
            <ul id="sidebar" class="menu hidden-xs" style="font-size: 16px">
                {menu}
            </ul>
            <div class="row text-center ads">
                <div class="col-xs-6 col-sm-12">
                </div>

            </div>
        </div>
        <div class="col-sm-10">
            <div class="row">
                <div class="col-sm-12 text-center">
                </div>
            </div>
            <div class="row text-left">
                <div class="col-sm-12">
                    <div id="main-content">

                            <div class="row" id="page-content">{content}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="container">
    <div class="row">
        <div class="col-sm-12 text-center">
            <img src="/images/line.jpg" alt="line">
        </div>
    </div>
    <div class="row ads" id="footer-ads">
        <div class="col-sm-12 text-center">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <ul>
                {footer}
            </ul>
        </div>
    </div>
</footer>
<div id="footer">
    <div id="footerBottom">
        <div class="copy floatLeft"></div>
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
{news_modal}

<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script src="/assets/bootstrap/js/jquery.min.js"></script>
<script src="/assets/bootstrap/js/jquery-ui.min.js"></script>
<script src="/assets/bootstrap/js/jquery.easing.1.3.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/bootstrap/js/toggler.min.js"></script>
<script src="/assets/bootstrap/js/scroll.min.js"></script>
<script src="/assets/bootstrap/js/datepicker.js"></script>
<script src="/assets/scripts/tinynav.min.js"></script>

<!-- Site functions -->
<script src="/assets/scripts/ajaxupload.js"></script>
<script src="/assets/scripts/my_account.js"></script>
<script src="/assets/scripts/forms.js"></script>
<script src="/assets/scripts/modal.js"></script>
<script src="/assets/scripts/getList.js"></script>
<script src="/assets/scripts/tooltipsy.min.js"></script>
<script src="/assets/scripts/sortable.js"></script>

{js}

<script src="/layout/member/assets/js/main.js"></script>

<script src="/assets/bootstrap/js/functions.js"></script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5c4c75ea51410568a1086c2c/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->

</body>
</html>
