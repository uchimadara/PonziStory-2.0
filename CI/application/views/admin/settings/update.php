<div class="styleshow">
    <a href="<?=site_url('adminpanel/admin_settings')?>">Main Settings</a>
</div>
<div class="formContainer">
    <?=form_open('', array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'SettingsAddFrm')); ?>
     <table>
        <tr>
          <td><label for="name">Module:</label></td>
          <td><?=$setting->module?></td>
        </tr>
        <tr>
          <td><label for="name">Name:</label></td>
          <td><?=$setting->label?><input type="hidden" name="name" value="<?=$setting->name?>"</td>
        </tr>
        <tr>
          <td><label for="name">Description:</label></td>
          <td><?=$setting->description?><input type="hidden" name="description" value="<?=$setting->description?>"</td>
        </tr>
        <tr>
          <td><label for="name">Value:</label></td>
          <td><input type="text" name="value" id="value" value="<?=$setting->value?>"/></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <input class="button_blue borderWhite" type="submit" value="Submit!" />
            <span class="loading"><img src="<?=asset('images/loading.gif')?>" /></span>
          </td>
        </tr>
     </table>
    </form>
</div>