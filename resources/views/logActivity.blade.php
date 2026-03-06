<!DOCTYPE html>

<html>

<head>
    <title>Audit Trails</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
<div class="container-fluid text-center ">
    <h2 class="shiny-button1 m-md-2">SYSTEM SECURITY DETAILED AUDIT TRAILS</h2>
    <hr class="cus1">
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Description</th>
            <th>URL accessed</th>
            <th>Method</th>
            <th>Ip</th>
            <th width="300px">User Agent</th>
            <th>User Id</th>
            <th>Action Time</th>
        </tr>
        @if($logs->count())
        @foreach($logs as $key => $log)
        <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $log->subject }}</td>
            <td class="text-success">{{ $log->url }}</td>
            <td><label class="label label-info">{{ $log->method }}</label></td>
            <td class="text-warning">{{ $log->ip }}</td>
            <td class="text-danger">{{ $log->agent }}</td>
            <td>{{App\User::where('id', $log->user_id)->value('pname') }}</td>
            <td class="text-danger">{{ $log->created_at }}</td>
        </tr>
        @endforeach
        @endif
    </table>

</div>

</body>
</html>
