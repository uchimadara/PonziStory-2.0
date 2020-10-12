<div class="showHide m-b-20 alert alert-info" id="text_ad_instructions">
    <h3>Instructions</h3>
    <ul>
        <li>Enter a headline (25 chars MAX) and body (60 chars MAX).</li>
        <li>Ads with all capital letters will
            be converted so only the first letter is capital.</li>
        <li> Do not enter any link that
            requires a person to be logged in to the site. Do not link to images.</li>
        <li>Ads for downloadable software (bots, paypal hacks, etc.) will be rejected.</li>
    </ul>

    <input type="checkbox" class="dontShow" data-what="text_ad_instructions" />
    <span class="fs12"> Don't show this again.</span>

</div>
<script>
    $('input:checkbox.dontShow').on('ifChecked', function (event) {
        $.get(mim.baseUrl + 'member/dontShow/text_ad_instructions', function (data) {
            $('#text_ad_instructions').hide("blind", {easing: 'swing'}, 400, function () {
            });
        });
    });
</script>