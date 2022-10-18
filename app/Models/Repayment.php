<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Repayment extends Model
{
    use SoftDeletes;

    protected $table = 'scheduled_repayment';

    protected $fillable = ['loan_id', 'repayment_amount', 'repayment_date', 'status','repayment_method'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function scopeStatus($query, $value)
    {
        return $query->where('status', $value);
    }
}
