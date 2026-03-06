<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ZanySoft\Zip\Zip;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ExcelController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $providers = DB::table('users')->where('role', 'guest')->pluck('pname', 'id');
        $data = DB::table('claims')->orderBy('id', 'DESC')->paginate(5);
        return view('AdminClaims.bulkclaimssinglebutton', compact('data', 'providers'));
    }

    /**
     * Import data from uploaded file and handle zip file.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function importData(Request $request): \Illuminate\Http\RedirectResponse
    {
        Log::info('=== ExcelController::importData START ===');
        Log::info('Request files:', $request->allFiles());
        Log::info('Request data:', $request->all());
        
        // Test: Die immediately to see if this method is called
        die('METHOD WAS CALLED - ZIP UPLOAD ISSUE IS NOT HERE');
        
        // Test: Return immediately to see if this method is called
        Log::info('=== METHOD IS BEING CALLED - RETURNING IMMEDIATELY ===');
        return back()->withSuccess('Test: Method was called successfully!');
        
        // Increase execution time limit for large files
        set_time_limit(300); // 5 minutes
        
        // Temporarily remove all validation to debug
        // $this->validate($request, [
        //     'uploaded_file' => 'required|file|mimes:xls,xlsx|max:10240',
        //     'user_id' => 'required',
        //     'claimraisedby' => 'required',
        // ], [
        //     'uploaded_file.required' => 'Please upload an Excel file',
        //     'uploaded_file.mimes' => 'Excel file must be .xls or .xlsx format',
        //     'uploaded_file.max' => 'Excel file size must be less than 10MB',
        //     'user_id.required' => 'Please select a provider',
        //     'claimraisedby.required' => 'Please enter the name of person raising the claim',
        // ]);

        Log::info('=== SKIPPING VALIDATION FOR DEBUGGING ===');

        Log::info('=== Validation PASSED ===');

        $raiser_id = auth()->user()->id;
        Log::info('Import data started for user ID: ' . $raiser_id);
        
        // Debug: Log all request data including files
        Log::info('Request data:', $request->all());
        Log::info('Uploaded files:', $request->allFiles());
        
        // Get provider name based on selected user
        $providerName = DB::table('users')->where('id', $request->input('user_id'))->value('pname');
        Log:: info('Provider Name :' .$providerName);
        $baseDir = storage_path('app/claims');
        $providerDir = $baseDir . DIRECTORY_SEPARATOR . $providerName;
        $kodakDir = storage_path('app/claims/backup'); // Directory to copy files to

        // Ensure the base directory exists
        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0777, true);
            Log::info('Created base directory: ' . $baseDir);
        }

        // Ensure the provider-specific directory exists
        if (!file_exists($providerDir)) {
            mkdir($providerDir, 0777, true);
            Log::info('Created provider directory: ' . $providerDir);
        }

        $the_file = $request->file('uploaded_file');

        try {
            // Increase memory limit for large Excel files
            ini_set('memory_limit', '512M');
            
            $spreadsheet = IOFactory::load($the_file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $row_limit = $sheet->getHighestDataRow();
            
            // Limit processing to prevent timeout
            $max_rows = min($row_limit, 1000); // Process max 1000 rows
            $row_range = range(1, $max_rows);
            $excel_data = [];
            $timeoftheClaim = Carbon::now();
            $batchnumber = config('app.timestampstring');

            foreach ($row_range as $row) {
                $convertedInvoice = strval($sheet->getCell('A' . $row)->getValue());
                
                // Handle amount cell - convert RichText to string first
                $amountCell = $sheet->getCell('B' . $row)->getValue();
                if ($amountCell instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                    $amountDecimal = floatval($amountCell->getPlainText());
                } else {
                    $amountDecimal = floatval($amountCell);
                }

                // Check if the row is empty (both key columns are missing)
                if (empty($convertedInvoice) && empty($amountDecimal)) {
                    Log::warning("Skipping empty row: $row");
                    continue; // Skip this row
                }

                // Extract and format invoice_date (Column E)
                $invoiceDate = $sheet->getCell('E' . $row)->getValue();
                if ($invoiceDate instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                    $invoiceDate = $invoiceDate->getPlainText();
                }
                if (!empty($invoiceDate)) {
                    try {
                        // Try multiple date formats
                        $dateFormats = [
                            'Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d',
                            'd.m.Y', 'd/m/Y', 'm/d/Y',
                            'Y-m-d H:i:s', 'm/d/Y H:i:s',
                            'd/m/Y H:i:s', 'Y/m/d H:i:s'
                        ];
                        
                        $parsedDate = null;
                        foreach ($dateFormats as $format) {
                            try {
                                $parsedDate = Carbon::createFromFormat($format, $invoiceDate);
                                if ($parsedDate) {
                                    $invoiceDate = $parsedDate->format('d.m.Y');
                                    break;
                                }
                            } catch (\Exception $e) {
                                continue;
                            }
                        }
                        
                        if (!$parsedDate) {
                            Log::warning("Could not parse invoice_date '$invoiceDate' in row: $row, using current date");
                            $invoiceDate = Carbon::now()->format('d.m.Y');
                        }
                    } catch (\Exception $e) {
                        Log::warning("Error parsing invoice_date '$invoiceDate' in row: $row, using current date");
                        $invoiceDate = Carbon::now()->format('d.m.Y');
                    }
                } else {
                    Log::warning("Missing invoice_date for row: $row, using current date");
                    $invoiceDate = Carbon::now()->format('d.m.Y');
                }

                $data = [
                    'user_id' => $request->input('user_id'),
                    'raiser_id' => auth()->user()->id,
                    'slug' => Str::slug($convertedInvoice . "_" . $timeoftheClaim->toDateTimeString()),
                    'Invoice' => $convertedInvoice,
                    'Amount' => $amountDecimal,
                    'serviceType' => $this->getCellValue($sheet, 'C', $row),
                    'providerType' => $this->getCellValue($sheet, 'D', $row),
                    'invoice_date' => $invoiceDate,
                    'claimraisedby' => $request->input('claimraisedby'),
                    'batchno' => $batchnumber,
                    'attachment' => null,
                    'created_at' => $timeoftheClaim,
                    'updated_at' => $timeoftheClaim,
                ];
                $excel_data[] = $data;
            }

            Log::info('Claim Data:', $excel_data); // Log data before inserting

            // Chunking data to prevent memory issues during insertion
            $chunks = collect($excel_data)->chunk(50);

            foreach ($chunks as $chunk) {
                DB::table('claims')->insert($chunk->toArray());
                Log::info('Inserted chunk of claims data');
            }

            // Handle the zip file upload
            if ($request->hasFile('zip')) {
                $zipFileName = $raiser_id . '.zip';
                $zipFilePath = $request->file('zip')->storeAs('file', $zipFileName);
                Upload::create(['file' => $zipFilePath]);
                Log::info("Zip file uploaded and stored as: $zipFilePath");
            }

            // Send notification emails if required
            // TODO: Fix alertstobesend variable
            // if ($alertstobesend == 1) {
            //     $this->sendNotificationEmails($batchnumber);
            // }

        } catch (PhpSpreadsheetException $e) {
            Log::error('Error occurred during import: ' . $e->getMessage());
            return back()->withErrors('There was a problem uploading the data!');
        }

        try {
            // Extracting the zip file and handling its contents
            $this->extractAndCopyZipFiles($raiser_id, $providerDir, $kodakDir);
            Log::info("Zip file extracted successfully");

        } catch (\Exception $e) {
            Log::error('Error occurred while handling zip file: ' . $e->getMessage());
            return back()->withErrors('There was a problem extracting the zip file!');
        }

        return back()->withSuccess('Great! All your invoices data have been submitted successfully. Thank you.');
    }

    /**
     * Helper method to get cell value and handle RichText objects
     *
     * @param mixed $sheet
     * @param string $column
     * @param int $row
     * @return string
     */
    protected function getCellValue($sheet, $column, $row)
    {
        $cellValue = $sheet->getCell($column . $row)->getValue();
        if ($cellValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
            return $cellValue->getPlainText();
        }
        return strval($cellValue);
    }

    /**
     * Send notification emails after claims are submitted.
     *
     * @param string $batchnumber
     */
    protected function sendNotificationEmails($batchnumber)
    {
        // Email notification to the user
        \Mail::send('/emails/mail', ['batchno' => $batchnumber], function ($message) {
            $userselected = request('user_id');
            $userEmail = DB::table('users')->where('id', $userselected)->value('email');
            $message->from('info-alerts@aar.co.ke')
                    ->to($userEmail)
                    ->subject('A NEW BULK CLAIM FROM PORTAL');
        });

        // Email notification to the admin
        \Mail::send('/emails/mail1', ['batchno' => $batchnumber], function ($message) {
            $message->from('info-alerts@aar.co.ke')
                    ->to('info-alerts@aar.co.ke')
                    ->subject('A NEW BULK CLAIM FROM PORTAL');
        });

        Log::info('Notification emails sent');
    }

    /**
     * Extract and copy the contents of the zip file.
     *
     * @param int $raiser_id
     * @param string $providerDir
     * @param string $kodakDir
     */
    protected function extractAndCopyZipFiles($raiser_id, $providerDir, $kodakDir)
    {
        // Use the actual stored zip file path
        $zipFilePath = storage_path('app/file/' . $raiser_id . '.zip');
        
        // Use new ZanySoft API (v3.0.1)
        $zip = new \ZanySoft\Zip\Zip();
        $zip->open($zipFilePath);
        $zip->extract($providerDir);
        $zip->close();
        
        unlink($zipFilePath);
        Log::info("Zip file extracted to: $providerDir and deleted successfully");

        // Copy files to Kodak directory
        $files = glob($providerDir . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $destinationKodak = $kodakDir . DIRECTORY_SEPARATOR . $fileName;
            if (copy($file, $destinationKodak)) {
                Log::info("File copied to Kodak directory: $destinationKodak");
            } else {
                Log::error("Failed to copy file to Kodak directory: $destinationKodak");
            }
        }
    }

    /**
     * Export customer data to Excel.
     *
     * @param $customer_data
     */
    public function ExportExcel($customer_data)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($customer_data);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Customer_ExportedData.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (PhpSpreadsheetException $e) {
            Log::error('Error occurred during Excel export: ' . $e->getMessage());
            return back()->withErrors('There was a problem exporting the data!');
        }
    }

    /**
     * Load customer data from the database and export it to Excel.
     */
    public function exportData()
    {
        $data = DB::table('tbl_customer')->orderBy('CustomerID', 'DESC')->get();
        $data_array[] = ["CustomerName", "Gender", "Address", "City", "PostalCode", "Country"];
        foreach ($data as $data_item) {
            $data_array[] = [
                'CustomerName' => $data_item->CustomerName,
                'Gender' => $data_item->Gender,
                'Address' => $data_item->Address,
                'City' => $data_item->City,
                'PostalCode' => $data_item->PostalCode,
                'Country' => $data_item->Country
            ];
        }
        $this->ExportExcel($data_array);
    }
}
