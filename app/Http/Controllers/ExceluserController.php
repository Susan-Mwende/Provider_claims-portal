<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ZanySoft\Zip\Zip;
use App\Models\Upload;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ExceluserController extends Controller
{
    public function importData(Request $request)
    {
        try {
            Log::info("Starting importData process.");

            $validator = Validator::make($request->all(), [
                'uploaded_file' => 'required|file|mimes:xls,xlsx|max:1024000',
                // 'zip' => 'nullable|file|mimes:zip,rar,rar4|max:1024000', // Temporarily disabled
                'claimraisedby' => 'required|string|max:255',
                'claims_from' => 'required|date',
                'claims_to' => 'required|date|after_or_equal:claims_from',
            ]);

            Log::info('Request data for validation:', $request->all());
            Log::info('Validation rules:', [
                'uploaded_file' => 'required|file|mimes:xls,xlsx|max:1024000',
                // 'zip' => 'nullable|file|mimes:zip,rar,rar4|max:1024000', // Temporarily disabled
                'claimraisedby' => 'required|string|max:255',
                'claims_from' => 'required|date',
                'claims_to' => 'required|date|after_or_equal:claims_from',
            ]);

            if ($validator->fails()) {
                Log::error("Validation failed.", ['errors' => $validator->errors()]);
                return back()->withErrors($validator)->withInput();
            }

            $raiser = auth()->user();
            Log::info("User authenticated: $raiser->name (ID: $raiser->id)");

            $claims_from = $request->input('claims_from');
            $claims_to = $request->input('claims_to');
            $the_file = $request->file('uploaded_file');

            // Backup the uploaded zip file
            if ($request->hasFile('zip')) {
                $this->backupZipFile($request->file('zip'), $claims_from, $claims_to, $raiser);
            }

            // Process Excel file
            Log::info("Loading Excel file: " . $the_file->getClientOriginalName());
            $spreadsheet = IOFactory::load($the_file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $highest_row = $sheet->getHighestDataRow();
            $excel_data = [];
            $time_of_claim = Carbon::now();
            $batch_number = config('app.timestampstring');
            $alertstobesend = DB::table('users')->where('id', $raiser->id)->value('sendalert');

            Log::info("Excel file loaded successfully. Processing rows with non-empty invoice...");

            // Skip header rows (first 2 rows contain headers)
            for ($row = 3; $row <= $highest_row; $row++) {
                $this->processRow($sheet, $row, $excel_data, $raiser, $time_of_claim, $request);
            }

            Log::info("Excel data processing completed. Total rows processed: " . count($excel_data));
            $this->insertDataInChunks($excel_data);

            if ($request->hasFile('zip')) {
                $this->handleZipFile($request->file('zip'), $raiser, $claims_from, $claims_to);
            }

            if ($alertstobesend == 1) {
                $this->sendEmailAlerts($raiser, $batch_number);
            }

            Log::info("ImportData process completed successfully.");
            return back()->withSuccess('Great! All your invoices data have been submitted successfully. Thank you');
        } catch (\Exception $e) {
            Log::error("An error occurred during import: " . $e->getMessage());
            return back()->withErrors('There was a problem uploading the data!');
        }
    }

    private function backupZipFile($zip_file, $claims_from, $claims_to, $raiser)
    {
        Log::info("Backup of zip file started.");
        $backup_directory = 'E:\\BULK_RAW_DATA\\' . $raiser->name;

        if (!file_exists($backup_directory)) {
            mkdir($backup_directory, 0777, true);
            Log::info("Backup directory created: $backup_directory");
        }

        $zip_name = pathinfo($zip_file->getClientOriginalName(), PATHINFO_FILENAME);
        $backup_filename = $zip_name . ' from ' . Carbon::parse($claims_from)->format('M d, Y') . ' to ' . Carbon::parse($claims_to)->format('M d, Y') . '.zip';
        $backup_path = $backup_directory . '\\' . $backup_filename;
        copy($zip_file->getRealPath(), $backup_path);
        Log::info("Backup of zip file created at: $backup_path");
    }

    private function processRow($sheet, $row, &$excel_data, $raiser, $time_of_claim, $request)
    {
        $invoice = $sheet->getCell('A' . $row)->getValue(); // Assuming invoice number is in column A
        if (empty($invoice)) {
            return;
        }

        $amount_decimal = $this->sanitizeDecimal($sheet->getCell('B' . $row)->getValue());
        $converted_invoice = strval($invoice);
        $invoice_date_raw = $sheet->getCell('E' . $row)->getValue();

        Log::info("Processing row $row: Invoice = $converted_invoice");

        $invoice_date = $this->parseInvoiceDate($invoice_date_raw, $row);
        if (!is_numeric($amount_decimal)) {
            Log::warning("Invalid amount for row $row: $amount_decimal");
            return;
        }

        $data = [
            'user_id' => $raiser->id,
            'raiser_id' => $raiser->id,
            'slug' => Str::slug($converted_invoice . "_" . $time_of_claim->toDateTimeString()),
            'Invoice' => $converted_invoice,
            'Amount' => $amount_decimal,
            'serviceType' => $sheet->getCell('C' . $row)->getValue(),
            'providerType' => $sheet->getCell('D' . $row)->getValue(),
            'invoice_date' => $invoice_date,
            'encounterno' => $sheet->getCell('F' . $row)->getValue(),
            'attachment' => $converted_invoice . ".pdf",
            'batchno' => config('app.timestampstring'),
            'claimraisedby' => $request->input('claimraisedby'),
            'created_at' => $time_of_claim,
            'updated_at' => $time_of_claim,
        ];
        $excel_data[] = $data;
    }

    private function parseInvoiceDate($invoice_date_raw, $row)
    {
        try {
            if (!empty($invoice_date_raw)) {
                return Carbon::parse($invoice_date_raw)->format('Y-m-d');
            } else {
                Log::warning("Invoice date missing for row $row");
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Error parsing invoice date for row $row: " . $e->getMessage());
            return null;
        }
    }

    private function insertDataInChunks($excel_data)
    {
        Log::info("Inserting data in chunks...");
        
        if (empty($excel_data)) {
            Log::warning("No data to insert - Excel data array is empty");
            return;
        }
        
        $max_params = 2000;
        $params_per_row = count($excel_data[0]);
        $chunk_size = intdiv($max_params, $params_per_row);
        Log::info("Chunk size calculated: $chunk_size rows per chunk.");

        $chunks = array_chunk($excel_data, $chunk_size);
        foreach ($chunks as $chunk) {
            DB::table('claims')->insert($chunk);
            Log::info("Inserted chunk of size " . count($chunk) . ".");
        }
        Log::info("Excel data inserted into database successfully.");
    }

    private function handleZipFile($zip_file, $raiser, $claims_from, $claims_to)
    {
        Log::info("Storing zip file...");
        $zip_path = $zip_file->storeAs('file', $raiser->id . '.zip');
        Upload::create(['file' => $zip_path]);
        Log::info("Zip file stored successfully: $zip_path");

        $user_directory = 'E:\\' . $raiser->name;
        if (!file_exists($user_directory)) {
            mkdir($user_directory, 0777, true);
            Log::info("User directory created: $user_directory");
        }

        $zip_name = pathinfo($zip_file->getClientOriginalName(), PATHINFO_FILENAME);
        $directory_name = $zip_name . ' from ' . Carbon::parse($claims_from)->format('M d, Y') . ' to ' . Carbon::parse($claims_to)->format('M d, Y');
        $zip_directory = $user_directory . '\\' . $directory_name;

        if (file_exists($zip_directory)) {
            $this->deleteDirectory($zip_directory);
            Log::info("Existing zip directory deleted: $zip_directory");
        }

        mkdir($zip_directory, 0777, true);
        Log::info("Zip directory created: $zip_directory");

        $zip = Zip::open(storage_path('app/' . $zip_path));
        // Get the list of files in the ZIP
        $zip_files = $zip->listFiles();
        $num_files_in_zip = count($zip_files);

        Log::info("Number of files in ZIP: $num_files_in_zip");
        Log::info("Extracting zip file...");
        try {
            $zip->extract($zip_directory);
            
            Log::info("Zip file extracted to directory: $zip_directory");
        } catch (\Exception $e) {
            Log::error("Error during zip extraction: " . $e->getMessage());
        }

        $zip->close();
        Storage::delete($zip_path);
        Log::info("Temporary zip file deleted: $zip_path");

        $kodak_directory = 'E:\\KODAK BULK CLAIMS\\' . $directory_name;
        if (file_exists($kodak_directory)) {
            $this->deleteDirectory($kodak_directory);
            Log::info("Existing Kodak directory deleted: $kodak_directory");
        }

        mkdir($kodak_directory, 0777, true);
        Log::info("Kodak directory created: $kodak_directory");

        $this->copyDirectory($zip_directory, $kodak_directory);
        Log::info("Copied unzipped folder to Kodak directory: $kodak_directory");
    }

    private function sendEmailAlerts($raiser, $batch_number)
    {
        Log::info("Sending email alerts to user ID: $raiser->id");
        Mail::send('/emails/mail', ['batchno' => $batch_number, 'Date' => time()], function ($message) use ($raiser) {
            $message->from('info-alerts@aar.co.ke');
            $message->to($raiser->email);
            $message->subject('Claim Created');
        });
        Log::info("Email alerts sent successfully.");
    }

    private function sanitizeDecimal($value)
    {
        $value = preg_replace('/[^\d.,-]/', '', $value);
        return (float) str_replace(',', '.', $value);
    }

    private function deleteDirectory($dir)
    {
        if (file_exists($dir)) {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($filePath)) {
                    $this->deleteDirectory($filePath);
                } else {
                    unlink($filePath);
                }
            }
            rmdir($dir);
        }
    }

    private function flattenDirectory($dir)
{
    Log::info("Flattening directory: $dir");
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $filePath = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_file($filePath) && strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) === 'pdf') {
            $uniqueDestination = $this->copyFileWithUniqueName($dir, basename($file));
            rename($filePath, $uniqueDestination);
            Log::info("Moved PDF file to: $uniqueDestination");
        } elseif (is_dir($filePath)) {
            $this->flattenDirectory($filePath);

            // Check if the subdirectory is empty after flattening
            if (count(scandir($filePath)) === 2) { // Only contains '.' and '..'
                rmdir($filePath);
                Log::info("Deleted empty subdirectory: $filePath");
            }
        }
    }
}

    private function copyFileWithUniqueName($dir, $filename)
    {
        $destination = $dir . DIRECTORY_SEPARATOR . $filename;
        $counter = 1;
        while (file_exists($destination)) {
            $destination = $dir . DIRECTORY_SEPARATOR . pathinfo($filename, PATHINFO_FILENAME) . "_$counter." . pathinfo($filename, PATHINFO_EXTENSION);
            $counter++;
        }
        return $destination;
    }

    private function copyDirectory($source, $destination)
    {
        if (is_dir($source)) {
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            $files = array_diff(scandir($source), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $source . DIRECTORY_SEPARATOR . $file;
                if (is_dir($filePath)) {
                    $this->copyDirectory($filePath, $destination . DIRECTORY_SEPARATOR . $file);
                } else {
                    $uniqueDestination = $this->copyFileWithUniqueName($destination, $file);
                    copy($filePath, $uniqueDestination);
                    Log::info("Copied file from $filePath to $uniqueDestination");
                }
            }
        }
    }
}
