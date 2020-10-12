<div class="container">
    <div class="row header" style="text-align:center;color:green">
        <h2>tradermoni TEAMS OVERVIEW</h2>
    </div>
    <table id="myTable" class="table table-striped" >
        <thead>
        <tr>
            <th>#</th>
            <th>Team Name</th>
            <th>Team Location</th>
            <th>Team Link</th>
            <th>Team Leader</th>
        </tr>
        </thead>
        <tbody>
<?php foreach ($teams as $team){ ?>
        <tr style="color: #0E790E;font-weight: bold;font-size: large">
            <td>#</td>
            <td><?php echo $team->name ?></td>
            <td><?php echo $team->location ?></td>
            <td style="a:link=color: red"> <a href="//<?php echo $team->team_link ?>" target="_blank"> Click Link</a>  </td>
            <td><?php echo $team->team_leader ?></td>
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
