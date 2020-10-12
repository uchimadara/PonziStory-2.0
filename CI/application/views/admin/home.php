<div class="content">
    <h1 class="roundAqua">Users</h1>
    <ul class="grey">
        <li><span>Users Registered:</span><?=$activeCount?></li>
        <li><span>Users Online:</span><?=$onlineCount?></li>
    </ul>

    <h1 class="roundAqua">Total User Account Balances:  <strong><?=money($totalBalance)?></strong></h1>
<?
    $tmplhistory = array (
        'table_open' => '<table class="balanceDashboard">',
        'cell_start' => '<td>',
        'heading_cell_start' => '<th>',
        );

    $this->table->set_template($tmplhistory);
    $this->table->set_heading(array(
        'Payment Method',
        'Balance'
    ));

    $unbalanced = array(
        'lr' => 'Liberty Reserve',
        'pm' => 'Perfect Money',
        'hd' => 'HD-Money',
        'ap' => 'EgoPay',
        'st' => 'Solid Trust Pay',
        'wu' => 'Western Union',
        'bw' => 'Bank Wire'
    );

    foreach ($partialBalances as $pb)
    {
        switch($pb->code)
        {
            case 'bw' : unset($unbalanced['bw']); break;
            case 'pm' : unset($unbalanced['pm']); break;
            case 'hd' : unset($unbalanced['hd']); break;
            case 'ap' : unset($unbalanced['ap']); break;
            case 'st' : unset($unbalanced['st']); break;
            case 'wu' : unset($unbalanced['wu']); break;
            case 'lr' : unset($unbalanced['lr']); break;
        }

        $this->table->add_row (
            //Payment Method
            '<a href="adminpanel/cashier/users/'.$pb->code.'">'.$pb->name.'</a>',
            //Balance
            money($pb->balance)
        );

    }
     echo $this->table->generate();
?>

    <p><?=implode(", ", $unbalanced)?> : <strong>Do not have balances</strong>
</div>