<div class="form-group fs16 mBottom5"><b>Banner</b> (728x90)</div>

<div class="mTop10" id="listingBannerImage">
    <label for="bannerImageURL">Enter URL:</label><br/>
    <input type="text" name="bannerImageURL" id="bannerImageURL" class="form-control input-sm" value="http://" onblur="checkBannerMultisizeURL(728);"/>
    <a href="javascript:void(0);" class="btn btn-alt btn-sm">Validate</a>
</div>

<div class="mTop10">
    <span id="sampleBannerImg">
        <img src="<?= asset('images/upload_banner_728x90.gif') ?>"/>
        <br/>.gif, .jpg, .png
    </span>

    <div id="bannerImgUploadLoading" class="loading hidden"></div>
    <span id="listing_banner_image" class="hidden"></span>
</div>
<br/>
