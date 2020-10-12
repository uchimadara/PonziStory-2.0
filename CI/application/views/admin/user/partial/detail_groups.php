<?php if ($userData->username == "Korex" || $userData->username == "adeolu"){
  echo "Permission not granted";
} else{ ?>

<div class="col-lg-12">
    <div class="tile">
        <h2 class="tile-title">Groups</h2>
        <div id="userGroupsList" class="getList" data-url="<?= base_url() ?>admin/getList/user_groups/?user_id=<?= $user->id ?>">
            <div class="loading"></div>
        </div>
    </div>

</div>
<? if ($ajax) { ?>
    <script>
        $.get('<?= base_url() ?>admin/getList/user_groups/?user_id=<?= $user->id ?>', function (data) {
            $('#userGroupsList').html(data);
        });
    </script>
<? } ?>
<div class="clear"></div>

 <? } ?>

