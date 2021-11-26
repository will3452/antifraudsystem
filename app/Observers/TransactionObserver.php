<?php

namespace App\Observers;

use App\Mail\TransactionCreated;
use App\Models\Fee;
use App\Models\Transaction;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TransactionObserver
{
    public function creating(Transaction $transaction)
    {
        $referenceNumber = Str::upper(Str::random(7)) . (Transaction::get()->count() + 1);
        $transaction['user_id'] = auth()->id() ?? 1;
        $transaction['reference_number'] = $referenceNumber;

        $amount = $transaction['amount'];
        $fee = (((nova_get_setting('fee') ?? 2) / 100) * $amount);

        $transaction['fee'] = $fee;
    }

    public function updating(Transaction $transaction)
    {
        $amount = $transaction['amount'];
        $fee = (((nova_get_setting('fee') ?? 2) / 100) * $amount);

        $transaction['fee'] = $fee;
    }

    public function created(Transaction $transaction)
    {
        // Mail::to($transaction->sender_email)->send(new TransactionCreated($transaction));
        // Mail::to($transaction->receiver_email)->send(new TransactionCreated($transaction));
    }
}
