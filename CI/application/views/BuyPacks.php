<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $goldpackk['websitename']; ?>
            <?php echo $goldpacks['websitename']; ?>
            <?php echo $bronzeepackk['websitename']; ?>
            <?php echo $silverrpackk['websitename']; ?>
            <?php echo $bronzepacks['websitename']; ?>
            <?php echo $silverpack['websitename']; ?></title>

	
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
    </style>
</head>
<body>


	<header>
    <div class="floatLeft p-l-15" id="logotype">
        <a href="/" title="Crypto Cogent" style="color:white;"><?php echo $goldpackk['websitename']; ?>
            <?php echo $goldpacks['websitename']; ?>
            <?php echo $bronzeepackk['websitename']; ?>
            <?php echo $silverrpackk['websitename']; ?>
            <?php echo $bronzepacks['websitename']; ?>
            <?php echo $silverpack['websitename']; ?>

        </a>
    </div>

    <div class="member">
        Welcome <?php echo $bronzepacks['username']; ?>   
        <?php echo $bronzeepackk['username']; ?>
        <?php echo $silverpack['username']; ?>
        <?php echo $silverrpackk['username']; ?>
        <?php echo $goldpackk['username']; ?>
        <?php echo $goldpacks['username']; ?>
        </div>
    <div class="statistics">
        Pack Balance <?php echo $bronzepacks['balance_amount']; ?> 
        <?php echo $bronzeepackk['amount_details']; ?>
        <?php echo $silverpack['amount_details']; ?>
        <?php echo $silverrpackk['user_amount']; ?>
        <?php echo $goldpackk['amount_details']; ?>
        <?php echo $goldpacks['user_amount']; ?> BTC

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

            <?php if(isset($bronzeepackk['bronze_pack'])): ?>
                <?php if($bronzeepackk['bronze_pack'] === '1'): ?>
                    <div class="alert alert-success">
                        Bronze Ad Packs Buy <?php echo $bronzeepackk['bronzeperprice']; ?> BTC   
                    </div>
                <?php endif; ?>

                <?php echo form_open('AdPacksPlan/BuyBronzePacks'); ?>
                    <input type="hidden" name="perpackprice" value="<?php echo $bronzeepackk['bronzeperprice']; ?>">
                    <div class="form-group">
                        <label for="amounttobuyadpacks">Select Amount To Buy Ad Packs</label>
                        <select name="amounttobuyadpacks" class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div style="text-align: center;">
                    <input type="submit" name="submit" value="Buy" class="btn btn-lg btn-info">
                    </div>
                <?php echo form_close(); ?>
            <?php endif; ?>


            <?php if(isset($silverpack['silver_pack'])): ?>

                <?php if($silverpack['silver_pack'] === '1'): ?>

                    <div class="alert alert-success">
                        Silver Ad Packs Buy <?php echo $silverpack['silverperprice']; ?> BTC   
                    </div>

                <?php endif; ?>

                 <?php echo form_open('AdPacksPlan/BuySilverPacks'); ?>
                    <input type="hidden" name="perpackprice" value="<?php echo $silverpack['silverperprice']; ?>">
                    <div class="form-group">
                        <label for="amounttobuyadpacks">Select Amount To Buy Ad Packs</label>
                        <select name="amounttobuyadpacks" class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div style="text-align: center;">
                    <input type="submit" name="submit" value="Buy" class="btn btn-lg btn-info">
                    </div>
                <?php echo form_close(); ?>


            <?php endif; ?>



            <?php if(isset($goldpackk['gold_pack'])): ?>

                <?php if($goldpackk['gold_pack'] === '1'): ?>

                    <div class="alert alert-success">
                        Gold Ad Packs Buy <?php echo $goldpackk['goldperprice']; ?> BTC   
                    </div>

                <?php endif; ?>

                 <?php echo form_open('AdPacksPlan/BuyGoldPacks'); ?>
                    <input type="hidden" name="perpackprice" value="<?php echo $goldpackk['goldperprice']; ?>">
                    <div class="form-group">
                        <label for="amounttobuyadpacks">Select Amount To Buy Ad Packs</label>
                        <select name="amounttobuyadpacks" class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div style="text-align: center;">
                    <input type="submit" name="submit" value="Buy" class="btn btn-lg btn-info">
                    </div>
                <?php echo form_close(); ?>


            <?php endif; ?>






                
                 <?php if(isset($goldpacks['error_buying'])): ?>
                    <?php if($goldpacks['error_buying'] === '1'): ?>
                        <div class="alert alert-warning">
                            You Don't Have Sufficient Balance To Buy Ad Credits
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(isset($goldpacks['error_buying'])): ?>
                    <?php if($goldpacks['error_buying'] === '2'): ?>
                        <div class="alert alert-info">
                            You Have Successfully Purchased Ad Pack Credits
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(isset($goldpacks['error_buying'])): ?>
                    <?php if($goldpacks['error_buying'] === '3'): ?>
                        <div class="alert alert-info">
                           You Have Reached The Limit To Buy Ad Credits
                        </div>
                    <?php endif; ?>
                <?php endif; ?>




                 <?php if(isset($silverrpackk['error_buying'])): ?>
                    <?php if($silverrpackk['error_buying'] === '1'): ?>
                        <div class="alert alert-warning">
                            You Don't Have Sufficient Balance To Buy Ad Credits
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(isset($silverrpackk['error_buying'])): ?>
                    <?php if($silverrpackk['error_buying'] === '2'): ?>
                        <div class="alert alert-info">
                            You Have Successfully Purchased Ad Pack Credits
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                  <?php if(isset($silverrpackk['error_buying'])): ?>
                    <?php if($silverrpackk['error_buying'] === '3'): ?>
                        <div class="alert alert-info">
                           You Have Reached The Limit To Buy Ad Credits
                        </div>
                    <?php endif; ?>
                <?php endif; ?>



                <?php if(isset($bronzepacks['error_buying'])): ?>
                    <?php if($bronzepacks['error_buying'] === '1'): ?>
                        <div class="alert alert-warning">
                            You Don't Have Sufficient Balance To Buy Ad Credits
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(isset($bronzepacks['error_buying'])): ?>
                    <?php if($bronzepacks['error_buying'] === '2'): ?>
                        <div class="alert alert-info">
                            You Have Successfully Purchased Ad Pack Credits
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(isset($bronzepacks['error_buying'])): ?>
                    <?php if($bronzepacks['error_buying'] === '3'): ?>
                        <div class="alert alert-info">
                           You Have Reached The Limit To Buy Ad Credits
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