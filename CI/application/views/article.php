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
        <div class="container pt-0" style="margin-bottom: 5px;padding-bottom: 6px;">
            <div class="row">
                <div class="col-md-8">


                    <? if (!empty($t->screenshot)) { ?>
                        <div class="t-screenshot"><img src="<?=SITE_ADDRESS?>uploads/<?=$t->screenshot?>" height="50" width="50"/></div>
                    <? } ?>

                    <blockquote class="theme-colored pt-20 pb-20">
                        <p><?=nl2br($t->content)?></p>
                        <footer style="color: #0E790E"> <cite title="Source Title"><?= $t->member ?></cite>   (  <?= date(DEFAULT_DATE_FORMAT, $t->date) ?> ) </footer>
                    </blockquote>
                    <hr>


                </div>
            </div>
        </div>
    </section>
    <? $odd = ($odd == 'odd') ? 'even' : 'odd'; } } ?>
