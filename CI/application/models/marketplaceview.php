<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crypto Cogent Market Place</title>

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
    <style type="text/css">
        .royaltypositionforsale
        {
            background-color: #72A230;
            padding-top: 5px;
    
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
        <a href="/" title="Crypto Cogent" style="color:white;">Crypto Cogent</a>
    </div>

    <div class="member">
    Welcome : <?php echo $marketplace['username']; ?>
    </div>
    <div class="statistics">
    Total Members: 
    </div>
    <div class="statistics">
    Balance: <?php echo $marketplace['current_balance']; ?> USD
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

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#marketplace" data-toggle="tab">MarketPlace</a></li>
                    <li><a href="#royaltyposition" data-toggle="tab">My Royalty Positions</a></li>
                    <li><a href="#statistics" data-toggle="tab">Statistics</a></li>
                </ul>


                <div class="tab-content">
                    
                    <div id="marketplace" class="tab-pane fade in active">
                        <p style="font-size:16px; margin-top: 10px;margin-bottom: 20px;">You currently have <?php echo $marketplace['roy_pos']; ?> royalty positions <br><br>

                        You can buy and sell Royalty Positions below. If there are no Royalty Positions available for sale, or you think the sale price is too high, you can place a 'bid' where you state how many Royalty Positions you want to purchase, and what price you're willing to pay. We will then hold your funds in escrow until a seller accepts your bid, or you cancel it
                        </p>
                            <div class="col-sm-12 col-md-12">
                                <div class="col-sm-12 col-md-8">
                                <?php if($marketplace['error'] === '1'): ?>
                                    <div class="alert alert-warning">
                                        <h4>You Dont have Sufficient Royalty Positions To Sell</h4>
                                    </div>
                                <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="col-sm-12 col-md-8">
                                <?php if($marketplace['error'] === '2'): ?>
                                    <div class="alert alert-warning">
                                        <h4>You Dont have Sufficient Balance To Buy Royalty Positions</h4>
                                    </div>
                                <?php endif; ?>
                                </div>
                            </div>
                           <div class="col-sm-12 col-md-12 royaltypositionforsale">
                               <div class="col-sm-6 col-md-7">
                                   <h4 style="color:#FFFFFF; font-weight:900;">Royalty Positions Listed For Sale</h4>
                               </div>
                                <div class="col-sm-6 col-md-5">
                               <?php echo form_open('marketplace/index'); ?>
                                    <input type="hidden" name="CheckValue">
                                   <input type="submit" name="SellYourRoyality" class="btn btn-lg btn-info" value="Sell Your Royalty Positions"></input>
                               <?php echo form_close(); ?>
                                </div>
                           </div>

                           <div class="col-sm-12 col-md-12">
                            <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Amount Per RP</th>
                                        <th>Amount To Sell RP</th>
                                        <th>Total Amount</th>
                                    </tr>

                                    <?php $count = 0; ?>

                                    <?php foreach ($marketplace['getRpForSale'] as $rpdataforsale): ?>

                                    <tr>
                                    <?php $count++; ?>
                                        <?php $amountperrp = $rpdataforsale->amount_per_rp;
                                            $amountosale = $rpdataforsale->royalty_positions;

                                            $totalamount = $amountperrp * $amountosale;

                                         ?>
                                        <td><?php $userrid = $rpdataforsale->user_id;


                                               $this->db->from('users')
                                               ->where('id',$userrid);
                                                $query = $this->db->get();
                                                    if($query->num_rows > 0 ) {
                                                    foreach ($query->result() as $row) {
                                                    // do your stuff
                                                        $username = $row->username;

                                                        echo $username;
                                                    }
                                                }



                                        ?></td>
                                        <td><?php echo $rpdataforsale->amount_per_rp." $"; ?></td>
                                        <td><?php echo $rpdataforsale->royalty_positions; ?></td>
                                        <td><?php echo $totalamount." $"; ?></td>
                                        <?php if($count === 1): ?>
                                            <td>
                                            <?php echo form_open('marketplace/buy_rp'); ?>
                                                <input type="hidden" name="hiddenidforbuy" value="<?php echo $rpdataforsale->id ?>">
                                                <input type="hidden" name="hiddenvalueforbuy" value="<?php echo $totalamount; ?>">
                                                <input type="hidden" name="hiddenrpvalue" value="<?php echo $rpdataforsale->royalty_positions; ?>">
                                                 <input type="submit" name="value_submit" class="btn btn-md btn-info" value="Buy">
                                            <?php echo form_close(); ?>
                                           </td>
                                        <?php endif; ?>
                                    </tr>
                                        <?php if($count === 8): ?>
                                            <?php break; ?>
                                        <?php endif; ?>
                                      <?php endforeach; ?>
                                </thead>
                                     </table>
                                </div>
                           </div>

                           <div class="col-sm-6 col-md-12 royaltypositionforsale">
                               <div class="col-sm-12 col-md-7">
                                   <h4 style="color:#FFFFFF; font-weight:900;">User Bids</h4>
                               </div>
                                <div class="col-sm-6 col-md-5">
                               <?php echo form_open('marketplace/placebid'); ?>
                                    <input type="hidden" name="CheckValue">
                                   <input type="submit" name="SellYourRoyality" class="btn btn-lg btn-info" value="Place Bid To Buy"></input>
                               <?php echo form_close(); ?>
                                </div>
                           </div>

                            <div class="col-sm-12 col-md-12">
                            <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Buyer</th>
                                        <th>Royalty Positions Wanted</th>
                                        <th>Price For Per RP</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>

                                <?php $count = 0; ?>
                                <?php foreach ($marketplace['bid_data'] as $rpbiddata): ?>

                                <tr>
                                               <?php $count++; ?>

                                                <?php $amountperrpbid = $rpbiddata->amount_per_rp;
                                            $amountosalebid = $rpbiddata->royalty_positions;

                                            $totalamountbid = $amountperrpbid * $amountosalebid;

                                         ?>
                                    <td>
                                        
                                            <?php $userridbid = $rpbiddata->user_id;


                                               $this->db->from('users')
                                               ->where('id',$userridbid);
                                                $query = $this->db->get();
                                                    if($query->num_rows > 0 ) {
                                                    foreach ($query->result() as $row) {
                                                    // do your stuff
                                                        $bidder_username = $row->username;

                                                        echo $bidder_username;
                                                    }
                                                }



                                        ?>



                                    </td>
                                      <td><?php echo $rpbiddata->royalty_positions; ?></td>
                                        <td><?php echo $rpbiddata->amount_per_rp." $"; ?></td>
                                        <td><?php echo $totalamountbid." $"; ?></td>

                                    <?php if($count === 1): ?>
                                            <td>
                                            <?php echo form_open('marketplace/accept_bid'); ?>
                                                <input type="hidden" name="bid_id" value="<?php echo $rpbiddata->id ?>">
                                                <input type="hidden" name="amount_bid" value="<?php echo $totalamountbid; ?>">
                                                <input type="hidden" name="rp_value_bid" value="<?php echo $rpbiddata->royalty_positions; ?>">
                                                <input type="hidden" name="bidder_userid" value="<?php echo $rpbiddata->user_id; ?>">
                                                 <input type="submit" name="value_submit" class="btn btn-md btn-info" value="Accept">
                                            <?php echo form_close(); ?>
                                           </td>
                                        <?php endif; ?>


                                </tr>
                                 <?php if($count === 8): ?>
                                            <?php break; ?>
                                        <?php endif; ?>

                            <?php endforeach; ?>
                            </table>
                            </div>
                            </div>



                    </div>

                    <div id="royaltyposition" class="tab-pane fade">
                        <p style="font-size:16px; margin-top: 10px; margin-bottom: 20px;">You currently own <?php echo $marketplace['roy_pos']; ?> royalty positions </p>

                    <div class="col-sm-12 col-md-12 royaltypositionforsale">
                               <div class="col-sm-6 col-md-7">
                                   <h4 style="color:#FFFFFF; font-weight:900;">My Royalty Pending Sale</h4>
                               </div>
                                <div class="col-sm-6 col-md-5">
                               <?php echo form_open('marketplace/index'); ?>
                                    <input type="hidden" name="CheckValue">
                                   <input type="submit" name="SellYourRoyality" class="btn btn-lg btn-info" value="Sell Your Royalty Positions"></input>
                               <?php echo form_close(); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Royalty Positions Wanted</th>
                                        <th>Price For Per RP</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <?php foreach ($marketplace['pending_sales'] as $pendingSales):?>
                                <tr>
                                    <td><?php echo $pendingSales->royalty_positions; ?></td>
                                    <td><?php echo $pendingSales->amount_per_rp; ?></td>
                                    <td>
                                        <?php
                                        $amount_per_rppp = $pendingSales->amount_per_rp;
                                        $rppp = $pendingSales->royalty_positions;

                                        $pendingtotalamount = $amount_per_rppp * $rppp;

                                        echo $pendingtotalamount;
                                         ?>


                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </table>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 royaltypositionforsale">
                               <div class="col-sm-6 col-md-7">
                                   <h4 style="color:#FFFFFF; font-weight:900;">My Active Bids</h4>
                               </div>
                                <div class="col-sm-6 col-md-5">
                                <a href="<?php echo base_url('marketplace/placebid'); ?>" class="btn btn-lg btn-info" value="Place Bid To Buy"></a>
                        </div>
                    </div>

                    <div id="statistics" class="tab-pane fade">
                        
                    </div>

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