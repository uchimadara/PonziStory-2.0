        <div class="tab-content">

            <? for ($level = 1; $level < count($tree); $level++) { ?>
                <div class="tab-pane <?=($level == 1) ? "active" : "" ?>" id="level<?=$level?>">
                    <table class="table">
                        <tr>
                            <th>Username</th>
                            <th>Signed Up</th>
                            <th>Stagge</th>
                            <th>Account Expires</th>
                            <th>Referrals</th>
                            <th>Came From</th>
                        </tr>
                        <? foreach ($tree[$level] as $user) { ?>

                         <tr>
                             <td><?=$user->username?></td>
                             <td><?=date(DEFAULT_DATE_FORMAT, $user->created_on)?></td>
                             <td><?=$user->account_level?></td>
                             <td><?=date(DEFAULT_DATE_FORMAT, $user->account_expires)?></td>
                             <td><?=number_format($user->referrals)?></td>
                             <td><?=$user->came_from?></td>
                         </tr>

                    <? } ?>
                    </table>
                </div>

            <? }  ?>
        </div>

<? if ($ajax) { ?>
    <script type="text/javascript">
        $('.tab').tabs();
    </script>
<? } ?>
