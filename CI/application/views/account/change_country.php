<div class="container" id="changeCountryFrm">
    <h1>Change your country</h1>
    <?=form_open(site_url('member/change_country'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changeCtryFrm')); ?>
        <div class="clearFix">
            <div class="loginPart">
                <div><label>Current Country</label> <strong><?=$country?></strong></div>
                <div><label for="country">New Country</label> <?=form_dropdown('country', $countries, $country_id, 'id="country"')?></div>
            </div>
        </div>
        <div class="bottom">
            <input class="btn btn-alt m-r-5" type="submit" value="SAVE"/>
        </div>
    </form>
</div>