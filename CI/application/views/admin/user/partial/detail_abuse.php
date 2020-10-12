<div class="col-md-6">
    <div class="tile">
        <table class="table">
            <tr>
                <td>Accounts registered with the same IP as that user</td>
                <td><?= ($foundRegIP != '' ? $foundRegIP : 'Not found') ?></td>
            </tr>
            <tr>
                <td>Accounts logged in with the same IP as that user</td>
                <td><?= ($foundLogIP != '' ? $foundLogIP : 'Not found') ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="clear"></div>