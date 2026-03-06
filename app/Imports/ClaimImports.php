<?php

namespace App\Imports;

use App\Models\Claim;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClaimImports implements ToModel, WithValidation
{
    use Importable;
    private $rows = 0;
    public $batchnumber;


    //public $batchnumber = Carbon::now();
    //private $batchnumber = strtotime("now");
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function rules(): array
    {
        return [
            '0' => ['unique:claims,Invoice','required'],
            '1' => ['required','numeric','gt:0'],
            '2' => 'required',
            '3' => 'required',
        ];

    }
    public function customValidationMessages()
    {
        return [
            '0.unique' => 'Invoice Number cannot be duplicated! Please remove all duplicates.',
            '0.required' => 'Invoice Number cannot be blank. Please fill all invoices in all rows',
            '1.required' => 'Amount cannot be blank. Fill all row details.',
            '1.gt:0' => 'Amount can only be a number and not any other format.',

        ];
    }
    //private $batchnumber1 = strtotime("now");
    public function model(array $row)
    {
      $counter =   ++$this->rows;
        $date = strtotime($row[4]);
        $date1 = date('Y-m-d',$date);
        $claimtime = Carbon::now();
        $batchnumber = config('app.timestampstring');
        $alertstobesend = DB::table('users')->where('id', request('user_id'))->value('sendalert');
        Claim::create([
            'Amount'     => $row[1],
            'Invoice'     => strval($row[0]),
            'user_id' => request('user_id'),
            'claimraisedby' => request('claimraisedby'),
            'raiser_id'     => auth()->user()->id,
            'slug' => \Str::slug($row[0]."_".$claimtime->toDateTimeString()),
            'serviceType'     => $row[2],
            'providerType'    => $row[3],
            'invoice_date'    => $date1,
            'batchno' => $batchnumber,
            'attachment'     => $row[0].".pdf",
        ]);

        if ($alertstobesend == 1) {
            if ($counter ==1) {
                //Send Email Notification to the user
                \Mail::send('/emails/mail', array(
                    'batchno'     => $batchnumber,
                    'Date' => time(),
                ), function ($message) {
                    $message->from('info-alerts@aar.co.ke');
                    $message->to(auth()->user()->email)->subject('A NEW BULK CLAIM FROM PORTAL');
                });
                //Send Email Notification to the Admin
                \Mail::send('/emails/mail1', array(
                    'batchno'     => $batchnumber,
                    'Date' => time(),
                ), function ($message) {
                    $message->from('info-alerts@aar.co.ke');
                    $message->to('info-alerts@aar.co.ke')->subject('A NEW BULK CLAIM FROM PORTAL');
                });
            }
        }
        //dd(($counter));
        /*    if(DB::table('users')->where('id', request('user_id'))->value('sendalert')==1) {
                return
                    \Mail::send('/emails/mail', array(
                        'Invoice'     => str_replace("-", "",$row[0]),
                        'Amount' => $row[1],
                        'serviceType' => $row[2],
                        'providerType' => $row[3],
                        'invoice_date' => $date1,
                        'Date' => time(),
                    ), function($message) use ($row){
                        $message->from('info-alerts@aar.co.ke');
                        $message->to(auth()->user()->email)->subject('A NEW BULK CLAIM FROM PORTAL');

                    });
            }*/
        //Send Email Notification to the Admin
        /*   if($alertstobesend==1) {
               \Mail::send('/emails/mail1', array(
                   'Invoice'     => str_replace("-", "",$row[0]),
                   'Amount' => $row[1],
                   'serviceType' => $row[2],
                   'providerType' => $row[3],
                   'invoice_date' => $date1,
                   'Date' => time(),
               ), function($message) use ($row){
                   $message->from('info-alerts@aar.co.ke');
                   $message->to('info-alerts@aar.co.ke')->subject('A NEW BULK CLAIM FROM PORTAL');

               });
           }*/

    }
    public function getRowCount(): int
    {

        return $this->rows;
    }

}
