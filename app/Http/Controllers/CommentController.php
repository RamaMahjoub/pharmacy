<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller 
{
    
    public function index(Product $product)
    {
        return response(Comment::where('product_id',$product->id)->get(),200);
    }

    public function store(Request $request,Product $product)
    {
        $request -> Validate([
         'comment'=>'required'
        ]);
        $comment =Comment::create([
            'product_id'=>$product->id ,
            'user_id'=>Auth::id(),
            'comment'=>$request->comment 
        ]);
        Product::where('id',$product->id)->increment('comments');
        return response($comment,201);
    }
}
