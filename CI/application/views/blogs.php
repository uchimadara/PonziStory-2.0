<h1>Testimonials
</h1>
<h2 class="heading-title">Latest Testimonials on tradermoni</h2>


<?if (empty($testimonials)) { echo "No testimonials yet."; }
else { $odd = 'odd'; foreach ($testimonials as $t) { ?>

    <!--    <div class="t-block --><?//=$odd?><!-- col-lg-12">-->
    <!---->
    <!--        --><?// if (!empty($t->screenshot)) { ?>
    <!--        <div class="t-screenshot"><img src="--><?//=SITE_ADDRESS?><!--uploads/--><?//=$t->screenshot?><!--" /></div>-->
    <!--        --><?// } ?>
    <!--        <div class="t-content">--><?//=nl2br($t->content)?><!--</div>-->
    <!--        <div class="by-member"> - --><?//= $t->member ?><!--</div>-->
    <!--        <div class="t-date">--><?//= date(DEFAULT_DATE_FORMAT, $t->date) ?><!--</div>-->
    <!--        <div class="clear"></div>-->
    <!---->
    <!--    </div>-->
    <!---->
    <?// $odd = ($odd == 'odd') ? 'even' : 'odd'; } } ?>


    <section>
        <div class="container pt-0">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading-line-bottom mt-0 mb-30">
                    </div>
                    <div class="icon-box mb-0 p-0">

                        <? if (!empty($t->screenshot)) { ?>
                            <div class="t-screenshot"><img src="<?=SITE_ADDRESS?>uploads/<?=$t->screenshot?>" height="50" width="50"/></div>
                        <? } ?>

                        <h3 style="color: green;float:left"><?= $t->member ?>  - <?= date(DEFAULT_DATE_FORMAT, $t->date) ?></h3>
                        <br/>

                        <hr>
                        <p class="text-gray">  <?=nl2br($t->content)?></p>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <? $odd = ($odd == 'odd') ? 'even' : 'odd'; } } ?>
