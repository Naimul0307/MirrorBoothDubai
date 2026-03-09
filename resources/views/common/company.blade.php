<section class="section-6 py-5">
    <div class="container">
        <h2 class="title-color mb-4">Who we work with</h2>
        <div class="divider-container">
            <div class="divider mb-3"></div>
        </div>
        <div class="cards">
            <div class="services-slider">
                @foreach($companies as $company)
                    <div class="supporter-logo">
                        <img src="{{ asset('uploads/companies/thumb/large/' . $company->image) }}" class="img-fluid" alt="{{ $company->name }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
