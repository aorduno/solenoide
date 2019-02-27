<?php

namespace App\Services\Impl;


use App\Models\Transaction;
use App\Services\TransactionService;

class TransactionServiceImpl implements TransactionService
{
    public function find($id)
    {
        return Transaction::findOrFail($id);
    }

    public function findAll()
    {
        return Transaction::all()->take(20);
    }

    public function create($request)
    {
        $transaction = new Transaction();
        $this->setAttributesFromData($request, $transaction);

        return $transaction->save();
    }

    public function update($id, $request)
    {
        $transaction = Transaction::findOrFail($id);
        $this->setAttributesFromData($transaction, $request);

        return $transaction->save();
    }

    public function delete($id)
    {
        return Transaction::destroy($id);
    }

    /**
     * @param $request
     * @param $transaction
     */
    public function setAttributesFromData($request, $transaction): void
    {
        $transaction->date = $request->date;
        $transaction->description = $request->description;
        $transaction->amount = $request->amount;
        $transaction->owner_id = $request->owner_id;
    }
}
