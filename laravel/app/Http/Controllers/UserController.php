<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    User
};
use App\Http\Requests\{
    UserRequest,
    CheckAllRequest,
    ImportRequest
};
use App\Helpers\{
    FormatResponse,
    UploadImport
};
use App\Exports\UserExport;
use App\Imports\UserImport;
use App\ImportTemplates\UserTemplateExport;

class UserController extends Controller
{    
    /**
     * Display a listing of the resource Index And Export
     *
     * @return \Iluminate\Http\Response
     *
    */
    public function indexFilter(){
        $request = request();

        $data = User::query();

        $data->select("id","code","username","email","deleted_at");
        
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
    public function store(UserRequest $request)
    {
        try{    
            \DB::beginTransaction();

            $data = User::create($request->validated());

            activity()
                ->performedOn($data)
                // ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $data->name,
                    'id' => $data->id,
                    'table' => 'users'
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
    public function update(UserRequest $request,User $user)
    {
        try{    
            \DB::beginTransaction();

            $user->update($request->validated());

            activity()
                ->performedOn($user)
                // ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $user->name,
                    'id' => $user->id,
                    'table' => 'users'
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
    public function destroy(User $user)
    {
        try{    
            \DB::beginTransaction();            
                    
            $user->delete();

            activity()
                ->performedOn($user)
                // ->causedBy(auth()->user())
                ->withProperties([
                    'name' => $user->name,
                    'id' => $user->id,
                    'table' => 'users'
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
                            
            $data = User::query()
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
                    'table' => 'users'
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
    
            User::query()
                ->whereIn("id",$request->checkboxs)
                ->delete();  
                
            activity()        
                // ->causedBy(auth()->user())
                ->withProperties([            
                    'id' => $request->checkboxs,  
                    'table' => 'users'                  
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
            
            User::query()
                ->withTrashed()
                ->whereIn("id",$request->checkboxs)
                ->restore();    

            activity()        
                // ->causedBy(auth()->user())
                ->withProperties([                            
                    'id' => $request->checkboxs,  
                    'table' => 'users'                  
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
            "code" => FormatResponse::getCode(new User(),"UR")
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
            ? 'user.pdf' 
            : 'user.xlsx';

        $extension =  $type == "pdf" 
            ? \Maatwebsite\Excel\Excel::DOMPDF 
            : \Maatwebsite\Excel\Excel::XLSX;

        return \Excel::download(new UserExport($this->indexFilter()),$filetype,$extension);        
    }
 
    /**
     * Print the listing of the resource 
     *     
     * @return \Illuminate\Http\Response
     */   
    public function print(){
        $pdf = \PDF::loadview('exports/user',[
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
        return \Excel::download(new UserTemplateExport,'file.xlsx');
    }
    
    /**
     * Import To Database
     */
    public function import(ImportRequest $request){
        try{            
            $import = UploadImport::doImport(new UserImport());

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
    public function getPrintDetail(User $user){
        $pdf = \PDF::loadview('prints/user',[              
            "user" => $user 
        ]);
        
        return  $pdf->stream();        
    }
}
