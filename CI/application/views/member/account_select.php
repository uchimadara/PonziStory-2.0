<h1>Your Account Details

    <h3 style="font-weight: bold;color: green;text-align: center"><a href="/back_office">CLICK HERE TO CONTINUE</a> </h3>
<!--    --><?// if (count($balances) < MAX_METHODS) { ?><!-- -->
    <? if (!count($balances) > 0 ) { ?>
    &nbsp;&nbsp;
        <a class="btn btn-alt replace" data-div="page-content" href="<?= site_url('member/form/ngn_wallet') ?>">
        Add Your Account
    </a>
    <? } ?>
</h1>
    <? $used = array();

    if ($balances):  ?>
        <div class="col-md-12">
            <table class="rwd-table">

                <? foreach ($balances as $balance) { ?>
                    <tr>
                        <td data-th="Name"><?= $balance->method_name ?></td>
                        <td data-th="Number"><?= $balance->account ?></td>
                        <td data-th="B.Name"><?= $balance->note ?></td>
                        <td data-th="Type"><?= $balance->payment_code ?></td>
<!--                        <td data-th="Action">-->
<!--                            <a class="replace m-r-10" data-div="page-content" title="Change Account" href="--><?//= site_url('member/update_wallet') ?><!--">-->
<!--                                <i class="fa fa-pencil-square-o fs20"></i>-->
<!--                            </a>-->
<!--                        </td>-->
                    </tr>
                <? } ?>
            </table>
        </div>
        <div class="clear"></div>
    <? endif; ?>


