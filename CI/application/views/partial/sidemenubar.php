<ul class="menu hidden-xs" id="sidebar">    
    <? if ($isAdmin) { ?>
        <li>
            <a class="sa-side-home" href="<?= SITE_ADDRESS ?>admin.html">
                <span class="menu-item">Admin</span>
            </a>
        </li>

    <? } ?>
    <?php echo Widget::run('menu','member'); ?>
</ul>