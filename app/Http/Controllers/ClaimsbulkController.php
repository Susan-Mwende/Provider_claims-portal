<?php

namespace App\Http\Controllers;

use Redirect;
use App\Imports\ClaimImports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use ZanySoft\Zip\Zip;
use Carbon\Carbon;
use Log;

class ClaimsbulkController extends Controller
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
     * Handle the import process
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls',
            'user_id' => 'required|integer',
            'claims_from' => 'required|date',
            'claims_to' => 'required|date',
        ]);

        $user_id = $request->input('user_id');
        $file = $request->file('file');
        $claims_from = Carbon::parse($request->input('claims_from'));
        $claims_to = Carbon::parse($request->input('claims_to'));
        $extension = $file->getClientOriginalExtension();
        $zip_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Get the original file name without extension

        if (!in_array(strtolower($extension), ['csv', 'xlsx', 'xls'])) {
            return Redirect::back()->withErrors(['file' => 'Sorry, this file extension is not allowed']);
        }

        // Import the Excel file
        Excel::import(new ClaimImports, $file, $user_id);
        $raiser_id = auth()->user()->id;

        // Define paths
        $zipFileUploadPath = storage_path('app/file');
        $backupPath = "E:\\ADMIN\\BULK RAW DATA.{$user_id}";
        $kodakBackupPath = "E:\\KODAK BULK CLAIMS";

        // Create backup directories if they do not exist
        $this->createDirectoryIfNotExists($backupPath);
        $this->createDirectoryIfNotExists($kodakBackupPath);

        // Define backup filenames
        $backupFilename = $zip_name . ' from ' . $claims_from->format('M d, Y') . ' to ' . $claims_to->format('M d, Y') . '.zip';
        $zipPath = $zipFileUploadPath . '\\' . $raiser_id . '.zip';

        // Backup the uploaded zip file
        $this->backupFile($zipPath, $backupPath, $backupFilename);
        $this->backupFile($zipPath, $kodakBackupPath, $zip_name);

        // Define the extraction path
        $extractionPath = "E:\\ADMIN\\{$user_id}";
        $extractedFolder = $zip_name . ' from ' . $claims_from->format('M d, Y') . ' to ' . $claims_to->format('M d, Y');
        $fullExtractionPath = $extractionPath . '\\' . $extractedFolder;

        // Create extraction directory if it does not exist
        $this->createDirectoryIfNotExists($fullExtractionPath);

        // Extract the zip file
        try {
            $zip = Zip::open($zipPath);
            Log::info('Extraction path: ' . $fullExtractionPath);
            $zip->extract($fullExtractionPath);
            $zip->close();

            // Count the number of extracted files
            $extractedFiles = scandir($fullExtractionPath);
            $fileCount = count(array_diff($extractedFiles, ['.', '..']));
            Log::info("Number of files extracted: $fileCount");

            // Delete the zip file after extraction
            if (unlink($zipPath)) {
                Log::info("Temporary zip file deleted: $zipPath");
            } else {
                Log::error("Failed to delete temporary zip file: $zipPath");
            }
        } catch (\Exception $e) {
            Log::error('Error extracting zip file: ' . $e->getMessage());
            return Redirect::back()->withErrors(['Error extracting zip file: ' . $e->getMessage()]);
        }

        return Redirect::back()->with('success', 'File imported and processed successfully.');
    }

    /**
     * Create a directory if it does not exist
     *
     * @param string $path
     * @return void
     */
    private function createDirectoryIfNotExists($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
            Log::info("Directory created: $path");
        }
    }

    /**
     * Backup a file to a specified directory
     *
     * @param string $filePath
     * @param string $backupPath
     * @param string $backupFilename
     * @return void
     */
    private function backupFile($filePath, $backupPath, $backupFilename)
    {
        if (file_exists($filePath)) {
            $backupFilePath = $backupPath . '\\' . $backupFilename;
            if (copy($filePath, $backupFilePath)) {
                Log::info("File backed up successfully: $backupFilePath");
            } else {
                Log::error("Failed to backup file: $backupFilePath");
            }
        } else {
            Log::error("File does not exist: $filePath");
        }
    }
}
