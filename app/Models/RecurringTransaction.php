<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'description',
        'amount',
        'currency',
        'type',
        'frequency',
        'start_date',
        'end_date',
        'last_run_date',
        'next_run_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'last_run_date' => 'date',
        'next_run_date' => 'date',
    ];

    /**
     * Get the user that owns the recurring transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that the recurring transaction belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
