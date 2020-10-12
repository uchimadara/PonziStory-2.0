<script type="text/javascript">
    function checkvalue(val)
    {
        if(val=="Others")
            document.getElementById('color').style.display='block';
        else
            document.getElementById('color').style.display='none';
    }

</script>

<div class="col-lg-6">
    <div class="tile">
        <h2 class="tile-title">REJECTION PAGE</h2>

        <h1 style="color: red">BE WARNED!!! MAKE SURE YOUR ARE VERY SURE OF WHATEVER REASON YOU CHOOSE BELOW!
            YOU ARE MORE LIKELY THE TO BANNED THAN THE OTHER PERSON </h1>

        <? if ($isGuest): ?>
            <p>
                <strong>Existing members:</strong> Please <?= anchor('/login', 'login') ?> to your account and submit a support ticket from your back office.
                <br/>
                <a href="<?= SITE_ADDRESS ?>login" class="btn btn-alt">Login</a>
            </p>
        <? endif; ?>
        <div class="formContainer p-10">
            <table class="rwd-table">
                <tr>
                    <th>Username</th>
                    <th>Phone</th>
                    <th>Amount (NGN)</th>

                </tr>
                <tr>

                        <td data-th="Username" style="color: red"><?= $usee->username ?></td>
                        <td data-th="Phone" style="color: red"><?=$usee->phone?></td>
                        <td data-th="Phone" style="color: red"><?=$usb->amount?></td>
                </tr>
            </table>

            <?= form_open(site_url('member/rejectPost/'.$this->uri->segment(3).'/'.$this->uri->segment(4)), array('method' => 'post', 'id' => 'supportFrm')); ?>

                <div class="form-group">
                    <label for="category">Reason</label>
                    <select name="reason" class="form-control input-sm" onchange='checkvalue(this.value);'>
                        <option value="FakePOP">Fake POP</option>
                        <option value="Scammer">Scammer</option>
                        <option value="Refusal2pay">Refusal to Pay</option>
                        <option value="Others">Others(Tell Us what happened)</option>
                    </select>
                    <textarea class="form-control auto-size m-b-10" id="color" name="message" style='display:none;'/></textarea>

                </div>



            <div class="formBottom">
                <input class="btn btn-alt" type="submit" value="REPORT"/>
            </div>
            </form>

<!--            <h1 style="color: red"> SEND A SUPPORT TICKET AFTER REPORTING TO ASCERTAIN YOUR CLAIMS </h1>-->
        </div>
    </div>
</div>



