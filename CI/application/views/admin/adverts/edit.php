<div class="styleshow">
    <a href="<?=site_url('adminpanel/adverts')?>">Summary</a> |
    <a href="<?=site_url('adminpanel/adverts/prices')?>">Prices</a>
</div>
<h2>Edit Campaign</h2>
<?=$error ? '<p class="error">' . $error . '</p>' : ''?>
<?=form_open_multipart()?>
<table>
    <tr>
        <td>ID</td>
        <td><?='#' . str_pad($campaignData->id, 4, '0', STR_PAD_LEFT)?></td>
    </tr>
    <tr>
        <td>Created</td>
        <td><?=Date('d/m/Y H:i', $campaignData->created)?></td>
    </tr>
    <tr>
        <td>Name</td>
        <td><?=form_input('name', $campaignData->name)?></td>
    </tr>
    <tr>
        <td>URL</td>
        <td><?=form_input('url', $campaignData->target_url)?></td>
    </tr>
    <tr>
        <td valign="top">Banner</td>
        <td><img src="<?=base_url() . 'campaign/fixed/' . $campaignData->image?>" /></td>
    </tr>
    <tr>
        <td></td>
        <td><?=form_upload('banner')?></td>
    </tr>
    <tr>
        <td colspan="2"><?=form_submit('save', 'Save', 'class="button_blue borderWhite"')?></td>
    </tr>
</table>
<?=form_close()?>