<h2>Referral Commissions</h2>

<div class="col-lg-12">
    <div class="tile p-10">
        <div class="formContainer">
            <? if (!empty($refComm)) {
                echo form_open(SITE_ADDRESS.'adminpanel/admin_settings/ref_comm', array('method' => 'post', 'id' => 'rcForm'));
                ?>

                <table class="table">
                    <tr>
                        <th>NAME</th>
                        <th>SORTING</th>
                        <? for ($i = 1; $i <= $maxLevels; $i++) { ?>

                            <th>Level <?= $i ?> (%)</th>
                        <? } ?>
                    </tr>

                    <?
                    foreach ($refComm as $rc) {
                        ?>
                        <tr>
                            <td>
                                <input type="hidden" name="id[]" value="<?= $rc->id ?>"/>
                                <input type="text" class="form-control input-sm" name="name[<?= $rc->id ?>]" value="<?= $rc->name ?>" size="20"/>
                            </td>
                            <td>
                                <input type="text" class="form-control input-sm" name="sorting[<?= $rc->id ?>]" value="<?= $rc->sorting ?>" size="3"/>
                            </td>
                            <? for ($i = 1; $i <= $maxLevels; $i++) {
                                $idx = "$i"; ?>

                                <td>
                                    <input type="text" class="form-control input-sm" name="level<?= $i ?>[<?= $rc->id ?>]" value="<?= ($rc->levels[$i]) ?>" size="3"/>
                                </td>
                            <? } ?>
                        </tr>
                        <?
                    } ?>
                </table>
                <div class="formBottom">
                    <input class="btn btn-alt" type="submit" value="UPDATE"/>
                </div>
                </form>

            <? } ?>
        </div>

        <h2>Add New</h2>

        <div class="formContainer">
            <?= form_open('adminpanel/admin_settings/add_ref_comm', array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'refCommAddFrm')); ?>

            <div class="form-group">
                <label for="name">Name:</label><br/>
                <input class="form-control input-sm" type="text" name="name" id="name" value=""/><br/>

            </div>
            <div class="form-group">
                <label for="sorting">Sorting:</label><br/>
                <input class="form-control input-sm" type="text" name="sorting" id="sorting" value=""/><br/>

            </div>

            <? for ($i = 1; $i <= $maxLevels; $i++) { ?>
                <div class="form-group">
                    <label for="level[<?= $i ?>]">Level <?= $i ?> (%):</label><br/>
                    <input class="form-control input-sm" type="text" name="level[<?= $i ?>]" value=""/><br/>

                </div>
            <? } ?>

            <div class="formBottom">
                <input class="btn btn-alt" type="submit" value="Add"/>
            </div>
            </form>
        </div>

    </div>
</div>

