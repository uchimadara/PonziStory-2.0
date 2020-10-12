<table>
    <tr>
        <th>Id
        <th>Username
        <th>Email
        <th>Active
        <th colspan="2">Add Deposit
    </tr>   
<? 
    foreach ($users as $user)
    {
?>
    <input type="hidden" id="code_pm" value="<?=$code?>" />
    <input type="hidden" id="account_id" value="<?=$account_id[0]->id?>" />
    <tr>
        <td><input type="hidden" id="user_id" value="<?=$user->id?>" /><?=$user->id?>
        <td><input type="hidden" id="userName" /><?=$user->username?>
        <td><?=$user->email?>
        <td><?=($user->active == 1) ? 'Yes':'No'?>
        <td><input type="text" id="amountDeposit" />
        <td><a href="#" id="buttonAddDeposit" class="button_blue">Add</a>
    </tr>
<?
    }
?>    
    
</table>    
 