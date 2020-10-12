<?=$methodsMenu?>
<div class="formContainer">
    <?=form_open(site_url('adminpanel/cashier/account/' . $code . ($account ? '/' . $account->id : '')), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'accountUpdate')); ?>
        <table>
            <tr>
                <td><strong>Name</strong></td>
                <td><?=form_input('name', $account ? $account->name : '', 'id="name"')?></td>
            </tr>
<?
    switch ($code)
    {
        case 'bw':
            $details = $account ? new BankWire($account->details) : null;
?>
            <tr>
                <td><strong>Bank Name</strong></td>
                <td><?=form_input('bank_name', $account ? $details->bank_name : '', 'id="bank_name"')?></td>
            </tr>
            <tr>
                <td valign="top"><strong>Bank Address</strong></td>
                <td><?=form_textarea('bank_address', $account ? $details->bank_address : '', 'id="bank_address"')?></td>
            </tr>
            <tr>
                <td><strong>Bank City</strong></td>
                <td><?=form_input('bank_city', $account ? $details->bank_city : '', 'id="bank_city"')?></td>
            </tr>
            <tr>
                <td><strong>Bank Country</strong></td>
                <td><?=form_dropdown('bank_country', $countries, $account ? $details->bank_country : '', 'id="bank_country"')?></td>
            </tr>
            <tr>
                <td><strong>Fullame</strong></td>
                <td><?=form_input('fullname', $account ? $details->fullname : '', 'id="fullname"')?></td>
            </tr>
            <tr>
                <td valign="top"><strong>Address</strong></td>
                <td><?=form_textarea('address', $account ? $details->address : '', 'id="address"')?></td>
            </tr>
            <tr>
                <td><strong>City</strong></td>
                <td><?=form_input('city', $account ? $details->city : '', 'id="city"')?></td>
            </tr>
            <tr>
                <td><strong>Country</strong></td>
                <td><?=form_dropdown('country', $countries, $account ? $details->country : '', 'id="country"')?></td>
            </tr>
            <tr>
                <td><strong>Account Number</strong></td>
                <td><?=form_input('account_number', $account ? $details->account_number : '', 'id="account_number"')?></td>
            </tr>
            <tr>
                <td><strong>BIC / SWIFT</strong></td>
                <td><?=form_input('bic_swift', $account ? $details->bic_swift : '', 'id="bic_swift"')?></td>
            </tr>
            <tr>
                <td><strong>IBAN</strong></td>
                <td><?=form_input('iban', $account ? $details->iban : '', 'id="iban"')?></td>
            </tr>
            <tr>
                <td valign="top"><strong>Other Information</strong></td>
                <td><?=form_textarea('info', $account ? $details->info : '', 'id="info"')?></td>
            </tr>
<?
            break;

        case 'wu':
            $details = $account ? new WesternUnion($account->details) : null;
?>
            <tr>
                <td><strong>First Name</strong></td>
                <td><?=form_input('first_name', $account ? $details->first_name : '', 'id="first_name"')?></td>
            </tr>
            <tr>
                <td><strong>Last Name</strong></td>
                <td><?=form_input('last_name', $account ? $details->last_name : '', 'id="last_name"')?></td>
            </tr>
            <tr>
                <td><strong>City</strong></td>
                <td><?=form_input('city', $account ? $details->city : '', 'id="city"')?></td>
            </tr>
            <tr>
                <td><strong>Country</strong></td>
                <td><?=form_dropdown('country', $countries, $account ? $details->country : '', 'id="country"')?></td>
            </tr>
<?
            break;

        case 'lr':
?>
            <tr>
                <td><strong>Account Number</strong></td>
                <td><?=form_input('account', $account ? $account->details : '', 'id="account"')?></td>
            </tr>
<?
            break;

        case 'ap':
        case 'st':
        case 'pm':
        case 'hd':
?>
            <tr>
                <td><strong>Account</strong></td>
                <td><?=form_input('account', $account ? $account->details : '', 'id="account"')?></td>
            </tr>
<?
            break;
    }
?>
            <tr>
                <td><strong>Extra 1</strong></td>
                <td><?=form_input('extra_field_1', $account ? $account->extra_field_1 : '', 'id="extra_field_1"')?></td>
            </tr>
            <tr>
                <td><strong>Extra 2</strong></td>
                <td><?=form_input('extra_field_2', $account ? $account->extra_field_2 : '', 'id="extra_field_2"')?></td>
            </tr>
            <tr>
                <td><strong>Minimum</strong></td>
                <td><?=form_input('minimum', $account ? $account->minimum : '', 'id="minimum"')?></td>
            </tr>
            <tr>
                <td><strong>Maximum</strong></td>
                <td><?=form_input('maximum', $account ? $account->maximum : '', 'id="maximum"')?></td>
            </tr>
            <tr>
                <td><strong>Maximum duration</strong></td>
                <td><?=form_dropdown('maximum_duration', array('' => '--', 'day' => 'Day', 'week' => 'Week', 'month' => 'Month'), $account ? $account->maximum_duration : '')?></td>
            </tr>

            <tr>
                <td><strong>Direction</strong></td>
                <td><?=form_dropdown('restrict_to', array('' => '--', 'in' => 'deposits', 'out' => 'cashouts'), $account ? $account->restrict_to : '')?></td>
            </tr>

            <tr>
                <td colspan="2">
                    <input class="button_blue borderWhite" type="submit" name="update" value="Update" />
                    <span class="loading"><img src="<?=asset('images/loading.gif')?>" /></span>
                </td>
            </tr>
        </table>
    </form>
</div>