<div class="showHide m-b-20 alert alert-info" id="payment_method_instructions">
    <h3>Instructions</h3>
    <p>
        Enter your payment processor or bank information. Add a note to let people know any details required
        to send the donations, like swift code, name on account, email address, etc. All information entered
        will be viewable by other members when they need to send a donation to you.
    </p>

    <input type="checkbox" class="dontShow" data-what="payment_method_instructions" />
    <span class="fs12"> Don't show this again.</span>

</div>
<script>
    $('input:checkbox.dontShow').on('ifChecked', function (event) {
        $.get(mim.baseUrl + 'member/dontShow/payment_method_instructions', function (data) {
            $('#payment_method_instructions').hide("blind", {easing: 'swing'}, 400, function () {
            });
        });
    });
</script>