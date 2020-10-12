<h1>tradermoni Testimonials (<span style="color: green"><?php echo $count ?> </span>)
</h1>
<h2 class="heading-title">Latest Testimonials on tradermoni</h2>

<section>
    <div class="container pt-0" style="margin-bottom: 5px;padding-bottom: 6px;">
        <div class="row">
            <div class="col-md-12">
                <table id="myTable" class="table table-striped" data-page-length='10' >
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Content</th>
                        <th>Name</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($testimonials as $team){ ?>
                        <tr style="font-weight: bold;font-size: large">
                            <td>#</td>
                            <td><?php echo $team->content ?></td>
                            <td style="color: #0E790E;"><?php echo $team->member ?></td>
                            <td> <?= date(DEFAULT_DATE_FORMAT, $team->date) ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</section>



<script>

    $(document).ready( function() {
        $('#myTable').dataTable( {
            "searching": false,
            "lengthMenu": [[25, 50, 100, 500, 1000],[25, 50, 100, 500]],
            "pageLength": 50

        } );
    } )
</script>

