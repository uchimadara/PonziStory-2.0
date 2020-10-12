<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $deposit['websitename']; ?></title>

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

</head>
<body>


	<header>
    <div class="floatLeft p-l-15" id="logotype">
        <a href="/" title="<?php echo $deposit['websitename']; ?>" style="color:white;"><?php echo $deposit['websitename']; ?></a>
    </div>

    <div class="member">
        Welcome <?php echo $deposit['username']; ?>    
        </div>
    <div class="statistics">
       <?php echo $deposit['amount_balanceee']." BTC"; ?>
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
                    <li>All disagreements and problems will be manually handled by system administrator. Submit a <a href="http://www.cryptocogent.com/support"><b>support ticket</b></a> to report any issues.
                    </li>
                    <li>You must read and agree to the Crypto Cogent <a href="http://www.cryptocogent.com/page/terms"><b>terms of service</b></a>.
                    </li>
                </ul>
                <input type="checkbox" class="dontShow" data-what="upgrade_instructions" />
                <span class="fs12"> Don't show this again.</span>

            </div>

                <h1>Buy Ad Packs</h1>
                <div class="col-sm-6">

               <?php if ($deposit['requestpending'] === 'pendingrequest') { ?>

                        <div class="alert alert-danger">
                            You Already Have A Pending Request
                        </div>

               <?php 

               }
               else{ 
                     echo form_open('deposit');?>
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

                <?php echo form_close(); 

            }
               ?>


             

                    <?php 

                            if (isset($deposit['TotalAmount'])) {
                                # code...
                                ?>
                                <h3>Total Price Of Ad Credits Will Be <?php echo $deposit['TotalAmount']; ?></h3>
                             <?php   
                            }
                    ?>
            </div>

            <div class="col-md-12">
                <h4 style="border:2px solid rgb(99, 227, 99); padding: 5px;">
                    <span class="red">IMPORTANT:</span> Send donation <b>ONLY</b> to the <b>blockchain.info</b> wallet listed below
                    &nbsp; <i class="fa fa-arrow-down red fs18" aria-hidden="true"></i>
                </h4>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <?php if(isset($deposit['btc_add'])): ?>
                    <div class="col-lg-12 center m-t-10">
                        <div class="alert alert-warning center">
                             Please pay your pending <?php if(isset($deposit['amounttopay'])): ?><?php echo $deposit['amounttopay']; ?><?php endif; ?> BTC to the following btc address
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

                     if (isset($deposit['ErrorAmount'])) {
                        # code...
                        ?>
                         <div class="col-lg-12 center m-t-10">
                            <div class="alert alert-danger center">
                            <h3><?php echo $deposit['ErrorAmount']; ?></h3>
                            </div>
                         </div>
                        <?php
                    }

                    ?>


                </div>
                <div class="col-md-12">
                <div class="clear"></div>
                     <h2 class="tile-title m-t-20"> STEP 2: Submit the transaction hash ID</h2>
                        <div class="showHide m-b-20 alert alert-info" id="step2_instructions">
                            <h3> Where to find the Transaction Hash ID after you made payment?</h3>

                           <ol class="fs15">
        <li> Go to <a href="https://blockchain.info/">https://blockchain.info/</a></li>
        <li>Copy the Bitcoin Wallet address you see in Step 1 and paste it in the search box on Blockchain.info then click on search.</li>
        <li> On the next page, look for Transactions (Oldest First). Just below that you will see a long string of characters.
        </li>
        <li>Copy that long string of characters and come paste it in here in the Transaction Hash ID field.</li>
        <li>Click on Submit. Voila, if you've done it correctly your upgrade will be in effect as soon as our automated system approves the transaction.
        </li>
    </ol>
    <div class="m-b-10">
        <b><span class="red">WARNING!</span> For users of LocalBitcoins.com, Xapo.com and similar exchanges:</b>
        Our automated system cannot verify all transactions from these websites. If you use localbitcoins.com and xapo.com we
        kindly ask you to create a Free Bitcoin Wallet with <a href="https://blockchain.info/wallet/#/">https://blockchain.info/wallet/#/</a>
        to do transactions safely and securely on our platform.
        We want all transactions to be secure and verifiable and thus they need to happen on the blockchain.
        Some wallet services allow for internal transfers without running it through the blockchain and that
        is unacceptable to Crypto Cogent. Please ensure you have a blockchain.info wallet to guarantee you have a safe donating experience.

    </div>
    <input type="checkbox" class="dontShow" data-what="step2_instructions" />
    <span class="fs12"> Don't show this again.</span>
     </div>

                </div>
                <div class="col-md-6">

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

    
                 <?php if(isset($deposit['messageReceived'])): ?>
                 <div class="alert alert-warning center">
                    <?php echo $deposit['messageReceived']; ?>
                 </div>
                <?php endif; ?>

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
                    <?php if(isset($deposit['completedata'])): ?>
                        <?php foreach ($deposit['completedata'] as $cmpdata): ?>
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
                    </tbody>
                </table>



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