<div class="formContainer">
    <?= form_open(site_url('adminpanel/users/edit_phgh2/'.$this->uri->segment(4).'/2'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'payment-form')); ?>


    <div class="form-group">
        <label for="from_account">Status</label>
        <input class="form-control" type="text" name="status" id="status" value="<?=$getAllGh->status?>"/>
    </div>
    <div class="form-group">
        <label for="amount">Amount </label>
        <input class="form-control" type="number" name="amount" id="amount" value="<?=$getAllGh->amount?>"/>
    </div>

    <div class="form-group">
        <label for="amount">Amount </label>
        <input class="form-control" type="number" name="rem_amount" id="rem_amount" value="<?=$getAllGh->rem_amount?>"/>
    </div>

    <hr class="whiter m-t-20 m-b-5" />
    <div class="formBottom">
        <input class="btn btn-alt fs16" type="submit" name="Save" value="Save" /> &nbsp;&nbsp;
        <button data-dismiss="modal" class="close-modal btn btn-alt fs16" type="button">Cancel</button>
    </div>
    </form>
</div>