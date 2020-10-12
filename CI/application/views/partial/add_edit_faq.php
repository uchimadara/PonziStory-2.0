<?=smiley_js()?>
<div class="container placeholder" id="addEditFaqFrm">
    <h1 class="roundAqua">
        <?if(!$faq):
            echo 'New FAQ';
            $faqId = NULL;
            $faqTitle = NULL;
            $faqText = NULL;
        else:
            echo 'Edit FAQ  ID: '.$faq->id;
            $faqId = $faq->id;
            $faqTitle = $faq->title;
            $faqText = $faq->text;
        endif;?>
    </h1>
    <?=form_open(site_url('member/add_edit_faq/' . $faqId), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'faqFrm')); ?>
        <div class="forumForms">
            <div><label for="Title">Title</label><input class="W99" type="text" id="title" name="title" value="<?=$faqTitle?>"/></div>
            <div>
                <input class="forumButton" type="button" data-open="b" data-close="/b" data-target="text" value="Bold"/>
                <input class="forumButton" type="button" data-open="i" data-close="/i" data-target="text" value="Italic"/>
                <input class="forumButton" type="button" data-open="u" data-close="/u" data-target="text" value="Underline"/>
            </div>
            <div class="clearFix">
                <span class="w460">
                    <label for="Text">Text: </label><textarea class="W99" name="text" id="text" cols="1" rows="8"><?=$faqText?></textarea>
                </span>
                <span class="smiley">
                    Click to insert a smiley!
                    <?=$smiley_table?>
                </span>
            </div>
            <input class="button_blue borderWhite" type="submit" value="Save" />
            <span class="loading"><img src="<?=asset('images/loading.gif')?>" /></span>
        </div>
    </form>
</div>