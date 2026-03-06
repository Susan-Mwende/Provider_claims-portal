namespace App\Http\Controllers;

use App\Imports\ClaimImports1;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Zip;
use Redirect;
use File;

class Claimsbulk1Controller extends Controller
{
    public function importExportView()
    {
        return view('importbulk');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function claimsbulke()
    {
        return Excel::download(new ClaimsExport, 'claims.xlsx');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            dd('Please attach an Excel file with data.');
        }

        if (!$request->file('file')->isValid()) {
            dd('The file attached is not a valid Excel file.');
        }

        $file = $request->file('file');
        $user_id = $request->input('user_id');

        $extension = $file->getClientOriginalExtension();

        if (in_array(strtolower($extension), ["csv", "xlsx", "xls"])) {
            Excel::import(new ClaimImports1, request()->file('file'), $user_id);

            $raiser_id = auth()->user()->id;
            $zipPath = 'E:\ADMIN CLAIMS\BULK\\' . $raiser_id . '.zip';

            // Copy the file to the new location
            File::copy($zipPath, 'X:\portal_claims_backup\admin_bulk_claims\\' . $raiser_id . '.zip');

            // Open and extract the zip file
            $zip = Zip::open($zipPath);
            $zip->extract('E:\ADMIN CLAIMS\BULK\SINGLE');
            $zip->close();

            // Delete the zip file after extraction
            unlink($zipPath);

            return back();
        } else {
            $status = 'Sorry, this file extension is not allowed';
            return Redirect::back()->with(session()->flash('Error_flash_message', $status));
        }
    }
}
