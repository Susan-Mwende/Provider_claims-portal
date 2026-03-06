<html>

<?php
    require 'C:\xampp\htdocs\CURRENT\reports\con_db.php'
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Advanced Claims- Search</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
</head>
<body>
<div class="">
    <br />
    <h3 align="center">Advanced Claims - Search</h3>
    <br />
    <br />
    <div class="row input-daterange">
        <div class="col-md-4">
            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
        </div>
        <div class="col-md-4">
            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
        </div>
        <div class="col-md-4">
            <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
            <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
        </div>
    </div>
    <br />
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="order_table">
            <thead>
            <tr>
                <th>No</th>
                <th>Invoice</th>
                <th>Provider</th>
                <th>Amount</th>
                <th>Invoice Date</th>
                <th>Service Type</th>
                <th>Provider Type</th>
                <th>Raised By</th>
                <th>Date Uploaded</th>
            </tr>
            </thead>
            <?php while ($data = sqlsrv_fetch_array($data1, SQLSRV_FETCH_ASSOC)) {
            ?>
            <tr>
                <td><?php  echo $data['id']  ?></td>
                <td><?php  echo $data['Invoice']  ?></td>
                <td><?php  echo $data['id']  ?></td>
                <td><?php  echo $data['amount']  ?></td>
                <td><?php  echo $data['id'] ?></td>
                <td><?php  echo $data['serviceType']  ?></td>
                <td><?php  echo $data['providerType']  ?></td>
                <td><?php  echo $data['claimraisedby']  ?></td>
                <td><?php  echo $data['id']  ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
</body>
</html>

<script>
    $(document).ready(function(){
        $('.input-daterange').datepicker({
            todayBtn:'linked',
            format:'yyyy-mm-dd',
            autoclose:true
        });

        load_data();

        function load_data(from_date = '', to_date = '')
        {
            $('#order_table').DataTable({


            });
        }

        $('#filter').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(from_date != '' &&  to_date != '')
            {
                $('#order_table').DataTable().destroy();
                load_data(from_date, to_date);
            }
            else
            {
                alert('Both Date is required');
            }
        });

        $('#refresh').click(function(){
            $('#from_date').val('');
            $('#to_date').val('');
            $('#order_table').DataTable().destroy();
            load_data();
        });

    });
</script>
