@extends('admin.layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Blogs / Edit</h1>
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
                    <form action="" method="post" name="editBlog" id="editBlog">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('bloglist') }}" class="btn btn-primary">Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{ $blog->name }}" name="name" id="name" class="form-control">
                                    <p class="error name-error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="name">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" value="{{ $blog->slug }}" class="form-control">
                                    <p class="error slug-error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="name">Description</label>
                                    <textarea name="description" id="description" class="summernote">{{ $blog->description }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="hidden" name="image_id" id="image_id" value="">
                                        <label for="Image">Image</label>
                                        <div id="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">    
                                                <br>Drop files here or click to upload.<br><br>                                            
                                            </div>
                                        </div>

                                        @if(!empty($blog->image))
                                        <img class="img-thumbnail my-4" src="{{ asset('uploads/blogs/thumb/small/'.$blog->image) }}" width="300">    
                                        <button type="button" class="btn btn-danger btn-sm remove-image" data-image="{{ $blog->image }}">Remove</button>                                   
                                        @endif                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Short Description</label>
                                        <textarea name="short_description" id="short_description" cols="30" rows="7" class="form-control">{{ $blog->short_desc }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" value="{{ $blog->meta_title }}" name="meta_title" id="meta_title" class="form-control" placeholder="MAX 70 CHARACTERS">
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" cols="30" rows="7" class="form-control"  placeholder="MAX 160 CHARACTERS">{{ $blog->meta_description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="7" class="form-control"  placeholder="MAX 160 CHARACTERS">{{ $blog->meta_keywords }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image_alt_text">Image Alt Text</label>
                                    <textarea name="image_alt_text" id="metaimage_alt_text_keywords" cols="30" rows="7" class="form-control"  placeholder="MAX 160 CHARACTERS">{{ $blog->image_alt_text }}</textarea>
                                </div>

                                <div class="form-group mt-4">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ ($blog->status == 1) ? 'selected' : '' }}>Active</option>
                                        <option value="0"  {{ ($blog->status == 0) ? 'selected' : '' }}>Block</option>
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
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }, success: function(file, response){
            $("#image_id").val(response.id);
        }
    });

    $("#editBlog").submit(function(event){
        event.preventDefault();
        $("button[type='submit']").prop('disabled', true);

        // Sync Summernote content to textarea
        $("#description").val($('.summernote').summernote('code'));

        $.ajax({
            url: '{{ route("blogs.update", $blog->id) }}',
            type: 'POST',
            dataType: 'json',
            data: $("#editBlog").serialize(),
            success: function(response){
                $("button[type='submit']").prop('disabled', false);
                if(response.status == 200) {
                    window.location.href = '{{ route("bloglist") }}'; 
                } else {
                    if(response.errors.name) {
                        $('.name-error').html(response.errors.name);
                    } else {
                        $('.name-error').html('');
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
            url: '{{ route("blogs.slug") }}',
            type: 'get',
            data: {name: $(this).val()},
            dataType: 'json',
            success: function(response){
                $("button[type='submit']").prop('disabled',false);
                $("#slug").val(response.slug);
            }
        })
    });

    
    $(document).on('click', '.remove-image', function() {
    let imageName = $(this).data('image');
    $('#image_id').val(''); // Clear the image_id field

    // Remove image preview and button from the DOM
    $(this).prev('img').remove();
    $(this).remove();

    // AJAX call to remove the main image from the server and database
    $.ajax({
        url: "{{ route('blogs.remove.image', $blog->id) }}",
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

</script>

@endsection