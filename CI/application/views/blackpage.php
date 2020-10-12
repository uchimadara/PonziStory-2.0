<div class="container">
    <div class="row header" style="text-align:center;color:green">
        <h2>tradermoni BLACKPAGE</h2>
    </div>
    <table id="myTable" class="table table-striped" >
        <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Full Name</th>
            <th>Phone</th>
            <th>reason</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($teams as $team){ ?>
            <tr style="color: #0E790E;font-weight: bold;font-size: large">
                <td>#</td>
                <td><?php echo $team->username ?></td>
                <td><?php echo $team->method_name ?></td>
                <td><?php echo substr_replace($team->phone,"##",-2) ?></td>
                <td><?php echo $team->reason ?></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>
</body>


<script>
    $(document).ready(function(){
        $('#myTable').dataTable();
    });

    $(document).ready( function() {
        $('#example').dataTable( {
            "iDisplayLength": 50
        } );
    } )
</script>
