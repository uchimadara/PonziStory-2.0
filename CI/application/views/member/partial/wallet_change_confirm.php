    <h1>Confirm Your Account Wallet Change</h1>
    <div class="alert alert-info fs16">
       <div class="alert alert-danger"><i class="fa fa-exclamation-triangle fs20 red" aria-hidden="true"></i>
          &nbsp;&nbsp; <b> Your wallet has not changed!</b></div>
        <span class="fs16">
                    A code has been sent to your email address that you must enter below in order to change your wallet address.
        Check your email and enter the code below.

        <br/><br/>
        Clicking cancel will cancel this change request.

        </span>
    </div>
    <form action="/member/update_wallet_confirm" method="post" enctype="multipart/form-data" name="bitcoin_walletForm" class="frm_ajax">

        <input type="hidden" name="method_name" value="<?=$method_name?>" />

        <div class="form-group"><label for="uuid">Enter the confirmation code here</label><span class="red">*</span>

            <input class="form-control input-sm" type="text" maxlength="255" name="uuid" value="">
        </div>

        <div class="clear"></div>
        <div class="formBottom">
            <input class="btn btn-alt m-r-10" type="submit" value="Submit">
            <a href="/member/update_wallet_cancel" class="btn btn-alt">Cancel</a>
        </div>

    </form>

