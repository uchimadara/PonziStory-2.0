<div id="uplineDiv">
    <div style="border-bottom: 1px solid #d8d8d8; margin-bottom:20px;padding-bottom:20px;">
        <div class="formContainer">
            <form action="<?= SITE_ADDRESS ?>adminpanel/users/reassign_upline" method="post" class="frm_ajax" enctype="multipart/form-data" name="uplineForm">
                <input type="hidden" name="user_id" value="<?= $upline[count($upline) - 1]->id ?>"/>

                <div class="form-group">
                    <label for="upline">Enter a username to reassign referrer:</label>
                    <input class="form-control input-sm" type="text" name="upline" id="upline" value=""/>
                </div>
                <div class="formBottom">
                    <input type="submit" value="Submit" class="btn btn-alt"/>

                </div>
            </form>
        </div>
    </div>

    <h3>Upline Tree</h3>
    <? $i = 0;
    foreach ($upline as $u) { ?>

        <div style="margin-left:<?= $i ?>px;width: 300px;margin-bottom: 10px;">
            <a href="<?= SITE_ADDRESS ?>admin/user/<?= $u->id ?>" style="color: #C3D7FF;"><?= $u->username ?></a>

            <div class="fs12" style="border:1px solid #d8d8d8; padding:5px;">
                <table class="fs14">
                    <tr>
                        <td>Name:</td>
                        <td><?= $u->first_name ?> <?= $u->last_name ?></td>
                    </tr>
                    <tr>
                        <td>Account level:</td>
                        <td><?= $u->account_level ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <? $i += 12;
    } ?>
</div>