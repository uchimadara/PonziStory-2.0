<h2>Manual Upgrade</h2>
<div class="formContainer">
    <?= form_open(site_url('adminpanel/users/upgrade/'.$userId), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'tx-form')); ?>
    <div class="form-group">
        <label for="method">Paid to:</label>
        <select name="payee_user_id" id="payee_user_id" class="form-control">
            <? foreach ($upline as $level => $user) { ?>
                <option value="<?= $user->id ?>">Level <?=$level?>: <?= $user->username ?></option>
            <? } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="from_account">Upgrade Level</label>
        <select name="upgrade_id" id="upgrade_id" class="form-control">
            <? foreach ($upgrades as $code => $upgrade) { ?>
                <option value="<?= $upgrade->id ?>">Level <?= $code ?>: <?= $upgrade->price ?></option>
            <? } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="transaction_id">Transaction ID</label>
        <input class="form-control" type="text" name="transaction_id" id="transaction_id" value="" placeholder/>
    </div>
    <div class="formBottom">
        <input class="btn btn-alt fs16" type="submit" name="Save" value="Save"/> &nbsp;&nbsp;
        <button data-dismiss="modal" class="close-modal btn btn-alt fs16" type="button">Cancel</button>
    </div>
    </form>
</div>

