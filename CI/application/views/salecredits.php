<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $sale['websitename']; ?></title>

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

<link rel="stylesheet" href="<?php echo base_url('custom/css/flipclock.css'); ?>" type="text/css" media="all" />

<style type="text/css">
    
</style>

</head>
<body>


	<header>
    <div class="floatLeft p-l-15" id="logotype">
        <a href="/" title="Crypto Cogent" style="color:white;"><?php echo $sale['websitename']; ?></a>
    </div>

    <div class="member">
        Welcome <?php echo $sale['username']; ?>    
        </div>
         <div class="statistics">
         <?php echo $sale['amount_balance']." BTC"; ?>
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

            <div class="showHide m-b-20 alert alert-info" id="upgrade_instructions">
                <h3>Instructions - Read Carefully</h3>

                <ul class="fs15">
                    <li>You must complete the following <b>2 steps</b>.</li>
                    <li><b>Step 1:</b> Send bitcoin payment to the wallet listed by the payee.</li>
                    <li><b>Step 2:</b> Provide the transaction hash ID using the form provided below.
                    </li>
                    <li>This system features automatic payment approval which takes 30-60 minutes.</li>
                    <li>Your upgrade will not be in effect until donation is validated and approved by the system.
                    </li>
                    <li>All donations are voluntarily and final. Refunds are not available.
                    </li>
                    <li>Communication about the upgrade process, donation and approval is between you and the payee only.
                    </li>
                    <li><b>You have 2 days to upgrade to Stage 1 or your account will be removed.</b></li>
                    <li>If your account expires while your donation for your first upgrade is pending approval your account will not be removed.
                    </li>
                    <li>All disagreements and problems will be manually handled by system administrator. Submit a <a href="<?php echo base_url('support'); ?>"><b>support ticket</b></a> to report any issues.
                    </li>
                    <li>You must read and agree to the Crypto Cogent <a href="<?php echo base_url('page/terms'); ?>"><b>terms of service</b></a>.
                    </li>
                </ul>
                <input type="checkbox" class="dontShow" data-what="upgrade_instructions" />
                <span class="fs12"> Don't show this again.</span>

            </div>

                <h1>Sale Ad Packs</h1>
                <div class="col-sm-6">
                 <?php if($sale['pendingrequest'] === "no"): ?>
                    <h3>You already have a pending request </h3>
                <?php else: ?>
                <?php echo form_open('salecreditpacks'); ?>\
                <div class="form-group">
                    <label for="creditsellvalue">
                    How many ad credits you want to sell
                    </label>
                    <select name="creditsellvalue" class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                    </select>
                    <div style="margin-top:30px;">     
                    <input type="submit" name="sellvaluesubmit" value="Sell" class="btn btn-md btn-primary" style="padding-left:20px !important; padding-right: 20px !important;">
                    </div>
                </div> 
                <?php echo form_close(); ?>
                <?php endif; ?>

                <?php if(isset($sale['confirmationmessage'])): ?>
                 <div class="alert alert-warning center">
                    <?php echo $sale['confirmationmessage']; ?>
                </div>
                <?php endif; ?>
                </div>

                <div class="col-sm-12 col-md-12 transaction_history">
                    <h4>Transaction History</h4>
                </div>
                
                <h2 class="tile-title m-t-20">Transaction History</h2>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Transaction No.</th>
                            <th>Credit Packs</th>
                            <th>Hash</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($sale['completedata'])): ?>
                        <?php foreach ($sale['completedata'] as $cmpdata): ?>
                        <tr>
                            <td><?php echo $cmpdata->id; ?></td>
                            <td><?php echo $cmpdata->credit_packs; ?></td>
                            <td><?php echo $cmpdata->hash; ?></td>
                            <td><?php echo $cmpdata->status; ?></td>
                        </tr>
                    <?php endforeach;?>
                <?php else: ?>
                    <td>NO REQUEST HAVE BEEN ADDED YET</td>
                    <?php endif; ?>
                        <tr>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
		</div>

	</div>						

<script src="http://www.cryptocogent.com/assets/bootstrap/js/jquery.min.js"></script>
<script src="http://www.cryptocogent.com/assets/bootstrap/js/jquery-ui.min.js"></script>
<script src="http://www.cryptocogent.com/assets/bootstrap/js/jquery.easing.1.3.js"></script>
<script src="http://www.cryptocogent.com/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="http://www.cryptocogent.com/assets/bootstrap/js/toggler.min.js"></script>
<script src="/assets/bootstrap/js/scroll.min.js"></script>
<script src="/assets/bootstrap/js/datepicker.js"></script>
<script src="/assets/scripts/tinynav.min.js"></script>

<!-- Site functions -->
<script src="http://www.cryptocogent.com/assets/scripts/ajaxupload.js"></script>
<script src="http://www.cryptocogent.com/assets/scripts/my_account.js"></script>
<script src="http://www.cryptocogent.com/assets/scripts/forms.js"></script>
<script src="http://www.cryptocogent.com/assets/scripts/modal.js"></script>
<script src="http://www.cryptocogent.com/assets/scripts/getList.js"></script>
<script src="http://www.cryptocogent.com/assets/scripts/tooltipsy.min.js"></script>
<script src="http://www.cryptocogent.com/assets/scripts/sortable.js"></script>

<script type="text/javascript" src="http://www.cryptocogent.com/layout/member/assets/js/jquery.plugin.min.js"></script><script type="text/javascript" src="http://www.cryptocogent.com/layout/member/assets/js/jquery.countdown.min.js"></script><script type="text/javascript">var mim = {
   baseUrl: 'http://www.cryptocogent.com/',
   assetPath: '/assets/',
   isGuest: false,
   isActive: false,
   launchtime: 0,
   alertInterval: 10000,
   alertCount: 0,
   teAlert: 'bell'
};
var currentDateTime = 0;</script><script type="text/javascript" src="http://www.cryptocogent.com/custom/js/flipclock.min.js"></script><script type="text/javascript" src="http://www.cryptocogent.com/custom/js/my_flipclock.js"></script>

<script src="http://www.cryptocogent.com/layout/member/assets/js/main.js"></script>

<script src="http://www.cryptocogent.com/assets/bootstrap/js/functions.js"></script>





</body>
</html>