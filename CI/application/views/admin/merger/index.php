<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="front-page">
        <h1 class="yellow">Merger</h1>

        <div class="tab-container tile">
            <ul class="nav tab nav-tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
                <li class="active"><a href="#tab0">Statistics </a></li>


                <li class=""><a href="#tab1">Bulk Merger</a></li>


                <li class=""><a href="#tab2">Ref Bonus</a></li>
                <li class=""><a href="#tab3">Add GH</a></li>
                <li class=""><a href="#tab4">Singular Merger</a></li>


            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab0">
                    <div class="col-lg-12">
                        <div class="tile">
                            <h2 class="tile-title">STatistics </h2>
<!--                            <div id="userGroupsList" class="getList" data-url="https://tdm.nghelpers.com/admin/getList/user_groups/?user_id=4807">-->
                            <div id="userGroupsList" class="getList">
                                    <form name="frm" id="frm" method="post" action="merger/merge">
                                <div class="col-lg-6">
                                    <table class="table" border="1">

                                        <thead>
                                        GH

                                        </thead>
                                        <tbody>
                                     <p>Total Number of GH:<?php echo $allGH ?>  </p>
                                     <p>Total Value of GH: <?php echo $allGHSum ?> </p>
                                     <p>Total Number of GH Today:<?php echo $allGHToday ?>  </p>
                                     <p>Total Value of GH Today: <?php echo $allGHSumToday ?> </p>
                                     <p>Total Number of Bonus Today: <?php echo $allBonus ?> </p>
                                        </tbody>

                                    </table>

                                </div>
                                <div class="col-lg-6">
                                    <table class="table" border="1">

                                        <thead>

                                        PH
                                        </thead>
                                        <tbody>
                                        <p> <?php echo $allPH ?> :Total Number of PH </p>
                                        <p> <?php echo $allPHSum ?>:Total Value of PH: </p>
                                        <p> <?php echo $allPHToday ?> :Total Number of PH Today: </p>
                                        <p> <?php echo $allPHSumToday ?>:Total Value of PH Today: </p>
                                        </tbody>


                                    </table>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                    <div class="clear"></div>
                </div>
                <div class="tab-pane" id="tab1">
                    <div class="col-lg-12">
                        <div class="tile">
                            <h2 class="tile-title">Bulk Merge - Highest to lowest </h2>
                            <!--                            <div id="userGroupsList" class="getList" data-url="https://tdm.nghelpers.com/admin/getList/user_groups/?user_id=4807">-->
                            <div id="userGroupsList" class="getList">
                              
                                <form name="frm"  id="frm" method="post" action="/adminpanel/merger/bulkMerge">
                                    <div class="col-sm-6">
                                        <table class="table" border="1">

                                            <thead>
                                            GH
                                            <tr>
                                                <th>Due Date</th>
                                                <th>Username</th>
                                                <th>Amount</th>
                                                <th><input type="checkbox" id="chckHead" class="checkAll" name="checkAll" /></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($GhMerge as $jtem){ ?>
                                                <tr role="row">

                                                    <td><input type="hidden" name="date[]" class="date" value="<?php echo $jtem->date_of_gh ?>" /> <?php echo $jtem->date_of_gh ?></td>
                                                    <td><input type="hidden" name="name[]" class="name" value="<?php echo $jtem->username ?>" /> <a href="https://tdm.nghelpers.com/admin/user/<?php echo $jtem->user_id ?>"> <?php echo $jtem->username ?></a> </td>
                                                    <td><input type="hidden" name="amount[]" class="amount" value="<?php echo $jtem->rem_amount ?>" /> <?php echo $jtem->rem_amount ?></td>
                                                    <input type="hidden" name="method_id[]" class="method" value="<?php echo $jtem->method_id ?>" />
                                                        <?php $strings = $jtem->date_of_gh.",". $jtem->username.",".$jtem->rem_amount.",".$jtem->method_id.",".$jtem->user_id;
                                                             $array = explode(",",$strings)

                                                        ?>
                                                    <td><input type="checkbox" name="check[]" class="chcktbl" value="<?php echo $strings ?>" /></td>

                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                            <script src="https://code.jquery.com/jquery-latest.min.js"></script>

                                            <script type="text/javascript">
                                                $('.chcktbl').click(function () {
                                                    var length = $('.chcktbl:checked').length;
                                                    if (length > 100) {
                                                        alert(length);
                                                        $('.chcktbl:not(:checked)').attr('disabled', true);
                                                    }
                                                    else {
                                                        $('.chcktbl:not(:checked)').attr('disabled', false);
                                                    }
                                                });
                                            </script>
                                            <script type="text/javascript">
                                                $('#chckHead').click(function () {
                                                    if (this.checked == false) {
                                                        $('.chcktbl:checked').attr('checked', false);
                                                    }
                                                    else {
                                                        $('.chcktbl:not(:checked)').attr('checked', true);
                                                    }
                                                });
                                                $('#chckHead').click(function () {
                                                });
                                            </script>
                                        </table>

                                    </div>
                                    <div class="col-sm-6">
                                        <table class="table" border="1">

                                            <thead>

                                            PH
                                            <tr>
                                                <th>Due Date</th>
                                                <th>Username</th>
                                                <th>Amount</th>
                                                <th><input type="checkbox" id="chckHead2" class="checkAll2" name="checkAll2" /></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($phMerge as $item){ ?>

                                                <tr role="row">
                                                    <td><input type="hidden" name="date2[]" class="date" value="<?php echo $item->date_of_gh ?>" /> <?php echo $item->date_of_ph ?></td>

                                                    <td><input type="hidden" name="name2[]" class="name" value="<?php echo $item->username ?>" /> <a href="https://tdm.nghelpers.com/admin/user/<?php echo $item->user_id ?>"> <?php echo $item->username ?></a> </td>
                                                    <td><input type="hidden" name="amount2[]" class="amount" value="<?php echo $item->rem_amount ?>" /> <?php echo $item->rem_amount ?> (<?php if($item->recom){ echo "<small style='color: green'>Recom</small>";} elseif($item->re_ph){echo "<small style='color: #008DCE'>Re-PH</small>";} else {echo "<small style='color: orangered'>New</small>";} ?>)</td>

                                                    <?php $strings2 = $item->date_of_gh.",". $item->username.",".$item->rem_amount.",".$item->user_id; ?>
                                                    <?php $array = explode(",",$strings2); ?>

                                                    <td><input type="checkbox" name="check2[]" class="chcktbl2" value="<?php echo $strings2; ?>" /></td>

                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                            <script type="text/javascript">
                                                $('.chcktbl2').click(function () {
                                                    var length = $('.chcktbl2:checked').length;
                                                    if (length > 100) {
                                                        alert(length);
                                                        $('.chcktbl2:not(:checked)').attr('disabled', true);
                                                    }
                                                    else {
                                                        $('.chcktbl2:not(:checked)').attr('disabled', false);
                                                    }
                                                });
                                            </script>
                                            <script type="text/javascript">
                                                $('#chckHead2').click(function () {
                                                    if (this.checked == false) {
                                                        $('.chcktbl2:checked').attr('checked', false);
                                                    }
                                                    else {
                                                        $('.chcktbl2:not(:checked)').attr('checked', true);
                                                    }
                                                });
                                                $('#chckHead2').click(function () {
                                                });
                                            </script>

                                        </table>
                                        <input class="btn btn-alt m-r-10" type="submit" name="submit" value="MERGE">
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                    <div class="clear"></div>
                </div>
                <div class="tab-pane" id="tab2">
                    <div class="col-lg-12">
                        <div class="tile">
                            <h2 class="tile-title">Highest to lowest </h2>
                            <!--                            <div id="userGroupsList" class="getList" data-url="https://tdm.nghelpers.com/admin/getList/user_groups/?user_id=4807">-->
                            <div id="userGroupsList" class="getList">
                                <form name="frm" id="frm" method="post" action="/adminpanel/merger/bonus">
                                    <div class="col-sm-6">
                                        <table class="table" border="1">

                                            <thead>
                                            GH
                                            <tr>
                                                <th>Due Date</th>
                                                <th>Username</th>
                                                <th>Amount</th>
                                                <th><input type="checkbox" id="chckHead" class="checkAll" name="checkAll" /></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($BonusMerge as $jtem){ ?>
                                                <tr role="row">
                                                    <td><input type="hidden" name="date[]" class="date" value="<?php echo $jtem->date_of_gh ?>" /> <?php echo $jtem->date_of_gh ?></td>
                                                    <td><input type="hidden" name="name[]" class="name" value="<?php echo $jtem->username ?>" /> <a href="https://tdm.nghelpers.com/admin/user/<?php echo $jtem->user_id ?>"> <?php echo $jtem->username ?></a> </td>
                                                    <td><input type="hidden" name="amount[]" class="amount" value="<?php echo $jtem->rem_amount ?>" /> <?php echo $jtem->rem_amount ?></td>
                                                    <input type="hidden" name="method_id[]" class="method" value="<?php echo $jtem->method_id ?>" />
                                                    <?php $strings = $jtem->date_of_gh.",". $jtem->username.",".$jtem->rem_amount.",".$jtem->method_id.",".$jtem->user_id;
                                                    $array = explode(",",$strings)

                                                    ?>
                                                    <td><input type="checkbox" name="check[]" class="chcktbl" value="<?php echo $strings ?>" /></td>

                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                            <script src="https://code.jquery.com/jquery-latest.min.js"></script>

                                            <script type="text/javascript">
                                                $('.chcktbl').click(function () {
                                                    var length = $('.chcktbl:checked').length;
                                                    if (length > 100) {
                                                        alert(length);
                                                        $('.chcktbl:not(:checked)').attr('disabled', true);
                                                    }
                                                    else {
                                                        $('.chcktbl:not(:checked)').attr('disabled', false);
                                                    }
                                                });
                                            </script>
                                            <script type="text/javascript">
                                                $('#chckHead').click(function () {
                                                    if (this.checked == false) {
                                                        $('.chcktbl:checked').attr('checked', false);
                                                    }
                                                    else {
                                                        $('.chcktbl:not(:checked)').attr('checked', true);
                                                    }
                                                });
                                                $('#chckHead').click(function () {
                                                });
                                            </script>
                                        </table>

                                    </div>
                                    <div class="col-sm-6">
                                        <table class="table" border="1">

                                            <thead>

                                            PH
                                            <tr>
                                                <th>Due Date</th>
                                                <th>Username</th>
                                                <th>Amount</th>
                                                <th><input type="checkbox" id="chckHead2" class="checkAll2" name="checkAll2" /></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($phMerge as $item){ ?>

                                                <tr role="row">
                                                    <td><input type="hidden" name="date2[]" class="date" value="<?php echo $item->date_of_gh ?>" /> <?php echo $item->date_of_ph ?></td>

                                                    <td><input type="hidden" name="name2[]" class="name" value="<?php echo $item->username ?>" /> <a href="https://tdm.nghelpers.com/admin/user/<?php echo $item->user_id ?>"> <?php echo $item->username ?></a> </td>
                                                    <td><input type="hidden" name="amount2[]" class="amount" value="<?php echo $item->rem_amount ?>" /> <?php echo $item->rem_amount ?> (<?php if($item->recom){ echo "<small style='color: green'>Recom</small>";} else {echo "<small style='color: orangered'>New</small>";} ?>)</td>

                                                    <?php $strings2 = $item->date_of_gh.",". $item->username.",".$item->rem_amount.",".$item->user_id; ?>
                                                    <?php $array = explode(",",$strings2); ?>

                                                    <td><input type="checkbox" name="check2[]" class="chcktbl2" value="<?php echo $strings2; ?>" /></td>

                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                            <script type="text/javascript">
                                                $('.chcktbl2').click(function () {
                                                    var length = $('.chcktbl2:checked').length;
                                                    if (length > 100) {
                                                        alert(length);
                                                        $('.chcktbl2:not(:checked)').attr('disabled', true);
                                                    }
                                                    else {
                                                        $('.chcktbl2:not(:checked)').attr('disabled', false);
                                                    }
                                                });
                                            </script>
                                            <script type="text/javascript">
                                                $('#chckHead2').click(function () {
                                                    if (this.checked == false) {
                                                        $('.chcktbl2:checked').attr('checked', false);
                                                    }
                                                    else {
                                                        $('.chcktbl2:not(:checked)').attr('checked', true);
                                                    }
                                                });
                                                $('#chckHead2').click(function () {
                                                });
                                            </script>

                                        </table>
                                        <input class="btn btn-alt m-r-10" type="submit" name="submit" value="MERGE">
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                    <div class="clear"></div>
                </div>
                <div class="tab-pane" id="tab3">
                    <div class="formContainer p-10">
                        <form action="/adminpanel/merger/insertGHManual" accept-charset="utf-8" method="post">
                            <div class="form-group">

                                <label for="category">Amount</label>
                                <select name="amount" class="form-control input-sm">
                                    <option value="2500">N2500</option>
                                    <option value="5000">N5000</option>
                                    <option value="10000">N10,000</option>
                                    <option value="20000">N20,000</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subject">Username</label>
                                <input class="form-control input-sm" type="text" placeholder="Make SURE the username is 100% correct with same Case" id="username" name="username">
                            </div>

                            <div class="formBottom">
                                <input class="btn btn-alt" name="submit" type="submit" value="submit">
                            </div>
                        </form>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="tab-pane" id="tab4">
                    <div class="col-lg-12">
                        <div class="tile">
                            <h2 class="tile-title">Singular Merge - Highest to lowest </h2>
                            <!--                            <div id="userGroupsList" class="getList" data-url="https://tdm.nghelpers.com/admin/getList/user_groups/?user_id=4807">-->
                            <div id="userGroupsList" class="getList">
                                <form name="frm"  method="post" action="/adminpanel/merger/merge">
                                    <div class="col-sm-6">
                                        <table class="table" border="1">

                                            <thead>
                                            GH
                                            <tr>
                                                <th>Due Date</th>
                                                <th>Username</th>
                                                <th>Amount</th>
                                                <th><input type="checkbox" id="chckHead" class="checkAll" name="checkAll" /></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($GhMerge as $jtem){ ?>
                                                <tr role="row">
                                                    <td><input type="hidden" name="date[]" class="date" value="<?php echo $jtem->date_of_gh ?>" /> <?php echo $jtem->date_of_gh ?></td>
                                                    <td><input type="hidden" name="name[]" class="name" value="<?php echo $jtem->username ?>" /> <a href="https://tdm.nghelpers.com/admin/user/<?php echo $jtem->user_id ?>"> <?php echo $jtem->username ?></a> </td>
                                                    <td><input type="hidden" name="amount[]" class="amount" value="<?php echo $jtem->rem_amount ?>" /> <?php echo $jtem->rem_amount ?></td>
                                                    <input type="hidden" name="method_id[]" class="method" value="<?php echo $jtem->method_id ?>" />

                                                    <td><input type="checkbox" name="check[]" class="chcktbl" value="<?php echo $jtem->user_id ?>" /></td>

                                                </tr>
                                            <?php } ?>

                                            </tbody>

                                            <script type="text/javascript">
                                                $('.chcktbl').click(function () {
                                                    var length = $('.chcktbl:checked').length;
                                                    if (length > 100) {
                                                        alert(length);
                                                        $('.chcktbl:not(:checked)').attr('disabled', true);
                                                    }
                                                    else {
                                                        $('.chcktbl:not(:checked)').attr('disabled', false);
                                                    }
                                                });
                                            </script>
                                            <script type="text/javascript">
                                                $('#chckHead').click(function () {
                                                    if (this.checked == false) {
                                                        $('.chcktbl:checked').attr('checked', false);
                                                    }
                                                    else {
                                                        $('.chcktbl:not(:checked)').attr('checked', true);
                                                    }
                                                });
                                                $('#chckHead').click(function () {
                                                });
                                            </script>
                                        </table>

                                    </div>
                                    <div class="col-sm-6">
                                        <table class="table" border="1">

                                            <thead>

                                            PH
                                            <tr>
                                                <th>Due Date</th>
                                                <th>Username</th>
                                                <th>Amount</th>
                                                <th><input type="checkbox" id="chckHead2" class="checkAll2" name="checkAll2" /></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($phMerge as $item){ ?>

                                                <tr role="row">
                                                    <td><input type="hidden" name="date2[]" class="date" value="<?php echo $item->date_of_gh ?>" /> <?php echo $item->date_of_ph ?></td>

                                                    <td><input type="hidden" name="name2[]" class="name" value="<?php echo $item->username ?>" /> <a href="https://tdm.nghelpers.com/admin/user/<?php echo $item->user_id ?>"> <?php echo $item->username ?></a> </td>
                                                    <td><input type="hidden" name="amount2[]" class="amount" value="<?php echo $item->rem_amount ?>" /> <?php echo $item->rem_amount ?></td>
                                                    <td><input type="checkbox" name="check2[]" class="chcktbl2" value="<?php echo $item->user_id ?>" /></td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                            <script type="text/javascript">
                                                $('.chcktbl2').click(function () {
                                                    var length = $('.chcktbl2:checked').length;
                                                    if (length > 100) {
                                                        alert(length);
                                                        $('.chcktbl2:not(:checked)').attr('disabled', true);
                                                    }
                                                    else {
                                                        $('.chcktbl2:not(:checked)').attr('disabled', false);
                                                    }
                                                });
                                            </script>
                                            <script type="text/javascript">
                                                $('#chckHead2').click(function () {
                                                    if (this.checked == false) {
                                                        $('.chcktbl2:checked').attr('checked', false);
                                                    }
                                                    else {
                                                        $('.chcktbl2:not(:checked)').attr('checked', true);
                                                    }
                                                });
                                                $('#chckHead2').click(function () {
                                                });
                                            </script>

                                        </table>
                                        <input class="btn btn-alt m-r-10" type="submit" name="submit" value="MERGE">
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                    <div class="clear"></div>
                </div>


            </div>
        </div>
    </div>
</div>