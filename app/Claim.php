<?php

namespace App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Claim extends Model
{
    protected $primaryKey = 'slug';
    public $incrementing = false;
    protected $fillable = [
        'user_id',
        'raiser_id',
        'Invoice',
        'Amount',
        'serviceType',
        'providerType',
        'attachment',
        'invoice_date',
        'slug',
        'claimraisedby',
        'batchno',
        'file_uploaded',
        'encounter',
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
