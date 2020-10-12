<table>
    <tr>
        <th width="100">ID</th>
        <th width="200">Name</th>
        <th width="100">User</th>
        <th width="100">Type</th>
        <th width="420">Image</th>
    </tr>
<? foreach ($data as $campaign): ?>
    <tr>
        <td><?=anchor('adminpanel/adverts/edit/' . $campaign->id, '#' . str_pad($campaign->id, 4, '0', STR_PAD_LEFT))?></td>
        <td><?=$campaign->name?></td>
        <td><?=$campaign->username?></td>
        <td><?=$campaign->type?></td>
        <td valign="middle" align="center">
          <? if ($campaign->type == 'auction') { ?>
            <img src="<?=base_url() . 'campaign/'.$campaign->type.'/' . $campaign->image?>" width="60" height="60" />
          <? } else { ?>
            <img src="<?=base_url() . 'campaign/'.$campaign->type.'/' . $campaign->image?>" width="360" height="47" />
          <? } ?>
        </td>
    </tr>
<? endforeach; ?>
</table>

<? if ($hasPages): ?>
<span class="paging"><strong>Page:</strong> <?=$paging?></span>
<? endif; ?>