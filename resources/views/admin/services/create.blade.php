@extends('admin.layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Services / Create</h1>
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
    <section class="content h-100">
        <div class="container-fluid h-100">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('service.create') }}" method="post" name="createServiceForm" id="createServiceForm">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('serviceList') }}" class="btn btn-primary">Back</a>
                            </div>
                            <div class="card-body">

                                <!-- Other Form Fields -->
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

                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a category</option>
                                        @if($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error category-error"></p>
                                </div>
                                <!-- Existing Video Link Section -->
                                <div class="form-group">
                                    <label for="videos_link">Video Link</label>
                                    <input type="text" name="videos_link" id="videos_link" class="form-control">
                                </div>

                                <!-- New Video Links Section -->
                                <div class="form-group">
                                    <label for="additional_videos_links">Additional Videos Links</label>
                                    <div id="additional_videos_links">
                                        <div class="input-group mb-2">
                                            <input type="text" name="additional_videos_links[]" class="form-control" placeholder="Enter additional video link">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary remove-link" type="button">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="add-video-link" class="btn btn-primary">Add Another Link</button>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="summernote"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="short_description">Short Description</label>
                                    <textarea name="short_description" id="short_description" cols="30" rows="7" class="form-control"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="MAX 70 CHARACTERS">
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" cols="30" rows="7" class="form-control"  placeholder="MAX 160 CHARACTERS"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="7" class="form-control"  placeholder="MAX 160 CHARACTERS"></textarea>
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
                                    </div>
                                    <div class="col-md-6">
                                            <label for="gallery">Image Gallery</label>
                                            <div id="gallery" class="dropzone dz-clickable">
                                                <div class="dz-message needsclick">
                                                    <br>Drop files here or click to upload.<br><br>
                                                </div>
                                            </div>
                                            <input type="hidden" name="gallery_images" id="gallery_images" value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="image_alt_text">Image Alt Text</label>
                                    <textarea name="image_alt_text" id="metaimage_alt_text_keywords" cols="30" rows="7" class="form-control"  placeholder="MAX 160 CHARACTERS"></textarea>
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
    Dropzone.autoDiscover = false;

    const dropzone = $("#image").dropzone({
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
        },
        url: "{{ route('tempUpload') }}",
        maxFiles: 1,
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif,image/webp",
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
        acceptedFiles: "image/jpeg,image/png,image/gif,image/webp",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(file, response){
            // Append the uploaded file ID to the hidden input field for gallery images
            let currentValue = $("#gallery_images").val();
            currentValue += (currentValue ? ',' : '') + response.id;
            $("#gallery_images").val(currentValue);
        },
        removedfile: function(file) {
            // Handle removal of files from gallery
            file.previewElement.remove();
            // Adjust gallery images array
            let fileId = file.upload.uuid;  // Assuming file uuid is returned or tracked
            let currentValue = $("#gallery_images").val();
            let newValue = currentValue.replace(new RegExp('(^|,)' + fileId + '(,|$)'), '$1').replace(/^,|,$/g, '');
            $("#gallery_images").val(newValue);
        }
    });

    $("#createServiceForm").submit(function(event){
        event.preventDefault();
        $("button[type='submit']").prop('disabled', true);

        $.ajax({
            url: '{{ route("service.create") }}',
            type: 'POST',
            dataType: 'json',
            data: $("#createServiceForm").serialize(),
            success: function(response){
                $("button[type='submit']").prop('disabled', false);

                if (response.status === 200) {
                    window.location.href = '{{ route("serviceList") }}';
                } else {
                    $('.error').html(''); // Clear previous errors
                    $.each(response.errors, function(field, messages) {
                        $('.' + field + '-error').html(messages[0]); // Custom field error
                    });
                }
            }
        });
    });

    // JavaScript to dynamically add/remove additional video links
    $(document).on('click', '#add-video-link', function() {
        $('#additional_videos_links').append(`
            <div class="input-group mb-2">
                <input type="text" name="additional_videos_links[]" class="form-control" placeholder="Enter additional video link">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary remove-link" type="button">Remove</button>
                </div>
            </div>
        `);
    });

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
