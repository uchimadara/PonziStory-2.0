<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">Update menu item</h2>
        <div class="tile-config dropdown">
            <a class="tile-menu" href="" data-toggle="dropdown"></a>
            <ul class="dropdown-menu pull-right text-right">
                <li><a href="<?= site_url('adminpanel/admin_menu') ?>">Back to menu tree</a></li>
            </ul>
        </div>

        <div id="cms_menuForm" class="p-10">



            <div class="formContainer">
                <?= form_open('', array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'MenuAddFrm')); ?>
                <div class="form-group">
                    <label for="name">Place:</label>
                    <?= form_dropdown('place', $this->picklist->select_values('cms_menu_list'), $menus->place, 'class="form-control"') ?>
                </div>
                <div class="form-group">
                    <label for="name">Display name:</label>
                    <input class="form-control" type="text" name="name" value="<?= $menus->name ?>" />
                </div>
                <div class="form-group">
                    <label for="name">URL:</label>
                    <input class="form-control" type="text" name="url" value="<?= $menus->url ?>" />
                </div>
                <div class="form-group">
                    <label for="name">Parent:</label>
                    <?= form_dropdown('parent_id', $this->picklist->select_values('cms_menu_parent_list', TRUE), $menus->parent_id, 'class="form-control"') ?>
                </div>
                <div class="form-group">
                    <label for="name">Icon:</label>
                    <?= form_dropdown('icon', $this->picklist->select_values('css_icon_list'), $menus->icon, 'class="form-control"') ?>
                </div>
                <div class="formBottom m-t-20">
                    <input type="submit" value="Submit" class="btn btn-alt m-r-10">
                    <input type="reset" value="Reset" class="btn btn-alt">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>