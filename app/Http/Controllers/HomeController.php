<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = DB::table('products')->get();
        return view('home', \compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $product_name = $request->product_name;
            $product_price = $request->product_price;
            $product_status = $request->product_status;
            $product_upc = $request->product_upc;
            $product_image_url  = "";

            if ($request->hasFile('product_image')) 
            {
                $product_image = $request->file('product_image');

                //Getting requested file extension and creating a new name for the file
                $new_name = \uniqid() . '.' . $product_image->getClientOriginalExtension();
                
                //Moving the file to the defined location
                $product_image_path = $product_image->move(\public_path('product_images'), $new_name);

                $product_image_url  = url('/') . "/product_images" . "/" . $new_name;
            } 
            else 
            {
                $product_image_url  = "none";
            }

            DB::table('products')->insertGetId(['name' => $product_name, 'price' => $product_price, 'upc' => $product_upc, 'status' => $product_status, 'img_url' => $product_image_url]);

            return response()->json([
                'status'   => "success",
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'    => "error",
                'response'  => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
