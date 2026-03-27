@extends('layouts.app')

@section('content')
<section class="section-3 py-5"></section>

<section class="section-2 py-5">
  <div class="container py-2">
    @auth('admin')
      <div class="text-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-dark">← Back to Admin Dashboard</a>
      </div>
    @endauth

    <div class="about-block">
      <h1 class="title-color text-center">MIRROR BOOTH DUBAI</h1>
      <div class="divider-container text-center">
        <div class="divider mb-3"></div>
      </div>
      <div class="mt-2 mb-3 text-muted">COST CALCULATOR</div>
      <div class="text-muted">Award-Winning Photo Booth & Game Rentals in Dubai</div>
      <p>
        A trusted name in the UAE, we offer over 80+ premium photo booths and interactive games,
        <br>providing the most comprehensive range of services in the GCC.
        <br>Renowned for our professionalism and reliability, we are dedicated to client satisfaction and event success.
        <br>Our goal is to craft memorable, branded experiences that elevate every occasion.
      </p>
    </div>
  </div>
</section>

<section class="section-6 py-5">
  <div class="container py-2">
    <div class="wrap">
      <div class="grid" style="grid-template-columns: 1.5fr 1.5fr; gap:16px">
        <div class="grid" style="gap:16px">

          <div class="card">
            <h2>Client Details</h2>
            <div class="g2">
              <div class="form-group">
                <label>Company Name</label>
                <input type="text" id="clientName" placeholder="Company Name" class="input-field"/>
              </div>
              <div class="form-group">
                <label>Address</label>
                <input type="text" id="clientAddress1" placeholder="Address Line 1" class="input-field"/>
              </div>
              <div class="form-group">
                <label>Contact Name</label>
                <input type="text" id="clientAddress2" placeholder="Contact Name" class="input-field"/>
              </div>
              <div class="form-group">
                <label>Phone</label>
                <input type="text" id="clientPhone" placeholder="Contact Number" class="input-field"/>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="text" id="clientEmail" placeholder="client@example.com" class="input-field"/>
              </div>
            </div>
          </div>

          <div class="card">
            <h2>1) Dates</h2>

            <div class="row" style="margin-bottom:12px;">
              <label style="display:flex; align-items:center; gap:8px; margin-right:20px;">
                <input type="radio" name="dateMode" id="singleDayMode" value="single" checked>
                <span>Single Day</span>
              </label>

              <label style="display:flex; align-items:center; gap:8px;">
                <input type="radio" name="dateMode" id="multipleDayMode" value="multiple">
                <span>Multiple Day</span>
              </label>
            </div>

            <div id="singleDateBox">
              <div class="row">
                <div style="flex:1">
                  <label>Select date</label>
                  <input type="date" id="singleDateInput" class="input-field" />
                </div>
              </div>
            </div>

            <div id="multipleDateBox" style="display:none;">
              <div class="row">
                <div style="flex:1">
                  <label>Start date</label>
                  <input type="date" id="multiStartDate" class="input-field" />
                </div>
                <div style="flex:1">
                  <label>End date</label>
                  <input type="date" id="multiEndDate" class="input-field" />
                </div>
              </div>
            </div>

            <div class="mt12">
              <button class="btn" id="addDate" type="button">Apply Date(s)</button>
            </div>

            <div class="mt12">
              <label>Selected date(s)</label>
              <div class="list" id="dateList"></div>
            </div>
          </div>

          <div class="card">
            <h2>2) Timings</h2>
            <div class="row">
              <div class="pill">
                <input type="radio" name="mode" id="auto" checked>
                <label for="auto" style="margin:0">Automatic</label>
              </div>
              <div class="pill">
                <input type="radio" name="mode" id="manual">
                <label for="manual" style="margin:0">Manual (per date)</label>
              </div>
            </div>

            <div id="autoBox" class="g2 mt12">
              <div><label>Start</label><input type="time" id="autoStart" value="10:00"></div>
              <div><label>End</label><input type="time" id="autoEnd" value="14:00"></div>
            </div>

            <div id="manualBox" class="mt12 hide"></div>
          </div>

          <div class="card">
            <h2>3) Choose a package</h2>
            <div class="g3">
              <div>
                <label>Package</label>
                <select id="pkg"></select>

                <label class="mt8">Package Time</label>
                <select id="packageTime"></select>

                <label class="mt8">Quantity</label>
                <input type="number" id="pkgQty" min="1" value="1">
              </div>

              <div>
                <label>Location (applies to THIS package item)</label>
                <select id="loc"></select>
              </div>

              <div>
                <label>Branding (applies to THIS package item)</label>
                <select id="bran"></select>
              </div>

              <div>
                <label>Advance Setup Mode</label>
                <select id="advanceMode" class="input-field">
                  <option value="">Select mode...</option>
                  <option value="dropdown">Dropdown Selection</option>
                  <option value="manual">Manual Input</option>
                </select>

                <div class="mt8" id="advanceDropdownWrap" style="display:none;">
                  <label>Advance Setup</label>
                  <select id="advanceSelect"></select>
                </div>

                <div class="mt8" id="advanceManualWrap" style="display:none;">
                  <label>Advance Setup Name</label>
                  <input type="text" id="advanceManualName" placeholder="Enter advance setup name" class="input-field" />

                  <label class="mt8">Advance Setup Price</label>
                  <input type="number" id="advanceManualPrice" placeholder="Enter price" min="0" class="input-field" />

                  <label class="mt8">Advance Setup Note</label>
                  <input type="text" id="advanceManualNote" placeholder="Enter note" class="input-field" />
                </div>

                <div class="mt8" id="advanceDateBox" style="display:none;">
                  <label style="margin:0;">Select Advance Setup Date</label>
                  <input type="date" id="advanceDate" />
                </div>
              </div>

              <div>
                <label>Notes (optional)</label>
                <input id="notes" type="text" placeholder="special requests..." />
              </div>
            </div>

            <h2 style="margin-top:30px;">5) Optional add-ons (applies to THIS package item)</h2>
            <div id="addonsBox"></div>

            <h2 class="mt20">6) Discount</h2>

            <div class="discount-section g2">
              <div>
                <label>Discount Type</label>
                <select id="discountType">
                  <option value="percent">Percentage (%)</option>
                  <option value="fixed">Fixed (AED)</option>
                </select>
              </div>

              <div>
                <label>Value</label>
                <input type="number" id="discountValue" min="0" value="0" />
              </div>
            </div>

            <button class="btn mt12" id="addPkgBtn" type="button" style="width:100%">
              Add Package Item
            </button>
          </div>
        </div>

        <div class="card">
          <h2>Summary</h2>
          <div id="summary"></div>
          <div class="mt16">
            <button class="btn primary" id="copy" type="button">Download PDF</button>
            <button class="btn" id="reset" type="button">Reset</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

