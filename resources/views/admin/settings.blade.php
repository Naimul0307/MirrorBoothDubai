@extends('admin.layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
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
                    @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                    @endif
                    <form action="" method="post" name="settingsFrom" id="settingsFrom">
                        <div class="card">
                            {{-- <div class="card-header">
                                <a href="{{ route('blogList') }}" class="btn btn-primary">Back</a>
                            </div> --}}
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Website Title</label>
                                    <input type="text" name="website_title" id="website_title" class="form-control" value="{{ (!empty($settings->website_title)) ? $settings->website_title : '' }}">
                                    <p class="error website-title-error"></p>
                                </div>

                                <div class="form-group">
                                    <label for="name">Email</label>
                                    <input type="text" value="{{ (!empty($settings->email)) ? $settings->email : ''  }}" name="email" id="email" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="name">Phone</label>
                                    <input type="text" value="{{ (!empty($settings->phone)) ? $settings->phone : '' }}" name="phone" id="phone" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="name">Copyright</label>
                                    <input type="text" value="{{ (!empty($settings->copy)) ? $settings->copy : '' }}" name="copy" id="copy" class="form-control">
                                </div>

                                <div class="mt-4">
                                    <h4><strong>Social Links</strong></h4>
                                    <hr>
                                    <div class="form-group">
                                        <label for="name">Facebook Url</label>
                                        <input type="text" value="{{ (!empty($settings->facebook_url)) ? $settings->facebook_url  : '' }}" name="facebook_url" id="facebook_url" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Twitter Url</label>
                                        <input type="text" value="{{ (!empty($settings->twitter_url)) ? $settings->twitter_url : '' }}"  name="twitter_url" id="twitter_url" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Instagram Url</label>
                                        <input type="text" value="{{ (!empty($settings->instagram_url)) ? $settings->instagram_url : '' }}"  name="instagram_url" id="instagram_url" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Watsapp Url</label>
                                        <input type="text" value="{{ (!empty($settings->whatsapp_url)) ? $settings->whatsapp_url : '' }}"  name="whatsapp_url" id="whatsapp_url" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Tiktok Url</label>
                                        <input type="text" value="{{ (!empty($settings->tiktok_url)) ? $settings->tiktok_url : '' }}"  name="tiktok_url" id="tiktok_url" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Linkedin Url</label>
                                        <input type="text" value="{{ (!empty($settings->linkedin_url)) ? $settings->linkedin_url : '' }}"  name="linkedin_url" id="linkedin_url" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Youtube Url</label>
                                        <input type="text" value="{{ (!empty($settings->youtube_url)) ? $settings->youtube_url : '' }}"  name="youtube_url" id="youtube_url" class="form-control">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Contact Card One</label>
                                            <textarea name="contact_card_one" id="contact_card_one" class="summernote" >{!! (!empty($settings->contact_card_one)) ? $settings->contact_card_one : '' !!}</textarea>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Contact Card Two</label>
                                            <textarea name="contact_card_two" id="contact_card_two" class="summernote" >{!! (!empty($settings->contact_card_two)) ? $settings->contact_card_two : ''  !!}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Contact Card Three</label>
                                            <textarea name="contact_card_three" id="contact_card_three" class="summernote" >{!! (!empty($settings->contact_card_three)) ? $settings->contact_card_three : '' !!}</textarea>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-6">
                                        <label for="">Featured Categories</label>
                                        <div class="row">
                                            <div class="col">
                                                <select name="category" id="category" class="form-control">
                                                    @if($categories)
                                                    @foreach ($categories as $category)
                                                        <option value="{{  $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col">
                                                <button onclick="addCategory();" type="button" class="btn btn-primary">
                                                    Add Category
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12" id="categories-wrapper">
                                                @if ($featuredServices->isNotEmpty())
                                                    @foreach ($featuredServices as $category)
                                                    <div class="ui-state-default" data-id='{{ $category->category_id }}' id='category-{{ $category->category_id }}''>
                                                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                        {{ $category->name }}
                                                        <button type="button" onclick="deleteService({{ $category->category_id }} );" class='btn btn-danger btn-sm'>Delete</button>
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

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

    function deleteService(id) {
        $("#category-"+id).remove();
    }

    $( function() {
        $( "#categories-wrapper" ).sortable();
    } );

    function addCategory(){
        var categoryId = $("#category").val()
        var categoryName = $("#category option:selected").text();

        var html = `<div class="ui-state-default" data-id='${categoryId}' id=category-${categoryId}><span
             class="ui-icon ui-icon-arrowthick-2-n-s"></span>${categoryName} <button type="button" onclick="deleteService(${categoryId});" class='btn btn-danger btn-sm'>Delete</button></div>`;

        var isFound = false;

        $("#categories-wrapper .ui-state-default").each(function(){
            var id = $(this).attr('data-id');
            if(id == categoryId){
                isFound = true;
            }
        });

        if(isFound == true){
            alert("You can not select same category again.");
        } else {
            $("#categories-wrapper").append(html);
        }
    }

    $("#settingsFrom").submit(function(event){
        event.preventDefault();
        $("button[type='submit']").prop('disabled',true);

        var categoriesString = $("#categories-wrapper").sortable('serialize');
        // console.log(categoriesString);
        // return false;
        var data = $("#settingsFrom").serializeArray();
        data[data.length] = {name: 'categories', value : categoriesString};

        $.ajax({
            url: '{{ route("settings.save") }}',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(response){
                $("button[type='submit']").prop('disabled',false);

                if(response.status == 200) {
                    // no error
                    window.location.href = '{{ route("settings.index") }}';
                } else {
                    // Here we will show errors
                    if(response.errors.website_title) {
                        $('.website-title-error').html(response.errors.website_title);
                    } else {
                        $('.website-title-error').html('');
                    }
                }
            }
        });
    });
</script>

@endsection
