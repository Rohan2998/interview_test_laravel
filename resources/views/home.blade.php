@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row-lg-12">
        <div class="card">
            <div class="card-title ">
                <h5 class="p-2 card-header bg-primary text-white"><b>Add Product</b></h5>
            </div>
            <div class="card-body">
                <form id="product-submit-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col form-group">
                            <label for="product_name"><b>Product Name:</b></label>
                            <input type="text" class="form-control" name="product_name" id="product_name" required>
                            <span class="validation" id="product_name_error" class="text-danger"></span>
                        </div>
                        <div class="col form-group">
                            <label for="product_price"><b>Product Price:</b></label>
                            <input type="number" class="form-control" name="product_price" id="product_price" required>
                            <span class="validation" id="product_price_error" class="text-danger"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col form-group">
                            <label for="product_status"><b>Product Status:</b></label>
                            <select class="form-control" name="product_status" id="product_status" required>
                                <option value="In Stock" selected>In Stock</option>
                                <option value="Out of Stock">Out of Stock</option>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="product_image"><b>Product Image:</b></label>
                            <input type="file" class="form-control" name="product_image" id="product_image" accept="Image/*">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 form-group">
                            <label for="product_upc"><b>Product UPC:</b></label>
                            <input type="text" class="form-control" name="product_upc" id="product_upc" required>
                            <span class="validation" id="product_upc_error" class="text-danger"></span>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center">
                        <input class="btn btn-lg btn-primary mr-2" type="submit" value="Submit">
                        <input class="btn btn-lg btn-secondary ml-2" type="reset" value="Clear">
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-title ">
                <h5 class="p-2 card-header bg-primary text-white"><b>Product List</b></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="view-products-table" class="table">
                        <thead>
                            <th>Sr.No</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>UPC</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @if ($products->isNotEmpty())
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <th>{{ $key + 1 }}</th>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>{{ $product->upc }}</td>
                                        <td>{{ $product->status }}</td>
                                        <td>
                                            <a target="_" href="{{ $product->img_url }}">
                                                <img class="img-thumbnail" style="height: 100px; width: 100px" src="{{ $product->img_url }}" alt="{{ $product->img_url }}" srcset="">
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <button href="#' +
                                                data.id + '" target="_" id="' +
                                                data.id + '" class="edit-flash-btn approve-button mr-1"><i class="fa fa-check-square-o" style="margin-right: 5px;"></i>Edit</button><button target="_" id="' +
                                                data.id + '" class="delete-flash-btn disapprove-button"><i class="fa fa-close" style="margin-right: 5px;"></i>Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="7"><strong class="text-danger"><h3>No Products Found</h3></strong></td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <th>Sr.No</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>UPC</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){

            $('#product_name').on('change', function(){
                let product = this.value;
                $('#product_name_error').html('');
                if(product == '')
                {
                    $('#product_name_error').html('');
                }
                else if (!product.replace(/\s/g, '').length) 
                {
                    $('#product_name_error').html('Product name contains only whitespace');
                }
                else
                {
                    $('#product_name_error').html('');
                }
            });

            $('#product_price').on('change', function(){
                let product_price = this.value;
                $('#product_name_error').html('');
                if(product_price == '')
                {
                    $('#product_price_error').html('');
                }
                else if (!product_price.replace(/\s/g, '').length) 
                {
                    $('#product_price_error').html('Product price contains only whitespace');
                }
                else
                {
                    $('#product_price_error').html('');
                }
            });

            $('#product_upc').on('change', function(){
                let product_upc = this.value;
                $('#product_upc_error').html('');
                if(product_upc == '')
                {
                    $('#product_upc_error').html('');
                }
                else if (!product_upc.replace(/\s/g, '').length) 
                {
                    $('#product_upc_error').html('Product upc contains only whitespace');
                }
                else
                {
                    $('#product_upc_error').html('');
                }
            });

            $('#product-submit-form').on('submit', function(event)
            {
                event.preventDefault();  
                $.ajax({
                    url:"{{ route('storeProduct') }}",
                    method:"POST",
                    data:new FormData(this),
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data)
                    {
                        if (data.status == 'success') 
                        {
                            document.getElementById("product-submit-form").reset();
                            $(".validation").html("");  
                            swal("Success", "Product Added Successfully!", "success");
                            location.reload();
                        } 
                        else 
                        {
                            $(".validation").html("");
                            swal("Error", data.response, "error");
                            location.reload();
                        }
                    }
                });       
            });
        });
    </script>
@endsection