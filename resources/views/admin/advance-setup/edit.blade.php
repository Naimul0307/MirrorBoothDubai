@extends('admin.layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Ad / Edit</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                    <form action="" method="post" name="addonEdit" id="addonEdit">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('advanceSetupList') }}" class="btn btn-primary">Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{ $advanceSetup->name }}" name="name" id="name" class="form-control">
                                    <p class="error name-error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="name">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" value="{{ $advanceSetup->slug }}" class="form-control">
                                    <p class="error slug-error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" name="price" id="price" value="{{ $advanceSetup->price }}" class="form-control">
                                    <p class="error price-error"></p>
                                </div>

                                <div class="form-group mt-4">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ ($advanceSetup->status == 1) ? 'selected' : '' }}>Active</option>
                                        <option value="0"  {{ ($advanceSetup->status == 0) ? 'selected' : '' }}>Block</option>
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

    $("#addonEdit").submit(function(event){
        event.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $.ajax({
            url: '{{ route("advanceSetup.update", $advanceSetup->id) }}',
            type: 'POST',
            dataType: 'json',
            data: $("#addonEdit").serialize(),
            success: function(response){
                $("button[type='submit']").prop('disabled', false);
                if(response.status == 200) {
                    window.location.href = '{{ route("advanceSetupList") }}';
                } else {
                    if(response.errors.name) {
                        $('.name-error').html(response.errors.name);
                    } else {
                        $('.name-error').html('');
                    }

                    if(response.errors.price) {
                        $('.price-error').html(response.errors.price[0]);
                    } else {
                        $('.price-error').html('');
                    }

                    if (response.errors.slug) {
                        $('.slug-error').html(response.errors.slug);
                    } else {
                        $('.slug-error').html('');
                    }
                }
            }
        });
    });

    $("#name").change(function(){
        $("button[type='submit']").prop('disabled',true);
        $.ajax({
            url: '{{ route("advanceSetup.slug") }}',
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
