<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
    <?php echo $sell_rp['websitename']; ?>
    </title>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/animate.min.css'); ?>">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/calendar.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/generics.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/common.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/member.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/footer.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/datepicker.css'); ?>">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles/modal.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('layout/member/assets/css/main.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('layout/member/assets/css/custom.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('layout/member/assets/css/jquery.countdown.css'); ?>">
    <style type="text/css">
        .royaltypositionforsale
        {
            background-color: #72A230;
            padding-top: 5px;
            padding-bottom: 5px;
        }
        .tab-content
        {
            padding:10px;
        }
    </style>

</head>
<body>


	<header>
    <div class="floatLeft p-l-15" id="logotype">
        <a href="/" title="Crypto Cogent" style="color:white;"> <?php echo $sell_rp['websitename']; ?></a>
    </div>

    <div class="member">
    Welcome : <?php echo $sell_rp['username']; ?>
    </div>
    <div class="statistics">
    Total Members: 
    </div>
    <div class="statistics">
    Balance: <?php echo $sell_rp['current_balance']; ?> USD
    </div>

    <div id="notify">
        <i class="fa fa-bell-o" id="alertBell"></i>
        <span id="notify-msg"></span>
    </div>

    <div class="pull-right" style="padding-top:3px; margin-right:5px;">
        <div id="google_translate_element"></div>
        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
            }
        </script>
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    </div>
    <div class="clear"></div>
	</header>

	<div class="container-fluid" id="content">
		<div class="row">
			<div class="col-sm-3" id="left">
            	<h3 class="menu-header">Member tools</h3>
            	<?php include('partial/menu.php'); ?>
			</div>
            <div class="col-sm-8 col-md-8" style="margin-top: 20px;">
            <div class="col-sm-12 col-md-12 royaltypositionforsale">
                     <div class="col-sm-12 col-md-7">
                        <h4 style="color:#FFFFFF; font-weight:900;">Sell Your Royalty Positions</h4>
                    </div>
                    <div class="col-sm-12 col-md-5">
                       <a href="<?php echo base_url('marketplace'); ?>" class="btn btn-lg btn-default">Back To Royalty Positionss</a>
                    </div>
            </div>
              <p style="font-size:16px; margin-top: 10px;margin-bottom: 20px;">You currently have <?php echo $sell_rp['roy_pos']; ?> royalty positions <br><br>

                    Use the form below to list your Royalty Positions for sale on your marketplace.
                </p>
                <div class="col-sm-12 col-md-12 royaltypositionforsale">
                     <div class="col-sm-12 col-md-12">
                        <h4 style="color:#FFFFFF; font-weight:900;">My Royalty Positions Available For Sale</h4>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12" style="margin-top: 20px;">
                    <div class="col-md-8">
                        <?php if($sell_rp['sellerror'] === 'sufficient_balance'): ?>
                            <div class="alert alert-danger">
                                <h4>You Don't Have Sufficient Royalty Positions To Sell</h4>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                         <?php if($sell_rp['sellerror'] === 'request_submitted'): ?>
                            <div class="alert alert-info">
                                <h4>Your Request Has Been Submitted</h4>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12" style="margin-top: 30px;">
                    <?php echo form_open('marketplace/sell_royaltypositions') ?>
                        <div class="form-group">
                            <label for="amountosell">Royalty Position Amount To Sell <?php echo $sell_rp['roy_pos']; ?></label>
                            <input type="text" name="royaltypositiontosell" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="salepriceperrp">Sale Price (Per RP)</label>
                            <input type="text" name="salepriceperrp" class="form-control" required>
                        </div>
                        <div style="text-align: center;">
                        <input type="submit" name="SaleRP" class="btn btn-lg btn-primary" value="Sell" style="padding-left: 30px; padding-right: 30px;font-weight: 900;">
                        </div>
                    <?php echo form_close(); ?>
                </div>

            </div>
		</div>
	</div>						

<script src="<?php echo base_url('assets/bootstrap/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/jquery-ui.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/jquery.easing.1.3.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/toggler.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/scroll.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/datepicker.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/tinynav.min.js'); ?>"></script>

<!-- Site functions -->
<script src="<?php echo base_url('assets/scripts/ajaxupload.js'); ?> "></script>
<script src="<?php echo base_url('assets/scripts/my_account.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/forms.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/modal.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/getList.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/tooltipsy.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/scripts/sortable.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('layout/member/assets/js/jquery.plugin.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('layout/member/assets/js/jquery.countdown.min.js'); ?>"></script>
<script type="text/javascript">var mim = {
   baseUrl: 'http://www.cryptocogent.com/',
   assetPath: '/assets/',
   isGuest: false,
   isActive: false,
   launchtime: 0,
   alertInterval: 10000,
   alertCount: 0,
   teAlert: 'bell'
};
var currentDateTime = 0;</script>
<script type="text/javascript" src="<?php echo base_url('custom/js/flipclock.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('custom/js/my_flipclock.js'); ?>"></script>

<script src="<?php echo base_url('layout/member/assets/js/main.js'); ?>"></script>

<script src="<?php echo base_url('assets/bootstrap/js/functions.js'); ?>"></script>





</body>
</html>