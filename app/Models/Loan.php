<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    protected $table = 'loan';

    protected $fillable = ['user_id', 'amount', 'payble_amount', 'loan_tenure', 'status','description','interest_rate'];

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    public function scopeStatus($query, $value)
    {
        return $query->where('status', $value);
    }
    
}
