<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $with = ['product', 'user'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_no',
        'user_id',
        'product_id',
        'quantity',
        'item_price',
        'total_price',
        'status',
        'paid_at',
        'updated_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // before
        self::creating(function ($model) {
            $model->user_id = auth()->id();
        });

        self::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        // after
        self::created(function ($model) {
            $model->product->calculateStock();
        });

        self::updated(function ($model) {
            $model->product->calculateStock();
        });

        self::deleted(function ($model) {
            $model->product->calculateStock();
        });
    }
}
