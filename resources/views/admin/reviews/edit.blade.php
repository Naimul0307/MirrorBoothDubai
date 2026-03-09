@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reviews / Edit</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
    <section class="content h-100">
        <div class="container-fluid h-100">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <form action="" method="post" name="editReviewForm" id="editReviewForm">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('reviewList') }}" class="btn btn-primary">Back</a>
                            </div>
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{ $review->name }}" name="name" id="name" class="form-control">
                                    <p class="error name-error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="name">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" value="{{ $review->slug }}" class="form-control">
                                    <p class="error slug-error"></p>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" name="image_id" id="image_id" value="">
                                            <label for="image">Image</label>
                                            <div id="image" class="dropzone dz-clickable">
                                                <div class="dz-message needsclick">
                                                    <br>Drop files here or click to upload.<br><br>
                                                </div>
                                            </div>

                                            @if(!empty($review->image))
                                                <img class="img-thumbnail my-4" src="{{ asset('uploads/reviews/thumb/large/'.$review->image) }}" width="300">
                                                <button type="button" class="btn btn-danger btn-sm remove-image" data-image="{{ $review->image }}">Remove</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ ($review->status == 1) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ ($review->status == 0) ? 'selected' : '' }}>Block</option>
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
    Dropzone.autoDiscover = false;

    const dropzone = $("#image").dropzone({
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
        },
        url:  "{{ route('tempUpload') }}",
        maxFiles: 1,
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/webp,gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(file, response){
            $("#image_id").val(response.id);
        }
    });

    $(document).on('click', '.remove-image', function() {
    let imageName = $(this).data('image');
    $('#image_id').val(''); // Clear the image_id field

    // Remove image preview and button from the DOM
    $(this).prev('img').remove();
    $(this).remove();

    // AJAX call to remove the main image from the server and database
    $.ajax({
        url: "{{ route('review.remove.image', $review->id) }}",
        type: 'POST',
        data: { image: imageName, _token: $('meta[name="_token"]').attr('content') },
        success: function(response) {
            if (response.status === 200) {
                console.log('Main image removed successfully');
            } else {
                console.log('Error removing main image: ' + response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('AJAX Error: ' + textStatus);
        }
    });
    });

    $("#name").change(function(){
        $("button[type='submit']").prop('disabled',true);
        $.ajax({
            url: '{{ route("review.slug") }}',
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
