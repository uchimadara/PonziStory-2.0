<h1>Add Setting</h1>
<div class="styleshow m-b-20">
    <a href="<?= site_url('adminpanel/admin_settings') ?>">Back to Settings</a>
</div>

<div class="col-lg-4">
    <div class="tile p-10">
        <div class="formContainer">
            <?= form_open('', array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'SettingsAddFrm')); ?>

            <div class="form-group">
                <label for="name">Group:</label>
                <?= form_dropdown('module', $this->picklist->select_values('settings_module_list'), '', ' class="form-control"') ?>


            </div>
            <div class="form-group">
                <label for="label">Label:</label>
                <input class="form-control" type="text" name="label" id="label" value=""/>

            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input class="form-control" type="text" name="description" id="description" value=""/>

            </div>
            <div class="form-group">
                <label for="format">Format:</label>
                <input class="form-control" type="text" name="format" id="format" value=""/>

            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input class="form-control" type="text" name="name" id="name" value=""/>

            </div>
            <div class="form-group">
                <label for="name">Value:</label>
                <input class="form-control" type="text" name="value" id="value"/>

            </div>


            <div class="formBottom">
                <input class="btn btn-alt" type="submit" value="Submit!"/>

            </div>
            </form>
        </div>
    </div>
</div>
