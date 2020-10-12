<?php
    $tmpl = array (
        'table_open'         => '<table width="1000" border="0"  cellpadding="5" cellspacing="0">',
        'heading_cell_start' => '<th class="tablebanner" align="left">'
    );

    $this->table->set_template($tmpl);
    $this->table->set_heading(array(
        'Id',
        'Username',
        'Email',
        'Date of birth',
        'Active'
    ));

    foreach ($users as $user)
    {
        $this->table->add_row
        (
            $user->id,
            anchor('admin/search_user', $user->username, 'class = "searchUser" user_id="'.$user->id.'"'),
            '<span class="tablehbidder">'
                .$user->email.
            '</span>',
            '<span class="tablehbidder">'
                .$user->date_of_birth.
            '</span>',
            '<span class="tablehbidder">'
                .($user->active == 1) ? 'Yes':'No'.
            '</span>'
        );
    }
    echo $this->table->generate();

    if ($hasPages): ?>
    <div class="paging">
        <?=$paging?>
    </div>
<? endif; ?>