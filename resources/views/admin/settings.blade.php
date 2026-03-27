@extends('admin.layouts.app')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content h-100">
        <div class="container-fluid h-100">
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <form action="" method="post" name="settingsFrom" id="settingsFrom">
                        <div class="card">
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="website_title">Website Title</label>
                                    <input type="text" name="website_title" id="website_title" class="form-control" value="{{ (!empty($settings->website_title)) ? $settings->website_title : '' }}">
                                    <p class="error website-title-error text-danger"></p>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" value="{{ (!empty($settings->email)) ? $settings->email : '' }}" name="email" id="email" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" value="{{ (!empty($settings->phone)) ? $settings->phone : '' }}" name="phone" id="phone" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="copy">Copyright</label>
                                    <input type="text" value="{{ (!empty($settings->copy)) ? $settings->copy : '' }}" name="copy" id="copy" class="form-control">
                                </div>

                                <div class="mt-4">
                                    <h4><strong>Social Links</strong></h4>
                                    <hr>

                                    <div class="form-group">
                                        <label for="facebook_url">Facebook Url</label>
                                        <input type="text" value="{{ (!empty($settings->facebook_url)) ? $settings->facebook_url : '' }}" name="facebook_url" id="facebook_url" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="twitter_url">Twitter Url</label>
                                        <input type="text" value="{{ (!empty($settings->twitter_url)) ? $settings->twitter_url : '' }}" name="twitter_url" id="twitter_url" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="instagram_url">Instagram Url</label>
                                        <input type="text" value="{{ (!empty($settings->instagram_url)) ? $settings->instagram_url : '' }}" name="instagram_url" id="instagram_url" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="whatsapp_url">Whatsapp Url</label>
                                        <input type="text" value="{{ (!empty($settings->whatsapp_url)) ? $settings->whatsapp_url : '' }}" name="whatsapp_url" id="whatsapp_url" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="tiktok_url">Tiktok Url</label>
                                        <input type="text" value="{{ (!empty($settings->tiktok_url)) ? $settings->tiktok_url : '' }}" name="tiktok_url" id="tiktok_url" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="linkedin_url">Linkedin Url</label>
                                        <input type="text" value="{{ (!empty($settings->linkedin_url)) ? $settings->linkedin_url : '' }}" name="linkedin_url" id="linkedin_url" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="youtube_url">Youtube Url</label>
                                        <input type="text" value="{{ (!empty($settings->youtube_url)) ? $settings->youtube_url : '' }}" name="youtube_url" id="youtube_url" class="form-control">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h4><strong>Quote PDF Settings</strong></h4>
                                    <hr>

                                    <div class="form-group">
                                        <label for="quote_sender_name">Quote Sender Name</label>
                                        <input type="text" value="{{ (!empty($settings->quote_sender_name)) ? $settings->quote_sender_name : '' }}" name="quote_sender_name" id="quote_sender_name" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="quote_sender_phone">Quote Sender Phone</label>
                                        <input type="text" value="{{ (!empty($settings->quote_sender_phone)) ? $settings->quote_sender_phone : '' }}" name="quote_sender_phone" id="quote_sender_phone" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="quote_sender_email">Quote Sender Email</label>
                                        <input type="text" value="{{ (!empty($settings->quote_sender_email)) ? $settings->quote_sender_email : '' }}" name="quote_sender_email" id="quote_sender_email" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="quote_sender_website">Quote Sender Website</label>
                                        <input type="text" value="{{ (!empty($settings->quote_sender_website)) ? $settings->quote_sender_website : '' }}" name="quote_sender_website" id="quote_sender_website" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="quote_footer_text">Quote Footer Text</label>
                                        <textarea name="quote_footer_text" id="quote_footer_text" class="summernote" rows="3">{{ (!empty($settings->quote_footer_text)) ? $settings->quote_footer_text : '' }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="quote_client_to_provide">Client to Provide (one line = one item)</label>
                                        <textarea name="quote_client_to_provide" id="quote_client_to_provide" class="summernote" rows="6">{{ (!empty($settings->quote_client_to_provide)) ? $settings->quote_client_to_provide : '' }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="quote_terms_conditions">Terms & Conditions (one line = one item)</label>
                                        <textarea name="quote_terms_conditions" id="quote_terms_conditions" class="summernote" rows="14">{{ (!empty($settings->quote_terms_conditions)) ? $settings->quote_terms_conditions : '' }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact_card_one">Contact Card One</label>
                                            <textarea name="contact_card_one" id="contact_card_one" class="summernote">{!! (!empty($settings->contact_card_one)) ? $settings->contact_card_one : '' !!}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Featured Categories</label>
                                        <div class="row">
                                            <div class="col">
                                                <select name="category" id="category" class="form-control">
                                                    @if($categories)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                                        <div class="ui-state-default" data-id="{{ $category->category_id }}" id="category-{{ $category->category_id }}">
                                                            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                            {{ $category->name }}
                                                            <button type="button" onclick="deleteService({{ $category->category_id }});" class="btn btn-danger btn-sm">Delete</button>
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
        </div>
    </section>
@endsection


@section('extraJs')

<script type="text/javascript">
    function deleteService(id) {
        $("#category-" + id).remove();
    }

    $(function() {
        $("#categories-wrapper").sortable();
    });

    function addCategory() {
        var categoryId = $("#category").val();
        var categoryName = $("#category option:selected").text();

        var html = `<div class="ui-state-default" data-id="${categoryId}" id="category-${categoryId}">
            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
            ${categoryName}
            <button type="button" onclick="deleteService(${categoryId});" class="btn btn-danger btn-sm">Delete</button>
        </div>`;

        var isFound = false;

        $("#categories-wrapper .ui-state-default").each(function() {
            var id = $(this).attr('data-id');
            if (id == categoryId) {
                isFound = true;
            }
        });

        if (isFound == true) {
            alert("You can not select same category again.");
        } else {
            $("#categories-wrapper").append(html);
        }
    }

    $("#settingsFrom").submit(function(event) {
        event.preventDefault();
        $("button[type='submit']").prop('disabled', true);

        var categoriesString = $("#categories-wrapper").sortable('serialize');
        var data = $("#settingsFrom").serializeArray();
        data[data.length] = { name: 'categories', value: categoriesString };

        $.ajax({
            url: '{{ route("settings.save") }}',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(response) {
                $("button[type='submit']").prop('disabled', false);

                if (response.status == 200) {
                    window.location.href = '{{ route("settings.index") }}';
                } else {
                    if (response.errors.website_title) {
                        $('.website-title-error').html(response.errors.website_title[0]);
                    } else {
                        $('.website-title-error').html('');
                    }
                }
            },
            error: function() {
                $("button[type='submit']").prop('disabled', false);
                alert('Something went wrong. Please try again.');
            }
        });
    });
</script>

@endsection