<div class="p-20 formContainer" id="walletForm">
    <h1>Update Your Account Details</h1>
    <? if (isset($instructions)) echo $instructions; ?>
    <form action="/member/update_wallet" method="post" enctype="multipart/form-data" name="bitcoin_walletForm" class="frm_ajax">

<!--        <input type="hidden" name="payment_code" value="">-->

<!--Note-->
        <div class="form-group"><label for="method_name">Bank Name</label><span class="red">*</span>
            <a class="tip" title="Enter the Bank Name."><i class="fa fa-question-circle"></i></a>
            <input class="form-control input-sm" type="text" maxlength="55" name="note" id="note" value="<?= $wallet->note ?>">
        </div>

<!--        Wallet Website-->
        <div class="form-group"><label for="method_name">Account Name</label><span class="red">*</span>
            <a class="tip" title="Enter the Account Name."><i class="fa fa-question-circle"></i></a>
            <input class="form-control input-sm" type="text" maxlength="255" name="method_name" id="method_name" value="<?= $wallet->method_name ?>">
        </div>

<!--        Wallet Address-->
        <div class="form-group"><label for="account">Account Number</label><span class="red">*</span>
            <a class="tip" title="Enter the Account Number."><i class="fa fa-question-circle"></i></a><input class="form-control input-sm" type="text" name="account" id="account" maxlength="255" value="<?= $wallet->account ?>">
        </div>

<!--payment_code-->
        <div class="form-group"><label for="method_name">Account Type</label><span class="red">*</span>
            <a class="tip" title="Enter the account Type."><i class="fa fa-question-circle"></i></a>
            <input class="form-control input-sm" type="text" maxlength="55" placeholder="Savings or Current" name="payment_code" id="payment_code" value="<?= $wallet->payment_code ?>">
        </div>


<!--        <div class="form-group"><label for="secret_answer">--><?//= $userData->secret_question ?><!--</label>-->
<!--            <a class="tip" title="Enter your secret answer"><i class="fa fa-question-circle"></i></a><input class="form-control input-sm" type="text" name="secret_answer" id="secret_answer" maxlength="" placeholder="Enter your secret answer">-->
<!--        </div>-->

        <div class="clear"></div>
        <h1 style="color: red"> BE WARNED AGAIN!! ONCE YOU SUBMIT THIS, YOU CAN NEVER CHANGE IT AGAIN</h1>
        <div class="formBottom">
            <input class="btn btn-alt m-r-10" type="submit" value="Submit">
            <a href="/back_office/accounts" class="btn btn-alt">Cancel</a>
        </div>

    </form>
</div>

<script>
    $('.tip').tooltipsy();
</script>