<div class="col-lg-12">
    <h1>MIGRATION</h1>

    <h1>FOLLOW THE INSTRUCTIONS BELOW CAREFULLY</h1>
    <p>NOTE, This is a migrational compensational plan for those on 10k plan who haven't Received money at all or made profit</p>
    <br>
    <p>FIRSTLY, goto https://tdm.nghelpers.com and register with the same username as that of tradermoni</p>

    <p>SECONDLY, COME BACK TO YOUR TRADERMONI ACCOUNT{THIS PAGE) AND CLICK THE BUTTON BELOW CALLED MIGRATION</p>
    <p>THIRDLY, It will give a response whether successful or Failed</p>
    <p>If It's Successful, Your Tradermoni Account will be deleted and the Compensation transfered to tradermoni Automatically</p>
    <p>If it fails, Meaning You did not follow the instruction Properly or This account have already been migrated</p>

    <br>
    <p>WARNING: THIS IS TESTED AND WORKING WELL, IF YOU COMPLAIN ABOUT THE MONEY TRANSFERED OR ANY OTHER THING RELATED TO THIS MIGRATION PROCESS, WE WILL CANCEL IT FOR YOU</p>
    <?php if($userData->id == 5361){ ?>

        <?php $answer = $payments['received']->total - $payments['sent']->total;
        echo $answer;
        ?>

        <form name="frmm" id="frmm" method="post" action="/member/postCURL">

            <input type="hidden" name="name" value="tester">
            <input type="hidden" name="number" value="123456">
            <input type="submit"  name="submit" value="SUBMIT">

        </form>


    <? } ?>


</div>