@php
    $quoteSettings = getSettings();

    $htmlToLines = function ($html) {
        $html = (string) $html;

        $html = str_replace(['<br>', '<br/>', '<br />'], "\n", $html);
        $html = preg_replace('/<\/p>/i', "\n", $html);
        $html = preg_replace('/<\/div>/i', "\n", $html);
        $html = preg_replace('/<li[^>]*>/i', '• ', $html);
        $html = preg_replace('/<\/li>/i', "\n", $html);

        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $lines = preg_split('/\r\n|\r|\n/', $text);
        $lines = array_map(function ($line) {
            return trim(preg_replace('/\s+/', ' ', $line));
        }, $lines);

        return array_values(array_filter($lines, function ($line) {
            return $line !== '';
        }));
    };

    $htmlToText = function ($html) use ($htmlToLines) {
        $lines = $htmlToLines($html);
        return implode(' ', $lines);
    };

    $clientToProvide = $htmlToLines($quoteSettings?->quote_client_to_provide ?? '');
    $termsConditions = $htmlToLines($quoteSettings?->quote_terms_conditions ?? '');

    $pdfSenderName = $quoteSettings?->quote_sender_name ?: 'MAHA KHAN';
    $pdfSenderPhone = $quoteSettings?->quote_sender_phone ?: ($quoteSettings?->phone ?: '');
    $pdfSenderEmail = $quoteSettings?->quote_sender_email ?: ($quoteSettings?->email ?: '');
    $pdfSenderWebsite = $quoteSettings?->quote_sender_website ?: 'www.mirrorboothdubai.com';
    $pdfFooterText = $htmlToText($quoteSettings?->quote_footer_text ?: 'Warehouse WH-S09, Plot Number 361- 0, Umm Ramool, Dubai, 10148, Dubai, United Arab Emirates');
@endphp

<script>
window.quoteCalculatorData = {
    packages: @json($packagesData),
    packageTimes: @json($packageTimesData),
    locations: @json($locationsData),
    addons: @json($addonsData),
    advanceSetups: @json($advanceSetupsData),
    branding: @json($brandingData)
};

window.quotePdfData = {
    clientToProvide: @json($clientToProvide),
    terms: @json($termsConditions),
    companyDetails: {
        senderName: @json($pdfSenderName),
        senderPhone: @json($pdfSenderPhone),
        senderEmail: @json($pdfSenderEmail),
        senderWebsite: @json($pdfSenderWebsite),
        footerText: @json($pdfFooterText)
    }
};
</script>

<script src="{{ asset('assets/js/quote-calculator.js') }}"></script>
<script src="{{ asset('assets/js/quote-pdf.js') }}"></script>
@endsection


