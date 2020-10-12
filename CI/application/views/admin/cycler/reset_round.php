<h1 class="yellow">Reset Cycler Queue</h1>
<div class="formContainer">
    <?= form_open(site_url('adminpanel/cycler/initiate/'.$round->id), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'adminFrm')); ?>
    <div class="col-md-6">
        <div class="tile">
            <h2 class="tile-title">Current Status</h2>

            <table class="table">
                <tr>
                    <td>Total Positions</td>
                    <td><?= number_format($round->positions) ?></td>
                </tr>
                <tr>
                    <td>Unused Tokens</td>
                    <td><?= $unusedTokens ?></td>
                </tr>
                <tr>
                    <td>Active Positions</td>
                    <td><span id="activePositions"><?= $activePositions ?></span>
                        (<?= roundDown($activePositions/$round->positions, 2)*100 ?>%)
                    </td>
                </tr>
                <tr>
                    <td>Reset Fund</td>
                    <td><span id="resetFundAmount"><?= RESET_FUND ?></span></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="tile">
            <h2 class="tile-title">Reset Fund Calculator</h2>

            <table class="table">
                <tr>
                    <td>Tokens / Active Positions =</td>
                    <td><span id="tOverA"><?= roundDown($unusedTokens/$activePositions, 2) ?></span>
                        &nbsp;&nbsp; <a class="btn btn-alt btn-sm useThis" data-id="tOverA">Use this %</a>
                    </td>
                </tr>
                <tr>
                    <td>Active Positions / Tokens =</td>
                    <td><span id="aOverT"><?= roundDown($activePositions/$unusedTokens, 2) ?></span>
                        &nbsp;&nbsp; <a class="btn btn-alt btn-sm useThis" data-id="aOverT">Use this %</a>
                    </td>
                </tr>
                <tr>
                    <td>Amount of Reset Fund to use</td>
                    <td>
                        <input class="form-control" type="text" name="reset_fund" id="resetFund" value="0.00" onkeyup="javascript:{maskAmount(this);calcFund();}"/>
                    </td>
                </tr>
                <tr>
                    <td>Paid to Each Position</td>
                    <td><span id="paidEach">0</span></td>
                </tr>
                <tr>
                    <td>Remainder</td>
                    <td><span id="remainder">0</span></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-md-6">
        <div class="tile">
            <h2 class="tile-title">Time till next round</h2>

            <table class="table">
                <tr>
                    <td>Hours</td>
                    <td>
                        <input class="form-control" type="text" name="hours" id="hours" value="24" onkeyup="javascript:maskInteger(this);"/>
                    </td>
                </tr>
                <tr>
                    <td>Minutes</td>
                    <td>
                        <input class="form-control" type="text" name="minutes" id="minutes" value="0" onkeyup="javascript:maskInteger(this);"/>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="clear"></div>
    <div class="col-md-6">
        <div class="tile">
            <div class="formBottom p-10">
                <input class="btn btn-alt" type="submit" value="Reset Cycler"/>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>

