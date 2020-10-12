<h1>Rewards</h1>
<? if ($userData->account_level == 0) { ?>
    <div class="col-lg-12">

        <div class="alert alert-warning">
            You must <a href="/back_office/upgrade">upgrade your account</a> to create text ads.
        </div>
    </div>

<? }  ?>

<?if (empty($products)) { echo "No rewards are available."; } else { ?>

        <h4>Your upgrade level has qualified you to receive the following rewards </h4>

<? $odd = 'odd'; foreach ($products as $p) { ?>

    <div class="p-block <?=$odd?> col-lg-12">


        <div class="p-title"><?=$p->title?></div>
        <div class="p-description"><?= nl2br($p->description) ?></div>
        <div class="p-download">

            <a href="/download/reward/<?=$p->id?>"><i class="fa fa-download fs20" aria-hidden="true"></i> &nbsp;Click here to download your reward</a>
        </div>

        <? if ($p->file_type == 'video') { ?>



        <? } elseif ($p->file_type == 'pdf') { ?>


        <? } ?>

        <div class="clear"></div>

    </div>

<? $odd = ($odd == 'odd') ? 'even' : 'odd'; } } ?>

