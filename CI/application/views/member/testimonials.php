
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>







<script>
    function popupCenter(url, title, w, h) {
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    }
</script>



<?php if($userData->account_level == "2"){ ?>
    <script type="text/javascript">
        // var page = "https://www.facebook.com/431801507378477/photos/a.431801844045110/431801820711779/?type=3&theater";
    </script>
<?php } ?>

<script type="text/javascript">

    // var $dialog = $('<div></div>')
    //     .html('<object style="border: 0px; " data="https://www.facebook.com/431801507378477/photos/a.431801844045110/431801820711779/?type=3&theater"   width="100%" height="100%"> <embed src="https://www.facebook.com/431801507378477/photos/a.431801844045110/431801820711779/?type=3&theater" width="600" height="400"> </embed> </object>')
    //     .dialog({
    //         autoOpen: false,
    //         modal: true,
    //         height: 550,
    //         width: 500,
    //         title: "External Testimonial"
    //     });
    //
    // $(document).ready(function(){
    //     $("#cc").click(function(){
    //         $dialog.dialog('open');
    //     });
    // });


</script>



<h1>Level <?php echo $userData->account_level ?> Testimonials
    <?// if ($canAdd) { ?>
    &nbsp;&nbsp;

    <!--    <a class="btn btn-alt" id="show-testimonia" title="Add Testimonial" onclick="return confirm('IF YOU DONT FOLLOW THE TESTIMONY FORMAT SAMPLE OR SIMILAR, YOUR ACCOUNT WILL BE LOCKED AUTOMATICALLY AND U WILL MISS DONATION');" href="--><?//= site_url('member/form/testimonial') ?><!--">-->
    <!--        Add Yours-->
    <!--    </a>-->

    <?// } ?>
</h1>

<h1 style="color: red">FOLLOW THE INSTRUCTIONS BELOW:</h1>

<p style="color: darkgreen">1. Make Sure you are Signed into Facebook on this browser</p>
<p style="color: darkgreen">2. Click the button below called SHARE TESTIMONY and a page will pop up</p>
<p style="color: darkgreen">3. Share the Facebook post/image as your testimony </p>
<p style="color: darkgreen">4. After Sharing, Close the Pop up, and click SUBMIT FOR VERIFICATION  </p>
<p style="color: red">WARNING: DO NOT CLICK SUBMIT VERIFICATION IF YOU HAVENT SHARED THE TESTIMONY ON FACEBOOK. </p>
<p style="color: red">NOTE: TO GET SYSTEM BONUS, YOU MUST SHARE THE TESTIMONY ON FACEBOOK </p>
<p style="color: red">NOTE 2: THE NAME YOU SUBMITTED AS YOUR FACEBOOK NAME MUST CORRELATE WITH NAME OF THE SHARER ON FACEBOOK </p>
<hr>

<p>Copy: Use this <b style="color: orangered;font-weight: bold"><?= $refUrl ?></b> to register</p>
<p><b style="color: orangered;font-weight: bold">TIP:</b> Copy your referral link above and paste it while sharing to get free downlines</p>

<!--<a class="btn btn-alt" id="cc" title="Add Testimonial">-->
<!--    SHARE TESTIMONY-->
<!--</a>-->
<?php if($CurrentPlan->plan_id == "1"){ ?>
    <a class="btn btn-alt" onclick="popupCenter('https://m.facebook.com/photo.php?fbid=141469403650840&id=100033633422470&set=gm.2141990639425084&source=48', 'myPop1',1000,500);" href="javascript:void(0);">SHARE TESTIMONY</a>

<?php } ?>

<?php if($CurrentPlan->plan_id == "2"){ ?>
    <a class="btn btn-alt" onclick="popupCenter('https://m.facebook.com/photo.php?fbid=141469546984159&id=100033633422470&set=gm.2141991232758358&source=48', 'myPop1',1000,500);" href="javascript:void(0);">SHARE TESTIMONY</a>

<?php } ?>

<br>
<br>
<br>

