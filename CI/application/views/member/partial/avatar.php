<div class="col-md-12 p-20">

    <h1>Change Avatar</h1>

    <div class="col-md-2 m-b-10">
        <span id="displayImage"><img src="<?= avatar($userData->avatar) ?>"/></span>
    </div>
    <div class="col-md-10 formContainer">
        <form id="avatarForm" name="avatarForm" method="post" action="<?= base_url() ?>member/save_avatar" class="frm_ajax" callback="avatarSaved">

            <div id="imageEdit" class="formContainer">
                <div id="accordion">

                    <h4>Use one of these images</h4>

                    <div style="margin-bottom: 35px;">
                        <? foreach ($defaults as $file) { ?>
                            <div class="defaultAvatar">
                                <img src="<?= site_url('avatars/default/'.$file) ?>" class='avatar-image-select' data-select="<?= $file ?>" style="cursor:pointer;"/>

                                <? $checked = ('default/'.$file == $userData->avatar) ? 'checked="checked"' : ''; ?>
                                <br/><input class="avatar-select" type="radio" name="default[]" value="<?= $file ?>" id="<?= str_replace('.', '-', $file) ?>" <?= $checked ?> />
                            </div>
                        <? } ?>
                    </div>

                    <h4>Use your own image</h4>

                    <div>
                        <div class="alert alert-info m-t-10">
                            Maximum size: 125x125 (500KB)
                        </div>

                        <div class="form-group fileUpload" id="imageUpload">
                            <label for="banner" class="uploadLabel">
                                <i class="fa fa-cloud-upload"></i> Upload your image</label><br/>

                            <div id="fileSelect">
                                <input class="form-control input-sm upload" type="file" name="banner" id="banner" size="20"/>
                            </div>
                        </div>

                    </div>
                    <h4>Use an image from the web</h4>

                    <div>
                        <div class="alert alert-info m-t-10">
                            Maximum size: 125x125 (500KB)
                        </div>
                        <div class="form-group" id="imageLink">
                            <label for="imageURL">Image URL</label><br/>
                            <input type="text" class="form-control input-sm" name="imageURL" id="imageURL" maxlength="255" placeholder="http://" data-maxh="125" data-maxw="125"/>
                            <a href="javascript:void(0);" class="btn btn-xs btn-alt m-t-5" id="validateImage">Validate</a>
                        </div>
                    </div>
                </div>

                <hr class="whiter"/>
                <div id="imageLoading" class="loading hidden"></div>
                <div id="avatarBottom" class="formBottom">
                    <a href="<?= site_url('back_office/profile') ?>" class="btn btn-alt m-r-5">Done</a>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    $(function () {
        $("#accordion").accordion();
    });
    ajax_upload();
</script>
