<html>
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
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

    <!-- dropdown library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"  crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>
<body>
<nav class="navbar navbar-expand-sm bg-light navbar-dark">
    <div class="container-fluid " >
        <a class="navbar-brand float-left" href="#">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS3az_4SvNHNpZcJg4QZZ4is28lYJGSGBc8aFFWlzsi&s" alt="Avatar Logo" style="width:70px;" class="rounded-pill">
        </a>
    </div>
    <div class="container-fluid " >
    <ul class="nav-item dropdown text-decoration-none">
        <a class="nav-link text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Claim Admin</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/admin">Dashboard</a></li>
            <li><a class="dropdown-item" href="/AdminClaims/Allclaims">Claims Management</a></li>
{{--            <li><a class="dropdown-item" href="/users/list">Manage Users</a></li>--}}
        </ul>
    </ul>
    </div>
</nav>
    <h3 align="center">Advanced Claims - Search</h3>

<div class="container-fluid">

    <div class="input-daterange">
        <div class="col-md-3">
            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
        </div>
        <div class="col-md-3">
            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
        </div>
    </div>
    <div class="col-md-3">
        <select name="provider" id="provider" class="form-control select2">
            <option value="" selected disabled>SELECT PROVIDER</option>
            @foreach($providers as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="invoice" id="invoice" class="form-control" placeholder="Invoice" />
        </div>
    <br />   <br />
    <br />   <br />

        <div class="d-grid gap-2 col-3 mx-auto">
            <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
            <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
        </div>

</div>
    <br />   <br />



{{--    <div class="table-responsive">--}}
        <table class="table table-bordered table-striped" id="order_table">
            <thead>
            <tr>
                <th>No</th>
                <th>Invoice</th>
                <th>Provider</th>
                <th>Provider Code</th>
                <th>Amount</th>
                <th>Invoice Date</th>
                <th>Service Type</th>
                <th>Provider Type</th>
                <th>Raised By</th>
                <th>Date Uploaded</th>
            </tr>
            </thead>
        </table>

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

        function load_data(from_date = '', to_date = '', invoice = '', provider = '')
        {
            $('#order_table').DataTable({
                dom: 'Blfrtip',
                buttons: ['csv','pdf', 'excel','print'],
                lengthMenu: [[25, 100,1000,10000,20000,50000, -1], [25, 100,1000,10000,20000,50000, "All"]],
                pageLength: 25,
                bAutoWidth : false,
                processing: true,
                serverSide: true,
                ajax: {
                    url:'{{ route("ClaimsView.index") }}',
                    data:{from_date:from_date, to_date:to_date, invoice:invoice, provider:provider},
                    type: "GET",
                },
                error: function(data){
                    console.log(data);
                },

                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'Invoice', name: 'Invoice'},
                    {data: 'pname', name: 'pname'},
                    {data: 'pcode', name: 'pcode'},
                    {data: 'amount', name: 'amount'},
                    {data: 'invoice_date', name: 'invoice_date'},
                    {data: 'serviceType', name: 'serviceType'},
                    {data: 'providerType', name: 'sproviderType'},
                    {data: 'claimraisedby', name: 'claimraisedby'},
                    {data: 'created_at', name: 'created_at'},
                ]


            });
        }

        $('#filter').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var invoice = $('#invoice').val();
            var provider = $('#provider').val();
            if(from_date != '' &&  to_date != '')
            {
                $('#order_table').DataTable().destroy();
                load_data(from_date, to_date, invoice,provider);
            }else if(invoice != ''){
                $('#order_table').DataTable().destroy();
                load_data('', '', invoice,provider);
            }else if(provider != ''){
                $('#order_table').DataTable().destroy();
                load_data('', '', invoice,provider);
            }
            else
            {
                alert('Both Date is required');
            }
        });

        $('#refresh').click(function(){
            $('#from_date').val('');
            $('#to_date').val('');
            $('#invoice').val('');
            $('#provider').val(null).trigger('change');
            $('#order_table').DataTable().destroy();
            load_data();
        });

    });


    $(document).ready(function() {
        $('.select2').select2({
        });
    });

</script>
