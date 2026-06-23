<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="google-site-verification" content="DRjpAQfoniJxaRdOE3UhWhguZA2bL32hftgz2ymHRYI" />
        <title>{{ $meta_title ?? 'MIRROR BOOTH EVENT SERVICES L.L.C.-DUBAI' }}</title>
        <link rel="canonical" href="{{ $meta_canonical ?? url()->current() }}">
        <meta name="title" content="{{ $meta_title ?? 'MIRROR BOOTH EVENT SERVICES L.L.C.-DUBAI' }}">
        <meta name="description" content="{{ $meta_description ?? 'Default description' }}">
        <meta name="keywords" content="{{ $meta_keywords ?? 'Default, Keywords' }}">
        <meta name="_token" content="{{ csrf_token() }}">

        {{-- Favicons --}}
        <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
        <link rel="apple-touch-icon" href="{{ asset('assets/apple-touch-icon.png') }}" sizes="180x180">
        <link rel="icon" href="{{ asset('assets/android-chrome-192x192.png') }}" sizes="192x192" type="image/png">
        <link rel="icon" href="{{ asset('assets/android-chrome-512x512.png') }}" sizes="512x512" type="image/png">

        {{-- Critical CSS --}}
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/navebar.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">

        {{-- Non-critical CSS deferred --}}
        <link rel="preload" href="{{ asset('assets/css/slick.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="{{ asset('assets/css/slick.min.css') }}"></noscript>

        <link rel="preload" href="{{ asset('assets/css/tom-select.main.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="{{ asset('assets/css/tom-select.main.css') }}"></noscript>

        <link rel="preconnect" href="https://cloud.fotomaster.com">
        <link rel="preload" as="image" href="{{ asset('assets/images/logo.webp') }}">

        @yield('extraCss')
    </head>

