<? if (($msg = $this->session->flashdata('success')) !== FALSE) { ?>
    <div class="alert alert-success alert-dismissable fade in fs14">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?= $msg ?>
    </div>
<? } ?>
<? if (($msg = $this->session->flashdata('error')) !== FALSE) { ?>
    <div class="alert alert-danger alert-dismissable fade in fs14">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?= $msg ?>
    </div>
<? } ?>
<? if (($msg = $this->session->flashdata('warning')) !== FALSE) { ?>
    <div class="alert alert-warning alert-dismissable fade in fs14">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?= $msg ?>
    </div>
<? } ?>
<? if (isset($msgWarn)) { ?>
    <div class="alert alert-warning alert-dismissable fade in fs14">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?= $msgWarn ?>
    </div>
<? } ?>
<? if (($msg = $this->session->flashdata('info')) !== FALSE) { ?>
    <div class="alert alert-info alert-dismissable fade in fs14">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?= $msg ?>
    </div>
<? } ?>

