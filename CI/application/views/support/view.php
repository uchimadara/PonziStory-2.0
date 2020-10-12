<div id="register" tabindex="-1" class="fs15 p20">
    <h2 class="tile-title" id="registerLabel"><?= $title ?></h2>

    <div class="well">
        <dl class="dl-horizontal">
            <? if ($ticket->user_id > 0) { ?>
                <dt>Member:</dt>
                <dd><?= $ticket->username ?></dd>
            <? } else { ?>
                <dt>Guest email:</dt>
                <dd><?= $ticket->email ?></dd>
            <? } ?>

            <dt>Subject:</dt>
            <dd><?= $ticket->subject ?></dd>

            <dt>Status:</dt>
            <dd><?= $ticket->status ?></dd>

            <dt>Category:</dt>
            <dd><?= $ticket->category ?></dd>

            <dt>Priority:</dt>
            <dd><?= $ticket->priority ?></dd>
        </dl>
    </div>


    <div id="tickets" class="container-fluid">
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

            <div class="row">
                <div class="col-sm-3">
                    <b><?= $user ?></b><br>
                    <? if ($lvlDesc) { ?>
                        <small><?= $lvlDesc ?></small>
                    <? } ?>
                </div>
                <blockquote class="col-sm-9 fs14">
                    <p><?= nl2br($message->message) ?></p>
                    <small><?= date('d-M-Y H:i', $message->created) ?></small>
                </blockquote>
            </div>

        <? endforeach; ?>
    </div>

    <div class="container-fluid">
        <h3>Reply</h3>

        <div class="formContainer">
            <?= form_open('/support/reply/'.$ticket->code, array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'supportFrm')); ?>
            <div class="form-group">

                <label for="message"></label>
                <textarea class="form-control auto-size" id=" message" name="message"></textarea>
            </div>
            <? if ($ticket->status == 'open'): ?>
                <div class="form-group m-b-20">
                    <input id="closeTicket" type="checkbox" name="status" value="closed" class=""/> &nbsp;
                    <label for="closeTicket">Close Ticket</label>

                </div>
            <? else: ?>
                <div class="securityQuestion m-b-10">
                    * Replying to a closed ticket will change its status to open, and you should receive a reply within 24 hours.
                </div>
            <? endif; ?>
            <div class="formBottom">
                <input type="submit" class="btn btn-alt" value="Submit Reply"/>

            </div>
            </form>
        </div>
    </div>
</div>