<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->select('id', 'name');
    }
    public function tailor()
    {
        return $this->belongsTo(Tailor::class, 'tailor_id', 'id')->select('id', 'name');
    }
}
