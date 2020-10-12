<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $description['websitename']; ?>
            <?php echo $webtask_desp['websitename']; ?>
            <?php echo $webtasks['websitename']; ?></title>

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
        .tasksss
        {
            border: 1px solid #5CB85C;
            padding: 20px;
            margin-top: 20px;
            border-radius: 25px;
        }
    </style>
</head>
<body>


	<header>
    <div class="floatLeft p-l-15" id="logotype">
        <a href="/" title="Crypto Cogent" style="color:white;"><?php echo $description['websitename']; ?>
            <?php echo $webtask_desp['websitename']; ?>
            <?php echo $webtasks['websitename']; ?>
        </a>
    </div>

    <div class="member">
     Welcome <?php echo $webtask_desp['username']; ?>
            <?php echo $description['username']; ?>
    </div>
    <div class="statistics">
        Pack Balance <?php echo $webtask_desp['amount_balance']; ?>
                   <?php echo $description['amount_balance']; ?> BTC
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
            <div class="col-sm-8 col-md-8" style="margin-top: 50px;">
                <div class="alert alert-success">
                 <h2>Task # <?php echo $webtask_desp['id']; ?></h2>
                 </div>
                <div class="alert alert-info">
                    Title
                </div>
                <h3>
                <?php echo $webtask_desp['title']; ?></h3>
                <div class="alert alert-info">
                    Description
                </div>
                <h4>
                <?php echo $webtask_desp['desp']; ?></h4>



                <div style="margin-top: 100px;">
                <?php echo form_open('webtask/proof_submission'); ?>
                    <input type="hidden" name="id" value="<?php echo $webtask_desp['id']; ?>">
                    <input type="hidden" name="title" value="<?php echo $webtask_desp['title']; ?>">
                    <div class="form-group">
                        <label for="urlforsubmission">Enter Url For Approval</label>
                        <input type="text" name="urlforsubmission" class="form-control">
                    </div>
                    <input type="submit" name="ProofSubmit" class="btn btn-md btn-success" value="Submit Proof">
                <?php echo form_close(); ?>
                </div>

                <?php if(isset($description['confirmation_msg'])): ?>

                    <?php if($description['confirmation_msg'] === '1'): ?>

                            <div class="alert alert-info">
                                
                                Url Have Been Successfully Submitted

                            </div>

                    <?php endif; ?>

                <?php endif; ?>

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