<h2>Hello Admin</h2> <br><br>

A new claim has been raised from the Claims Portal <br><br>

The details of the claim are as follows: <br><br>

Provider Email: {{ auth()->user()->email}} <br>
Batch Number: {{ $batchno }} <br>
Date: {{ date("d/m/Y") }} <br>
With the batch Number here, generate a detailed report of the claims submitted.

<hr>
Thank you.
