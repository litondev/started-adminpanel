<?php

use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\{
	UserController,
	CustomerController,
	ProductController,
	TransactionController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$version = "v1";

Route::group(["prefix" => $version],function() use ($version) {
	// STATUS
    Route::get('/status', function () use ($version) {
		return response([
			'message' => 'active (running)',
			'version' => $version
		]);
	})->name("status");

	Route::group(["prefix" => "master","as" => "master."],function(){
		// USER ROUTE
			// PRINT (DONE)
	    	Route::get("/user/get-print-detail/{user}",[UserController::class,"getPrintDetail"])->name("user.get-user-detail");

			// EXPORT  (EXCEL/PDF) ALL (DONE)
			Route::get("/user/export/{type}",[UserController::class,"export"])->name("user.export");

			// PRINT ALL (DONE)
		    Route::get("/user/print",[UserController::class,"print"])->name("user.print");	

		    // IMPORT 
			Route::post("/user/import",[UserController::class,"import"])->name("user.import");

			// IMPORT TEMPLATE (DONE)
	    	Route::get("/user/import",[UserController::class,"importTemplate"])->name("user.import-template");

	    	// GET CODE (DONE)
	    	Route::get("/user/get-code",[UserController::class,"getCode"])->name("user.get-code");

	    	// RESTORE ALL (DONE)
			Route::post("/user/restore-all",[UserController::class,"restoreAll"])->name("user.restore-all");

			// DESTROY ALL (DONE)
	    	Route::delete("/user/destroy-all",[UserController::class,"destroyAll"])->name("user.destroy-all");

	    	// RESTORE (DONE)
	    	Route::post("/user/restore/{id}",[UserController::class,"restore"])->name("user.restore");

	    	// CRUD (DONE)
			Route::apiResource("user",UserController::class)->only(["index","store","destroy","update","show"]);

		// CUSTOMER ROUTE 
			// GET USER FOR CUSTOMER (DONE)
			Route::get("/customer/get-user",[CustomerController::class,"getUser"])->name("customer.get-user");

			// PRINT (DONE)
	    	Route::get("/customer/get-print-detail/{customer}",[CustomerController::class,"getPrintDetail"])->name("customer.get-customer-detail");

			// EXPORT  (EXCEL/PDF) ALL (DONE)
			Route::get("/customer/export/{type}",[CustomerController::class,"export"])->name("customer.export");

			// PRINT ALL (DONE)
		    Route::get("/customer/print",[CustomerController::class,"print"])->name("customer.print");	

		    // IMPORT 
			Route::post("/customer/import",[CustomerController::class,"import"])->name("customer.import");

			// IMPORT TEMPLATE (DONE)
	    	Route::get("/customer/import",[CustomerController::class,"importTemplate"])->name("customer.import-template");

	    	// GET CODE (DONE)
	    	Route::get("/customer/get-code",[CustomerController::class,"getCode"])->name("customer.get-code");

	    	// RESTORE ALL (DONE)
			Route::post("/customer/restore-all",[CustomerController::class,"restoreAll"])->name("customer.restore-all");

			// DESTROY ALL (DONE)
	    	Route::delete("/customer/destroy-all",[CustomerController::class,"destroyAll"])->name("customer.destroy-all");

	    	// RESTORE (DONE)
	    	Route::post("/customer/restore/{id}",[CustomerController::class,"restore"])->name("customer.restore");

	    	// CRUD (DONE)
			Route::apiResource("customer",CustomerController::class)->only(["index","store","destroy","update","show"]);

		// PRODUCT ROUTE 
			// GET USER FOR PRODUCT (DONE)
			Route::get("/product/get-user",[ProductController::class,"getUser"])->name("product.get-user");

			// PRINT (DONE)
	    	Route::get("/product/get-print-detail/{product}",[ProductController::class,"getPrintDetail"])->name("product.get-product-detail");

			// EXPORT  (EXCEL/PDF) ALL (DONE)
			Route::get("/product/export/{type}",[ProductController::class,"export"])->name("product.export");

			// PRINT ALL (DONE)
		    Route::get("/product/print",[ProductController::class,"print"])->name("product.print");	

		    // IMPORT 
			Route::post("/product/import",[ProductController::class,"import"])->name("product.import");

			// IMPORT TEMPLATE (DONE)
	    	Route::get("/product/import",[ProductController::class,"importTemplate"])->name("product.import-template");

	    	// GET CODE (DONE)
	    	Route::get("/product/get-code",[ProductController::class,"getCode"])->name("product.get-code");

	    	// RESTORE ALL (DONE)
			Route::post("/product/restore-all",[ProductController::class,"restoreAll"])->name("product.restore-all");

			// DESTROY ALL (DONE)
	    	Route::delete("/product/destroy-all",[ProductController::class,"destroyAll"])->name("product.destroy-all");

	    	// RESTORE (DONE)
	    	Route::post("/product/restore/{id}",[ProductController::class,"restore"])->name("product.restore");

	    	// CRUD (DONE)
			Route::apiResource("product",ProductController::class)->only(["index","store","destroy","update","show"]);
	});

	Route::group(["prefix" => "transaction","as" => "transaction."],function(){
		// TRANSACTION ROUTE 
			// GET USER FOR TRANSACTION
			Route::get("/product/get-user",[TransactionController::class,"getUser"])->name("product.get-user");

			// GET CUSTOMER FOR TRANSACTION
			Route::get("/product/get-customer",[TransactionController::class,"getCustomer"])->name("product.get-customer");

			// GET PRODUCT FOR TRANSACTION
			Route::get("/product/get-product",[TransactionController::class,"getCustomer"])->name("product.get-customer");

			// PRINT 
	    	Route::get("/product/get-print-detail/{user}",[TransactionController::class,"getPrintDetail"])->name("product.get-transaction-detail");

			// EXPORT  (EXCEL/PDF) ALL
			Route::get("/product/export/{type}",[TransactionController::class,"export"])->name("eproduct.xport");

			// PRINT ALL
		    Route::get("/product/print",[TransactionController::class,"print"])->name("product.print");	

	    	// GET CODE
	    	Route::get("/product/get-code",[TransactionController::class,"getCode"])->name("product.get-code");

	    	// RESTORE ALL
			Route::post("/product/restore-all",[TransactionController::class,"restoreAll"])->name("product.restore-all");

			// DESTROY ALL
	    	Route::delete("/product/destroy-all",[TransactionController::class,"destroyAll"])->name("product.destroy-all");

	    	// RESTORE 
	    	Route::post("/product/restore/{id}",[TransactionController::class,"restore"])->name("product.restore");

	    	// CRUD
			Route::apiResource("product",TransactionController::class)->only(["index","store","destroy","update","show"]);
	});
});