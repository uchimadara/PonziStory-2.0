<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 border-right-blog">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-0">
        <div class="third-section-blog">
            <img src="<?=avatar($avatar)?>">
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 p-0">
        <div class="third-section-blog">
            <h2><?=$member?></h2>

            <h3><?=date(DEFAULT_DATE_FORMAT,$date)?> </h3>
        </div>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 p-0">
        <div class="third-section-blog">
            <h4>Amount Paid <br><span><?=money($paid)?></span></h4>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="third-sectio-blog-para">
            <p>
                <span>“</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?=nl2br(ellipsis($content, 250))?>
            </p>
        </div>
    </div>
</div>
