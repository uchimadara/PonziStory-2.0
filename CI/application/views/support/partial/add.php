<div class="container support">
    <h1>Submit Support Ticket</h1>
    <? if ($isGuest): ?>
        <p>
            <strong>Existing members:</strong> Please <?= anchor('/login', 'login') ?> to your account and submit a support ticket from your back office.
            <br/>
            <a href="<?=SITE_ADDRESS?>login" class="btn btn-alt">Login</a>
        </p>
    <? endif; ?>
    <?=form_open('support/add', array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'supportFrm')); ?>
        <div class="clearFix">
            <div class="loginPart">
                <img src="<?=asset('images/support.png')?>" />
            </div>
            <div class="loginPart">
            <? if ($isGuest): ?>
                <div>
                    <label for="email">Your Email Address</label>
                    <input type="text" id="email" name="email" />
                    <div class="securityQuestion">* Confirmation of your ticket and reply notifications will be sent here.</div>
                </div>
            <? endif; ?>
                <div>
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" />
                </div>
                <div>
                    <label for="message">Message</label>
                    <textarea cols="1" rows="6" id="message" name="message" /></textarea>
                </div>
            </div>
        </div>
        <div class="bottom">
            <span class="loading"><img src="<?=asset('images/loading.gif')?>" /></span>
            <input class="button_blue" type="submit" value="Submit Ticket" />
        </div>
    </form>
</div>