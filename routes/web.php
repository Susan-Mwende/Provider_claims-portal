<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminClaimsController,
    AdminUsersController,
    ClaimsController,
    ClaimsbulkController,
    Claimsbulk1Controller,
    ClaimsViewTestController,
    DateRangeController,
    ExcelController,
    ExceluserController,
    ExportController,
    FileController,
    HomeController,
    MyController,
    ProviderController,
    ReportController,
    TestClaimsController,
    UploadController,
    UserController
};
use App\Http\Controllers\Auth\{
    TwoFactorController,
    PwdExpirationController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true, 'register' => false]);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/suspended', function () {
    return view('auth.InactiveUser');
});

Route::get('/providers', [ProviderController::class, 'index'])->name('providers');
Route::get('/providers/{user_id}/approve', [ProviderController::class, 'approve'])->name('providers.approve');
Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);

// Test route to verify basic routing works
Route::get('/test-admin', function() {
    return 'Admin route works! Susan can access admin.';
});

Route::get('/test-verify', function() {
    return 'Verify route works!';
});

// Handle uppercase Admin route (case mismatch fix)
Route::get('/Admin', function() {
    return redirect('/admin');
});
Route::get('/Claims/search', [ClaimsController::class, 'search'])->name('Claims.search');
Route::resource('/Claims', ClaimsController::class);

//debug claims report
Route::resource('/Test', TestClaimsController::class);

Route::get('/Claims.bulkclaimsusersinglebutton.import', [ExceluserController::class, 'index'])->name('bulkclaimsusersinglebutton');
Route::post('Claims.bulkclaimsusersinglebutton.import', [ExceluserController::class, 'importData'])->name('bulkclaimsusersinglebutton');

Route::get('claimsreportforprovider', [ClaimsController::class, 'gettheclaimsprovider']);
Route::get('claimsreportforprovider/reports', [ClaimsController::class, 'getClaimsprovider'])->name('claimsreportforprovider');

// Admin Only
Route::middleware(['can:isAdmin', 'twofactor'])->group(function () {
    Route::get('/admin', [AdminUsersController::class, 'index'])->name('home');

    // Users management
    Route::get('/users', [AdminUsersController::class, 'add_users_form'])->name('users.add');
    Route::get('/search', [AdminUsersController::class, 'search'])->name('users.search');
    Route::post('/users', [AdminUsersController::class, 'submit_users_data'])->name('users.save');
    Route::get('/users/list', [AdminUsersController::class, 'fetch_all_users'])->name('users.list');
    Route::get('/users/edit/{user}', [AdminUsersController::class, 'edit_users_form'])->name('users.edit');
    Route::patch('/users/edit/{user}', [AdminUsersController::class, 'edit_users_form_submit'])->name('users.update');
    Route::get('/users/{user}', [AdminUsersController::class, 'view_single_users'])->name('users.view');
    Route::delete('/users/{user}', [AdminUsersController::class, 'delete_users'])->name('users.delete');

    Route::get('/AdminClaims.getbulkclaimssinglebutton.import', [ExcelController::class, 'index'])->name('viewbulkclaimssinglebutton');
    Route::post('AdminClaims.bulkclaimssinglebutton.import', [ExcelController::class, 'importData'])->name('bulkclaimssinglebutton');

    // Admin Claim
    Route::get('/AdminClaims/Allclaims', [AdminClaimsController::class, 'fetch_all_AdminClaims'])->name('AdminClaims.list');
    Route::get('/AdminClaims', [AdminClaimsController::class, 'add_AdminClaims_form'])->name('AdminClaims.add');
    Route::get('/AdminClaims/search', [AdminClaimsController::class, 'search'])->name('AdminClaims.search');
    Route::post('/AdminClaims', [AdminClaimsController::class, 'submit_AdminClaims_data'])->name('AdminClaims.save');
    Route::get('/AdminClaims/edit/{claim}', [AdminClaimsController::class, 'edit_AdminClaims_form'])->name('AdminClaims.edit');
    Route::patch('/AdminClaims/edit/{claim}', [AdminClaimsController::class, 'edit_AdminClaims_form_submit'])->name('AdminClaims.update');
    Route::get('/AdminClaims/{claim}', [AdminClaimsController::class, 'view_single_AdminClaims'])->name('AdminClaims.view');
    Route::delete('/AdminClaims/{claim}', [AdminClaimsController::class, 'delete_AdminClaims'])->name('AdminClaims.delete');
    Route::get('pdfview', [AdminClaimsController::class, 'pdfview'])->name('pdfview');
    Route::get('logActivity', [HomeController::class, 'logActivity'])->name('audit-trails');
    Route::get('search/adminclaims', [AdminClaimsController::class, 'searchInClaims'])->name('claims.results');
    Route::resource('daterange', DateRangeController::class);
    Route::resource('ClaimsView', ClaimsViewTestController::class);

    Route::get('userslist', [UserController::class, 'userslist'])->name('userslist');
    Route::get('claimslist1', [AdminClaimsController::class, 'gettheclaims']);
    Route::get('claimslist1/report', [AdminClaimsController::class, 'getClaims'])->name('claimslist1');
});

