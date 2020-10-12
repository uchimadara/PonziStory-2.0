<div class="formContainer" id="changeCountryFrm">
    <?= form_open(site_url('member/change_country'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changeCtryFrm')); ?>
    <div class="clearFix">
        <div class="loginPart">
            <div><label>Current Country</label> <strong><?= $this->picklist->select_value('country_list', $userData->country_id) ?></strong></div>
            <div>
                <label for="country">New Country</label>
                <?= form_dropdown('country', $this->picklist->select_values('country_list'), $userData->country_id, 'id="country" class="form-control m-b-10"') ?>
            </div>
            <div class="form-group">
                <label for="secret_answer"><?= $userData->secret_question ?></label>
                <input class="form-control input-sm" type="text" value="" name="secret_answer" id="secret_answer"/>
            </div>
        </div>
    </div>
    <div class="formBottom">
        <input class="btn btn-alt m-r-5" type="submit" value="SAVE"/>
    </div>
    </form>
</div>