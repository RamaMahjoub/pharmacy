<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use DateTime;
Use \Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProductContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getImagePath( UploadedFile $image){
       $image_name =   time() . '.' . $image->getClientOriginalExtension();
       $image ->storeAs('public/images',$image_name);
       return 'storage/images/'. $image_name ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $owner_id=Auth::id();

        $request->validate([
        'name' => 'required',
        'image' => 'required|File',
        'exp_date'=>'required',
        'category'=>'required',
        'contact_info'=> 'required',
        'quantity'=> 'required',
        'price'=> 'required',
        'discount_date_1',
        'discount_value_1',
        'discount_date_2',
        'discount_value_2',
        'discount_date_3',
        'discount_value_3'
       ]);

       $path = $this->getImagePath($request ->file('image')) ;
       $product=new Product();
       $product->owner_id=$owner_id;
       $product->name=$request->name;
       $product->image=$path;
       $product->exp_date=$request->exp_date;
       $product->category=$request->category;
       $product->contact_info=$request->contact_info;
       $product->quantity=$request->quantity;
       $product->price=$request->price;
       $product->discount_date_1=$request->discount_date_1;
       $product->discount_value_1=$request->discount_value_1;
       $product->discount_date_2=$request->discount_date_2;
       $product->discount_value_2=$request->discount_value_2;
       $product->discount_date_3=$request->discount_date_3;
       $product->discount_value_3=$request->discount_value_3;
       $product->save();
        return response($product);
       //return response("created successfuly");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDetails($id)
    {
        $product=Product::find($id);
        $expDate=$product->exp_date;
        $discount_Date_1=$product->discount_date_1;
        $discount_Date_2=$product->discount_date_2;
        $discount_Date_3=$product->discount_date_3;
        $product->views+=1;
        $product->save();
        $date = now();
        //الزمن المتبقي لانتهاء الصلاحية
        $expDateYear=\Carbon\Carbon::parse($expDate)->format('Y');
        $expDateMonth=\Carbon\Carbon::parse($expDate)->format('m');
        $expDateDay=\Carbon\Carbon::parse($expDate)->format('d');
        $toExpDate=(($expDateYear-$date->year-1)*365)+
        (((12-$date->month)+($expDateMonth-1))*30)+
        30-$date->day+$expDateDay;

        //الزمن المتبقي للحسم الاول
        $discount_Date_1_Year=\Carbon\Carbon::parse($discount_Date_1)->format('Y');
        $discount_Date_1_Month=\Carbon\Carbon::parse($discount_Date_1)->format('m');
        $discount_Date_1_Day=\Carbon\Carbon::parse($discount_Date_1)->format('d');
        $toDiscount_Date_1=(($discount_Date_1_Year-$date->year-1)*365)+
        (((12-$date->month)+($discount_Date_1_Month-1))*30)+
        30-$date->day+$discount_Date_1_Day;//2024-1-10 | 2021-12-20

        //الزمن المتبقي للحسم الثاني
        $discount_Date_2_Year=\Carbon\Carbon::parse($discount_Date_2)->format('Y');
        $discount_Date_2_Month=\Carbon\Carbon::parse($discount_Date_2)->format('m');
        $discount_Date_2_Day=\Carbon\Carbon::parse($discount_Date_2)->format('d');
        $toDiscount_Date_2=(($discount_Date_2_Year-$date->year-1)*365)+
        (((12-$date->month)+($discount_Date_2_Month-1))*30)+
        30-$date->day+$discount_Date_2_Day;//2024-8-1 | 2021-12-20

        //الزمن المتبقي للحسم الثالث
        $discount_Date_3_Year=\Carbon\Carbon::parse($discount_Date_3)->format('Y');
        $discount_Date_3_Month=\Carbon\Carbon::parse($discount_Date_3)->format('m');
        $discount_Date_3_Day=\Carbon\Carbon::parse($discount_Date_3)->format('d');
        $toDiscount_Date_3=(($discount_Date_3_Year-$date->year-1)*365)+
        (((12-$date->month)+($discount_Date_3_Month-1))*30)+
        30-$date->day+$discount_Date_3_Day;

        //لمعرفة السعر الذي سنعرضه
        if($toExpDate>=$toDiscount_Date_1)
            $priceNow=$product->price-($product->price*((int)$product->discount_value_1/100));
        else if($toExpDate>=$toDiscount_Date_2 && $toExpDate<$toDiscount_Date_1)
            $priceNow=$product->price-($product->price*((int)$product->discount_value_2/100));
        else if($toExpDate<$toDiscount_Date_3)
            $priceNow=$product->price-($product->price*((int)$product->discount_value_3/100));

        /*echo $toExpDate;
        echo " ";
        echo $toDiscount_Date_1;
        echo " ";
        echo $toDiscount_Date_2;
        echo " ";
        echo $toDiscount_Date_3;
        echo " ";
        echo $priceNow;*/

        $response=[$priceNow,$product];
        return response($response);
    }

    public function showAllProducts()
    {
        $products=Product::get();
        return response($products);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $product = Product::find($id);
        if(Auth::id()==$product->owner_id){
            $product->update ( $request->all() );
            return response($product) ;
        }
        else return response(" failed");
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $product=Product::find($id);
        if(Auth::id()==$product->owner_id)
           return Product::destroy($id);
        else
             return response(" failed");
    }


    public function searchByName($name){
        return Product::where('name',$name)->get() ;
    }

    public function searchByCategory($category){
        return Product::where('category',$category)->get() ;
    }

    public function searchByExp_date($expDate){
        return Product::where('exp_date',$expDate)->get() ;
    }

    public function sort(){
        return Product::orderBy('exp_date','asc')->get();
    }

    public function myProduct()
    {
        $id=Auth::id();
        return response(Product::where('owner_id',$id)->get());
    }

    public function likes(Product $product)
    {
        //$product=Product::find($id);
        $product->likes+=1;
        $product->save();
    }


}
