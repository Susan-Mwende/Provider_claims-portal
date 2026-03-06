<?php

namespace App\Http\Controllers;

use \Carbon\Carbon;
use App\Models\Claim;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ClaimsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        Log::info('ClaimsController initialized.');
    }

    public function claimslist(ClaimsDataTable $dataTable)
    {
        Log::info('Rendering claims list view.');
        return $dataTable->render('claimslist');
    }

    public function userindex(Request $request)
    {
        if ($request->ajax()) {
            Log::info('Processing AJAX request for user index.');
            $model = Claim::with('users');
            return DataTables::eloquent($model)
                ->addColumn('users', function (Claim $claim) {
                    return $claim->users->name;
                })
                ->toJson();
        }
        Log::info('Rendering user index view.');
        return view('users');
    }

    public function index(): \Illuminate\View\View
    {
        $user_id = auth()->user()->id;
        Log::info("Fetching claims for user ID: $user_id.");
        $myClaims = Claim::toBase()->where('user_id', $user_id)->orderBy('id', 'desc')->paginate(25);
        return view('Claims/index', compact('myClaims'));
    }

    public function search(Request $request): \Illuminate\View\View
    {
        $search = $request->get('search');
        Log::info("Searching claims with keyword: $search.");

        $claimsQuery = Claim::query();

        if (!auth()->user()->can('isAdmin')) {
            $claimsQuery->where('user_id', auth()->id());
        }

        $claimsQuery->where(function ($q) use ($search) {
            $q->where('Invoice', 'like', '%' . $search . '%')
                ->orWhere('invoice_date', 'like', '%' . $search . '%')
                ->orWhere('amount', 'like', '%' . $search . '%')
                ->orWhere('Amount', 'like', '%' . $search . '%');
        });

        $claims = $claimsQuery->orderByDesc('id')->paginate(25);
        return view('Claims/index', ['myClaims' => $claims]);
    }

    public function create(): \Illuminate\View\View
    {
        Log::info('Rendering create claim form.');
        return view('Claims/create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        Log::info('Starting claim store process.');

        // Custom validation rule for invoice uniqueness per provider
        $invoiceValidation = Rule::unique('Claims')->where(function ($query) {
            return $query->where('user_id', auth()->user()->id);
        });

        // Validate request data with updated invoice validation
        $this->validate($request, [
            'Amount' => 'required|numeric|gt:0',
            'Invoice' => ['required', $invoiceValidation],
            'serviceType' => 'required',
            'providerType' => 'required',
            'attachment' => 'required',
            'invoice_date' => 'required|date',
            'claimraisedby' => 'required',
            'attachment.*' => 'mimes:pdf,docx,doc,zip|max:100048',
            'claims_from' => 'required|date',
            'claims_to' => 'required|date|after_or_equal:claims_from',
        ], [
            'Invoice.unique' => 'This invoice has already been submitted by your facility. Please check and try again with a different invoice number.'
        ]);

        $currentDate = date('d-m-Y');
        $userName = auth()->user()->name;
        $safeUserName = preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $userName);
        $userPath = storage_path("app/claims/single/{$safeUserName}");
        $backupPath = storage_path("app/claims/single_backups/{$currentDate}/{$safeUserName}");

        // Create directories if they don't exist
        foreach ([$userPath, $backupPath] as $path) {
            if (!File::isDirectory($path)) {
                try {
                    File::makeDirectory($path, 0777, true);
                    Log::info("Directory created: $path");
                } catch (\Throwable $e) {
                    Log::error("Failed to create directory: {$path}. Error: {$e->getMessage()}");
                    return redirect()->back()->withErrors(['msg' => 'Failed to create upload directory.']);
                }
            }
        }

        $data = [];
        if ($request->hasfile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                $name = $file->getClientOriginalName();
                $destinationUser = $userPath . '/' . $name;
                $destinationBackup = $backupPath . '/' . $name;

                try {
                    $file->move($userPath, $name);
                    $data[] = $name;
                    Log::info("File uploaded to user directory: $destinationUser");

                    if (File::copy($destinationUser, $destinationBackup)) {
                        Log::info("File copied to backup directory: $destinationBackup");
                    } else {
                        Log::error("Failed to copy file to backup directory: $destinationBackup");
                    }
                } catch (\Exception $e) {
                    Log::error("File handling failed: " . $e->getMessage());
                    return redirect()->back()->withErrors(['msg' => 'Failed to upload or backup files.']);
                }
            }
        }

        $alertstobesend = auth()->user()->sendalert;
        $claimtime = Carbon::now();

        // Sanitize inputs
        $invoice = filter_var($request->input('Invoice'), FILTER_SANITIZE_STRING);
        $amount = filter_var($request->input('Amount'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        if (!is_numeric($amount) || $amount <= 0) {
            Log::error("Invalid amount: $amount");
            return redirect()->back()->withErrors(['msg' => 'Invalid amount value.']);
        }

        try {
            $claim = new Claim();
            $claim->user_id = auth()->user()->id;
            $claim->raiser_id = auth()->user()->id;
            $claim->Invoice = str_replace("-", "", $invoice);
            $claim->Amount = $amount;
            $claim->batchno = 'single';
            $claim->serviceType = filter_var($request->input('serviceType'), FILTER_SANITIZE_STRING);
            $claim->providerType = filter_var($request->input('providerType'), FILTER_SANITIZE_STRING);
            $claim->invoice_date = $request->input('invoice_date');
            $claim->claimraisedby = filter_var($request->input('claimraisedby'), FILTER_SANITIZE_STRING);
            $claim->attachment = json_encode($data);
            $claim->slug = Str::slug($invoice . "_" . $claimtime->toDateTimeString());
            $claim->save();

            Log::info("Claim stored successfully: Invoice - {$claim->Invoice}, Amount - {$claim->Amount}");

            // Send email alerts if enabled
            if ($alertstobesend === '1') {
                $this->sendEmailAlerts($claim, $request);
            }

            return redirect('/Claims')->with('success', 'Your claim has been submitted successfully. Thank you.');
        } catch (\Exception $e) {
            Log::error("Failed to save claim: " . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => 'Failed to save claim.']);
        }
    }

    protected function sendEmailAlerts($claim, $request)
    {
        try {
            // Provider notification
            \Mail::send('/emails/singleclaimprovidermail', [
                'Invoice' => $claim->Invoice,
                'Amount' => $claim->Amount,
                'serviceType' => $request->get('serviceType'),
                'providerType' => $request->get('providerType'),
                'invoice_date' => $request->get('invoice_date'),
                'Date' => time(),
            ], function ($message) {
                $message->from('info-alerts@aar.co.ke')
                       ->to(auth()->user()->email)
                       ->subject('A NEW SINGLE CLAIM FROM PORTAL');
            });

            Log::info("Email alert sent to user: " . auth()->user()->email);

            // Admin notification
            \Mail::send('/emails/singleclaimprovidermail1', [
                'Invoice' => $claim->Invoice,
                'Amount' => $claim->Amount,
                'serviceType' => $request->get('serviceType'),
                'providerType' => $request->get('providerType'),
                'invoice_date' => $request->get('invoice_date'),
                'Date' => time(),
            ], function ($message) {
                $message->from('info-alerts@aar.co.ke')
                       ->to('info-alerts@aar.co.ke')
                       ->subject('A NEW SINGLE CLAIM FROM PORTAL');
            });

            Log::info("Email alert sent to admin.");
        } catch (\Exception $e) {
            Log::error("Failed to send email alerts: " . $e->getMessage());
        }
    }

    public function show(Request $request, string $slug): \Illuminate\View\View
    {
        set_time_limit(0);
        Log::info("Fetching claim with slug: $slug");
        $myClaim = Claim::query()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();
        return view('Claims.show', compact('myClaim'));
    }

    public function edit(Request $request, string $slug): \Illuminate\View\View
    {
        set_time_limit(0);
        Log::info("Editing claim with slug: $slug");
        $myClaim = Claim::query()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();
        return view('Claims.edit', compact('myClaim'));
    }

    public function update(Request $request, Claim $claims): \Illuminate\Http\RedirectResponse
    {
        Log::info("Updating claim ID: {$claims->id}");

        // Custom validation rule for invoice uniqueness on update
        $invoiceValidation = Rule::unique('Claims')
            ->ignore($claims->id)
            ->where(function ($query) {
                return $query->where('user_id', auth()->user()->id);
            });

        $this->validate($request, [
            'Amount' => 'required|numeric|gt:0',
            'Invoice' => ['required', $invoiceValidation],
            'serviceType' => 'required',
            'providerType' => 'required',
            'invoice_date' => 'required|date',
        ], [
            'Invoice.unique' => 'This invoice has already been submitted by your facility. Please check and try again with a different invoice number.'
        ]);

        try {
            $claims->update($request->all());
            Log::info("Claim updated successfully: {$claims->id}");
            return redirect()->back()->with('success', 'Claim updated successfully.');
        } catch (\Exception $e) {
            Log::error("Failed to update claim: " . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => 'Failed to update claim.']);
        }
    }

    public function destroy(Claim $claims): \Illuminate\Http\RedirectResponse
    {
        Log::info("Attempting to delete claim ID: {$claims->id}");
        try {
            $claims->delete();
            Log::info("Claim deleted successfully: {$claims->id}");
            return redirect()->back()->with('success', 'Claim deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Failed to delete claim: " . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => 'Failed to delete claim.']);
        }
    }

    public function gettheclaimsprovider(): \Illuminate\View\View
    {
        return view('claimsreportforprovider');
    }

    public function getClaimsprovider(Request $request)
    {
        $query = Claim::query()->latest();

        if (!auth()->user()->can('isAdmin')) {
            $query->where('user_id', auth()->id());
        }

        if ($request->ajax()) {
            return DataTables::eloquent($query)
                ->addColumn('Amount', function (Claim $claim) {
                    return $claim->Amount ?? $claim->amount;
                })
                ->editColumn('created_at', function (Claim $claim) {
                    return optional($claim->created_at)->format('Y/m/d');
                })
                ->addIndexColumn()
                ->toJson();
        }

        abort(404);
    }

    protected function checkDuplicateInvoice(string $invoice, int $userId): bool
    {
        return Claim::where('Invoice', $invoice)
                   ->where('user_id', $userId)
                   ->exists();
    }
}