<div class="col-lg-12">
    <h3> PH List  </h3>

    <div id="paymentList" class="getList" data-url="https://tdm.nghelpers.com/admin/getList/payments_sent?payer_user_id=5134">
        <div class="listContainer rms-sortable">
            <table class="rwd-table">
                <tbody>
                <tr>
                    <th>Date of PH</th>
                    <th>Date to GH</th>
                    <th>Amount</th>
                    <th>Remaining Amt</th>
                    <th>Status</th>
                    <th class="center">Edit</th>
                </tr>

                <?php foreach ($getAllPh as $phlist){ ?>
                <tr>
                    <td data-th="Date"><?php echo $phlist->date_of_ph ?></td>
                    <td data-th="Date to Gh"><?php echo $phlist->date_of_ph ?></td>
                    <td data-th="Amount"><span class="green"><?php echo money($phlist->amount,"₦") ?></span></td>
                    <td data-th="Rem_Amount"><span class="green"><?php echo money($phlist->rem_amount,"₦") ?></span></td>
                    <td data-th="Status"><?php if($phlist->status == 1){
                        echo "In Queue - 1";
                        } elseif ($phlist->status == 2){
                        echo "Merged - 2";
                        } elseif ($phlist->status == 3){
                        echo "POP uploaded - 3";
                        }elseif ($phlist->status == 4){
                        echo "Confirmed - 4";
                        }elseif ($phlist->status == 5){
                        echo "Cashed Out - 5";
                        }else{
                        echo "Problem with PH - 6";
                        }

                        ?></td>
                    <td data-th="Edit" class="center"><a href="https://tdm.nghelpers.com/adminpanel/users/edit_phgh/<?php echo $phlist->id ?>/1" title="Edit Ph" class="popup"><i class="fa fa-pencil-square-o"></i></a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>


</div>
</div>
</div>

<div class="col-lg-12">
    <h3> GH List  </h3>

    <div id="paymentList" class="getList" data-url="https://tdm.nghelpers.com/admin/getList/payments_sent?payer_user_id=5134">
        <div class="listContainer rms-sortable">
            <table class="rwd-table">
                <tbody>
                <tr>
                    <th>Date Added</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Remaining Amt</th>
                    <th>Status</th>
                    <th class="center">Edit</th>
                </tr>

                <?php foreach ($getAllGh as $ghlist){ ?>
                    <tr>
                        <td data-th="Date"><?php echo $ghlist->date_added ?></td>
                        <td data-th="Date to Gh"><?php echo $ghlist->type ?></td>
                        <td data-th="Amount"><span class="green"><?php echo money($ghlist->amount,"₦") ?></span></td>
                        <td data-th="Rem_Amount"><span class="green"><?php echo money($ghlist->rem_amount,"₦") ?></span></td>
                        <td data-th="Status"><?php if($ghlist->status == 1){
                                echo "In Queue - 1";
                            } elseif ($ghlist->status == 2){
                                echo "Merged - 2";
                            } elseif ($ghlist->status == 3){
                                echo "POP uploaded - 3";
                            }elseif ($ghlist->status == 4){
                                echo "Confirmed - 4";
                            }else{
                                echo "Problem with GH - 6";
                            }

                            ?></td>
                        <td data-th="Edit" class="center"><a href="https://tdm.nghelpers.com/adminpanel/users/edit_phgh2/<?php echo $ghlist->id ?>/2" title="Edit Gh" class="popup"><i class="fa fa-pencil-square-o"></i></a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>


        </div>
    </div>
</div>