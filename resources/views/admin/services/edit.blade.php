@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Services / Edit</h1>
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
                    <form action="" method="post" name="editServiceForm" id="editServiceForm">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('serviceList') }}" class="btn btn-primary">Back</a>
                            </div>
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{ $service->name }}" name="name" id="name" class="form-control">
                                    <p class="error name-error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="name">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" value="{{ $service->slug }}" class="form-control">
                                    <p class="error slug-error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a category</option>
                                        @if($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option {{ ($service->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="summernote">{{ $service->description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="short_description">Short Description</label>
                                    <textarea name="short_description" id="short_description" cols="30" rows="7" class="form-control">{{ $service->short_desc }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" value="{{ $service->meta_title }}" name="meta_title" id="meta_title" class="form-control" placeholder="MAX 70 CHARACTERS">
                                </div>
                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" cols="30" rows="7" class="form-control"  placeholder="MAX 160 CHARACTERS">{{ $service->meta_description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="7" class="form-control"  placeholder="MAX 160 CHARACTERS">{{ $service->meta_keywords }}</textarea>
                                </div>

                                <!-- Existing Video Link Section -->
                                <div class="form-group">
                                    <label for="videos_link">Video Link</label>
                                    <input type="text" value="{{ $service->videos_link }}" name="videos_link" id="videos_link" class="form-control">
                                </div>

                                <!-- New Video Links Section -->
                                <div class="form-group">
                                    <label for="additional_videos_links">Additional Videos Links</label>
                                    <div id="additional_videos_links">
                                        @if(is_array($additional_videos_links))
                                            @foreach ($additional_videos_links as $link)
                                                <div class="input-group mb-2">
                                                    <input type="text" name="additional_videos_links[]" class="form-control" value="{{ $link }}" placeholder="Enter additional video link">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary remove-link" type="button">Remove</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" id="add-video-link" class="btn btn-primary">Add Another Link</button>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Main Image Upload -->
                                        <input type="hidden" name="image_id" id="image_id" value="">
                                        <label for="image">Image</label>
                                        <div id="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">
                                                <br>Drop files here or click to upload.<br><br>
                                            </div>
                                        </div>

                                        @if(!empty($service->image))
                                            <img class="img-thumbnail my-4" src="{{ asset('uploads/services/thumb/small/'.$service->image) }}" width="300">
                                            <button type="button" class="btn btn-danger btn-sm remove-image" data-image="{{ $service->image }}">Remove</button>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Gallery Image Upload -->
                                        <div class="form-group">
                                            <label for="gallery">Image Gallery</label>
                                            <div id="gallery" class="dropzone dz-clickable">
                                                <div class="dz-message needsclick">
                                                    <br>Drop files here or click to upload.<br><br>
                                                </div>
                                            </div>
                                            <input type="hidden" name="gallery_images" id="gallery_images" value="">
                                        </div>

                                        <div id="gallery-preview" class="row">
                                            @if(!empty($service->gallery_images))
                                                @foreach(json_decode($service->gallery_images) as $galleryImage)
                                                    <div class="col-md-3 gallery-item">
                                                        <img class="img-thumbnail" src="{{ asset('uploads/services/gallery/'.$galleryImage) }}" width="150">
                                                        <button type="button" class="btn btn-danger btn-sm remove-gallery-image" data-image="{{ $galleryImage }}">Remove</button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="image_alt_text">Image Alt Text</label>
                                    <textarea name="image_alt_text" id="metaimage_alt_text_keywords" cols="30" rows="7" class="form-control"  placeholder="MAX 160 CHARACTERS">{{ $service->image_alt_text }}</textarea>
                                </div>

                                <div class="form-group mt-4">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ ($service->status == 1) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ ($service->status == 0) ? 'selected' : '' }}>Block</option>
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

    // Image gallery Dropzone configuration
    const galleryDropzone = $("#gallery").dropzone({
        url: "{{ route('uploadGalleryImage') }}",  // Assuming same route for temporary uploads
        maxFiles: null,
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(file, response){
            let currentValue = $("#gallery_images").val();
            currentValue += (currentValue ? ',' : '') + response.id;
            $("#gallery_images").val(currentValue);
        },
        removedfile: function(file) {
            file.previewElement.remove();
            let fileId = file.upload.uuid;  // Assuming file uuid is returned or tracked
            let currentValue = $("#gallery_images").val();
            let newValue = currentValue.replace(new RegExp('(^|,)' + fileId + '(,|$)'), '$1').replace(/^,|,$/g, '');
            $("#gallery_images").val(newValue);

            // AJAX call to remove the image from the server
            $.ajax({
                url: "{{ route('service.remove.gallery.image', $service->id) }}",
                type: 'POST',
                data: { image: fileId, _token: $('meta[name="_token"]').attr('content') },
                success: function(response) {
                    if (response.status === 200) {
                        console.log('Image removed successfully');
                    } else {
                        console.log('Error removing image');
                    }
                }
            });
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
        url: "{{ route('service.remove.image', $service->id) }}",
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

    $(document).on('click', '.remove-gallery-image', function() {
        let imageName = $(this).data('image');
        let galleryImages = $('#gallery_images').val().split(',').filter(image => image !== imageName);
        $('#gallery_images').val(galleryImages.join(','));
        $(this).closest('.gallery-item').remove();

        // AJAX call to remove the gallery image from the server and database
        $.ajax({
            url: "{{ route('service.remove.gallery.image', $service->id) }}",
            type: 'POST',
            data: { image: imageName, _token: $('meta[name="csrf-token"]').attr('content') }, // Use the correct CSRF token meta name
            success: function(response) {
                if (response.status === 200) {
                    console.log('Gallery image removed successfully');
                } else {
                    console.log('Error removing gallery image: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('AJAX Error: ' + textStatus);
            }
        });
    });

    // Add more video link fields
    $('#add-video-link').on('click', function() {
        $('#additional_videos_links').append(`
            <div class="input-group mb-2">
                <input type="text" name="additional_videos_links[]" class="form-control" placeholder="Enter additional video link">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary remove-link" type="button">Remove</button>
                </div>
            </div>
        `);
    });

    // Remove video link fields
    $(document).on('click', '.remove-link', function() {
        $(this).closest('.input-group').remove();
    });


    $("#name").change(function(){
        $("button[type='submit']").prop('disabled',true);
        $.ajax({
            url: '{{ route("service.slug") }}',
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
