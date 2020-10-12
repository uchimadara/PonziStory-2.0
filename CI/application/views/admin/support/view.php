<div class="mainRound">
    <div class="heading">
        <div class="floatLeft"><?= $title ?>
        </div>
        <div class="headerControl"></div>
        <div class="clear"></div>
    </div>
    <div class="body sortable pageable" id="user_body">
        <div class="content">
            <ul class="grey">
                <? if ($type == 'guest'): ?>
                    <li><span>Guest Email: </span><?= $ticket['ticket']->email ?></li>
                <? else: ?>
                    <li><span>Username: </span><?= $ticket['ticket']->username ?></li>
                <? endif; ?>
                <li><span>Subject: </span><?= $ticket['ticket']->subject ?></li>
                <li><span>Created: </span><?= date("jS M Y, H:i", $ticket['ticket']->created) ?></li>
                <li><span>Updated: </span><?= date("jS M Y, H:i", $ticket['ticket']->updated) ?></li>
                <li>
                    <span>Status:  </span><span class="<?= $ticket['ticket']->status ?>"><?= $ticket['ticket']->status ?></span>
                </li>
            </ul>

            <table id="tickets" class="support">
                <?
                foreach ($ticket['messages'] as $i => $message):
                    if ($type == 'guest')
                        $username = $message->username ? $message->username : 'Guest Wrote: ';
                    else
                        $username = $message->username ? $message->username : 'User Wrote: ';

                    $lvl     = $message->lvl_name;
                    $lvlDesc = $message->lvl_description;
                    if ($i != 0) {
                        ?>
                        <tr>
                            <td colspan="2" class="hr"></td>
                        </tr>
                    <?
                    }
                    ?>
                    <tr class="<?= $lvl ?>">
                        <td width="165px">
                            <strong><?= $username ?></strong><span><?= date('jS M Y, H:i', $message->created) ?></span>
                        </td>
                        <td valign="top" width="519px">
                            <?= nl2br($message->message) ?>
                        </td>
                    </tr>
                <? endforeach; ?>
            </table>

            <div class="formContainer">
                <?= form_open(null, array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'ticketReplyFrm')); ?>
                <label for="message">Reply</label><textarea name="message" rows="11" cols="20" id="message"></textarea>
                <input class="button_blue borderWhite" type="submit" value="Submit Answer"/>
                <span><input type="checkbox" name="status" value="closed"/>Close ticket</span>
                <span class="loading"><img src="<?= asset('images/loading.gif') ?>"/></span>
                </form>
            </div>
        </div>
    </div>
</div>
