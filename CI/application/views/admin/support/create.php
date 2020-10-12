<div class="content">
    <div class="formContainer">
        <?=form_open(null, array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'ticketReplyFrm')); ?>
            <div>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" />
            </div>
            <div>
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" />
            </div>
            <div>
                <label for="message">Message</label>
                <textarea cols="1" rows="6" id="message" name="message" /></textarea>
            </div>
            <div>
                <span class="loading"><img src="<?=asset('images/loading.gif')?>" /></span>
                <input class="button_blue" type="submit" value="Submit Ticket" />
            </div>
        </form>
    </div>
</div>