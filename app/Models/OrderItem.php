<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function jama()
    {
        return $this->belongsTo(Jama::class, 'jama_id', 'id');
    }
    public function payjama()
    {
        return $this->belongsTo(Payjama::class, 'payjama_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->select('id', 'name', 'category_id')->with('category');
    }
    
}
