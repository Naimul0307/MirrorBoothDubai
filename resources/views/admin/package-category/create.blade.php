@extends('admin.layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Package Category / Create</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content  h-100"">
        <div class="container-fluid  h-100"">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12 ">
                    <form action="" method="post" name="createPackageCategoryForm" id="createPackageCategoryForm">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('packageCategoryList') }}" class="btn btn-primary">Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                    <p class="error name-error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="name">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control">
                                    <p class="error slug-error"></p>
                                </div>
                                <div class="form-group mt-4">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.row -->
            <!-- /.row (main row) -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection


@section('extraJs')

<script type="text/javascript">

    $("#createPackageCategoryForm").submit(function(event){
        event.preventDefault();
        $("button[type='submit']").prop('disabled',true);

        $.ajax({
            url: '{{ route("packageCategory.store") }}',
            type: 'POST',
            dataType: 'json',
            data: $("#createPackageCategoryForm").serializeArray(),
            success: function(response){
                $("button[type='submit']").prop('disabled',false);

                if(response.status == 200) {
                    // no error
                    window.location.href = '{{ route("packageCategoryList") }}';
                } else {
                    // Here we will show errors
                    $('.name-error').html(response.errors.name);
                }
            }
        });
    });

    $("#name").change(function(){
        $("button[type='submit']").prop('disabled',true);
        $.ajax({
            url: '{{ route("packageCategory.slug") }}',
            type: 'get',
            data: {name: $(this).val()},
            dataType: 'json',
            success: function(response){
                $("button[type='submit']").prop('disabled',false);
                $("#slug").val(response.slug);
            }
        })
    });
</script>

@endsection
