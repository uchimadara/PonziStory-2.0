<div class="tile tile-dark" id="accounts">
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Enabled</th>
        </tr>
<?
    foreach($paymentMethods as $method):
?>
        <tr>
            <td><?= $method->name?></a></td>
            <td><input type="checkbox" class="status" name="<?= $method->id?>" value="on"<?= $method->enabled ? ' checked' : ''?> /></td>
        </tr>
<? endforeach; ?>
    </table>
</div>