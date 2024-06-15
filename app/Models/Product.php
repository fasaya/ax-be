<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $with = ['supplier'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'code',
        'name',
        'description',
        'status',
        'price',
        'discounted_price',
        'stock',
        'image',
        'created_by',
        'updated_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function calculateStock(): int
    {
        // Count quantity from stock
        $stock = Stock::where('product_id', $this->id)->sum('quantity');

        // Count quantity from transaction
        $transaction = Transaction::where('product_id', $this->id)->sum('quantity');

        // Calculate stock
        $calculate = $stock - $transaction;

        // Update product quantity 
        self::where('id', $this->id)->update(['stock' => $calculate]);

        return $calculate;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        self::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }
}
