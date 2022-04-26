<?php 
namespace App\Service\Transaction;

use App\Models\{
    Transaction,
    Product 
};

class TransactionStoreService{
    public function store($request){        
        $payload = $request->validated();

        unset($payload["transaction_details"]);

        $transaction = Transaction::create($payload);

        $transaction->transaction_details()->delete();

        foreach($request->transaction_details as $item){                
            $product = Product::query()                
                ->select("id","sold","name","stock")
                ->where("id",$item["product_id"])
                ->first();

            throw_if(
                $product->stock < 1,
                new \Exception("Produk ".$product->name." Stok kosong")
            );

            throw_if(
                $product->stock - $item["quantity"] < 0,
                new \Exception("Produk ".$product->name." Stok tidak cukup")
            );
            
            $product->update([
                "stock" => $product->stock - $item["quantity"],
                "sold" => $product->sold + $item["quantity"]
            ]);

            $transaction->transaction_details()->create([
                "product_id" => $item["product_id"],
                "amount" => $item["amount"],
                "quantity" => $item["quantity"],
                "total" => $item["total"]
            ]);                      
        }

        return $transaction;
    }
}