<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    User,
    Product,
    Customer
};
use App\Http\Requests\{
    ProductRequest,
    CheckAllRequest,
    ImportRequest
};
use App\Exports\ProductExport;
use App\Helpers\{
    FormatResponse,
    UploadImport
};
use Illuminate\Support\Str;
use App\Imports\ProductImport;
use App\ImportTemplates\ProductTemplateExport;

class ProductController extends Controller
{  
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = Product::query();

        $data->select(
            "id","code","name",
            "user_id",         
            "deleted_at"
        );  

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
    public function store(ProductRequest $request)
    {
        try{    
            \DB::beginTransaction();
            
            $payload = $request->validated();

            $product = Product::create($payload);

            if($request->hasFile('images')) { 
                $images = [];     

                foreach($request->file('images') as $file){              
                    array_push($images,$this->uploadImage($file));                    
                }

                $product->images = $images;
                $product->save();
            }         

            activity()
                ->performedOn($product)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $product->name,
                    'id' => $product->id,
                    'table' => 'products'
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
    public function update(ProductRequest $request,Product $product)
    {
        try{    
            \DB::beginTransaction();
    
            $payload = $request->validated();

            $oldImages = $product->images;

            $product->update($payload);            

            if($request->hasFile('images')) { 
                $images = [];     

                foreach($request->file('images') as $file){              
                    array_push($images,$this->uploadImage($file));                    
                }

                $product->images = $images;
                $product->save();
            }         

            if($request->hasFile("images")){
                foreach($oldImages as $item){
                    $this->destoryImage($item);
                }
            }

            activity()
                ->performedOn($product)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $product->name,
                    'id' => $product->id,
                    'table' => 'products'
                ])
                ->log('Updated Data');

            \DB::commit();
            return response()->json([
                "status" => true,
                "code" => $product->code
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
    public function destroy(Product $product)
    {
        try{    
            \DB::beginTransaction();            
                    
            $product->delete();

            activity()
                ->performedOn($product)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $product->name,
                    'id' => $product->id,
                    'table' => 'products'
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
                            
            $data = Product::query()
                ->withTrashed()
                ->where("id",$id)
                ->firstOrFail();            
                        
            $data->restore();
            
            activity()
                ->performedOn($data)
                ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $data->name,
                    'id' => $data->id,
                    'table' => 'products'
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
    
            Product::query()
                ->whereIn("id",$request->checkboxs)
                ->delete();  
                
            activity()        
                ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'products'                  
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
            
            Product::query()
                ->withTrashed()
                ->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                ->causedBy(auth()->user())
                ->withProperties([                            
                    'id' => $request->checkboxs,  
                    'table' => 'products'                  
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
     * Export the listing of the resource 
     *
     * @param  $type excel | pdf
     * @return \Illuminate\Http\Response
     */   
    public function export($type){
        $filetype = $type == 'pdf' 
            ? 'product.pdf' 
            : 'product.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new ProductExport($this->indexFilter()),$filetype,$extension);        
    }
 
    /**
     * Print the listing of the resource 
     *     
     * @return \Illuminate\Http\Response
     */   
    public function print(){
        $pdf = \PDF::loadview('exports/product',[
              "data" => !request()->filled("all") 
                ? $this->indexFilter()->getCollection() 
                : $this->indexFilter()
        ]);
        
        return  $pdf->stream();
    }

    /**
     * Destroy File
     *
     * @param pdf | jpg
     */
    public function destoryImage($file){
        if($file && file_exists(public_path('images/products/'.$file)) && $file != "default.png"){
            unlink(public_path('images/products/'.$file));
        }
    }

    /**
     * Upload File
     *
     * @param pdf | jpg
     */
    public function uploadImage($file){
        $fileName = Str::random("20").".".$file->extension();
       
        \Image::make($file)->resize(null, 500, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(public_path("images/products/".$fileName));        

        return $fileName;
    }

    /**
     * Show Detail
     */
    public function show(Product $product){
        $product->load([
            "user" => function($q){
                // WITH TRASHED
                $q->withTrashed()->select("id","code","name");
            },          
        ]);

        return response()->json($product);
    }

    /**
     * Download Template Import
     */
    public function importTemplate(){
        return \Excel::download(new ProductTemplateExport,'file.xlsx');
    }
    
    /**
     * Import To Database
     */
    public function import(ImportRequest $request){
        try{            
            $import = UploadImport::doImport(new ProductImport());

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
    public function getPrintDetail(Product $product){
        $pdf = \PDF::loadview('prints/product',[              
            "product" => $product
        ]);
        
        return  $pdf->stream();        
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
    
        return response()->json($data);
    }

}
