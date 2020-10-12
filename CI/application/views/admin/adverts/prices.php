<div class="styleshow">
    <a href="<?=site_url('adminpanel/adverts')?>">Summary</a> |
    <a href="<?=site_url('adminpanel/adverts/prices')?>">Prices</a>
</div>
<h2>Edit Campaign</h2>
<?=$error ? '<p class="error">' . $error . '</p>' : ''?>
<?=form_open()?>
<table>
    <tr>
        <td><strong>Current Price</strong></td>
        <td><?=money($currentPrice)?></td>
    </tr>
    <tr>
        <td><strong>New Price</strong></td>
        <td><?=form_input('price', $currentPrice)?></td>
    </tr>
    <tr>
        <td valign="top"><strong>Impression Values</strong></td>
        <td>
            lvl 1: <?=form_input('impression_value[1]', $impressionValues[1])?><br/>
            lvl 2: <?=form_input('impression_value[2]', $impressionValues[2])?><br/>
            lvl 3: <?=form_input('impression_value[3]', $impressionValues[3])?><br/>
            lvl 4: <?=form_input('impression_value[4]', $impressionValues[4])?><br/>
            lvl 5: <?=form_input('impression_value[5]', $impressionValues[5])?><br/>
            lvl 6: <?=form_input('impression_value[6]', $impressionValues[6])?><br/>
        </td>
    </tr>
    <tr>
        <td colspan="2"><?=form_submit('save', 'Save', 'class="button_blue borderWhite"')?></td>
    </tr>
</table>
<?=form_close()?>