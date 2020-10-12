<!-- Side Menu Member-->
<ul class="list-unstyled side-menu">    
    <?php if ($isAdmin) { ?>
        <li>
            <a class="sa-side-home" href="<?= SITE_ADDRESS ?>admin.html">
                <span class="menu-item">Admin</span>
            </a>
        </li>

    <?php } ?>
    <?php echo Widget::run('menu','member'); ?>
</ul>

