<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
	    
    protected $guarded = [];
    
    protected $casts = [
        'images' => 'array',
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }
}
