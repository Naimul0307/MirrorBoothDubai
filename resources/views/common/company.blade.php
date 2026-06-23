<section class="section-6 py-5">
    <div class="container">

        <h2 class="title-color mb-4">Who we work with</h2>

        <div class="divider-container">
            <div class="divider mb-3"></div>
        </div>

        <div class="companies-slider">

            @foreach($companies as $company)

                @if(!empty($company->image))

                    <div class="px-2">

                        <div class="supporter-logo text-center">

                            <img
                                src="{{ asset('uploads/companies/thumb/large/' . $company->image) }}"
                                class="img-fluid"
                                alt="{{ $company->name }}">

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

    </div>
</section>
