<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kyslik\ColumnSortable\Sortable;

class Claim extends Model
{
    use HasFactory, Sortable;

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

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'Amount' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the claim.
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who raised the claim.
     */
    public function raiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'raiser_id');
    }
}
