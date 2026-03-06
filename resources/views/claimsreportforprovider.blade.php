<!DOCTYPE html>
<html>
<head>
    <title>Detailed Claims Reports: {{now()}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Search for claims and Generate Reports<span><a href="/home" class="btn btn-primary float-right">Provider Dashboard</a></span></h2>
    <table class="table table-bordered yajra-datatable">
        <thead>
        <tr>
            <th>No</th>
            <th>Invoice</th>
            <th>Amount</th>
            <th>Invoice Date</th>
            <th>Service Type</th>
            <th>Provider Type</th>
            <th>Raised By</th>
            <th>Batch Number</th>
            <th>Uploaded at</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

</body>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css"/>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

<script type="text/javascript">
    $(function () {

        var table = $('.yajra-datatable').DataTable({
            buttons: ['csv','pdf', 'excel','print'],
            dom: 'Blfrtip',
            lengthMenu: [[10,25,50, 100,200, -1], [10,25,50, 100,200, "All"]],
            exportOptions: {
                columns: [':visible']
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('claimsreportforprovider') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'Invoice', name: 'Invoice'},
                {data: 'Amount', name: 'amount'},
                {data: 'invoice_date', name: 'invoice_date'},
                {data: 'serviceType', name: 'serviceType'},
                {data: 'providerType', name: 'providerType'},
                {data: 'claimraisedby', name: 'claimraisedby'},
                {data: 'batchno', name: 'batchno'},
                {data: 'created_at', name: 'created_at'},
               /* {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
*/
            ],

        });

    });
</script>
</html>
