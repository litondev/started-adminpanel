<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
	use SoftDeletes;
	
    protected $guarded = [];    

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function customer(){
    	return $this->belongsTo(Customer::class);
    }

    public function transaction_details(){
    	return $this->hasMany(TransactionDetail::class);
    }
}
