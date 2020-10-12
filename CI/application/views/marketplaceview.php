<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $marketplace['websitename']; ?></title>

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
        .royaltypositionforsale
        {
            background-color: #72A230;
            padding-top: 5px;
    
        }
        .tab-content
        {
            padding:10px;
        }
        .left-text{
            text-align: left;
            font-size: 16px;
        }
        .right-text{
            text-align: right;
            font-size: 16px;
        }
    </style>

</head>
<body>


	<header>
    <div class="floatLeft p-l-15" id="logotype">
        <a href="/" title="Crypto Cogent" style="color:white;"><?php echo $marketplace['websitename']; ?></a>
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
                               <?php echo form_open('marketplace'); ?>
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
                                                <input type="hidden" name="hiddenusernameseller" value="<?php echo $username ?>">
                                                <input type="hidden" name="hiddenperrpsell" value="<?php echo $rpdataforsale->amount_per_rp; ?>">
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
                                                <input type="hidden" name="bidder_username" value="<?php echo $bidder_username; ?>">
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
                               <?php echo form_open('marketplace'); ?>
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
                                        <th>ID</th>
                                        <th>Royalty Positions Wanted</th>
                                        <th>Price For Per RP</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <?php foreach ($marketplace['pending_sales'] as $pendingSales):?>
                                <tr>
                                    <td><?php echo $pendingSales->id; ?></td>
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
                                    <td>
                                         <?php echo form_open('marketplace/cancel_sales'); ?>
                                        <input type="hidden" name="user_idtoadd" value="<?php echo $pendingSales->user_id; ?>">
                                        <input type="hidden" name="totalnumberofrospos" value="<?php echo $pendingSales->royalty_positions; ?>">
                                        <input type="hidden" name="idtodeletependingsales" value="<?php echo $pendingSales->id; ?>">
                                        <input type="submit" name="cancel" class="btn btn-sm btn-danger" value="Cancel">
                                        <?php echo form_close(); ?>
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
                                <a href="<?php echo base_url('marketplace/placebid'); ?>" class="btn btn-lg btn-info" style="margin-bottom: 5px;">Place Bid To Buy</a>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Royalty Positions Wanted</th>
                                        <th>Price For Per RP</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <?php foreach ($marketplace['active_bids'] as $activeBids):?>
                                <tr>
                                    <td><?php echo $activeBids->id; ?></td>
                                    <td><?php echo $activeBids->royalty_positions; ?></td>
                                    <td><?php echo $activeBids->amount_per_rp; ?></td>
                                    <td>
                                        <?php
                                        $amount_per_rppp = $activeBids->amount_per_rp;
                                        $rppp = $activeBids->royalty_positions;

                                        $pendingtotalamount = $amount_per_rppp * $rppp;

                                        echo $pendingtotalamount;
                                         ?>


                                    </td>
                                    <td>
                                         <?php echo form_open('marketplace/cancel_bids'); ?>
                                        <input type="hidden" name="idtocancelbids" value="<?php echo $activeBids->id; ?>">
                                        <input type="submit" name="cancel" class="btn btn-sm btn-danger" value="Cancel">
                                        <?php echo form_close(); ?>
                                    </td>



                                </tr>
                            <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>

                    <div id="statistics" class="tab-pane fade">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <p style="font-size:16px;">Use this page, along with our monetization portfolio, results page and upcoming development news in the forum to help determine the resale (and future potential) value of our Royalty Positions</p>
                            </div>
                            <div class="col-sm-6 col-md-12 royaltypositionforsale" style="padding-bottom: 5px;margin-top: 15px;">
                               <div class="col-sm-12 col-md-7">
                                   <h4 style="color:#FFFFFF; font-weight:900;">Marketplace Statistics</h4>
                               </div>
                                <div class="col-sm-6 col-md-5">
                                <a href="<?php echo base_url('marketplace'); ?>" class="btn btn-lg btn-default">Marketplace</a>
                                </div>
                           </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="col-md-6">
                                    <p class="left-text">Royalty Positions In Circulation</p>
                               
                                </div>
                                <div class="right-text">
                                     <p class="right-text"><?php echo $marketplace['totalnumberofdividends']; ?> Royalty Positions</p>
                                </div>
                           </div>
                           <div class="col-sm-12 col-md-12">
                                <div class="col-md-6">
                                    <p class="left-text">Turn Over All Time</p>
                               
                                </div>
                                <div class="right-text">
                                     <p class="right-text"><?php echo $marketplace['fullamountpaid']; ?> $</p>
                                </div>
                           </div>
                           <div class="col-sm-12 col-md-12">
                                <div class="col-sm-6 col-md-12 royaltypositionforsale" style="padding-bottom: 5px;margin-top: 15px;">
                               <div class="col-sm-12 col-md-12">
                                   <h4 style="color:#FFFFFF; font-weight:900;">Dividend History</h4>
                               </div>
                               </div>
                               <div class="col-sm-12 col-md-12">
                                   <table class="table">
                                       <thead>
                                           <tr>
                                            <th>Date</th>
                                            <th>Royalty Positions Held</th>
                                            <th>Total Dividends</th>
                                            <th>Dividend Per RP</th>
                                            <th>My Earnings</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                            <?php foreach ($marketplace['history_dividends'] as $history_dividends) {
                                                ?>
                                                <tr>

                                                <?php
                                                    $myearnings = $history_dividends->dividends_paid;

                                                    $myearningss = $myearnings * $marketplace['onebtcpricee'];

                                                    $myearning_show = number_format($myearningss, 3);

                                                    $perrpprice = $history_dividends->dividend_per_rp;

                                                    $perrppricee = number_format($perrpprice, 3);

                                                    $totaldividendspaid = $history_dividends->total_dividend_amount;

                                                    $totaldividendspaidd = number_format($totaldividendspaid, 3);

                                                 ?>


                                                    <td><?php echo $history_dividends->date_dividends; ?></td>
                                                    <td><?php echo $history_dividends->royalty_positions; ?></td>
                                                    <td><?php echo $totaldividendspaidd . " $";  ?></td>
                                                   <td><?php echo $perrppricee. "$"; ?></td>
                                                    <td><?php echo $myearning_show. "$"; ?></td>
                                                </tr>


                                                <?php
                                            } ?>
                                       </tbody>
                                   </table>
                               </div>
                           </div>
                        </div>
                    </div>

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