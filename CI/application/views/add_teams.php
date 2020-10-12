<div class="p-20 formContainer" id="walletForm">
    <h1>Add Your Team Details Below</h1>

<!--    <h2> <a href="http://t.me/tradermoniteamleaders"> JOIN THE TEAM LEADERS GROUP FIRST</a> </h2>-->

    <h3 style="color: red">THIS TEAMS ARE STRICTLY FOR tradermoni DISCUSSIONS ALONE, ANY OTHER ENTITY PROMOTED ASIDE tradermoni WILL LEAD TO ACCOUNT TERMINATION</h3>
</br>
    <h3 style="color: #0E790E">Your Team Name to filled Below Should in this format : eg TEAM POSSIBLE, TEAM ACHIEVERS..HOWEVER in your WHATSAPP/TELEGRAM YOU ADD tradermoni TO IT, eg tradermoni TEAM ACHIEVERS </h3>

    <form action="/teams/addTeamPost" method="post" enctype="multipart/form-data" name="team">

        <!--        <input type="hidden" name="payment_code" value="">-->

        <!--Note-->
        <div class="form-group"><label for="name">Team Name</label><span class="red">*</span>
            <a class="tip" title="Enter Team Name."><i class="fa fa-question-circle"></i></a>
            <input class="form-control input-sm" type="text" maxlength="55" name="name" id="name" value="<?= $wallet->name ?>">
        </div>


        <div class="form-group"><label for="location">Location</label><span class="red">*</span>
            <a class="tip" title="Enter Team Location."><i class="fa fa-question-circle"></i></a>
            <span style="color: red;font-size: 14px">follow this Format: LEKKI LAGOS, WUSE ABUJA, NSUKKA ENUGU etc</span>
            <input class="form-control input-sm" type="text" maxlength="55" name="location" id="location" placeholder="eg: MUSHIN LAGOS, WUSE ABUJA, NSUKKA ENUGU etc">
        </div>

        <!--        Wallet Website-->
        <div class="form-group"><label for="team_link">Team Link (WhatsApp/Telegram)</label><span class="red">*</span>
            <a class="tip" title="Enter the Team Link."><i class="fa fa-question-circle"></i></a>
            <input class="form-control input-sm" type="text" maxlength="255" name="team_link" id="team_link" value="<?= $wallet->team_link ?>">
        </div>



        <!--        <div class="form-group"><label for="secret_answer">--><?//= $userData->secret_question ?><!--</label>-->
        <!--            <a class="tip" title="Enter your secret answer"><i class="fa fa-question-circle"></i></a><input class="form-control input-sm" type="text" name="secret_answer" id="secret_answer" maxlength="" placeholder="Enter your secret answer">-->
        <!--        </div>-->

        <div class="clear"></div>
        <div class="formBottom">
            <input class="btn btn-alt m-r-10" type="submit" value="Submit">
            <a href="/back_office" class="btn btn-alt">Cancel</a>
        </div>

    </form>
</div>

<script>
    $('.tip').tooltipsy();
</script>