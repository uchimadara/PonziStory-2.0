<div class="mainRound">
    <div class="heading">
        <div class="floatLeft"><?= $title ?>
            <span class="headingButtons">
                <a href="<?= site_url('adminpanel/users/detail/'.$user->id) ?>" class="replace" data-div="user_body">Profile</a>
                <a href="<?= site_url('adminpanel/users/campaigns/'.$user->id) ?>">Campaigns</a>
                <a href="<?= site_url('adminpanel/users/history/'.$user->id.'/all') ?>">History</a>
                <a href="<?= site_url('adminpanel/users/summary/'.$user->id.'/all') ?>">Summary</a>
                <a href="<?= site_url('adminpanel/users/viewList/list_user_tickets/'.$user->id) ?>" class="replace" data-div="user_body">Tickets</a>
            </span>
        </div>
        <div class="headerControl"></div>
        <div class="clear"></div>
    </div>
    <div class="body sortable pageable" id="user_body">
        <div class="formContainer">
            <? if ($user->banned == 1): ?>
                <p style="color: red;"><strong>This user is banned!</strong></p>
            <? endif; ?>
            <?= form_open(site_url('adminpanel/users/detail/'.$user->id), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'userUpdate')); ?>
            <table>
                <tr>
                    <td><strong>User ID</strong></td>
                    <td><?= $user->id ?></td>
                </tr>
                <tr>
                    <td><label for="username"><strong>Username</strong></label></td>
                    <td><input type="text" name="username" id="username" value="<?= $user->username ?>"/></td>
                </tr>
                <tr>
                    <td><label for="password"><strong>New Password</strong></label></td>
                    <td><input type="text" name="password" id="password"/></td>
                </tr>
                <tr>
                    <td><label for="email"><strong>Current Email Address</strong></label></td>
                    <td><input type="text" name="email" id="email" value="<?= $user->email ?>"/></td>
                </tr>
                <tr>
                    <td><label for="day"><strong>Date of Birth</strong></label></td>
                    <td>
                        <select id="day" name="day">
                            <?
                            for ($i = 1; $i < 32; $i++)
                                echo '<option value="'.$i.'"'.($i == date('d', strtotime($user->date_of_birth)) ? ' selected' : '').'>'.$i.'</option>';
                            ?>
                        </select>
                        <select name="month">
                            <?
                            $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                            foreach ($months as $i => $month)
                                echo '<option value="'.($i + 1).'"'.(($i + 1) == date('m', strtotime($user->date_of_birth)) ? ' selected' : '').'>'.$month.'</option>';
                            ?>
                        </select>
                        <select name="year">
                            <?
                            for ($i = 1900; $i < date('Y') + 1; $i++)
                                echo '<option value="'.$i.'"'.($i == date('Y', strtotime($user->date_of_birth)) ? ' selected' : '').'>'.$i.'</option>';
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="country"><strong>Country</strong></label></td>
                    <td><?= form_dropdown('country', $countries, $user->country, 'id="country"') ?></td>
                </tr>
                <tr>
                    <td valign="top"><strong>Groups</strong></td>
                    <td>
                        <? foreach ($groups as $groupId => $description): ?>
                            <input type="checkbox" value="<?= $groupId ?>" name="group[]" <?= (isset($userGroups[$groupId]) ? ' checked' : '') ?> /> <?= $description ?>
                            <br/>
                        <? endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><strong>Payment Methods</strong>
                        <a href="<?= site_url('adminpanel/users/payment/'.$user->id) ?>">edit</a></td>
                    <td>
                        <? if (count($methods)): ?>
                            <table>
                                <? foreach ($methods as $method): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= site_url('adminpanel/cashier/deposit/0/'.$user->id.'/'.$method->payment_code) ?>"><img src="<?= asset('images/currencies/'.$method->payment_code.'.gif') ?>" title="<?= $method->name ?>"/></a>
                                        </td>
                                        <td><?= anchor('adminpanel/users/adjust/'.$user->id.'/'.$method->payment_code, money($method->balance)) ?></td>
                                    </tr>
                                <? endforeach; ?>
                            </table>
                        <? else: ?>
                            <em>none</em>
                        <? endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Shares</strong></td>
                    <td><?= number_format($user->shares) ?></td>
                </tr>
                <tr>
                    <td><strong>IP Address</strong></td>
                    <td><?= long2ip($user->ip_address) ?></td>
                </tr>
                <tr>
                    <td valign="top"><strong>Last 5 different login IP's</strong></td>
                    <td>
                        <? foreach ($last5Ips as $last5Ip): ?>
                            <?= long2ip($last5Ip->user_ip) ?> - <?= date('d/m/Y - H:i:s', $last5Ip->date) ?><br/>
                        <? endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="active"><strong>Active</strong></label></td>
                    <td>
                        <input type="checkbox" value="1" name="active" id="active" <?= ($user->active == 1 ? ' checked' : '') ?> />
                    </td>
                </tr>
                <tr>
                    <td><label for="banned"><strong>Banned</strong></label></td>
                    <td>
                        <input type="checkbox" name="banned" value="1" id="banned" <?= ($user->banned == 1 ? ' checked' : '') ?>/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input class="button_blue borderWhite" type="submit" name="update" value="Update"/>
                        <span class="loading"><img src="<?= asset('images/loading.gif') ?>"/></span>
                    </td>
                </tr>
            </table>

            <?= form_hidden('user_id', $user->id) ?>
            </form>
        </div>

        <div class="formContainer">
            <h3>Refered by:</h3>

            <p><?= isset ($referrer['L1']) ? '<b>'.$referrer['L1']->username.'</b>'.(isset ($referrer['L2']) ? ' and <b>'.$referrer['L2']->username.'</b>' : '') : '' ?></p>

            <p></p><?= anchor('adminpanel/users/referrals/'.$user->id, 'Referrals') ?></p>
        </div>
    </div>
</div>

