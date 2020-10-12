<div class="formContainer p-10" id="socialNetworkForm">
    <?= form_open(site_url('member/add_social'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changePwdFrm')); ?>
    <div class="loginPart">
        <div class="form-group">
            <label for="name">Social Network</label>
            <?= form_dropdown('name', $this->picklist->select_values('social_network_list'), '', 'class="form-control input-sm"') ?>
        </div>
        <div class="form-group">
            <label for="link">URL</label>
            <input class="form-control input-sm" type="text" value="http://" name="link"/>
        </div>
    </div>
    <div class="formBottom">
        <input class="btn btn-alt m-r-5" type="submit" value="Save"/>
    </div>
    </form>
</div>