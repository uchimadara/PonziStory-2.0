<div id="new_news" class="modal fade" data-modal="modal-18">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">News: <?=$title?></h4>
            </div>
            <div id="newsModal" class="modal-body">
                <?=$content?>
            </div>
            <div id="newsModalFooter" class="modal-footer" style="display:none;">
                <button type="button" class="btn btn-sm" data-dismiss="modal">Close</button>
               <a href="<?= SITE_ADDRESS.'news/article/'.$slug ?>" class="btn btn-sm m-l-15">Go to article</a>
            </div>
        </div>
    </div>
</div>
