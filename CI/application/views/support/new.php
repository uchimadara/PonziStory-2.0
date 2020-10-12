<div class="col-lg-6">
    <div class="tile">
        <h2 class="tile-title">New Support Ticket</h2>

        <? if ($isGuest): ?>
            <p>
                <strong>Existing members:</strong> Please <?= anchor('/login', 'login') ?> to your account and submit a support ticket from your back office.
                <br/>
                <a href="<?= SITE_ADDRESS ?>login" class="btn btn-alt">Login</a>
            </p>
        <? endif; ?>
        <div class="formContainer p-10">
            <?= form_open('support/add', array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'supportFrm')); ?>
                <div class="form-group">
                    <label for="email">Your Email Address</label>
                    <input type="text" id="email" name="email" class="form-control input-sm"/>
                </div>
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

