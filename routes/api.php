<?php

use App\Models\Product;
use App\Http\Controllers\ProductContoller;
use App\Http\Controllers\AuthContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware'=>['auth:sanctum']], function () {

    Route::post('/update/{id}',[ProductContoller::class,'update']);
    Route::post('/logout',[AuthContoller::class,'logout']);
    Route::delete('/delete/{id}',[ProductContoller::class,'delete']);
    Route::post('/store',[ProductContoller::class,'store']);
    Route::get('/myProduct',[ProductContoller::class,'myProduct']);
    Route::get('/addlike',[ProductContoller::class,'likes']);
    Route::post('/showDetails/{id}/addComment',[CommentContoller::class,'store']);
    Route::delete('/showDetails/{id}/showComments',[CommentContoller::class,'index']);
    Route::post('/showDetails/{id}/likes',[LikeContoller::class,'store']);
    Route::get('/showDetails/{id}/likes',[LikeContoller::class,'index']);

});

Route::get('/products/searchByName/{name}',[ProductContoller::class,'searchByName']);
Route::get('/products/searchByCategory/{category}',[ProductContoller::class,'searchByCategory']);
Route::get('/products/searchByExp_date/{exp_date}',[ProductContoller::class,'searchByExp_date']);
Route::post('/login',[AuthContoller::class,'login']);
Route::post('/register',[AuthContoller::class,'register']);
Route::get('/showDetails/{id}',[ProductContoller::class,'showDetails']);
Route::get('/showAllProducts',[ProductContoller::class,'showAllProducts']);
Route::get('/sortProducts',[ProductContoller::class,'sort']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
