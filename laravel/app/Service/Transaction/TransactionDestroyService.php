<?php 
namespace App\Service\Transaction;

use App\Models\Transaction;

class TransactionDestroyService{
    public function destroy($id){    
        $transaction = Transaction::query()
            ->where("id",$id)
            ->first();

        throw_if(
            !$transaction,
            new \Exception("Transaksi tidak ditemukan")
        );

        $transaction->delete();

        return $transaction;
    }
}