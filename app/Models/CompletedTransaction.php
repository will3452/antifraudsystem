<?php
namespace App\Models;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class CompletedTransaction extends Transaction
{
    protected $table = 'transactions';
    protected static function booted()
    {
        static::addGlobalScope('completedTransaction', function (Builder $builder) {
            $builder->where('status', 'completed');
        });
    }
}
