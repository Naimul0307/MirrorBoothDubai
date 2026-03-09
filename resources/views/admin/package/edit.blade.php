@extends('admin.layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Package / Edit</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('packageList') }}">Package List</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content h-100">
    <div class="container-fluid h-100">
        <div class="row">
            <div class="col-md-12">
                <form id="packageEdit" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('packageList') }}" class="btn btn-secondary">Back</a>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $package->name }}">
                                <small class="text-danger name-error"></small>
                            </div>

                            <div class="form-group">
                                <label>Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" readonly value="{{ $package->slug }}">
                                <small class="text-danger slug-error"></small>
                            </div>

                            <div class="form-group">
                                <label>Price</label>
                                <input type="text" name="price" id="price" class="form-control" value="{{ $package->price }}">
                                <small class="text-danger price-error"></small>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="summernote">{{ $package->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Included Hours</label>
                                <input type="number" name="included_hours" id="included_hours" class="form-control" value="{{ $package->included_hours }}">
                                <small class="text-danger included-hours-error"></small>
                            </div>

                            <div class="form-group">
                                <label>Branding</label>
                                <select name="brand_id" class="form-control">
                                    <option value="">-- Select Branding --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" 
                                            {{ $package->brands->first() && $package->brands->first()->id == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }} (AED {{ number_format($brand->price,2) }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-danger branding-error"></small>
                            </div>

                            <div class="form-group">
                                <label>Extra Hours</label>
                                <select name="hour_id" class="form-control">
                                    <option value="">-- Select Hour --</option>
                                    @foreach($hours as $hour)
                                        <option value="{{ $hour->id }}" 
                                            {{ $package->hours->first() && $package->hours->first()->id == $hour->id ? 'selected' : '' }}>
                                            {{ $hour->name }} (AED {{ number_format($hour->price,2) }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-danger hours-error"></small>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $package->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $package->status == 0 ? 'selected' : '' }}>Block</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extraJs')
<script>
$(document).ready(function(){
    $("#packageEdit").submit(function(e){
        e.preventDefault();
        let submitBtn = $(this).find("button[type='submit']");
        submitBtn.prop('disabled', true);
        $(".text-danger").text('');

        $.ajax({
            url: '{{ route("package.update", $package->id) }}',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response){
                submitBtn.prop('disabled', false);
                if(response.status === 200){
                    window.location.href = '{{ route("packageList") }}';
                } else if(response.errors){
                    $('.name-error').text(response.errors.name?.[0] || '');
                    $('.slug-error').text(response.errors.slug?.[0] || '');
                    $('.price-error').text(response.errors.price?.[0] || '');
                    $('.branding-error').text(response.errors.brand_id?.[0] || '');
                }
            },
            error: function(){
                submitBtn.prop('disabled', false);
                alert('Something went wrong!');
            }
        });
    });

    $("#name").on("change", function(){
        let name = $(this).val();
        if(name.trim() !== ''){
            $.get('{{ route("package.slug") }}', {name:name}, function(response){
                $("#slug").val(response.slug);
            }, 'json');
        }
    });
});
</script>
@endsection