// Home Page Route
Route::group(['middleware' => ['auth', 'twofactor']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('verified');
});

Route::middleware('can:isAuditor')->group(function () {
    Route::get('Reporting/dashboard', [ReportController::class, 'index'])->name('reporting.dashboard');
    Route::get('ClaimsReports', [ReportController::class, 'gettheclaims']);
    Route::get('ClaimsReports/report', [ReportController::class, 'getClaims'])->name('ClaimsReports');
});

Route::middleware('can:isGuest')->group(function () {
    //
});

Route::get('file', [FileController::class, 'create']);
Route::post('file', [FileController::class, 'store']);

Route::get('export', [MyController::class, 'export'])->name('export');
Route::get('importExportView', [MyController::class, 'importExportView']);
Route::post('import', [MyController::class, 'import'])->name('import');

// CLAIMS BULK IMPORT - Admin
Route::get('claimsbulke', [Claimsbulk1Controller::class, 'export'])->name('claimsbulke');
Route::get('importExportView', [Claimsbulk1Controller::class, 'importExportView']);
Route::post('admin-upload-bulk-Claims', [Claimsbulk1Controller::class, 'import'])->name('admin-upload-bulk-Claims');

// CLAIMS BULK IMPORT - User
Route::get('claimsbulke', [Claimsbulk1Controller::class, 'export'])->name('claimsbulke');
Route::get('importExportView', [ClaimsbulkController::class, 'importExportView']);
Route::post('upload-bulk-Claims', [ClaimsbulkController::class, 'import'])->name('upload-bulk-Claims');

Route::get('/upload', [UploadController::class, 'index']);
Route::post('/upload', [UploadController::class, 'store']);
Route::get('Getproviders', [AdminClaimsController::class, 'retieveProvider']);
Route::get('/passwordChange', [PwdExpirationController::class, 'showPasswordExpirationForm']);
Route::get('/passwordExpiration', [PwdExpirationController::class, 'showPasswordExpirationForm']);
Route::post('/passwordExpiration', [PwdExpirationController::class, 'postPasswordExpiration'])->name('passwordExpiration');
Route::resource('export', ExportController::class);

//display single claim

Route::get('/pdf/{file}', function ($file) {
    // file path
   $path = public_path('portal_claims_backup/single_claims' . '/' . $file);
    // header
   $header = [
     'Content-Type' => 'application/pdf',
     'Content-Disposition' => 'inline; filename="' . $file . '"'
   ];
  return response()->file($path, $header);
})->name('pdf');


//Route::resource('ClaimsViewReport', 'ClaimsViewReportController');
