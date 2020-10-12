<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $webtasks['websitename']; ?>
            <?php echo $WebTask['websitename']; ?>

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

<link rel="stylesheet" href="<?php echo base_url('custom/css/flipclock.css'); ?>" type="text/css" media="all" />

    <style type="text/css">
    
    /* COMMON PRICING STYLES */
        .panel.price,
        .panel.price>.panel-heading{
            border-radius:0px;
             -moz-transition: all .3s ease;
            -o-transition:  all .3s ease;
            -webkit-transition:  all .3s ease;
        }
        .panel.price:hover{
            box-shadow: 0px 0px 30px rgba(0,0,0, .2);
        }
        .panel.price:hover>.panel-heading{
            box-shadow: 0px 0px 30px rgba(0,0,0, .2) inset;
        }
        
                
        .panel.price>.panel-heading{
            box-shadow: 0px 5px 0px rgba(50,50,50, .2) inset;
            text-shadow:0px 3px 0px rgba(50,50,50, .6);
        }
            
        .price .list-group-item{
            border-bottom-:1px solid rgba(250,250,250, .5);
        }
        
        .panel.price .list-group-item:last-child {
            border-bottom-right-radius: 0px;
            border-bottom-left-radius: 0px;
        }
        .panel.price .list-group-item:first-child {
            border-top-right-radius: 0px;
            border-top-left-radius: 0px;
        }
        
        .price .panel-footer {
            color: #fff;
            border-bottom:0px;
            background-color:  rgba(0,0,0, .1);
            box-shadow: 0px 3px 0px rgba(0,0,0, .3);
        }
        
        
        .panel.price .btn{
            box-shadow: 0 -1px 0px rgba(50,50,50, .2) inset;
            border:0px;
        }
        
    /* green panel */
    
        
        .price.panel-green>.panel-heading {
            color: #fff;
            background-color: #57AC57;
            border-color: #71DF71;
            border-bottom: 1px solid #71DF71;
        }
        
            
        .price.panel-green>.panel-body {
            color: #fff;
            background-color: #65C965;
        }
                
        
        .price.panel-green>.panel-body .lead{
                text-shadow: 0px 3px 0px rgba(50,50,50, .3);
        }
        
        .price.panel-green .list-group-item {
            color: #333;
            background-color: rgba(50,50,50, .01);
            font-weight:600;
            text-shadow: 0px 1px 0px rgba(250,250,250, .75);
        }
        
        /* blue panel */
    
        
        .price.panel-blue>.panel-heading {
            color: #fff;
            background-color: #608BB4;
            border-color: #78AEE1;
            border-bottom: 1px solid #78AEE1;
        }
        
            
        .price.panel-blue>.panel-body {
            color: #fff;
            background-color: #73A3D4;
        }
                
        
        .price.panel-blue>.panel-body .lead{
                text-shadow: 0px 3px 0px rgba(50,50,50, .3);
        }
        
        .price.panel-blue .list-group-item {
            color: #333;
            background-color: rgba(50,50,50, .01);
            font-weight:600;
            text-shadow: 0px 1px 0px rgba(250,250,250, .75);
        }
        
        /* red price */
        
    
        .price.panel-red>.panel-heading {
            color: #fff;
            background-color: #D04E50;
            border-color: #FF6062;
            border-bottom: 1px solid #FF6062;
        }
        
            
        .price.panel-red>.panel-body {
            color: #fff;
            background-color: #EF5A5C;
        }
        
        
        
        
        .price.panel-red>.panel-body .lead{
                text-shadow: 0px 3px 0px rgba(50,50,50, .3);
        }
        
        .price.panel-red .list-group-item {
            color: #333;
            background-color: rgba(50,50,50, .01);
            font-weight:600;
            text-shadow: 0px 1px 0px rgba(250,250,250, .75);
        }
        
        /* grey price */
        
    
        .price.panel-grey>.panel-heading {
            color: #fff;
            background-color: #6D6D6D;
            border-color: #B7B7B7;
            border-bottom: 1px solid #B7B7B7;
        }
        
            
        .price.panel-grey>.panel-body {
            color: #fff;
            background-color: #808080;
        }
        

        
        .price.panel-grey>.panel-body .lead{
                text-shadow: 0px 3px 0px rgba(50,50,50, .3);
        }
        
        .price.panel-grey .list-group-item {
            color: #333;
            background-color: rgba(50,50,50, .01);
            font-weight:600;
            text-shadow: 0px 1px 0px rgba(250,250,250, .75);
        }
        
        /* white price */
        
    
        .price.panel-white>.panel-heading {
            color: #333;
            background-color: #f9f9f9;
            border-color: #ccc;
            border-bottom: 1px solid #ccc;
            text-shadow: 0px 2px 0px rgba(250,250,250, .7);
        }
        
        .panel.panel-white.price:hover>.panel-heading{
            box-shadow: 0px 0px 30px rgba(0,0,0, .05) inset;
        }
            
        .price.panel-white>.panel-body {
            color: #fff;
            background-color: #dfdfdf;
        }
                
        .price.panel-white>.panel-body .lead{
                text-shadow: 0px 2px 0px rgba(250,250,250, .8);
                color:#666;
        }
        
        .price:hover.panel-white>.panel-body .lead{
                text-shadow: 0px 2px 0px rgba(250,250,250, .9);
                color:#333;
        }
        
        .price.panel-white .list-group-item {
            color: #333;
            background-color: rgba(50,50,50, .01);
            font-weight:600;
            text-shadow: 0px 1px 0px rgba(250,250,250, .75);
        }
        .bronzeshares{
            background-color: #EF5A5C;
            color:#FFFFFF;
            border-radius: 15px;
            margin-left: 20px;

        }
        .silvershares
        {
             background-color: #73A3D4;
            color:#FFFFFF;
            border-radius: 15px;
            margin-left: 80px;
        }
        .golshares
        {
             background-color: #65C965;
            color:#FFFFFF;
            border-radius: 15px;
            margin-left: 70px;
        }

    </style>
