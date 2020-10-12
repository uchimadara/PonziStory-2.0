
<?php if($valid > 0){ ?>



<? if (isset($openTickets) && !empty($openTickets)){ ?>
    <div class="col-lg-5">
        <div class="tile">
            <h2 class="tile-title">Open Support Tickets (<?= count($openTickets) ?>)</h2>
            <table class="table">

                <caption style="color: orangered">Your Ticket is open, the admins will reply you shortly, Stay Tuned!!!</caption<br>
                <caption style="color: orangered">It's an offense to reply multiple times on different threads on the same issue</caption>


                <tr>
                    <th class="W50">Subject</th>
                    <th class="W25">Last Reply By</th>
                    <th class="W25">Time Since Last Reply</th>
                </tr>
                <? foreach ($openTickets as $ticket): ?>
                    <tr>
                        <td><?= $ticket->updated > $ticket->last_read ? '<img src="'.asset('images/icons/star.png').'" title="New reply" /> ' : '' ?>
                            <a href="<?= site_url('/support/'.$ticket->code) ?>"><?= ellipsis($ticket->subject, 50) ?></a>
                        </td>
                        <td><?= ($ticket->responder_id == $userId ? 'You' : $ticket->username) ?></td>
                        <td><?= timespan($ticket->updated, now()) ?></td>
                    </tr>
                <? endforeach; ?>
            </table>
        </div>
    </div>


    <? } elseif (isset($closedTickets)  && !empty($closedTickets)){ ?>
        <div class="col-lg-5">
            <div class="tile">
                <h2 class="tile-title">Closed Support Tickets (<?= count($closedTickets) ?>)</h2>

                <table class="table">
                    <p style="color: orangered;font-weight: bold">You are entitled to 1 free support ticket every month, We will Charge N250 for per ticket after the first free one of the month</p>

                    <caption>REPLY TO A TICKET TO RE-OPEN IT</caption>
                    <caption style="color: orangered">You have Something New you wanna discuss?? Reply the Ticket Below And Continue it</caption>
                    <br>
                    <caption style="color: orangered">It's an offense to reply multiple times on different threads on the same issue</caption>

                    <tr>
                        <th class="W50">Subject</th>
                        <th class="W25">Last Reply By</th>
                        <th class="W25"></th>
                    </tr>
                    <? foreach ($closedTickets as $ticket): ?>
                        <tr>
                            <td><?= $ticket->updated > $ticket->last_read ? '<img src="'.asset('images/icons/star.png').'" title="New reply" /> ' : '' ?>
                                <a href="<?= site_url('/support/'.$ticket->code) ?>"><?= ellipsis($ticket->subject, 50) ?></a>
                            </td>
                            <td><?= ($ticket->responder_id == $userId ? 'You' : $ticket->username) ?></td>
                            <td></td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
        </div>
    <? } else { ?>



<div class="col-lg-5">
    <div class="tile">
        <h2 class="tile-title">New Support Ticket</h2>
        <p style="color: orangered;font-weight: bold">You are entitled to 1 free support ticket every month, We will Charge N250 for per ticket after the first free one of the month</p>


        <h3 style="color: red">SUPPORT TICKET IS FOR ONLY TECHNICAL ISSUES AFFECTING YOUR ACCOUNT ..eg lock,fake pop, confirmation,guilder etc (For Enquiry.GOTO LIVECHAT) </h3>

<br>
        <h2 style="color: red">You are entitled to 1 free support ticket every month, We will Charge N250 for per ticket after the first free one of the month</h2>


        <div class="formContainer p-10">
            <?= form_open('support/add', array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'supportFrm')); ?>
            <div class="form-group">
                <label for="category">Category</label>
                <?= form_dropdown('category', $this->picklist->select_values('support_category_list'), '', 'class="form-control input-sm"') ?>
            </div>
            <div class="form-group">
                <label for="priority">Priority</label>
                <?= form_dropdown('priority', $this->picklist->select_values('support_priority_list'), '', 'class="form-control input-sm"') ?>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input class="form-control input-sm" type="text" id="subject" name="subject"/>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control auto-size m-b-10" id="message" name="message"/></textarea>
            </div>
            <div class="formBottom">
                <input class="btn btn-alt" type="submit" value="Submit Ticket"/>
            </div>
            </form>
        </div>
    </div>
</div>

<? } ?>
<? }else{ ?>

    <h2 style="color: orangered">Only Fully committed Financial Members can Access Our Support. Goto Livechat. Thanks</h2>


<? } ?>

<div class="clear"></div>
