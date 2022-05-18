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
        try {
            $product = DB::table('products')->where('id', $id)->first();
            $html = "";
            $html .= '<div class="row">
                        <div class="col form-group">
                            <label for="current_product_name"><b>Product Name:</b></label>
                            <input type="text" class="form-control" name="current_product_name" id="current_product_name" value="' . $product->name . '" required>
                            <span class="validation" id="current_product_name_error" class="text-danger"></span>
                        </div>
                        <div class="col form-group">
                            <label for="current_product_price"><b>Product Price:</b></label>
                            <input type="number" class="form-control" name="current_product_price" id="current_product_price" value="' . $product->price . '" required>
                            <span class="validation" id="current_product_price_error" class="text-danger"></span>
                        </div>
                    </div>';

            if ($product->status == "In Stock") {
                $html .= '<div class="row">
                            <div class="col form-group">
                                <label for="current_product_status"><b>Product Status:</b></label>
                                <select class="form-control" name="current_product_status" id="current_product_status" required>
                                    <option value="In Stock" selected>In Stock</option>
                                    <option value="Out of Stock">Out of Stock</option>
                                </select>
                            </div>
                            <div class="col form-group">
                                <label for="new_product_image"><b>Product Image:</b></label>
                                <input type="file" class="form-control" name="new_product_image" id="new_product_image" accept="Image/*">
                            </div>
                        </div>';
            } else {
                $html .= '<div class="row">
                            <div class="col form-group">
                                <label for="current_product_status"><b>Product Status:</b></label>
                                <select class="form-control" name="current_product_status" id="current_product_status" required>
                                    <option value="In Stock">In Stock</option>
                                    <option value="Out of Stock" selected>Out of Stock</option>
                                </select>
                            </div>
                            <div class="col form-group">
                                <label for="new_product_image"><b>Product Image:</b></label>
                                <input type="file" class="form-control" name="new_product_image" id="new_product_image" accept="Image/*">
                            </div>
                        </div>';
            }
            
            $html .= '<div class="row">
                        <div class="col-6 form-group">
                            <label for="current_product_upc"><b>Product UPC:</b></label>
                            <input type="text" class="form-control" name="current_product_upc" id="current_product_upc" value="' . $product->upc . '" required>
                            <span class="validation" id="product_upc_error" class="text-danger"></span>
                        </div>
                        <div class="col-6 form-group">
                            <label for="current_product_img"><b>Product UPC:</b></label>
                            <a target="_" href="' . $product->img_url . '">
                                <img id="current_product_img" class="img-thumbnail" style="height: 100px; width: 100px" src="' . $product->img_url . '" alt="' . $product->img_url . '" srcset="">
                            </a>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center">
                        <input class="btn btn-lg btn-primary mr-2" type="submit" value="Submit">
                        <input class="btn btn-lg btn-secondary ml-2" type="reset" value="Clear">
                    </div>';

            return response()->json([
                'status'    => "success",
                'html'  => $html
            ]);
        } 
        catch (\Exception $e) {
            return response()->json([
                'status'    => "error",
                'response'  => $e->getMessage()
            ]);
        }
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
        try {

            $product_name = $request->current_product_name;
            $product_price = $request->current_product_price;
            $product_status = $request->current_product_status;
            $product_upc = $request->current_product_upc;
            $product_image_url  = "";

            if ($request->hasFile('new_product_image')) 
            {
                $product_image = $request->file('new_product_image');

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

            DB::table('products')->where('id', $id)->update(['name' => $product_name, 'price' => $product_price, 'upc' => $product_upc, 'status' => $product_status, 'img_url' => $product_image_url]);

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
     * Destroy Multiple Products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyMultiple($ids)
    {
        try {

            $productIds = \json_decode($ids, true); 

            foreach ($productIds as $key => $productId) {
                DB::table('products')->where('id', $productId)->delete();
            }

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            DB::table('products')->where('id', $id)->delete();

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
}
