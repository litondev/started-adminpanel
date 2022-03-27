<?php

namespace App\Helpers;

class FormatResponse{
	/**
     * Handle Custom Error
     *     
     * @return \Illuminate\Http\Response
     */   
	public static function failed($error){		
		if($error->getCode() != 422){
			$errorDataJson = [
				"message" => $error->getMessage(),
				"line" => $error->getLine(),
				"file" => $error->getFile()
			];

			$errorDataString = "\n".
				"\t"."message: ".$error->getMessage()."\n".
				"\t"."line: ".$error->getLine()."\n".
				"\t"."file: ".$error->getFile()."\n";

			\Log::channel("coex")->info($errorDataString);

			$response["message"] = env("APP_DEBUG",true) ? $errorDataJson : "Terjadi Kesalahan";	
		}else{
			$response["message"] = $error->getMessage();
		}

		return response()->json($response,500);
	}	

	public static function countAble($x) {
		if(strval($x) === "0.00"){
			return $x;
		}
		
		$x = intval($x);

    	$angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

    	if ($x < 12)
      		return " " . $angka[$x];
    	elseif ($x < 20)
      		return self::countAble($x - 10) . " belas";
    	elseif ($x < 100)
      		return self::countAble($x / 10) . " puluh" . self::countAble($x % 10);
    	elseif ($x < 200)
    	  	return "seratus" . self::countAble($x - 100);
    	elseif ($x < 1000)
      		return self::countAble($x / 100) . " ratus" . self::countAble($x % 100);
    	elseif ($x < 2000)
      		return "seribu" . self::countAble($x - 1000);
    	elseif ($x < 1000000)
      		return self::countAble($x / 1000) . " ribu" . self::countAble($x % 1000);
    	elseif ($x < 1000000000)
      		return self::countAble($x / 1000000) . " juta" . self::countAble($x % 1000000);
  	}

	public static function getCode($model,$initial){
        $lastCode = $model->query()
            ->select("code")    
            ->withTrashed()
            ->where("code","like","%".date("Ym")."%")
            ->orderBy("id","desc")
            ->first();
                
        $code = $lastCode 
            ? explode(".",$lastCode->code)
            : array($initial,rand(001,999),date("Ym"),0);    
        
        return $code[0].".".$code[1].".".$code[2].".".( intval($code[3]) + 1 );      
	}
}