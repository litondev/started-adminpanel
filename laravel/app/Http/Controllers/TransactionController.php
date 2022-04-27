<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Transaction,
    TransactionDetail,
    Customer,
    User,
    Product
};
use App\Http\Requests\{
    TransactionRequest,
    CheckAllRequest,
};
use App\Helpers\FormatResponse;
use App\Exports\TransactionExport;
use App\Service\Transaction\{
    TransactionDestroyService,
    TransactionStoreService,
    TransactionUpdateService
};

class TransactionController extends Controller
{  
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = Transaction::query();

        $data->select(
            "id","code","total","grand_total","quantity",
            "user_id","customer_id",
            "deleted_at"
        );

        $data->with([
            "user" => function($q){
                // WITH TRASHED
                $q->withTrashed()->select("id","code","name");
            },
            "customer" => function($q){
                // WITH TRASHED
                $q->withTrashed()->select("id","code","name");
            }        
        ]);
        
        if($request->filled("soft_deleted")){
            if($request->soft_deleted == "deleted"){
                $data->onlyTrashed();
            }else if($request->soft_deleted == "all"){
                $data->withTrashed();
            }          
        }     

        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("code","like","%".$request->search."%");            
            });

            $data->orWhereHas("user",function($q) use ($request){
                $q->where("code","like","%".$request->search."%")
                    ->where("name","like","%".$request->search."%");
            });            

            $data->orWhereHas("customer",function($q) use ($request){
                $q->where("code","like","%".$request->search."%")
                    ->where("name","like","%".$request->search."%");
            });
        }        

        $data = $data->orderBy($request->order ?? "id",$request->sort ?? "desc");

        if(!$request->filled("all")){
            $data = $data->paginate($request->per_page ?? 10);
        }else{
            $data = $data->get();
        }                   
    
        return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->indexFilter());                
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request,TransactionStoreService $service)
    {
        try{    
            \DB::beginTransaction();

            $transaction = $service->store($request);
                    
            activity()
                ->performedOn($transaction)
                // ->causedBy(auth()->user())
                ->withProperties([                    
                    'code' => $transaction->code,
                    'id' => $transaction->id,
                    'table' => 'transactions'
                ])
                ->log('Created Data');

            \DB::commit();      
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ids
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionRequest $request,TransactionUpdateService $service,$id)
    {
        try{                
            \DB::beginTransaction();

            $transaction = $service->update($id,$request);
          
            activity()
                ->performedOn($transaction)
                // ->causedBy(auth()->user())
                ->withProperties([
                    'code' => $transaction->code,
                    'id' => $transaction->id,
                    'table' => 'transactions'
                ])
                ->log('Updated Data');

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionDestroyService $service,$id)
    {
        try{    
            \DB::beginTransaction();                                   

            $transaction = $service->destroy($id);

            activity()
                ->performedOn($transaction)
                // ->causedBy(auth()->user())
                ->withProperties([
                    'code' => $transaction->code,
                    'id' => $transaction->id,
                    'table' => 'transactions'
                ])
                ->log('Deleted Data');
                
            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id){
        try{
            \DB::beginTransaction(); 
                            
            $data = Transaction::query()
                ->withTrashed()
                ->where("id",$id)
                ->firstOrFail();            
                        
            $data->restore();
            
            activity()
                ->performedOn($data)
                // ->causedBy(auth()->user())
                ->withProperties([
                    'code' => $data->code,
                    'id' => $data->id,
                    'table' => 'transactions'
                ])
                ->log('Restore Data');

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    /**
     * Remove all listing of the resource 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(CheckAllRequest $request){
        try{
            \DB::beginTransaction();
    
            Transaction::query()
                ->whereIn("id",$request->checkboxs)
                ->delete();  
                
            activity()        
                // ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'transactions'                  
                ])
                ->log('Deleted All Data');

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    /**
     * Restore all listing of the resource 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restoreAll(CheckAllRequest $request){
        try{
            \DB::beginTransaction();            
            
            Transaction::query()
                ->withTrashed()
                ->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                // ->causedBy(auth()->user())
                ->withProperties([                            
                    'id' => $request->checkboxs,  
                    'table' => 'transactions'                  
                ])
                ->log('Restore All Data');

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }   
    }

    /**
     * Get Code
     */
    public function getCode(){    
        return response()->json([
            "code" => FormatResponse::getCode(new Transaction(),"TN")
        ]);        
    }

    /**
     * Export the listing of the resource 
     *
     * @param  $type excel | pdf
     * @return \Illuminate\Http\Response
     */   
    public function export($type){
        $filetype = $type == 'pdf' 
            ? 'transaction.pdf' 
            : 'transaction.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new TransactionExport($this->indexFilter()),$filetype,$extension);        
    }
 
    /**
     * Print the listing of the resource 
     *     
     * @return \Illuminate\Http\Response
     */   
    public function print(){
        $pdf = \PDF::loadview('exports/transaction',[
              "data" => !request()->filled("all") 
                ? $this->indexFilter()->getCollection() 
                : $this->indexFilter()
        ]);
        
        return  $pdf->stream();
    }

    /**
     * Print Detail
     */
    public function getPrintDetail($id){    
        $transaction = Transaction::findOrFail($id);

        $transaction->load([
            "user" => function($q){
                $q->withTrashed()->select("id","code","name");
            },
            "customer" => function($q){
                $q->withTrashed()->select("id","code","name");
            },
            "transaction_details" => function($q){
                $q->select("transaction_id","product_id","quantity","total","amount");
            },
            "transaction_details.product" => function($q){
                $q->withTrashed()->select("id","code","name");
            }
        ]);

        $pdf = \PDF::loadview('prints/transaction',[              
            "transaction" => $transaction
        ]);
        
        return  $pdf->stream();        
    }

    /**
     * Show Detail
     */
    public function show($id){
        $transaction = Transaction::findOrFail($id);

        $transaction->load([
            "user" => function($q){
                $q->withTrashed()->select("id","code","name");
            },
            "customer" => function($q){
                $q->withTrashed()->select("id","code","name");
            },
            "transaction_details" => function($q){
                $q->select("transaction_id","product_id","quantity","total","amount");
            },
            "transaction_details.product" => function($q){
                $q->withTrashed()->select("id","code","name");
            }
        ]);

        return response()->json($transaction);
    }

    /**
     * Get User
     */
    public function getUser(){
        $request = request();

        $data = User::query();

        $data->select("id","code","name");

        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("code","like","%".$request->search."%")
                    ->orWhere("name","like","%".$request->search."%");                    
            });
        }        

        $data->orderBy($request->order ?? "id",$request->sort ?? "desc");

        $data = $data->paginate($request->per_page ?? 10);        
    
        return $data;
    }


    /**
     * Get Customer
     */
    public function getCustomer(){
        $request = request();

        $data = Customer::query();

        $data->select("id","code","name");

        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("code","like","%".$request->search."%")
                    ->orWhere("name","like","%".$request->search."%");                    
            });
        }        

        $data->orderBy($request->order ?? "id",$request->sort ?? "desc");

        $data = $data->paginate($request->per_page ?? 10);        
    
        return $data;
    }

     /**
     * Get Product
     */
    public function getProduct(){
        $request = request();

        $data = Product::query();

        $data->select("id","code","name","price","stock");

        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("code","like","%".$request->search."%")                    
                    ->orWhere("name","like","%".$request->search."%");                    
            });
        }        

        $data->where("stock",">",0);

        $data->orderBy($request->order ?? "id",$request->sort ?? "desc");

        $data = $data->paginate($request->per_page ?? 10);        
    
        return $data;
    }
}