<body id="top">
    <header>

        {{-- ─── DESKTOP NAV ─── --}}
        <nav class="desktop-nav" aria-label="Desktop navigation">
            <div class="nav-bar">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        {{-- ✅ removed height attribute — CSS controls height:auto --}}
                        <img src='{{ asset("assets/images/logo.webp") }}'
                             alt='Mirror Booth Dubai Logo'
                             class="logo-img"
                             width="220"/>
                    </a>
                </div>
                <ul class="menu" role="list">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                            Services <i class="fa-solid fa-caret-down" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu" role="list">
                            @foreach (getCategories() as $category)
                                <li><a href="{{ route('categories.index', ['slug' => $category->slug]) }}">{{ $category->name }}</a></li>
                            @endforeach
                            <li><a href="{{ route('project') }}">Project</a></li>
                            <li><a href="{{ route('services') }}">View All</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('blogs') }}">Blog</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li>
                        <form class="search-form" action="{{ route('services.search') }}" method="get" role="search">
                            <button type="button" class="search-icon-btn" aria-label="Open search" aria-expanded="false" aria-controls="search-input-desktop">
                                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                            </button>
                            <input id="search-input-desktop" type="search" name="keyword" class="search-input" hidden aria-label="Search services">
                            <button type="submit" class="search-submit-btn" aria-label="Submit search" hidden>
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- ─── MOBILE NAV ─── --}}
        <nav class="mobile-nav" aria-label="Mobile navigation">
            <div class="nav-bar">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        {{-- ✅ removed height attribute --}}
                        <img src='{{ asset("assets/images/logo.webp") }}'
                             alt='Mirror Booth Dubai Logo'
                             class="logo-img"
                             width="160"/>
                    </a>
                </div>
                <button class="menu-toggle" id="menu-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu" type="button">
                    <i class="fas fa-bars" aria-hidden="true"></i>
                </button>
                <button class="close-menu" id="close-menu" aria-label="Close menu" type="button">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <ul class="menu" id="mobile-menu" role="list">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                        Services <i class="fas fa-caret-down" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu" role="list">
                        @foreach (getCategories() as $category)
                            <li><a href="{{ route('categories.index', ['slug' => $category->slug]) }}">{{ $category->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('project') }}">Project</a></li>
                        <li><a href="{{ route('services') }}">View All</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                <li><a href="{{ route('blogs') }}">Blog</a></li>
                <li><a href="{{ route('faq') }}">FAQ</a></li>
                <li>
                    <form class="search-form" action="{{ route('services.search') }}" method="get" role="search">
                        <button type="button" class="search-icon-btn" aria-label="Open search" aria-expanded="false" aria-controls="search-input-mobile">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        </button>
                        <input id="search-input-mobile" type="search" name="keyword" class="search-input" hidden aria-label="Search services">
                        <button type="submit" class="search-submit-btn" aria-label="Submit search" hidden>
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

    </header>

    {{-- WhatsApp Float Button --}}
    <a href="https://wa.me/971502664501?text=Hello%20there!" class="whatsapp-button" target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp">
        <img src="{{ asset('uploads/WhatsApp.svg') }}" alt="" width="24" height="24" aria-hidden="true">
        Chat with us
    </a>

    <main id="main-content">
        @yield('content')
    </main>

    {{-- ─── FOOTER ─── --}}
    <footer class="footer section gray-bg" aria-label="Site footer">
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="widget mb-5 mb-lg-0">
                        <h3 class="footer-heading mb-3">Services</h3>
                        <ul class="list-unstyled footer-menu lh-35">
                            @foreach (getCategories() as $category)
                                <li><a href="{{ route('categories.index', ['slug' => $category->slug]) }}">{{ $category->name }}</a></li>
                            @endforeach
                            <li><a href="{{ route('project') }}">Project</a></li>
                            <li><a href="{{ route('services') }}">View All</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="widget mb-5 mb-lg-0">
                        <h3 class="footer-heading mb-3">Quick Links</h3>
                        <ul class="list-unstyled footer-menu lh-35">
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                            <li><a href="{{ route('faq') }}">FAQ</a></li>
                        </ul>
                    </div>
                    <div class="widget mt-4">
                        <a href="https://cloud.fotomaster.com/console/badges/check/mNzip2Kxv3FV2vNhLaCF" target="_blank" rel="noopener noreferrer" title="Click to Verify Genuineness">
                            <img src="https://cloud.fotomaster.com/foto-master-badge-genuiness.png"
                                 alt="Certified Photo Booth: Click to Verify Genuineness"
                                 width="120" loading="lazy"
                                 style="height:auto;">
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="widget widget-contact mb-5 mb-lg-0">
                        <h3 class="footer-heading mb-3">Get in Touch</h3>
                        @php $settings = getSettings(); @endphp
                        <div class="footer-contact-block mb-4">
                            @if(!empty($settings) && $settings->email)
                            <p class="footer-contact-item">
                                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                                <a class="footer-contact-link" href="mailto:{{ $settings->email }}" aria-label="Email us">{{ $settings->email }}</a>
                            </p>
                            @endif
                            @if(!empty($settings) && $settings->phone)
                            <p class="footer-contact-item">
                                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                                <a class="footer-contact-link" href="tel:{{ $settings->phone }}" aria-label="Call us">{{ $settings->phone }}</a>
                            </p>
                            @endif
                        </div>

                        <ul class="list-inline footer-socials mt-4" aria-label="Social media links">
                            @if(!empty($settings) && $settings->facebook_url)
                            <li class="list-inline-item">
                                <a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" aria-label="Visit our Facebook page">
                                    <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                                </a>
                            </li>
                            @endif
                            @if(!empty($settings) && $settings->twitter_url)
                            <li class="list-inline-item">
                                <a href="{{ $settings->twitter_url }}" target="_blank" rel="noopener noreferrer" aria-label="Visit our Twitter page">
                                    <i class="fa-brands fa-twitter" aria-hidden="true"></i>
                                </a>
                            </li>
                            @endif
                            @if(!empty($settings) && $settings->instagram_url)
                            <li class="list-inline-item">
                                <a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Visit our Instagram page">
                                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                                </a>
                            </li>
                            @endif
                            @if(!empty($settings) && $settings->whatsapp_url)
                            <li class="list-inline-item">
                                <a href="{{ $settings->whatsapp_url }}" target="_blank" rel="noopener noreferrer" aria-label="Contact us on WhatsApp">
                                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                                </a>
                            </li>
                            @endif
                            @if(!empty($settings) && $settings->tiktok_url)
                            <li class="list-inline-item">
                                <a href="{{ $settings->tiktok_url }}" target="_blank" rel="noopener noreferrer" aria-label="Visit our TikTok page">
                                    <i class="fa-brands fa-tiktok" aria-hidden="true"></i>
                                </a>
                            </li>
                            @endif
                            @if(!empty($settings) && $settings->linkedin_url)
                            <li class="list-inline-item">
                                <a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener noreferrer" aria-label="Visit our LinkedIn page">
                                    <i class="fa-brands fa-linkedin" aria-hidden="true"></i>
                                </a>
                            </li>
                            @endif
                            @if(!empty($settings) && $settings->youtube_url)
                            <li class="list-inline-item">
                                <a href="{{ $settings->youtube_url }}" target="_blank" rel="noopener noreferrer" aria-label="Visit our YouTube channel">
                                    <i class="fa-brands fa-youtube" aria-hidden="true"></i>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>

            <div class="footer-btm py-4 mt-5">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-6">
                        @if(!empty($settings) && $settings->copy)
                        <div class="copyright">
                            <a href="https://dubai-photobooth.com/" class="custom-link" aria-label="Visit our website">{{ $settings->copy }}</a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <a class="backtop scroll-top-to reveal" href="#top" aria-label="Back to top">
                            <i class="icofont-long-arrow-up" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/slick.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/custom.js') }}" defer></script>
    <script src="{{ asset('assets/js/disableRightClick.js') }}" defer></script>
    <script src="{{ asset('assets/js/nav-bar.js') }}" defer></script>
    <script src="{{ asset('assets/js/jspdf.umd.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/jspdf.plugin.autotable.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/tom-select.complete.min.js') }}" defer></script>

    <script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
    });
    </script>

    @yield('extraJs')
</html>
