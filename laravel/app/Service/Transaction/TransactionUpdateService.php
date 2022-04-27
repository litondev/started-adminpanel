<?php 
namespace App\Service\Transaction;

use App\Models\{
  Transaction,
  TransactionDetail,
  Product 
};

class TransactionUpdateService{
    public function update($id,$request){
      $transaction = Transaction::query()
        ->where("id",$id)
        ->first();
      
      throw_if(
        !$transaction,
        new \Exception("Transaksi tidak ditemukan")
      );

      $payload = $request->validated();

      unset($payload["transaction_details"]);
      
      $transaction->update($payload);

      foreach($request->transaction_details as $item){                
          $product = Product::query()
              ->select("id","sold","name","stock")
              ->where("id",$item["product_id"])
              ->first();

          $transactionDetail = TransactionDetail::query()              
              ->firstOrCreate([
                "product_id" => $item["product_id"],
                "transaction_id" => $transaction->id,
              ],[
                "transaction_id" => $transaction->id,
                "product_id" => $item["product_id"],
                "amount" => $item["amount"],
                "quantity" => $item["quantity"],
                "total" => $item["total"]
              ]);

          throw_if(
              ($product->stock + $transactionDetail->quantity) - $item["quantity"] < 0,
              new \Exception("Produk ".$product->name." Stok tidak cukup")
          );

          $product->update([
              "stock" => ( ($product->stock + $transactionDetail->quantity) - $item["quantity"]),
              "sold" => ( ($product->sold - $transactionDetail->quantity) + $item["quantity"])
          ]);

          $transactionDetail->update([
              "amount" => $item["amount"],
              "quantity" => $item["quantity"],
              "total" => $item["total"]
          ]);
      }

      return $transaction;    
    }
}