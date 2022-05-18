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
                {{-- <div class="d-flex justify-content-end">
                    <label for="view-products-table">
                            <button id="delete-selected-btn" type="button" class="btn btn-md btn-danger">Delete Selected</button>
                    </label>
                </div> --}}
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
                            {{-- <th>
                                <input type="checkbox" name="selectAll" id="example-select-all">
                                &nbsp;
                                <label for="example-select-all">Select All</label>
                            </th> --}}
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
                                                <button id="{{ $product->id }}" class="edit-product-btn btn btn-md btn-primary mr-1">
                                                    Edit
                                                </button>
                                                <button id="{{ $product->id }}" class="delete-product-btn btn btn-md btn-danger">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <fieldset> 
                                                <div class="custom-control custom-checkbox">
                                                    <span class="custom-control-indicator"></span><input type="checkbox" class="custom-control-input customCheck" name="customCheck" id="{{ 'customCheck' .$product->id }}" value="{{ $product->id }}">
                                                    <label class="custom-control-label" for="{{ 'customCheck' .$product->id }}"></label>
                                                </div>
                                            </fieldset>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="8"><strong class="text-danger"><h3>No Products Found</h3></strong></td>
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
                            {{-- <th>#</th> --}}
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit-product-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Product</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="product-update-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" id="product_id">
                    <div id="dynamic-div">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
    <script>
        $(document).ready(function(){
            // $('#view-products-table').DataTable();

            //Validations for adding product
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

            //Add product
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

            //Delete Single Product
            $('#view-products-table').on('click', '.delete-product-btn', function (event) {
                event.preventDefault();
                let text = "Delete this Product?";
                if (confirm(text) == true) {
                    let id = this.id;
                    let url = "{{ route('deleteProduct', ":getid") }}";
                    url = url.replace(':getid', id );
                    $.ajax({
                        url: url,
                        method:"GET",
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                            if (data.status == 'success') 
                            {
                                swal("Success", "Product Deleted Successfully!", "success");
                                location.reload();
                            } 
                            else 
                            {
                                swal("Error", data.response, "error");
                                location.reload();
                            }
                        }
                    });
                } 
            });

            //Multiple Deletion
            let productIds = [];
            $('#example-select-all').on('click', function() {
                var isSelectAllChecked =  $(this).is(':checked');
                $('#view-products-table').find('tr').each(function() {
                    var row = $(this);
                    var id = row.find('input[type="checkbox"]').val();
                    console.log("Checkbox Id :" + id);
                    row.find('input[type="checkbox"]').prop('checked', isSelectAllChecked);
                });
            });

            $('#delete-selected-btn').on('click', function(event){
                event.preventDefault();
                let text = "Delete selected Product?";
                if (confirm(text) == true) {
                    // var cells = $('#view-products-table').column(7).nodes();

                    // for (var i = 0; i < cells.length; i += 1) {
                    //     if (cells[i].querySelector("input[type='checkbox']").checked) {
                    //         productIds.push(cells[i].querySelector("input[type='checkbox']").value);
                    //     }
                    // }

                    var table = document.getElementById("view-products-table");
                    for (var i = 0, row; row = table.rows[i]; i++) {
                        //iterate through rows
                        //rows would be accessed using the "row" variable assigned in the for loop
                        for (var j = 0, col; col = row.cells[j]; j++) {
                            let checkBox = col.innerHTML;
                            console.log(checkBox);
                        }  
                    }

                    
                    // var productIdsJson = JSON.stringify(productIds);

                    // console.log('productIdswith js array ' + productIdsJson);

                    // let url = "{{ route('destroyMultiple', ":getid") }}";
                    // url = url.replace(':getid', productIdsJson );

                    // $.ajax({
                    //     url: url,
                    //     method: "GET",
                    //     success: function(data) {
                    //         if (data.status == "success") {
                    //             $('#example-select-all').prop('checked', false); 
                    //             productIds = [];
                    //             swal("Success", "Products Deleted Successfully!", "success");
                    //             location.reload();
                    //         } else {
                    //             // alert(data.response);
                    //             swal("Error", data.response, "error");
                    //             location.reload();
                    //         }
                    //     },
                    //     error: function(e) {
                    //         alert(e.error);
                    //     }
                    // });
                }
            });

            // Handle click on checkbox to set state of "Select all" control
            $('#view-products-table tbody').on('change', 'input[type="checkbox"]', function() {
                // If checkbox is not checked
                if (!this.checked) {
                    var el = $('#example-select-all').get(0);
                    // If "Select all" control is checked and has 'indeterminate' property
                    if (el && el.checked && ('indeterminate' in el)) {
                        // Set visual state of "Select all" control
                        // as 'indeterminate'
                        el.indeterminate = true;
                    }
                }
            });

            //Edit Product
            $('#view-products-table').on('click', '.edit-product-btn', function (event) {
                $('#dynamic-div').html('');
                $('#product_id').val('');
                let id = this.id;
                let url = "{{ route('editProduct', ":getid") }}";
                url = url.replace(':getid', id );
                $.ajax({
                    url: url,
                    method:"GET",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data)
                    {
                        if (data.status == 'success') 
                        {
                            $('#product_id').val(id);
                            $('#dynamic-div').html(data.html);
                            $('#edit-product-modal').modal('show');
                        } 
                        else 
                        {
                            swal("Error", data.response, "error");
                        }
                    }
                });
            });

            //Update Product
            $('#product-update-form').on('submit', function(event){
                event.preventDefault();
                let id = $('#product_id').val();
                let url = "{{ route('updateProduct', ":getid") }}";
                url = url.replace(':getid', id );
                $.ajax({
                    url:url,
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
                            document.getElementById("product-update-form").reset();
                            $(".validation").html("");  
                            swal("Success", "Product Updated Successfully!", "success");
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