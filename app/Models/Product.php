<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
     'owner_id',
     'name',
     'image',
     'exp_date',
     'category',
     'contact_info',
     'quantity',
     'price',
     'views',
     "likes",
     'discount_date_1',
     'discount_value_1',
     'discount_date_2',
     'discount_value_2',
     'discount_date_3',
     'discount_value_3'
    ];
}
