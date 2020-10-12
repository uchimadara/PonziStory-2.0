    <div class="col-xs-12 fs12">
        <h2 class="hbg-d"><?= $title ?></h2>
        <div class="clear"></div>

        <table class="table">
            <? if ($ticket->user_id > 0){ ?>

            <tr>
                <td>Member:</td>
                <td><?= $ticket->username ?></td>
            </tr>
           <? } else { ?>
            <tr>
                <td>Guest Email:</td>
                <td><?= $ticket->email ?></td>
            </tr>


            <? } ?>
            <tr>
                <td>Subject:</td>
                <td><?= $ticket->subject ?></td>
            </tr>
            <tr>
                <td>Status:</td>
                <td><?= $ticket->status ?></td>
            </tr>
            <tr>
                <td>Category:</td>
                <td><?= $ticket->category ?></td>
            </tr>
            <tr>
                <td>Priority:</td>
                <td><?= $ticket->priority ?></td>
            </tr>
        </table>
        <table id="tickets" class="support">
            <?
            foreach ($messages as $i => $message):
                $user    = $message->username ? $message->username : 'You wrote:';
                $lvl     = $message->lvl_name;
                $lvlDesc = $message->lvl_description;
                if ($message->username && $message->username == $userData->username) {
                    $commentClass = '';
                } else {
                    $commentClass = 'admin';
                }

                if ($i != 0):
                    ?>
                <? endif; ?>
                <tr class="<?= $lvl ?>">
                    <td colspan="2">
                        Date: <?= date('d-M-Y H:i', $message->created) ?>
                    </td>
                </tr>
                <tr class="<?= $lvl ?>">
                    <td class="user"><?= $user ?><span class="<?= $commentClass?>"><?= $lvlDesc ?></span></td>
                    <td class="comment">
                        <?= nl2br($message->message) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="hr"></td>
                </tr>
            <? endforeach; ?>
        </table>
    </div>

<div class="col-xs-12">
        <h2 class="hbg-d">Reply</h2>
        <div class="clear"></div>

        <div class="formContainer p20">
        <?= form_open('/support/reply/'.$ticket->code, array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'supportFrm')); ?>
        <div class="form-group">

            <label for="message">Your message</label>
            <textarea class="form-control auto-size" id=" message" name="message"></textarea>
        </div>
        <? if ($ticket->status == 'open'): ?>
            <div class="checkbox m-b-20">
                <label>
                    <input id="closeTicket" type="checkbox" name="status" value="closed"/>Close ticket
                </label>
            </div>
        <? else: ?>
            <div class="securityQuestion">
                * Replying to a closed ticket will change its status to open, and you should receive a reply within 24 hours.
            </div>
        <? endif; ?>
        <div class="formBottom">
            <input type="submit" class="btn btn-alt" value="Submit Reply"/>

        </div>
        </form>
    </div>
</div>
