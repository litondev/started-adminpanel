<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/api/v1/status');
});

Route::get("/getPublicPath",function(){
    return public_path();
});

Route::get("/getPd",function(){
    \DB::unprepared("CREATE PROCEDURE `test`()
    BEGIN
        SELECT * FROM users;
    END;");
    return \DB::select("call test()");
});

Route::get("/getJson",function(){
    $json = json_decode('[100,100,100]');
    if(!$json){
        return "no valid";
    }else{
        return $json;
    }
});

Route::get("/getLenCode",function(){
    // PT.1(shape).1(detail 1).1(detail 2).1(dominant).1(finishing)
    $code_edit = count(explode(".","PT.1.1.1.1.1.0001"));
    $code_add = count(explode(".","PT.1.1.1.1.1"));

    return [
        "code_add" => $code_add,
        "code_edit" => $code_edit
    ];
});