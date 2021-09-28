<?php
namespace App\Models;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class PendingTransaction extends Transaction
{
    protected $table = 'transactions';
    protected static function booted()
    {
        static::addGlobalScope('pendingTransaction', function (Builder $builder) {
            $builder->where('status', 'pending');
        });
    }
}
