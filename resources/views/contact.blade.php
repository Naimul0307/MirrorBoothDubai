@extends('layouts.app')

@section('content')
<section class="section-3 py-5">
</section>

<section class="section-2 py-5">
    <div class="container py-2">
        <div class="about-block">
            <h1 class="title-color text-center">MIRROR BOOTH DUBAI</h1>
            <div class="divider-container text-center">
                <div class="divider mb-3"></div>
            </div>
            <div class="mt-2 mb-3 text-muted">CONTACT US</div>
            <div class="text-muted">Award-Winning Photo Booth & Game Rentals in Dubai</div>
            <p> A trusted name in the UAE, we offer over 80+ premium photo booths and interactive games, 
            <br>providing the most comprehensive range of services in the GCC.
            <br>Renowned for our professionalism and reliability, we are dedicated to client satisfaction and event success. 
            <br>Our goal is to craft memorable, branded experiences that elevate every occasion.</p>
           </div>
    </div>
</section>
<section class="pt-5 pb-0" style="background: white">
    <div class="container contact-box" >
        <div class="row">
            <div class="col-lg-12 text-center mx-auto">
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-8 col-xl-6 text-center mx-auto">
                <h2 class="mb-4 text-black">We're here to help!</h2>
            </div>
        </div>

        <!-- Contact info box -->
        <div>
            @if(!empty($settings) && $settings->contact_card_one != '')
                <div>
                    {!! $settings->contact_card_one !!}
                </div>
            @endif
        </div>
    </div>

    <div class="container my-5">
        <div class="row g-4 g-lg-0 align-items-center">

            <!-- Contact form START -->
            <div class="col-md-12">
                <!-- Title -->
                <form action="" method="post" id="contactForm" name="contactForm">
                    <!-- Name -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4 bg-light-input">
                                <label for="name" class="form-label">Your name *</label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name">
                                <p class="name-error invalid-feedback"></p>
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="mb-4 bg-light-input">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control form-control-lg" id="phone" name="phone">
                                <p class="phone-error invalid-feedback"></p>
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="mb-4 bg-light-input">
                                <label for="email" class="form-label">Email address *</label>
                                <input type="text" class="form-control form-control-lg" id="email" name="email">
                                <p class="email-error invalid-feedback"></p>
                            </div>
                        </div>
                    </div>


                    <!-- Message -->
                    <div class="mb-4 bg-light-input">
                        <label for="textareaBox" class="form-label">Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="4"></textarea>
                        <p class="message-error invalid-feedback"></p>
                    </div>
                    <!-- Button -->
                    <div class="d-grid">
                        <button class="btn btn-lg btn-primary mb-0" id="submit" type="submit">Send Message</button>
                    </div>
                </form>
            </div>
            <!-- Contact form END -->
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <iframe class="w-100 h-400px grayscale rounded" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57739.31542034597!2d55.329430203630615!3d25.24678463557677!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f5b87fb362717%3A0xb19ed1733a5679d9!2sMirror%20Booth%20Dubai!5e0!3m2!1sen!2sae!4v1725969859667!5m2!1sen!2sae" height="500" style="border:0;" aria-hidden="true" tabindex="0"></iframe>
            </div>
        </div>
    </div>
    
    @include('common.review')

    @include('common.company')
</section>

@endsection

@section('extraJs')
<script type="text/javascript">
    $("#contactForm").submit(function(event){
        event.preventDefault();
        $("#submit").prop('disabled', true);

        $.ajax({
            url: '{{ route("sendContactEmail") }}',
            type: 'POST',
            data: $("#contactForm").serializeArray(),
            dataType: 'json',
            success: function(response){
                $("#submit").prop('disabled', false);

                if (response.status == 0) {
                    if (response.errors.name) {
                        $("#name").addClass('is-invalid');
                        $(".name-error").html(response.errors.name);
                    } else {
                        $("#name").removeClass('is-invalid');
                        $(".name-error").html('');
                    }

                    if (response.errors.email) {
                        $("#email").addClass('is-invalid');
                        $(".email-error").html(response.errors.email);
                    } else {
                        $("#email").removeClass('is-invalid');
                        $(".email-error").html('');
                    }
                    
                    if (response.errors.phone) {
                        $("#phone").addClass('is-invalid');
                        $(".phone-error").html(response.errors.phone);
                    } else {
                        $("#phone").removeClass('is-invalid');
                        $(".phone-error").html('');
                    }


                    if (response.errors.message) {
                        $("#message").addClass('is-invalid');
                        $(".message-error").html(response.errors.message);
                    } else {
                        $("#message").removeClass('is-invalid');
                        $(".message-error").html('');
                    }
                } else {
                    window.location.href = '{{ url("/contact") }}';
                }
            },
            error: function(xhr){
                $("#submit").prop('disabled', false);

                let errorMessage = 'Server error';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }

                alert('Error: ' + errorMessage);
                console.error('AJAX Error:', xhr);
            }
        });
    });
</script>

@endsection


