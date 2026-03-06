<h2 class="text-success card-header">Hello {{ auth()->user()->pname }}</h2> <br><br>

Thank you for filing a claim with AAR. We have received it and we will get back to you soonest. Thank you. <br><br>

Your Claim details are as follows: <br><br>

Provider Email: {{ auth()->user()->email}} <br>
Batch Number: single <br>
Date: {{ date("d/m/Y") }} <br>
You can track your claims by date submitted.

<hr>
Thank you.
