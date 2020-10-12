<div class="col-md-12 p-20">

    <h1><?= ($formName == 'donation_method') ? 'Payment Method Account' : wordify($formName) ?></h1>
    <? if (isset($instructions)) {
        echo $instructions;
    } ?>

    <div id="<?= $formName ?>Form">
        <div class="formContainer">
            <form action="<?= (isset($formURL)) ? $formURL : '' ?>" method="post" enctype="multipart/form-data" name="<?= $formName ?>Form" class="frm_ajax">
                <?= $form ?>

                <div class="clear"></div>
                <div class="formBottom">
                    <input type="hidden" name="salt" value="<?= $salt ?>"/>
                    <input class="btn btn-alt m-r-10" type="submit" value="Submit"/>
                    <input class="btn btn-alt" type="reset" value="Reset"/>
                </div>
            </form>

        </div>
    </div>
</div>
<div class="clear"></div>

<? if ($ajax) { ?>
    <script>
        $('.tip').tooltipsy();

        //Date Only
        if ($('.date')[0]) {
            $('.date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        }

        //Time only
        if ($('.time-only')[0]) {
            $('.time-only').datetimepicker({
                pickDate: false
            });
        }

        //12 Hour Time
        if ($('.time-only-12')[0]) {
            $('.time-only-12').datetimepicker({
                pickDate: false,
                pick12HourFormat: true
            });
        }

        $('.datetime-pick input:text').on('click', function () {
            $(this).closest('.datetime-pick').find('.add-on i').click();
        });

        if ($('input[name="screenshotImageURL"]').length) {
            initScreenshot('<?=$formName?>-form');
        }
    </script>

<? } ?>
