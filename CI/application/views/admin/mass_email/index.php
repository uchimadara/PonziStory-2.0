<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">Email Members</h2>

        <div class="p-10">
            Constants you can use in the email subject and body:<br/>
            [USERNAME] [EMAIL] [REF_LINK] [BALANCE] <br/>

            <div class="formContainer" id="blasterForm">
                <?= form_open(site_url('adminpanel/mass_email'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'massEmailFrm')); ?>
                <div class="form-group">
                    <label for="from_name">From Name</label><input class="form-control" type="text" name="from_name" id="from" value="<?= SITE_NAME ?>"/><br/>

                </div>
                <div class="form-group">
                    <label for="from_email">From Email</label><input class="form-control" type="text" name="from_email" id="from_email" value="<?= $this->config->item('admin_email', 'ion_auth') ?>"/><br/>

                </div>
                <div class="form-group">
                    <label for="subject">Subject</label><input class="form-control" type="text" name="subject" id="subject" /><br/>

                </div>
                <div class="form-group">
                    <label for="email_options">Email Options</label>
                    <select class="form-control" name="email_options" id="email_options">
                        <option value="<?= EMAIL_NEWS ?>">System News</option>
                        <option value="<?= EMAIL_NEW_FEATURES ?>">New Feature Email</option>
                        <option value="<?= EMAIL_ALL ?>">Email All Users</option>
                    </select>

                </div>
                <div class="form-group">
                    <label for="account_level">Active</label>
                    <?= form_dropdown('active', array('1' => 'Yes', '0' => 'No'), '1', ' class="form-control"') ?>

                </div>
                <div class="form-group">
                    <label for="account_level">Account Level</label>
                    <?= form_dropdown('account_level', $account_levels, '', ' class="form-control"') ?>

                </div>
                <div class="form-group">
                    <label for="member_group">Special Group</label>
                    <?= form_dropdown('member_group', array(''=>'','lifetime' =>'Lifetime Members', 'shareholders' => 'Shareholders'), '', ' class="form-control"') ?>

                </div>
                <div class="form-group">
                    <label for="send_date">Send Date</label>

                    <div class="input-append datetime date-only">
                        <input data-format="dd-MM-yyyy" type="text" class="form-control input-sm dp" name="send_date"/>
                        <span class="add-on"><i class="fa fa-calendar dpIcon"></i></span>
                    </div>
                </div>
                <div class="form-group"
                <div class="input-icon datetime-pick time-only">
                    <label for="send_time">Send Time</label>
                    <input name="send_time" data-format="hh:mm" type="text" class="form-control input-sm"/>
                                <span class="add-on">
                        <span class="add-on"><i class="fa fa-clock-o dpIcon"></i></span>
                                </span>
                </div>

                <div class="form-group">
                    <label for="country">Send to users from:</label>
                    <?= form_dropdown('country', $countries, array(), 'class="form-control" id="country"') ?>

                </div>
                <div class="form-group">
                    <label for="template">Template</label>
                    <select name="template" id="template" class="form-control">
                        <option value="default">Default</option>
                        <option value="">None</option>
                    </select>

                </div>
                <div class="form-group">
                    <label for="message">Message</label><textarea class='htmlEdit' name="message" rows="11" cols="20" id="message"></textarea>
                </div>

                <div class="bottom">
                    <input class="btn btn-alt" type="submit" value="Send Mass Mail"/>

                </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>

</script>
