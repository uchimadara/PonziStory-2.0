        <div class="formContainer">
            <?= form_open(site_url('adminpanel/users/edit_payment/'.$payment->id), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'payment-form')); ?>
            <table class="table">
                <tr>
                    <td>From Member:</td>
                    <td> <?= $payment->payer ?> </td>
                    <td> <?= $payment->payer_name ?></td>
                </tr>
                <tr>
                    <td>To Member: </td>
                    <td> <?= $payment->payee ?> </td>
                    <td> <?= $payment->payee_name ?></td>
                </tr>
                <tr>
                    <td>Membership Level:</td>
                    <td> <?= $payment->title ?> </td>
                    <td></td>
                </tr>
            </table>

            <div class="form-group">
                <label for="method">Payment method</label>
                <select name="method_id" id="method_id" class="form-control">
                    <? foreach ($paymentMethods as $balance) { ?>
                        <option value="<?= $balance->id ?>"><?= $balance->method_name ?></option>
                    <? } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="from_account">Payer account</label>
                <input class="form-control" type="text" name="from_account" id="from_account" value="<?=$payment->from_account?>"/>
            </div>
            <div class="form-group">
                <label for="transaction_id">Transaction ID</label>
                <input class="form-control" type="text" name="transaction_id" id="transaction_id" value="<?= $payment->transaction_id ?>"/>
            </div>
            <div class="form-group">
                <label for="amount">Amount USD</label>
                <input class="form-control" type="text" name="amount" id="amount" value="<?= $payment->amount ?>"/>
            </div>
            <div class="form-group">
                <label for="currency">Currency: </label>&nbsp;
                <?= form_dropdown('currency', $this->picklist->select_values('currency_list'), $payment->currency, 'id="currency" class="form-control"') ?>
            </div>
            <div class="form-group" id="currency_amount_input">
                <label for="currency_amount">Currency Amount</label>
                <input class="form-control" type="text" name="currency_amount" id="currency_amount" value="<?= $payment->currency_amount ?>"/>
            </div>
            <div class="form-group">
                <label for="details">Transaction Details</label>
                <textarea class="form-control auto-size" row="5" name="details" id="details"><?= $payment->details ?></textarea>
            </div>
            <hr class="whiter m-t-20 m-b-5" />
            <div class="formBottom">
                <input class="btn btn-alt fs16" type="submit" name="Save" value="Save" /> &nbsp;&nbsp;
                <button data-dismiss="modal" class="close-modal btn btn-alt fs16" type="button">Cancel</button>
            </div>
            </form>
        </div>

