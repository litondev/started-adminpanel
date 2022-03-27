<?php

namespace App\Helpers;

class UploadImport{
	public static function doImport($import){		           
        $fileName = rand()."-".request()->file('file')->getClientOriginalName();
              
        request()->file("file")->move('imports',$fileName);
                                       
        \Excel::import($import,public_path('/imports/'.$fileName));

        unlink(public_path('/imports/'.$fileName));
    
    	return $import;       
	}	
}