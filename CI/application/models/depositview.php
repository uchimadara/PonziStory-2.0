<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crypto Cogent Member Dashboard</title>

	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link href="http://www.cryptocogent.com/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://www.cryptocogent.com/assets/bootstrap/css/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="http://www.cryptocogent.com/assets/bootstrap/css/calendar.css" rel="stylesheet">
    <link href="http://www.cryptocogent.com/assets/bootstrap/css/generics.css" rel="stylesheet">
    <link href="http://www.cryptocogent.com/assets/bootstrap/css/common.css" rel="stylesheet">
    <link href="http://www.cryptocogent.com/assets/bootstrap/css/member.css" rel="stylesheet" media="screen">
    <link href="http://www.cryptocogent.com/assets/bootstrap/css/footer.css" rel="stylesheet" media="screen">
    <link href="http://www.cryptocogent.com/assets/bootstrap/css/datepicker.css" rel="stylesheet" media="screen">
    <link href="http://www.cryptocogent.com/assets/styles/modal.css" rel="stylesheet" media="screen">

    <link href="http://www.cryptocogent.com/layout/member/assets/css/main.css" rel="stylesheet">
    <link href="http://www.cryptocogent.com/layout/member/assets/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="http://www.cryptocogent.com/layout/member/assets/css/jquery.countdown.css" type="text/css" media="all" />
<link rel="stylesheet" href="http://www.cryptocogent.com/custom/css/flipclock.css" type="text/css" media="all" />

</head>
<body>


	<header>
    <div class="floatLeft p-l-15" id="logotype">
        <a href="/" title="Crypto Cogent" style="color:white;">Crypto Cogent</a>
    </div>

    <div class="member">
        Welcome <?php echo $deposit['username']; ?>    
        </div>
    <div class="statistics">
        Pack Balance <?php echo $deposit['pack_balance'] ?>
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
            <div class="col-sm-1"></div>
            <div class="col-sm-8 col-md-8" style="margin-top: 20px;">
                <h1>Buy Ad Packs</h1>
                <div class="col-sm-6">
                <?php echo form_open('deposit'); ?>
                <div class="form-group">
                    <label for="creditbuyvalue">
                    How many ad credits you want to buy
                    </label>
                    <select name="creditbuyvalue" class="form-control">
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
                    <input type="submit" name="buyvaluesubmit" value="Buy" class="btn btn-md btn-primary" style="padding-left:20px !important; padding-right: 20px !important;">
                    </div>
                </div> 
                <?php echo form_close(); ?>

                    <?php 

                            if (isset($deposit['TotalAmount'])) {
                                # code...
                                ?>
                                <h3>Total Price Of Ad Credits Will Be <?php echo $deposit['TotalAmount']; ?></h3>
                             <?php   
                            }
                    ?>

                <?php if(isset($deposit['btc_add'])): ?>
                    <div class="col-lg-12 center m-t-10">
                        <div class="alert alert-warning center">
                             Please pay <?php echo $deposit['amount']; ?> BTC to the following btc address
                            <p style="font-weight:bold;"><?php echo "<br>".$deposit['btc_add']; ?></p>
                        </div>
                    </div>
                     <?php 
                    endif;
                    ?>

            <?php 

                    if (isset($deposit['paymentMessage'])) {
                        # code...
                        ?>
                         <div class="col-lg-12 center m-t-10">
                            <div class="alert alert-danger center">
                            <h3><?php echo $deposit['paymentMessage']; ?></h3>
                            </div>
                         </div>
                        <?php
                    }

            ?>


                <?php echo form_open('deposit'); ?>

                    <div class="form-group">
                        <label for="hashPayment">Enter Transaction Hash</label>
                        <input type="text" name="hashPayment" class="form-control" placeholder="Enter Hash" required>
                    </div>
                    <div class="form-group">
                        <label for="exactPayment">Enter Exact Amount</label>
                        <input type="text" name="exactPayment" class="form-control" placeholder="Exact Amount" required>
                    </div>
                    <div style="text-align:center">
                    <input type="submit" name="submitPaymentInfo" class="btn btn-md btn-primary" value="Submit Payment">
                    </div>
                <?php echo form_close(); ?>

                </div>
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