<? if (!empty($balances) && $userData->balance >= .01) { ?>
    <div class="form-group">
        Apply account balance to purchase:<br/>
        <br/>
        <? foreach ($balances as $b) {
            if ($b->balance > 0) {
                ?>
                <input type="radio" name="apply" value="<?= $b->code ?>" id="apply<?= $b->code ?>"/>
                <label for="apply<?= $b->code ?>"><span class="ppIcon <?= $b->code ?>"></span> <?= money($b->balance) ?>
                </label><br/>
            <? }
        } ?>

    </div>

<? } ?>
<div class="form-group">

    <label for="method">External Payment Method:</label>
    <select id="method" name="method" class="form-control">
        <? foreach ($paymentMethods as $pm) { ?>
            <option value="<?= $pm->code ?>"><?= $pm->name ?> [+ <?= $fees[$pm->code]->percent ?>% + <?= money($fees[$pm->code]->fixed) ?>]</option>
        <? } ?>
    </select>
</div>
<div class="form-group m-t-10">
    <input type="checkbox" name="agree" value="1" id="agree"/>
    <label for="agree" class="after_checkbox fs18">
        I agree to the <?= anchor(SITE_ADDRESS.'/tos.html', 'Terms of Sevice', 'class="shaded" target="_blank"') ?>
    </label>

</div>
<p class="arialBold">Absolutely no refunds on any purchases!</p>
<br/>
<i>Your total will be calculated for your selected payment method and you will confirm the transaction on the next screen.</i>
