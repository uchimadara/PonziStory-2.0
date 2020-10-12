<div class="form-group fs16 mBottom5"><b>Banner</b> (468x60)</div>

<div class="mTop10" id="listingBannerImage">
    <label for="bannerImageURL">Enter URL:</label><br/>
    <input type="text" name="bannerImageURL" id="bannerImageURL" class="form-control input-sm" value="http://" onblur="checkBannerMultisizeURL(468);"/>
    <a href="javascript:void(0);" class="btn btn-alt btn-sm">Validate</a>
</div>

<div class="mTop10">
    <span id="sampleBannerImg">
        <img src="<?= asset('images/upload_banner_468x60.gif') ?>"/>
        <br/>.gif, .jpg, .png
    </span>

    <div id="bannerImgUploadLoading" class="loading hidden"></div>
    <span id="listing_banner_image" class="hidden"></span>
</div>
<br/>
