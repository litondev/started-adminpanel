<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Customer,
    User,
};
use App\Http\Requests\{
    CustomerRequest,
    CheckAllRequest,
    ImportRequest
};
use App\Helpers\{
    FormatResponse,
    UploadImport
};
use App\Exports\CustomerExport;
use App\Imports\CustomerImport;
use App\ImportTemplates\CustomerTemplateExport;

class CustomerController extends Controller
{  
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = Customer::query();

        $data->select("id","code","name","address","payment_type","user_id","deleted_at");

        $data->with([
            "user" => function($q){
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
                $q->orWhere("code","like","%".$request->search."%")
                    ->orWhere("name","like","%".$request->search."%");                    
            });

            $data->orWhereHas("user",function($q) use ($request){
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
    public function store(CustomerRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $data = Customer::create($request->validated());

            activity()
                ->performedOn($data)
                // ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $data->name,
                    'id' => $data->id,
                    'table' => 'customers'
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request,Customer $customer)
    {
        try{    
            \DB::beginTransaction();

            $customer->update($request->validated());

            activity()
                ->performedOn($customer)
                // ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $customer->name,
                    'id' => $customer->id,
                    'table' => 'customers'
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
    public function destroy(Customer $customer)
    {
        try{    
            \DB::beginTransaction();            
                    
            $customer->delete();

            activity()
                ->performedOn($customer)
                // ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $customer->name,
                    'id' => $customer->id,
                    'table' => 'customers'
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
                            
            $data = Customer::query()
                ->withTrashed()
                ->where("id",$id)
                ->firstOrFail();            
                        
            $data->restore();
            
            activity()
                ->performedOn($data)
                // ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $data->name,
                    'id' => $data->id,
                    'table' => 'customers'
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
    
            Customer::query()
                ->whereIn("id",$request->checkboxs)
                ->delete();  
                
            activity()        
                // ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'customers'                  
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
            
            Customer::query()
                ->withTrashed()
                ->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                // ->causedBy(auth()->user())
                ->withProperties([                            
                    'id' => $request->checkboxs,  
                    'table' => 'customers'                  
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
            "code" => FormatResponse::getCode(new Customer(),"CR")
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
            ? 'customer.pdf' 
            : 'customer.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new CustomerExport($this->indexFilter()),$filetype,$extension);        
    }
 
    /**
     * Print the listing of the resource 
     *     
     * @return \Illuminate\Http\Response
     */   
    public function print(){
        $pdf = \PDF::loadview('exports/customer',[
              "data" => !request()->filled("all") 
                ? $this->indexFilter()->getCollection() 
                : $this->indexFilter()
        ]);
        
        return  $pdf->stream();
    }

    /**
     * Download Template Import
     */
    public function importTemplate(){
        return \Excel::download(new CustomerTemplateExport,'file.xlsx');
    }
    
    /**
     * Import To Database
     */
    public function import(ImportRequest $request){
        try{            
            $import = UploadImport::doImport(new CustomerImport());

            return response()->json([
                "status" => true,                
                "success" => $import->getSuccess(),
                "faileds" => $import->getFaileds()
            ]);
        }catch(\Exception $e){
            return FormatResponse::failed($e);
        }
    }

    /**
     * Print Detail
     */
    public function getPrintDetail(Customer $customer){
        $pdf = \PDF::loadview('prints/customer',[              
            "customer" => $customer
        ]);
        
        return  $pdf->stream();        
    }

    /**
     * Show Detail
     */
    public function show(Customer $customer){
        $customer->load([
            "user" => function($q){
                // WITH TRASHED
                $q->withTrashed()->select("id","code","name");
            },          
        ]);

        return response()->json($customer);
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
}
