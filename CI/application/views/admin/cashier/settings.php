<table border="0" cellspacing="10" cellpadding="10">
    <tr>
<? foreach ($billing as $operation=>$bills):?>
    <? foreach ($bills as $type=>$bill): ?>
        <td>
            <div class="formContainer">
                <b><?=UCWords($operation) . ' ' . strtoupper($type)?></b>
                <?=form_open(site_url('adminpanel/cashier/settings/' . $code), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'set')); ?>

                    <input type="hidden" name="type" value="<?=$type?>" />
                    <input type="hidden" name="operation" value="<?=$operation?>" />

                    <label for="percent">Percent</label><br />
                    <input type="text" name="percent" value="<?=$bill ? $bill->percent : ''?>" id="percent" /><br />

                    <label for="fixed">Fixed</label><br />
                    <input type="text" name="fixed" value="<?=$bill ? $bill->fixed : ''?>" id="fixed" /><br />

                    <label for="max">Max</label><br />
                    <input type="text" name="max" value="<?=$bill ? $bill->max : ''?>" id="max" /><br />

                    <input type="submit" value="Update" class="button_blue"/>
                    <span class="loading"><img src="<?=asset('images/loading.gif')?>" /></span>

                </form>
            </div>
        </td>
    <?endforeach;?>
<?endforeach;?>
    </tr>
</table>