</head>
<body>


	<header>
    <div class="floatLeft p-l-15" id="logotype">
        <a href="/" title="Crypto Cogent" style="color:white;"><?php echo $webtasks['websitename']; ?>
            <?php echo $WebTask['websitename']; ?>
            <?php echo $webtasks['websitename']; ?>
        </a>
    </div>

    <div class="member">
        Welcome <?php echo $WebTask['username']; ?>
        <?php echo $webtasks['username']; ?>    
        </div>
    <div class="statistics">
        Pack Balance <?php echo $WebTask['amount_balance']; ?> BTC
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
            <div class="col-sm-8 col-md-8">
                 <div class="col-md-12" style="margin-top: 50px;">

                        <?php $totalShares = $WebTask['bronze_shares'] + $WebTask['silver_shares'] + $WebTask['gold_shares']; ?>

                     <div class="col-md-3 bronzeshares">
                         <div class="col-md-4">
                         <img src="<?php echo base_url('assets/images/bronzeicon.png'); ?>" style="width:100%; margin-top: 10px;">
                         </div>
                         <div class="col-md-8" style="margin-top: 14px;">
                            <p style="font-size:14px;font-weight:900;">Bronze Shares</p>
                            <p style="font-size:18px; text-align: center;"><?php echo $WebTask['bronze_shares']; ?></p>

                         </div>
                    </div>

                    <div class="col-md-3 silvershares">
                         <div class="col-md-4">
                         <img src="<?php echo base_url('assets/images/silvericon.png'); ?>" style="width:100%; margin-top: 10px;">
                         </div>
                         <div class="col-md-8" style="margin-top: 14px;">
                            <p style="font-size:14px;font-weight:900;">Silver Shares</p>
                            <p style="font-size:18px; text-align: center;"><?php echo $WebTask['silver_shares']; ?></p>

                         </div>
                    </div>

                    <div class="col-md-3 golshares">
                         <div class="col-md-4">
                         <img src="<?php echo base_url('assets/images/gs2.png'); ?>" style="width:100%; margin-top: 10px;">
                         </div>
                         <div class="col-md-8" style="margin-top: 14px;">
                            <p style="font-size:14px;font-weight:900;">Gold Shares</p>
                            <p style="font-size:18px; text-align: center;"><?php echo $WebTask['gold_shares']; ?></p>

                         </div>
                    </div>
                 </div>
                 <div class="col-md-12" style="margin-top: 50px;">
                     <div class="col-sm-12 col-md-4">
                        <div class="panel price panel-red">
                        <div class="panel-heading  text-center">
                        <h2>Bronze Plan</h2>
                        </div>
                        <div class="panel-body text-center">
                            <p class="lead" style="font-size:25px"><strong><?php echo $WebTask['BronzePerSharePrice']; ?> BTC</strong></p>
                        </div>
                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item"><i class="icon-ok text-danger"></i><?php echo $WebTask['BronzeSharePerUser']; ?> Ad Packs Limit</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i><?php echo $WebTask['BronzeBannerAds']; ?> Banner Ads</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i><?php echo $WebTask['BronzeTextAds']; ?> Text Ads</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i>Upto <?php echo $WebTask['BronzeDailyPercentage']; ?>% Daily Profit</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i>Total Profit <?php echo $WebTask['BronzeMaxReturn']; ?>%</li>
                        </ul>
                        <div class="panel-footer">
                            <?php echo form_open('AdPacksPlan/BuyBronzePack'); ?>
                            <input type="hidden" name="PriceBronzePack" value="<?php echo $WebTask['BronzePerSharePrice']; ?>">
                            <input type="submit" name="BuyBronzePack" class="btn btn-lg btn-block btn-danger" value="Buy Now">
                            <?php echo form_close();  ?>
                        </div>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-4">
                         <div class="panel price panel-blue">
                        <div class="panel-heading  text-center">
                        <h2>Silver Plan</h2>
                        </div>
                        <div class="panel-body text-center">
                            <p class="lead" style="font-size:25px"><strong><?php echo $WebTask['SilverPerSharePrice']; ?> BTC</strong></p>
                        </div>
                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item"><i class="icon-ok text-danger"></i><?php echo $WebTask['SilverSharePerUser']; ?> Ad Packs Limit</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i><?php echo $WebTask['SilverBannerAds']; ?> Banner Ads</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i><?php echo $WebTask['SilverTextAds']; ?> Text Ads</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i>Upto <?php echo $WebTask['SilverDailyPercentage']; ?>% Daily Profit</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i>Total Profit <?php echo $WebTask['SilverMaxReturn']; ?>%</li>
                        </ul>
                        <div class="panel-footer">
                            <?php echo form_open('AdPacksPlan/BuySilverPack'); ?>
                            <input type="hidden" name="PriceSilverPack" value="<?php echo $WebTask['SilverPerSharePrice']; ?>">
                            <input type="submit" name="BuySilverPack" class="btn btn-lg btn-block btn-primary" value="Buy Now">
                            <?php echo form_close();  ?>
                        </div>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-4">
                         <div class="panel price panel-green">
                        <div class="panel-heading  text-center">
                        <h2>Gold Plan</h2>
                        </div>
                        <div class="panel-body text-center">
                            <p class="lead" style="font-size:25px"><strong><?php echo $WebTask['GoldPerSharePrice']; ?> BTC</strong></p>
                        </div>
                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item"><i class="icon-ok text-danger"></i><?php echo $WebTask['GoldSharePerUser']; ?> Ad Packs Limit</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i><?php echo $WebTask['GoldBannerAds']; ?> Banner Ads</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i><?php echo $WebTask['GoldTextAds']; ?> Text Ads</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i>Upto <?php echo $WebTask['GoldDailyPercentage']; ?>% Daily Profit</li>
                            <li class="list-group-item"><i class="icon-ok text-danger"></i>Total Profit <?php echo $WebTask['GoldMaxReturn']; ?>%</li>
                        </ul>
                        <div class="panel-footer">
                            <?php echo form_open('AdPacksPlan/BuyGoldPack'); ?>
                            <input type="hidden" name="PriceGoldPack" value="<?php echo $WebTask['GoldPerSharePrice']; ?>">
                            <input type="submit" name="BuyGoldPack" class="btn btn-lg btn-block btn-success" value="Buy Now">
                            <?php echo form_close();  ?>
                        </div>
                        </div>
                     </div>
                 </div> 

                 <div class="col-md-12">
                    <h2 class="tile-title m-t-20">My Ad Packs</h2>
                     <table class="table table-striped">
                         <thead>
                             <tr>
                                <th>Id</th>
                                 <th>Ad Packs</th>
                                 <th>Status</th>
                                 <th>Total Return</th>
                                 <th>Price</th>
                                <th>Received</th>
                             </tr>
                         </thead>
                         <tbody>
                         <?php foreach ($WebTask['history'] as $history) { ?>

                         <?php $totalreturninpercentage = $history->total_max_return;

                            $priceofoncredit = $history->price;

                            $totalreturninbtc = ($priceofoncredit / 100) * $totalreturninpercentage;

                            $dailyreturninpercentage = $history->max_percentage;

                            $dailyreturninbtc = ($priceofoncredit / 100) * $dailyreturninpercentage;



                          ?>
                            <tr>
                            <td><?php echo $history->id; ?></td>
                             <td><?php echo $history->ad_pack; ?></td>
                             <td><?php echo $history->status; ?></td>
                             <td><?php echo $totalreturninbtc . " BTC"; ?></td>
                              <td><?php echo $history->price . " BTC"; ?></td>
                             <td><?php echo $dailyreturninbtc . " BTC"; ?> </td>
                             </tr>

                          <?php      
                         } ?>    
                         </tbody>
                     </table>
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