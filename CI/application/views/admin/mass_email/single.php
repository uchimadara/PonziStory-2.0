<div class="tile p-10">
    <h2>Email <?= $user->username.' &lt;'.$user->email.'&gt;' ?></h2>
    <p>
    Constants you can use in the email subject and body:<br/>
    [USERNAME] [EMAIL] [REF_LINK] [ID] [ACTIVATION_CODE]<br/>
    </p>
    <div class="formContainer" id="blasterForm">
        <?= form_open(site_url('adminpanel/mass_email/email_user/'.$user->id), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'massEmailFrm')); ?>

        <div class="form-group">
            <label for="from_name">From Name</label><input class="form-control" type="text" name="from_name" id="from" size="25" value="<?= SITE_NAME ?>"/><br/>

        </div>
        <div class="form-group">
            <label for="from_email">From Email</label><input class="form-control" type="text" name="from_email" id="from_email" size="25" value="<?= $this->config->item('admin_email', 'ion_auth') ?>"/><br/>

        </div>
        <div class="form-group">
            <label for="subject">Subject</label><input class="form-control" type="text" name="subject" id="subject" size="25"/><br/>

        </div>
        <div class="form-group">
            <label for="template">Template</label>
            <select class="form-control" name="template" id="template">
                <option value="default">Default</option>
                <option value="">None</option>
            </select>

        </div>
        <div class="form-group">
            <label for="message">Message</label><textarea class="htmlEdit" name="message" id="message"></textarea>

        </div>
        <div class="formBottom">
            <input class="btn btn-alt" type="submit" value="Send eMail"/>

        </div>

        </form>

    </div>
</div>