<form action="https://tdm.nghelpers.com/member/form/testimonial" method="post" enctype="multipart/form-data" name="testimonialForm" class="frm_ajax">

    <input type="hidden" name="content" id="content" onclick="return confirm('TO AVOID ACCOUNT DELETION, BE VERY SURE YOU HAVE SHARED THE POST');"  value="TESTIMONY AWAITING VERIFICATION"/>
    <input class="btn btn-alt m-r-10" type="submit" value="SUBMIT FOR VERIFICATION"/>
</form>
<br>
<hr>

<!--<h1>Testimonials-->
<!--    --><?//// if ($canAdd) { ?>
<!--    &nbsp;&nbsp;-->
<!---->
<!--    <a class="btn btn-alt" id="show-testimonia" title="Add Testimonial" onclick="return confirm('IF YOU DONT FOLLOW THE TESTIMONY FORMAT SAMPLE OR SIMILAR, YOUR ACCOUNT WILL BE LOCKED AUTOMATICALLY AND U WILL MISS DONATION');" href="--><?//= site_url('member/form/testimonial') ?><!--">-->
<!--        Add Yours-->
<!--    </a>-->
<!---->
<!--    --><?//// } ?>
<!--</h1>-->
<!---->
<!--<h1 style="color: red">WARNING:</h1>-->
<!--<p style="color: red">FAILURE TO ADHERE TO THE TESTIMONY SAMPLE BELOW OR SIMILAR FORMAT WILL LEAD TO ACCOUNT SUSPENSION</p>-->
<!--<h2>SAMPLE TESTIMONY</h2>-->
<!--<p style="color: #0E790E">Hi, My name is {Your Name}, From {Your Location}. A {Your current level eg SilverPlus} participant of TRADERMONI.-->
<!--    I received {Amount} from {no. of participant} each. And I have upgraded to {last level eg Platinum} to receive 45k. My phone Number is 08033333333-->
<!--    {Closing Remarks eg: Thanks to TRADERMONI}-->
<!--</p>-->
<hr>
<? if (isset($pending) && $pending) { ?>
<div class="alert alert-info">
    <i class="fa fa-info-circle blue" aria-hidden="true"></i>&nbsp;
    <!--    Your testimonial has been submitted and you will receive an email after the admin has reviewed it.</div>-->
    <? } ?>

    <?if (empty($testimonials)) { echo "No items to display."; }
    else { $odd = 'odd'; foreach ($testimonials as $t) { ?>

        <div class="t-block <?=$odd?> col-lg-12">

            <? if (!empty($t->screenshot)) { ?>
                <div class="t-screenshot"><img src="<?=SITE_ADDRESS?>uploads/<?=$t->screenshot?>"width="100" height="100" /></div>
            <? } ?>
            <div class="t-content"><?=nl2br($t->content)?></div>
            <div class="by-member"> - <?= $t->member ?></div>
            <div class="t-date"><?= date(DEFAULT_DATE_FORMAT, $t->date) ?></div>
            <div class="clear"></div>

        </div>

        <? $odd = ($odd == 'odd') ? 'even' : 'odd'; } } ?>

    <? if ($canAdd) { ?>
        <div id="add-testimonial" class="modal fade" data-modal="modal-18">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Your Testimonial</h4>
                    </div>
                    <div id="testimonialModal" class="modal-body">
                        <div id="<?= $formName ?>Form">
                            <div class="formContainer">
                                <form action="/member/form/testimonial" method="post" enctype="multipart/form-data" name="testimonialForm" class="frm_ajax">
                                    <?= $testimonialForm ?>

                                    <div class="clear"></div>
                                    <div class="formBottom">
                                        <input type="hidden" name="salt" value="<?= $userData->salt ?>"/>
                                        <input class="btn btn-alt m-r-10" type="submit" value="Submit"/>
                                        <input class="btn btn-alt" type="reset" value="Reset"/>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" >
                        <button type="button" class="btn btn-sm" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    <? } ?>
