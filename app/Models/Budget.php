<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'wallet_id', // Added wallet_id to fillable
        'category_id',
        'amount',
        'period',
        'start_date',
        'end_date',
    ];

    /**
     * Get the user that owns the budget.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that the budget belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the wallet that the budget belongs to.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
