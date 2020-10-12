<div class="formContainer" id="changeCountryFrm">
    <?= form_open(site_url('member/change_address'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changeCtryFrm')); ?>
    <div class="clearFix">
        <div class="loginPart">
            <div><label>Current Address</label> <br><strong><?= $userData->address.'<br>'.$userData->postal_code.' '.$userData->city.'<br>'.$userData->state ?></strong>
            </div>
            <div class="form-group">
                <label for="address">New Address</label>
                <input class="form-control input-sm" type="text" id="address" name="address"/>
            </div>
            <div class="form-group">
                <label for="city">New City</label>
                <input class="form-control input-sm" type="text" id="city" name="city"/>
            </div>
            <div class="form-group">
                <label for="state">New Region / State</label>
                <input class="form-control input-sm" type="text" id="state" name="state"/>
            </div>
            <div class="form-group">
                <label for="postal_code">New Postcode / Zip</label>
                <input class="form-control input-sm" type="text" id="postal_code" name="postal_code"/>
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