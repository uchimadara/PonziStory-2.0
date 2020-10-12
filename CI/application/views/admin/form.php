<? if (isset($instructions)) { ?>
    <div class="tile-light p-10"><? echo $instructions; ?></div>
<? } ?>

<div class="p-10" id="<?= $formName ?>Form">
    <div class="formContainer">
        <form action="<?= (isset($formURL)) ? $formURL : '' ?>" method="post" enctype="multipart/form-data" name="<?= $formName ?>Form" class="frm_ajax">
            <?= $form ?>

            <div class="clear"></div>
            <div class="formBottom m-t-20">
                <input type="hidden" name="salt" value="<?= $salt ?>"/>
                <input class="btn btn-alt m-r-10" type="submit" value="Submit"/>
                <input class="btn btn-alt" type="reset" value="Reset"/>
            </div>
        </form>

    </div>
</div>
<? if ($ajax) { ?>
    <script>
        $('.tip').tooltipsy();

        if ($('.htmlEdit').length) {
            tinymce.init({
                selector: ".htmlEdit",
                theme: "modern",
                plugins: [
                    "advlist autolink lists link image charmap preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons paste textcolor"
                ],
                toolbar1: "undo redo | styleselect | bold italic fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "preview media | forecolor backcolor emoticons | source",
                image_advtab: true,
                menubar: true

            });
//            $('.htmlEdit').summernote({
//                toolbar: [
//                    //['style', ['style']], // no style button
//                    ['style', ['bold', 'italic', 'underline', 'clear']],
//                    ['fontsize', ['fontsize']],
//                    ['color', ['color']],
//                    ['para', ['ul', 'ol', 'paragraph']],
//                    //['height', ['height']],
//                    ['insert', ['picture', 'link']], // no insert buttons
//                    //['table', ['table']], // no table button
//                    //['help', ['help']] //no help button
//                ],
//                height: 200,
//                resizable: true
//            });

//            $('.message-options').click(function () {
//                $(this).closest('.modal').find('.note-toolbar').toggle();
//            });

        }
        //Date Only
        if ($('.date')[0]) {
            $('.date').datepicker({
                autoclose:true,
                pickTime: false,
                format:'yyyy-mm-dd'
            });
            $('td.day').on('click', function () {
                $('.date').datepicker('hide');
            });
        }

        $('.dp input:text').on('click', function () {
            $(this).closest('.date').find('.add-on i').click();
        });


    </script>

<? } ?>
