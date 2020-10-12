<? $edit = !$account->locked && $account->account != ''; ?>
<div class="col-md-12 p-20">

    <h1>
        <img src="<?= asset('images/currencies/logo-'.$code.'.png') ?>"/> <br/>
        <?= $edit ? 'Change ' : 'Register' ?> your <?= $account->name ?> Account</h1>

    <div class="formContainer formsDeposit">
        <? if (!$edit) : ?>
            <? if ($code == 'bw') : ?>
                <strong>Please enter your account information below:</strong>
            <? endif; ?>
            <div class="alert alert-info">
                This is the account where you want to receive payments.<br/>
            </div>
        <? endif; ?>
        <?= form_open(site_url('member/method/'.$code), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'accountFrm')); ?>
        <? echo form_hidden('code', $code);
        switch ($code) {
            // Bank Wire
            case 'bw':
            {
                if ($edit):
                    $details = new BankWire($account->account);?>
                    <div class="alert alert-info">Current Account: <strong><?= $details->getTitle(); ?></strong></div>
                <? endif; ?>
                <div class="form-group">
                    <label for="bank_name">Bank Name:</label>
                    <input type="text" class="form-control input" name="bank_name" id="bank_name" value="<?= $edit ? $details->bank_name : '' ?>"/>
                </div>

                <div class="form-group">
                    <label class="top" for="bank_address">Bank Address:</label>
                    <textarea name="bank_address" id="bank_address"><?= $edit ? $details->bank_address : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label for="bank_city">Bank City:</label>
                    <input type="text" class="form-control input" name="bank_city" id="bank_city" value="<?= $edit ? $details->bank_city : '' ?>"/>
                </div>

                <div class="form-group">
                    <label for="bank_country">Bank Country:</label>
                    <?= form_dropdown('bank_country', $countries, $edit ? $details->bank_country : '', 'id="bank_country"') ?>
                </div>

                <div class="form-group">
                    <label for="fullname">Your Full Name:</label>
                    <input type="text" class="form-control input" name="fullname" id="fullname" value="<?= $edit ? $details->fullname : '' ?>"/>
                </div>

                <div class="form-group">
                    <label class="top" for="address">Your Full Address:</label>
                    <textarea name="address" id="address"><?= $edit ? $details->address : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label for="city">Your City:</label>
                    <input type="text" class="form-control input" name="city" id="city" value="<?= $edit ? $details->city : '' ?>"/>
                </div>

                <div class="form-group">
                    <label for="country">Your Country:</label>
                    <?= form_dropdown('country', $countries, $edit ? $details->country : $country, 'id="country"') ?>
                </div>

                <div class="form-group">
                    <label for="account_number">Account Number:</label>
                    <input type="text" class="form-control input" name="account_number" id="account_number" value="<?= $edit ? $details->account_number : '' ?>"/>
                </div>

                <div class="form-group">
                    <label for="bic_swift">BIC / SWIFT:</label>
                    <input type="text" class="form-control input" name="bic_swift" id="bic_swift" value="<?= $edit ? $details->bic_swift : '' ?>"/>
                </div>

                <div class="form-group">
                    <label for="iban">IBAN:</label>
                    <input type="text" class="form-control input" name="iban" id="iban" value="<?= $edit ? $details->iban : '' ?>"/>
                </div>

                <div class="form-group">
                    <label class="top" for="info">Other Information:</label>
                    <textarea name="info" id="info"><?= $edit ? $details->info : '' ?></textarea>
                </div>
                <?
                break;
            }

            // Western Union
            case 'wu':
            {
                if ($edit):
                    $details = new WesternUnion($account->account);?>
                    <h2 class="alert alert-info">Current Account: <strong><?= $details->getTitle(); ?></strong></h2>
                <? endif; ?>
                <div class="form-group">
                    <label for="first_name">First Name: </label>
                    <input type="text" class="form-control input" name="first_name" id="first_name" value="<?= $edit ? $details->first_name : '' ?>"/>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control input" name="last_name" id="last_name" value="<?= $edit ? $details->last_name : '' ?>"/>
                </div>
                <div>
                    <label for="city">City:</label>
                    <input type="text" class="form-control input" name="city" id="city" value="<?= $edit ? $details->city : '' ?>"/>
                </div>
                <div class="form-group">
                    <label for="city">Region / State:</label>
                    <input type="text" class="form-control input" name="region" id="region" value="<?= $edit ? $details->region : '' ?>"/>
                </div>
                <div class="form-group">
                    <label for="city">Postcode / Zip:</label>
                    <input type="text" class="form-control input" name="zip" id="zip" value="<?= $edit ? $details->zip : '' ?>"/>
                </div>
                <div class="form-group">
                    <label for="country">Country:</label>
                    <?= form_dropdown('country', $countries, $edit ? $details->country : $country, 'id="country"') ?>
                </div>
                <?
                break;
            }

            default:
                ?>

                <? if ($edit): ?>
                    <div class="alert alert-info">Current Account:
                        <strong><?= $account->account ?></strong></div>
                <? endif; ?>
                    <div class="form-group">
                        <label for="account"><?= $account->name ?> Account:</label>
                        <input type="text" class="form-control input" name="account" id="account"/>
                    </div>
                    <div class="form-group">
                        <label for="confirm_account">Repeat Account:</label>
                        <input type="text" class="form-control input" name="confirm_account" id="confirm_account"/>
                    </div>

                <?
        } ?>

        <div class="form-group">
            <label for="secret_answer"><?= $userData->secret_question ?></label>
            <input type="text" class="form-control input" name="secret_answer" id="secret_answer"/>
        </div>

        <div class="formBottom">
            <input type="submit" class="btn btn-alt" value="Save"/>
            <a href="<?=site_url('back_office/accounts')?>" class="btn">Cancel</a>
        </div>
        </form>
    </div>

</div>
