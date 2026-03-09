@extends('admin.layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Locations / Edit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content h-100">
        <div class="container-fluid h-100">
            <div class="row">
                <div class="col-md-12">
                    <form action="" method="post" name="locationEdit" id="locationEdit">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('locationList') }}" class="btn btn-primary">Back</a>
                            </div>
                            <div class="card-body">

                                {{-- Name --}}
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{ $location->name }}" name="name" id="name" class="form-control">
                                    <p class="error text-danger name-error"></p>
                                </div>

                                {{-- Slug --}}
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" value="{{ $location->slug }}" class="form-control">
                                    <p class="error text-danger slug-error"></p>
                                </div>

                                {{-- Surcharge --}}
                                <div class="form-group">
                                    <label for="surcharge">Surcharge</label>
                                    <input type="text" name="surcharge" id="surcharge" value="{{ $location->surcharge }}" class="form-control">
                                    <p class="error text-danger surcharge-error"></p>
                                </div>

                                {{-- Status --}}
                                <div class="form-group mt-4">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ ($location->status == 1) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ ($location->status == 0) ? 'selected' : '' }}>Block</option>
                                    </select>
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#locationEdit").submit(function(event){
            event.preventDefault();
            $("button[type='submit']").prop('disabled', true);

            $.ajax({
                url: '{{ route("location.update", $location->id) }}',
                type: 'POST', // Change to PUT in route if needed
                dataType: 'json',
                data: $("#locationEdit").serialize(),
                success: function(response){
                    $("button[type='submit']").prop('disabled', false);

                    if(response.status == 200) {
                        window.location.href = '{{ route("locationList") }}';
                    } else {
                        // Name error
                        if(response.errors && response.errors.name) {
                            $('.name-error').html(response.errors.name);
                        } else {
                            $('.name-error').html('');
                        }

                        // Surcharge error
                        if(response.errors && response.errors.surcharge) {
                            $('.surcharge-error').html(response.errors.surcharge);
                        } else {
                            $('.surcharge-error').html('');
                        }

                        // Slug error
                        if(response.errors && response.errors.slug) {
                            $('.slug-error').html(response.errors.slug);
                        } else {
                            $('.slug-error').html('');
                        }
                    }
                }
            });
        });

        $("#name").change(function(){
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route("location.slug") }}',
                type: 'GET',
                data: { name: $(this).val() },
                dataType: 'json',
                success: function(response){
                    $("button[type='submit']").prop('disabled', false);
                    $("#slug").val(response.slug);
                }
            });
        });
    </script>
@endsection
