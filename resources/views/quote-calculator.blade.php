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

          {{-- Step 1 --}}
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

          {{-- Step 2 --}}
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

          {{-- Step 3 --}}
          <div class="card">
            <h2>3) Choose a package</h2>
            <div class="g3">
              <div>
                <label>Package</label>
                <select id="pkg"></select>

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

        
            {{-- Step 5 --}}
            <h2 style="margin-top:30px;">5) Optional add-ons (applies to THIS package item)</h2>
            <div id="addonsBox"></div>

            {{-- Step 6 --}}
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

        {{-- Summary --}}
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

<script>
/** =========================
 *  DATA
 *  ========================= */
const PACKAGES       = @json($packagesData);
const LOCATIONS      = @json($locationsData);
const ADDONS         = @json($addonsData);
const ADVANCE_SETUPS = @json($advanceSetupsData);
const BRANDING       = @json($brandingData);

/** =========================
 *  ELEMENTS
 *  ========================= */
const pkgSelect   = document.getElementById('pkg');
const locSelect   = document.getElementById('loc');
const brandSelect = document.getElementById('bran');
const notesInput  = document.getElementById('notes');
const addPkgBtn   = document.getElementById('addPkgBtn');

const addDateBtn      = document.getElementById('addDate');
const dateList        = document.getElementById('dateList');

const singleDayMode   = document.getElementById('singleDayMode');
const multipleDayMode = document.getElementById('multipleDayMode');

const singleDateBox   = document.getElementById('singleDateBox');
const multipleDateBox = document.getElementById('multipleDateBox');

const singleDateInput = document.getElementById('singleDateInput');
const multiStartDate  = document.getElementById('multiStartDate');
const multiEndDate    = document.getElementById('multiEndDate');

const autoRadio   = document.getElementById('auto');
const manualRadio = document.getElementById('manual');
const autoBox     = document.getElementById('autoBox');
const manualBox   = document.getElementById('manualBox');

const addonsBoxEl = document.getElementById('addonsBox');
const summaryBox  = document.getElementById('summary');

const discountTypeSelect = document.getElementById('discountType');
const discountValueInput = document.getElementById('discountValue');

const resetBtn = document.getElementById('reset');
const copyBtn  = document.getElementById('copy');

const advanceModeEl        = document.getElementById('advanceMode');
const advanceSelectEl      = document.getElementById('advanceSelect');
const advanceDropdownWrap  = document.getElementById('advanceDropdownWrap');
const advanceManualWrap    = document.getElementById('advanceManualWrap');
const advanceManualNameEl  = document.getElementById('advanceManualName');
const advanceManualPriceEl = document.getElementById('advanceManualPrice');
const advanceManualNoteEl  = document.getElementById('advanceManualNote');
const advanceDateBox       = document.getElementById('advanceDateBox');
const advanceDateEl        = document.getElementById('advanceDate');

/** =========================
 *  STATE
 *  ========================= */
let selectedDates = [];
let selectedItems = [];
let editingItemId = null;

let discountType  = discountTypeSelect.value;
let discountValue = parseFloat(discountValueInput.value) || 0;

let pkgTS = null;
let branTS = null;
let advanceTS = null;

/** =========================
 *  POPULATE DROPDOWNS
 *  ========================= */
function populateDropdowns() {
  pkgSelect.innerHTML = '';

  const pkgEmpty = document.createElement('option');
  pkgEmpty.value = '';
  pkgEmpty.textContent = 'Select package...';
  pkgSelect.appendChild(pkgEmpty);

  PACKAGES.forEach(p => {
    const opt = document.createElement('option');
    opt.value = String(p.id);
    opt.textContent = `${p.name} (${p.price} AED)`;
    pkgSelect.appendChild(opt);
  });

  locSelect.innerHTML = '';

  const locEmpty = document.createElement('option');
  locEmpty.value = '';
  locEmpty.textContent = 'Select location...';
  locSelect.appendChild(locEmpty);

  LOCATIONS.forEach(l => {
    const opt = document.createElement('option');
    opt.value = String(l.id);
    opt.textContent = `${l.name} (+${l.surcharge} AED)`;
    locSelect.appendChild(opt);
  });

  brandSelect.innerHTML = '';

  const brandEmpty = document.createElement('option');
  brandEmpty.value = '';
  brandEmpty.textContent = 'Select branding...';
  brandSelect.appendChild(brandEmpty);

  BRANDING.forEach(b => {
    const opt = document.createElement('option');
    opt.value = String(b.id);
    opt.textContent = `${b.name} (+${b.price} AED)`;
    brandSelect.appendChild(opt);
  });
}

function populateAdvanceDropdown() {
  advanceSelectEl.innerHTML = '';

  const empty = document.createElement('option');
  empty.value = "";
  empty.textContent = "Select advance setup...";
  advanceSelectEl.appendChild(empty);

  ADVANCE_SETUPS.forEach(a => {
    const opt = document.createElement('option');
    opt.value = String(a.id);
    opt.textContent = `${a.name} (+${a.price} AED)`;
    advanceSelectEl.appendChild(opt);
  });
}

/** =========================
 *  INIT TOMSELECT
 *  ========================= */
function initSearchSelects() {
  if (pkgTS) pkgTS.destroy();
  if (branTS) branTS.destroy();

  pkgTS = new TomSelect('#pkg', {
    placeholder: 'Type package name...',
    allowEmptyOption: true,
    maxOptions: 2000,
    searchField: ['text'],
    sortField: [{ field: '$score' }, { field: 'text' }],
    openOnFocus: true
  });

  branTS = new TomSelect('#bran', {
    placeholder: 'Type branding name...',
    allowEmptyOption: true,
    maxOptions: 2000,
    searchField: ['text'],
    sortField: [{ field: '$score' }, { field: 'text' }],
    openOnFocus: true
  });
}

function initAdvanceSelect() {
  if (advanceTS) advanceTS.destroy();

  advanceTS = new TomSelect('#advanceSelect', {
    placeholder: 'Type advance setup...',
    allowEmptyOption: true,
    maxOptions: 2000,
    searchField: ['text'],
    sortField: [{ field: '$score' }, { field: 'text' }],
    openOnFocus: true
  });

  advanceTS.on('change', () => {
    if (advanceModeEl.value === 'dropdown') {
      const val = advanceTS.getValue();
      const hasAny = !!val;
      advanceDateBox.style.display = hasAny ? 'block' : 'none';
      if (!hasAny) advanceDateEl.value = '';
    }
  });
}

/** =========================
 *  ADVANCE MODE UI
 *  ========================= */
function toggleAdvanceModeUI() {
  const mode = advanceModeEl.value;

  if (mode === 'dropdown') {
    advanceDropdownWrap.style.display = 'block';
    advanceManualWrap.style.display = 'none';

    const val = advanceTS ? advanceTS.getValue() : advanceSelectEl.value;
    advanceDateBox.style.display = val ? 'block' : 'none';

    advanceManualNameEl.value = '';
    advanceManualPriceEl.value = '';
    advanceManualNoteEl.value = '';
  } else if (mode === 'manual') {
    advanceDropdownWrap.style.display = 'none';
    advanceManualWrap.style.display = 'block';
    advanceDateBox.style.display = 'block';

    if (advanceTS) advanceTS.setValue("", true);
    else advanceSelectEl.value = "";
  } else {
    advanceDropdownWrap.style.display = 'none';
    advanceManualWrap.style.display = 'none';
    advanceDateBox.style.display = 'none';

    if (advanceTS) advanceTS.setValue("", true);
    else advanceSelectEl.value = "";

    advanceManualNameEl.value = '';
    advanceManualPriceEl.value = '';
    advanceManualNoteEl.value = '';
    advanceDateEl.value = '';
  }
}

/** =========================
 *  STEP 5: ADDONS CHECKBOXES
 *  ========================= */
function renderAddonsCheckboxes() {
  addonsBoxEl.innerHTML = '';
  ADDONS.forEach(a => {
    const div = document.createElement('div');
    div.className = "addon-row";
    div.innerHTML = `
      <label style="display:flex; align-items:center; gap:8px;">
        <input type="checkbox" value="${a.id}">
        <span>${a.name} (+${a.price} AED)</span>
      </label>
    `;
    addonsBoxEl.appendChild(div);
  });
}

/** =========================
 *  HELPERS
 *  ========================= */
function formatDate(d) {
  const dt = new Date(d);
  if (Number.isNaN(dt.getTime())) return d;
  return dt.toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' });
}

function stripHtml(s) {
  return String(s || "")
    .replace(/<br\s*\/?>/gi, "\n")
    .replace(/<\/p>/gi, "\n")
    .replace(/<[^>]*>/g, "")
    .replace(/&nbsp;/g, " ")
    .trim();
}

function descToLines(desc) {
  const clean = stripHtml(desc);
  if (!clean) return [];
  return clean.split(/\r?\n|•/).map(x => x.trim()).filter(Boolean);
}

function calcDurationHours(start, end) {
  if (!start || !end) return 0;
  const [sh, sm] = start.split(':').map(Number);
  const [eh, em] = end.split(':').map(Number);
  let duration = (eh + em / 60) - (sh + sm / 60);
  if (duration < 0) duration += 24;
  return duration;
}

function formatHours(h) {
  return Number.isInteger(h) ? `${h} hours` : `${h.toFixed(2)} hrs`;
}

function getTimesFromUI(dateStr) {
  if (autoRadio.checked) {
    return {
      start: document.getElementById('autoStart')?.value || '10:00',
      end: document.getElementById('autoEnd')?.value || '14:00',
    };
  }

  const startEl = manualBox.querySelector(`input[data-date="${dateStr}"][data-type="start"]`);
  const endEl   = manualBox.querySelector(`input[data-date="${dateStr}"][data-type="end"]`);

  return {
    start: startEl?.value || '10:00',
    end: endEl?.value || '14:00',
  };
}

function getItemTimesForDate(item, dateStr) {
  if (!item.timesByDate) {
    return { start: '10:00', end: '14:00' };
  }

  const exact = item.timesByDate[dateStr];
  if (exact) {
    return {
      start: exact.start || '10:00',
      end: exact.end || '14:00'
    };
  }

  const availableDates = Object.keys(item.timesByDate);
  if (availableDates.length === 1) {
    const first = item.timesByDate[availableDates[0]];
    return {
      start: first.start || '10:00',
      end: first.end || '14:00'
    };
  }

  return { start: '10:00', end: '14:00' };
}

function getDateMode() {
  return singleDayMode.checked ? 'single' : 'multiple';
}

function getDatesBetween(start, end) {
  const dates = [];
  const current = new Date(start + 'T00:00:00');
  const endDate = new Date(end + 'T00:00:00');

  while (current <= endDate) {
    const yyyy = current.getFullYear();
    const mm = String(current.getMonth() + 1).padStart(2, '0');
    const dd = String(current.getDate()).padStart(2, '0');
    dates.push(`${yyyy}-${mm}-${dd}`);
    current.setDate(current.getDate() + 1);
  }

  return dates;
}

function getTotalDays(item) {
  return Array.isArray(item.savedDates) && item.savedDates.length ? item.savedDates.length : 1;
}

function getDateRangeText(item) {
  const dates = item.savedDates || [];
  if (!dates.length) return '-';
  if (dates.length === 1) return formatDate(dates[0]);
  return `${formatDate(dates[0])} to ${formatDate(dates[dates.length - 1])}`;
}

/** =========================
 *  INCLUDED HOURS + EXTRA HOURS
 *  ========================= */
function getIncludedHours(item) {
  return Number(item.includedHours || 4);
}

function formatIncludedHours(item) {
  const h = getIncludedHours(item);
  return Number.isInteger(h) ? `${h} hours` : `${h.toFixed(2)} hrs`;
}

function getExtraHoursCount(item, duration) {
  const included = getIncludedHours(item);
  return duration > included ? Math.ceil(duration - included) : 0;
}

function getPackageExtraHourRate(pkgOrItem) {
  if (!pkgOrItem) return 0;

  if (pkgOrItem.extraHourRate != null && !isNaN(Number(pkgOrItem.extraHourRate))) {
    return Number(pkgOrItem.extraHourRate);
  }

  if (pkgOrItem.extra_hour_rate != null && !isNaN(Number(pkgOrItem.extra_hour_rate))) {
    return Number(pkgOrItem.extra_hour_rate);
  }

  if (pkgOrItem.extra_hour_price != null && !isNaN(Number(pkgOrItem.extra_hour_price))) {
    return Number(pkgOrItem.extra_hour_price);
  }

  if (Array.isArray(pkgOrItem.hours) && pkgOrItem.hours.length) {
    const found = pkgOrItem.hours.find(h => h && h.price != null && !isNaN(Number(h.price)));
    if (found) {
      return Number(found.price);
    }
  }

  return 0;
}

function getItemExtraHourRate(item) {
  return getPackageExtraHourRate(item);
}

function getItemExtraHoursBreakdown(item) {
  const dates = Array.isArray(item.savedDates) ? item.savedDates : [];
  const rate = getItemExtraHourRate(item);
  const qty = Number(item.qty || 1);

  return dates.map(date => {
    const t = getItemTimesForDate(item, date);
    const duration = calcDurationHours(t.start, t.end);
    const extraHours = getExtraHoursCount(item, duration);

    return {
      date,
      start: t.start,
      end: t.end,
      duration,
      extraHours,
      rate,
      qty,
      total: extraHours * rate * qty
    };
  });
}

function getItemTotalExtraHourCharge(item) {
  const breakdown = getItemExtraHoursBreakdown(item);

  return breakdown.reduce((acc, row) => {
    if (row.extraHours > 0) {
      acc.extraHours += Number(row.extraHours || 0);
      acc.total += Number(row.total || 0);
    }
    acc.rate = Number(row.rate || 0);
    return acc;
  }, {
    extraHours: 0,
    rate: 0,
    total: 0
  });
}

function getItemSubtotal(item) {
  const totalDays = getTotalDays(item);

  let s = Number(item.price) * Number(item.qty) * totalDays;
  s += (item.locations || []).reduce((acc, l) => acc + Number(l.surcharge), 0);
  s += (item.branding || []).reduce((acc, b) => acc + Number(b.price), 0);
  s += (item.addons || []).reduce((acc, a) => acc + Number(a.price), 0);

  const extraHour = getItemTotalExtraHourCharge(item);
  s += Number(extraHour.total || 0);

  return s;
}

/** =========================
 *  DATE MODE UI
 *  ========================= */
function toggleDateModeUI() {
  if (getDateMode() === 'single') {
    singleDateBox.style.display = 'block';
    multipleDateBox.style.display = 'none';
  } else {
    singleDateBox.style.display = 'none';
    multipleDateBox.style.display = 'block';
  }
}

/** =========================
 *  DATES UI
 *  ========================= */
function renderDateList() {
  dateList.innerHTML = '';

  if (!selectedDates.length) return;

  if (selectedDates.length === 1) {
    const row = document.createElement('div');
    row.className = 'row';
    row.innerHTML = `
      <span>${formatDate(selectedDates[0])} (1 day)</span>
      <button class="btn small danger" id="clearDatesBtn" type="button">x</button>
    `;
    dateList.appendChild(row);
  } else {
    const row = document.createElement('div');
    row.className = 'row';
    row.innerHTML = `
      <span>${formatDate(selectedDates[0])} to ${formatDate(selectedDates[selectedDates.length - 1])} (${selectedDates.length} days)</span>
      <button class="btn small danger" id="clearDatesBtn" type="button">x</button>
    `;
    dateList.appendChild(row);

    selectedDates.forEach(d => {
      const item = document.createElement('div');
      item.style.fontSize = '13px';
      item.style.padding = '4px 0';
      item.textContent = `• ${formatDate(d)}`;
      dateList.appendChild(item);
    });
  }

  const clearBtn = document.getElementById('clearDatesBtn');
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      selectedDates = [];
      renderDateList();
      renderManualTimes();
      updateSummary();
    });
  }
}

/** =========================
 *  TIMINGS UI
 *  ========================= */
autoRadio.addEventListener('change', () => {
  autoBox.classList.remove('hide');
  manualBox.classList.add('hide');
  updateSummary();
});

manualRadio.addEventListener('change', () => {
  manualBox.classList.remove('hide');
  autoBox.classList.add('hide');
  renderManualTimes();
  updateSummary();
});

function renderManualTimes() {
  manualBox.innerHTML = '';

  selectedDates.forEach(date => {
    const existingItem = editingItemId ? selectedItems.find(x => x.id === editingItemId) : null;
    const existingTime = existingItem ? getItemTimesForDate(existingItem, date) : { start: '10:00', end: '14:00' };

    const div = document.createElement('div');
    div.className = 'row g2';
    div.innerHTML = `
      <div>
        <label>${formatDate(date)} start</label>
        <input type="time" data-date="${date}" data-type="start" value="${existingTime.start}">
      </div>
      <div>
        <label>${formatDate(date)} end</label>
        <input type="time" data-date="${date}" data-type="end" value="${existingTime.end}">
      </div>
    `;
    manualBox.appendChild(div);
  });

  manualBox.querySelectorAll('input').forEach(input => input.addEventListener('change', updateSummary));
}

function applyTimesToUI(item) {
  if (!item || !item.timesByDate) return;

  if (autoRadio.checked && selectedDates.length) {
    const first = selectedDates[0];
    const t = getItemTimesForDate(item, first);
    document.getElementById('autoStart').value = t.start || '10:00';
    document.getElementById('autoEnd').value = t.end || '14:00';
    return;
  }

  selectedDates.forEach(d => {
    const t = getItemTimesForDate(item, d);
    const s = manualBox.querySelector(`input[data-date="${d}"][data-type="start"]`);
    const e = manualBox.querySelector(`input[data-date="${d}"][data-type="end"]`);
    if (s) s.value = t.start || '10:00';
    if (e) e.value = t.end || '14:00';
  });
}

/** =========================
 *  EDIT MODE
 *  ========================= */
function setEditingMode(isEditing) {
  if (isEditing) {
    addPkgBtn.textContent = "Save Changes";
    addPkgBtn.classList.add('primary');

    if (!document.getElementById('cancelEditBtn')) {
      const cancelBtn = document.createElement('button');
      cancelBtn.type = "button";
      cancelBtn.id = "cancelEditBtn";
      cancelBtn.className = "btn mt12";
      cancelBtn.textContent = "Cancel Edit";
      cancelBtn.style.width = "100%";
      addPkgBtn.parentNode.insertBefore(cancelBtn, addPkgBtn.nextSibling);

      cancelBtn.addEventListener('click', () => {
        editingItemId = null;
        setEditingMode(false);
        clearFormSelections();
        updateSummary();
      });
    }
  } else {
    addPkgBtn.textContent = "Add Package Item";
    addPkgBtn.classList.remove('primary');
    const cancelBtn = document.getElementById('cancelEditBtn');
    if (cancelBtn) cancelBtn.remove();
  }
}

function clearFormSelections() {
  document.getElementById('pkgQty').value = 1;

  if (pkgTS) {
    pkgTS.setValue('', true);
  } else {
    pkgSelect.value = '';
  }

  if (branTS) {
    branTS.setValue('', true);
  } else {
    brandSelect.value = '';
  }

  locSelect.value = '';

  advanceModeEl.value = '';

  if (advanceTS) {
    advanceTS.setValue("", true);
  } else {
    advanceSelectEl.value = "";
  }

  advanceDropdownWrap.style.display = "none";
  advanceManualWrap.style.display = "none";
  advanceDateBox.style.display = "none";

  advanceManualNameEl.value = "";
  advanceManualPriceEl.value = "";
  advanceManualNoteEl.value = "";
  advanceDateEl.value = "";

  addonsBoxEl.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);

  selectedDates = [];
  singleDayMode.checked = true;
  multipleDayMode.checked = false;
  singleDateInput.value = '';
  multiStartDate.value = '';
  multiEndDate.value = '';

  autoRadio.checked = true;
  manualRadio.checked = false;
  autoBox.classList.remove('hide');
  manualBox.classList.add('hide');

  document.getElementById('autoStart').value = '10:00';
  document.getElementById('autoEnd').value = '14:00';

  toggleDateModeUI();
  toggleAdvanceModeUI();
  renderDateList();
  renderManualTimes();
}

function setFormFromItem(item) {
  selectedDates = Array.isArray(item.savedDates) ? [...item.savedDates] : [];

  if ((item.dateMode || 'single') === 'multiple' && selectedDates.length > 1) {
    singleDayMode.checked = false;
    multipleDayMode.checked = true;
    multiStartDate.value = selectedDates[0] || '';
    multiEndDate.value = selectedDates[selectedDates.length - 1] || '';
  } else {
    singleDayMode.checked = true;
    multipleDayMode.checked = false;
    singleDateInput.value = selectedDates[0] || '';
    multiStartDate.value = '';
    multiEndDate.value = '';
  }

  toggleDateModeUI();
  renderDateList();

  if (pkgTS) pkgTS.setValue(String(item.pkgId), true);
  else pkgSelect.value = String(item.pkgId);

  document.getElementById('pkgQty').value = item.qty || 1;

  if (item.locations && item.locations.length) {
    locSelect.value = String(item.locations[0].id);
  } else {
    locSelect.value = '';
  }

  const brId = (item.branding && item.branding.length) ? String(item.branding[0].id) : '';
  if (branTS) branTS.setValue(brId, true);
  else brandSelect.value = brId;

  const advOne = (item.addons || []).find(a => a.type === 'advance');

  if (advOne) {
    if (advOne.isManual) {
      advanceModeEl.value = 'manual';
      toggleAdvanceModeUI();

      advanceManualNameEl.value = advOne.name || '';
      advanceManualPriceEl.value = advOne.price || '';
      advanceManualNoteEl.value = advOne.note || '';
      advanceDateEl.value = advOne.selectedDate || '';
    } else {
      advanceModeEl.value = 'dropdown';
      toggleAdvanceModeUI();

      const advId = String(advOne.id || "");
      if (advanceTS) advanceTS.setValue(advId, true);
      else advanceSelectEl.value = advId;

      advanceDateEl.value = advOne.selectedDate || '';
      advanceDateBox.style.display = 'block';
    }
  } else {
    advanceModeEl.value = '';
    toggleAdvanceModeUI();
  }

  const addonIds = new Set((item.addons || []).filter(a => a.type === 'addon').map(a => String(a.id)));
  addonsBoxEl.querySelectorAll('input[type=checkbox]').forEach(cb => {
    cb.checked = addonIds.has(String(cb.value));
  });

  const firstDate = selectedDates[0];
  const firstTime = firstDate ? getItemTimesForDate(item, firstDate) : { start: '10:00', end: '14:00' };

  autoRadio.checked = true;
  manualRadio.checked = false;
  autoBox.classList.remove('hide');
  manualBox.classList.add('hide');
  document.getElementById('autoStart').value = firstTime.start || '10:00';
  document.getElementById('autoEnd').value = firstTime.end || '14:00';

  renderManualTimes();
  applyTimesToUI(item);
}

/** =========================
 *  SUMMARY
 *  ========================= */
function updateSummary() {
  const tables = [];
  let allSubtotals = 0;

  selectedItems.forEach(item => {
    const subtotal = getItemSubtotal(item);
    const extraHour = getItemTotalExtraHourCharge(item);

    tables.push({
      item,
      subtotalBeforeDiscount: subtotal,
      extraHour
    });

    allSubtotals += subtotal;
  });

  let itemsListHtml = '';
  if (selectedItems.length) {
    itemsListHtml += `<h3>Added Package Items</h3>
      <table border="1" cellspacing="0" cellpadding="5" style="width:100%; margin-bottom:20px; border-collapse:collapse">
        <tr style="background:#f5f5f5">
          <th>#</th>
          <th>Package</th>
          <th class="right">Qty</th>
          <th class="right">Days</th>
          <th>Date Range</th>
          <th>Location</th>
          <th>Branding</th>
          <th>Add-ons</th>
          <th class="right">Action</th>
        </tr>`;

    selectedItems.forEach((it, idx) => {
      const locTxt = (it.locations || []).map(x => x.name).join(', ') || '-';
      const brTxt  = (it.branding || []).map(x => x.name).join(', ') || '-';
      const adTxt  = (it.addons || []).map(a => {
        const noteText = a.note ? ` (${a.note})` : '';
        return a.selectedDate
          ? `${a.name}${noteText} - ${formatDate(a.selectedDate)}`
          : `${a.name}${noteText}`;
      }).join(', ') || '-';

      itemsListHtml += `
        <tr>
          <td>${idx + 1}</td>
          <td>${it.name}</td>
          <td class="right">${it.qty}</td>
          <td class="right">${getTotalDays(it)}</td>
          <td>${getDateRangeText(it)}</td>
          <td>${locTxt}</td>
          <td>${brTxt}</td>
          <td>${adTxt}</td>
          <td class="right">
            <button class="btn small" type="button" data-edit-item="${it.id}">Edit</button>
            <button class="btn small danger" type="button" data-remove-item="${it.id}">Remove</button>
          </td>
        </tr>`;
    });

    itemsListHtml += `</table>`;
  }

  let packagesTable = '';
  if (!selectedItems.length) {
    packagesTable = `<p class="muted">Add at least 1 package item to show tables.</p>`;
  } else {
    tables.forEach(ti => {
      const { item, subtotalBeforeDiscount } = ti;
      let subtotal = subtotalBeforeDiscount;

      let discountAmount = 0;
      if (discountType === 'percent') {
        discountAmount = subtotal * (discountValue / 100);
      } else {
        discountAmount = (allSubtotals > 0)
          ? (Number(discountValue || 0) * (subtotal / allSubtotals))
          : 0;
      }

      const safeDiscount = Math.min(discountAmount, subtotal);
      const afterDiscount = subtotal - safeDiscount;
      const vat = afterDiscount * 0.05;
      const total = afterDiscount + vat;

      const descLines = descToLines(item.desc || "");
      const descHtml = descLines.length
        ? `<div style="margin-top:6px; font-size:12px; line-height:1.4; white-space:pre-line;">${descLines.map(x => "• " + x).join("\n")}</div>`
        : "";

      let rows = `
        <tr>
          <td>
            ${getDateRangeText(item)}
            <div style="margin-top:4px; font-size:12px;">${getTotalDays(item)} day(s)</div>
          </td>
          <td>
            <b>${item.name}</b>
            ${descHtml}
          </td>
          <td class="right">${Number(item.qty)}</td>
          <td class="right">${formatIncludedHours(item)}</td>
          <td class="right">${Number(item.price).toFixed(2)}</td>
          <td class="right">${(Number(item.price) * Number(item.qty) * getTotalDays(item)).toFixed(2)}</td>
        </tr>`;

      (item.locations || []).forEach(loc => {
        rows += `
          <tr>
            <td></td>
            <td>Logistic labor setup & dismantling - ${loc.name}</td>
            <td class="right">1</td>
            <td></td>
            <td class="right">${Number(loc.surcharge).toFixed(2)}</td>
            <td class="right">${Number(loc.surcharge).toFixed(2)}</td>
          </tr>`;
      });

      (item.branding || []).forEach(br => {
        rows += `
          <tr>
            <td></td>
            <td>${br.name}</td>
            <td class="right">1</td>
            <td></td>
            <td class="right">${Number(br.price).toFixed(2)}</td>
            <td class="right">${Number(br.price).toFixed(2)}</td>
          </tr>`;
      });

      (item.addons || []).forEach(a => {
        const noteText = a.note ? ` (${a.note})` : '';
        const title = a.selectedDate ? `${a.name}${noteText} - ${formatDate(a.selectedDate)}` : `${a.name}${noteText}`;
        rows += `
          <tr>
            <td></td>
            <td>${title}</td>
            <td class="right">1</td>
            <td></td>
            <td class="right">${Number(a.price).toFixed(2)}</td>
            <td class="right">${Number(a.price).toFixed(2)}</td>
          </tr>`;
      });

      const extraHourRows = getItemExtraHoursBreakdown(item).filter(ex => ex.extraHours > 0);

      extraHourRows.forEach(ex => {
        rows += `
          <tr>
            <td>${formatDate(ex.date)}</td>
            <td>Additional Hours (${ex.start} - ${ex.end})</td>
            <td class="right">${ex.qty}</td>
            <td class="right">${ex.extraHours} hour${ex.extraHours > 1 ? 's' : ''}</td>
            <td class="right">${ex.rate.toFixed(2)}</td>
            <td class="right">${ex.total.toFixed(2)}</td>
          </tr>`;
      });

      rows += `
        <tr>
          <td colspan="5" class="right"><b>SUBTOTAL</b></td>
          <td class="right">${subtotal.toFixed(2)}</td>
        </tr>
        <tr>
          <td colspan="5" class="right"><b>DISCOUNT</b></td>
          <td class="right">-${safeDiscount.toFixed(2)}</td>
        </tr>
        <tr>
          <td colspan="5" class="right"><b>VAT 5%</b></td>
          <td class="right">${vat.toFixed(2)}</td>
        </tr>
        <tr style="background:#eee">
          <td colspan="5" class="right"><b>TOTAL</b></td>
          <td class="right"><b>${total.toFixed(2)}</b></td>
        </tr>`;

      packagesTable += `
        <h3>${item.name} — ${getDateRangeText(item)}</h3>
        <table border="1" style="width:100%; border-collapse:collapse; margin-bottom:12px">
          <tr style="background:#000;color:#fff">
            <th>DATE</th>
            <th>DESCRIPTION</th>
            <th>QTY</th>
            <th>DURATION</th>
            <th>UNIT PRICE</th>
            <th>TOTAL</th>
          </tr>
          ${rows}
        </table>`;
    });
  }

  summaryBox.innerHTML = itemsListHtml + packagesTable;

  summaryBox.querySelectorAll('button[data-remove-item]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-remove-item');

      selectedItems = selectedItems.filter(x => x.id !== id);

      if (editingItemId === id) {
        editingItemId = null;
        setEditingMode(false);
        clearFormSelections();
      }

      updateSummary();
    });
  });

  summaryBox.querySelectorAll('button[data-edit-item]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-edit-item');
      const item = selectedItems.find(x => x.id === id);
      if (!item) return;

      editingItemId = id;
      setFormFromItem(item);
      setEditingMode(true);
    });
  });
}

/** =========================
 *  ADD / SAVE PACKAGE ITEM
 *  ========================= */
addPkgBtn.onclick = () => {
  if (!selectedDates.length) {
    alert("Please select event date(s).");
    return;
  }

  const pkgId = pkgTS ? pkgTS.getValue() : pkgSelect.value;
  const pkg = PACKAGES.find(p => String(p.id) === String(pkgId));
  const qty = parseInt(document.getElementById('pkgQty').value) || 1;

  if (!pkg) {
    alert("Please select a package.");
    return;
  }

  const itemAddons = [];

  if (advanceModeEl.value === 'dropdown') {
    const selectedAdvanceId = advanceTS ? advanceTS.getValue() : advanceSelectEl.value;

    if (selectedAdvanceId) {
      const advDate = advanceDateEl.value || null;
      if (!advDate) {
        alert("Please select date for Advance Setup");
        return;
      }

      const a = ADVANCE_SETUPS.find(x => String(x.id) === String(selectedAdvanceId));
      if (a) {
        itemAddons.push({
          id: a.id,
          name: a.name,
          price: Number(a.price),
          selectedDate: advDate,
          type: 'advance',
          isManual: false,
          note: ''
        });
      }
    }
  }

  if (advanceModeEl.value === 'manual') {
    const manualName = (advanceManualNameEl.value || '').trim();
    const manualPrice = Number(advanceManualPriceEl.value || 0);
    const manualNote = (advanceManualNoteEl.value || '').trim();
    const advDate = advanceDateEl.value || null;

    if (manualName || manualPrice || manualNote || advDate) {
      if (!manualName) {
        alert("Please enter Advance Setup name");
        return;
      }

      if (!advDate) {
        alert("Please select date for Advance Setup");
        return;
      }

      itemAddons.push({
        id: 'manual-advance',
        name: manualName,
        price: manualPrice,
        selectedDate: advDate,
        type: 'advance',
        isManual: true,
        note: manualNote
      });
    }
  }

  addonsBoxEl.querySelectorAll('input[type=checkbox]:checked').forEach(cb => {
    const a = ADDONS.find(x => String(x.id) === String(cb.value));
    if (!a) return;
    itemAddons.push({
      id: a.id,
      name: a.name,
      price: Number(a.price),
      selectedDate: null,
      type: 'addon'
    });
  });

  const loc = LOCATIONS.find(l => String(l.id) === String(locSelect.value));
  const itemLocs = loc ? [{ id: loc.id, name: loc.name, surcharge: Number(loc.surcharge) }] : [];

  const brId = branTS ? branTS.getValue() : brandSelect.value;
  const br = BRANDING.find(b => String(b.id) === String(brId));
  const itemBrands = br ? [{ id: br.id, name: br.name, price: Number(br.price) }] : [];

  const normalizedDates = [...selectedDates].sort();

  const timesByDate = {};
  normalizedDates.forEach(d => {
    const uiTime = getTimesFromUI(d);
    timesByDate[d] = {
      start: uiTime.start || '10:00',
      end: uiTime.end || '14:00'
    };
  });

  const snapshot = {
    pkgId: pkg.id,
    name: pkg.name,
    desc: pkg.desc || "",
    price: Number(pkg.price),
    includedHours: Number(pkg.included_hours || 4),
    extraHourRate: Number(getPackageExtraHourRate(pkg) || 0),
    qty,
    dateMode: getDateMode(),
    locations: itemLocs,
    branding: itemBrands,
    addons: itemAddons,
    hours: Array.isArray(pkg.hours) ? pkg.hours : [],
    savedDates: normalizedDates,
    timesByDate
  };

  if (editingItemId) {
    const idx = selectedItems.findIndex(x => x.id === editingItemId);
    if (idx !== -1) {
      selectedItems[idx] = { ...selectedItems[idx], ...snapshot };
    }

    editingItemId = null;
    setEditingMode(false);
    clearFormSelections();
    updateSummary();
    return;
  }

  selectedItems.push({
    id: (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : String(Date.now() + Math.random()),
    ...snapshot
  });

  clearFormSelections();
  updateSummary();
};

/** =========================
 *  ADD DATE
 *  ========================= */
addDateBtn.onclick = () => {
  const mode = getDateMode();

  if (mode === 'single') {
    if (!singleDateInput.value) {
      alert("Please select a date.");
      return;
    }

    selectedDates = [singleDateInput.value];
  } else {
    if (!multiStartDate.value || !multiEndDate.value) {
      alert("Please select start date and end date.");
      return;
    }

    if (multiStartDate.value > multiEndDate.value) {
      alert("Start date cannot be greater than end date.");
      return;
    }

    selectedDates = getDatesBetween(multiStartDate.value, multiEndDate.value);
  }

  renderDateList();
  renderManualTimes();
  updateSummary();
};

/** =========================
 *  DISCOUNT + TIME CHANGE
 *  ========================= */
discountTypeSelect.addEventListener('change', e => {
  discountType = e.target.value;
  updateSummary();
});

discountValueInput.addEventListener('input', e => {
  discountValue = parseFloat(e.target.value) || 0;
  updateSummary();
});

document.getElementById('autoStart').addEventListener('change', updateSummary);
document.getElementById('autoEnd').addEventListener('change', updateSummary);

singleDayMode.addEventListener('change', toggleDateModeUI);
multipleDayMode.addEventListener('change', toggleDateModeUI);
advanceModeEl.addEventListener('change', toggleAdvanceModeUI);

/** =========================
 *  RESET
 *  ========================= */
resetBtn.addEventListener('click', () => {
  selectedDates = [];
  selectedItems = [];
  editingItemId = null;

  discountTypeSelect.value = 'percent';
  discountValueInput.value = 0;
  discountType = discountTypeSelect.value;
  discountValue = parseFloat(discountValueInput.value) || 0;

  setEditingMode(false);
  clearFormSelections();
  renderDateList();
  renderManualTimes();
  updateSummary();
});

/** =========================
 *  INIT
 *  ========================= */
populateDropdowns();
populateAdvanceDropdown();
initSearchSelects();
initAdvanceSelect();
renderAddonsCheckboxes();

if (pkgTS) pkgTS.setValue('', true);
if (branTS) branTS.setValue('', true);
if (advanceTS) advanceTS.setValue('', true);
locSelect.value = '';

toggleDateModeUI();
toggleAdvanceModeUI();
renderDateList();
renderManualTimes();
setEditingMode(false);
updateSummary();
</script>

<script>
copyBtn.addEventListener('click', async () => {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('p', 'mm', 'a4');

  const fmt = (num) => Number(num).toFixed(2);

  const PAGE_W = doc.internal.pageSize.getWidth();
  const PAGE_H = doc.internal.pageSize.getHeight();

  const MARGIN_L = 14;
  const MARGIN_R = 14;

  const HEADER_H = 55;
  const START_Y_FIRST = 72;
  const START_Y_OTHER = 60;
  const FOOTER_H = 10;

  const FOOTER_TEXT =
    "Warehouse WH-S09, Plot Number 361- 0, Umm Ramool, Dubai, 10148, Dubai, United Arab Emirates";

  const loadImage = async (url) => {
    const response = await fetch(url);
    const blob = await response.blob();

    return await new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onloadend = () => resolve(reader.result);
      reader.onerror = reject;
      reader.readAsDataURL(blob);
    });
  };

  const clientName = document.getElementById("clientName").value || "N/A";
  const clientAddress1 = document.getElementById("clientAddress1").value || "";
  const clientAddress2 = document.getElementById("clientAddress2").value || "";
  const clientPhone = document.getElementById("clientPhone").value || "";
  const clientEmail = document.getElementById("clientEmail").value || "";

  const quotationNo = Math.floor(Math.random() * 10000);
  const todayText = new Date().toLocaleDateString('en-US', {
    month: 'short',
    day: '2-digit',
    year: 'numeric'
  }).toUpperCase();

  const logoBase64 = await loadImage('/assets/images/logo.jpg');

  function drawHeader() {
    doc.setFillColor(255, 255, 255);
    doc.rect(0, 0, PAGE_W, HEADER_H, "F");

    const logoX = MARGIN_L;
    const logoY = 12;
    const logoW = 62;
    const logoH = 20;
    doc.addImage(logoBase64, "PNG", logoX, logoY, logoW, logoH);

    const barX = logoX + logoW;
    const barY = logoY + 7;
    const barW = PAGE_W - barX - MARGIN_R;
    const barH = 6;

    doc.setFillColor(230, 0, 0);
    doc.rect(barX, barY, barW, barH, "F");
  }

  function drawBillToFirstPage() {
    const billX = MARGIN_L;
    const billY = 12 + 20 + 12;

    doc.setTextColor(0, 0, 0);
    doc.setFont("helvetica", "bold");
    doc.setFontSize(9);
    doc.text("Bill To:", billX, billY);

    doc.setFont("helvetica", "normal");
    doc.setFontSize(9);

    const billLines = [
      clientName !== "N/A" ? clientName : "",
      clientAddress1 || "",
      clientAddress2 || "",
      clientPhone ? `P: ${clientPhone}` : "",
      clientEmail || ""
    ].filter(Boolean);

    let yy = billY + 5;
    billLines.forEach(line => {
      doc.text(line, billX, yy);
      yy += 4;
    });
  }

  function drawQuoteMetaFirstPage() {
    doc.setTextColor(0, 0, 0);

    doc.setFont("helvetica", "bold");
    doc.setFontSize(9);
    doc.text(todayText, PAGE_W - MARGIN_R, START_Y_FIRST - 6, { align: "right" });

    doc.setFont("helvetica", "normal");
    doc.setFontSize(8);
    doc.text(`QUOTE #: ${quotationNo}`, PAGE_W - MARGIN_R, START_Y_FIRST - 2, { align: "right" });
  }

  function drawFooter() {
    doc.setFont("helvetica", "normal");
    doc.setFontSize(8);
    doc.setTextColor(60);
    doc.text(FOOTER_TEXT, PAGE_W / 2, PAGE_H - 6, { align: "center" });
  }

  function stripHtmlLocal(s) {
    return String(s || "")
      .replace(/<br\s*\/?>/gi, "\n")
      .replace(/<\/p>/gi, "\n")
      .replace(/<[^>]*>/g, "")
      .replace(/&nbsp;/g, " ")
      .trim();
  }

  function makeBulletLines(desc) {
    const clean = stripHtmlLocal(desc);
    if (!clean) return [];
    const parts = clean.split(/\r?\n|•/).map(x => x.trim()).filter(Boolean);
    return parts.map(x => "• " + x);
  }

  drawHeader();
  drawBillToFirstPage();
  drawQuoteMetaFirstPage();
  drawFooter();

  const tables = [];
  let allSubtotals = 0;

  selectedItems.forEach(item => {
    const subtotal = getItemSubtotal(item);
    const extraHour = getItemTotalExtraHourCharge(item);

    tables.push({
      item,
      subtotalBeforeDiscount: subtotal,
      extraHour
    });

    allSubtotals += subtotal;
  });

  let cursorY = START_Y_FIRST;

  function newPage() {
    doc.addPage();
    drawHeader();
    drawFooter();
    cursorY = START_Y_OTHER;
  }

  function ensureSpace(needHeightMm) {
    const bottomLimit = PAGE_H - FOOTER_H - 10;
    if (cursorY + needHeightMm > bottomLimit) newPage();
  }

  function renderOneTable(ti) {
    const { item, subtotalBeforeDiscount } = ti;

    let subtotal = subtotalBeforeDiscount;

    let discountAmount = 0;
    if (discountType === 'percent') {
      discountAmount = subtotal * (discountValue / 100);
    } else {
      discountAmount = (allSubtotals > 0)
        ? (Number(discountValue || 0) * (subtotal / allSubtotals))
        : 0;
    }

    const safeDiscount = Math.min(discountAmount, subtotal);
    const afterDiscount = subtotal - safeDiscount;
    const vat = afterDiscount * 0.05;
    const total = afterDiscount + vat;

    const body = [];

    const bullets = makeBulletLines(item.desc || "");
    const descParts = [item.name];
    if (bullets.length) descParts.push(...bullets);

    const descCell = descParts.join("\n");
    const dateCell = `${getDateRangeText(item)}\n${getTotalDays(item)} day(s)`;

    body.push([
      dateCell,
      descCell,
      String(Number(item.qty)),
      formatIncludedHours(item),
      fmt(item.price),
      fmt(Number(item.price) * Number(item.qty) * getTotalDays(item))
    ]);

    (item.locations || []).forEach(loc => {
      body.push([
        "",
        "Logistic labor setup & dismantling in " + loc.name,
        "1",
        "",
        fmt(loc.surcharge),
        fmt(loc.surcharge)
      ]);
    });

    (item.branding || []).forEach(br => {
      body.push([
        "",
        br.name,
        "1",
        "",
        fmt(br.price),
        fmt(br.price)
      ]);
    });
  
    (item.addons || []).forEach(a => {
    const isAdvance =
      a.type === 'advance' ||
      String(a.name).trim().toUpperCase().includes("ADVANCE");

    const noteText = a.note ? ` (${a.note})` : '';

    const title = isAdvance && a.selectedDate
      ? `${a.name}${noteText} - ${formatDate(a.selectedDate)}`
      : `${a.name}${noteText}`;

    const addonDuration = isAdvance ? "2 hours" : "";

    body.push([
      "",
      title,
      "1",
      addonDuration,
      fmt(a.price),
      fmt(a.price)
    ]);
  });

    const extraHourRows = getItemExtraHoursBreakdown(item).filter(ex => ex.extraHours > 0);

    extraHourRows.forEach(ex => {
      body.push([
        formatDate(ex.date),
        `ADDITIONAL HOURS (${ex.start} - ${ex.end})`,
        String(ex.qty),
        `${ex.extraHours} hour${ex.extraHours > 1 ? 's' : ''}`,
        fmt(ex.rate),
        fmt(ex.total)
      ]);
    });

    body.push(["", "", "", "", "SUBTOTAL", fmt(subtotal)]);
    body.push(["", "", "", "", "DISCOUNT", "-" + fmt(safeDiscount)]);
    body.push(["", "", "", "", "VAT 5%", fmt(vat)]);
    body.push(["", "", "", "", "TOTAL AMOUNT", fmt(total)]);

    ensureSpace(70);

    doc.autoTable({
      startY: cursorY,
      theme: "grid",
      head: [[
        "DATE",
        "DESCRIPTION",
        "QTY",
        "EVENT\nDURATION",
        "UNIT PRICE\n(AED)",
        "TOTAL PRICE\n(AED)"
      ]],
      body,
      styles: {
        font: "helvetica",
        fontSize: 9,
        cellPadding: 2,
        lineWidth: 0.2,
        lineColor: [0, 0, 0],
        textColor: [0, 0, 0],
        valign: "top"
      },
      headStyles: {
        fillColor: [0, 0, 0],
        textColor: 255,
        fontStyle: "bold",
        fontSize: 8,
        halign: "center",
        lineWidth: 0.2
      },
      columnStyles: {
        0: { cellWidth: 28 },
        1: { cellWidth: 72 },
        2: { cellWidth: 10, halign: "center" },
        3: { cellWidth: 22, halign: "center" },
        4: { cellWidth: 24, halign: "center" },
        5: { cellWidth: 24, halign: "center" }
      },
      didParseCell: function (data) {
        if (data.section === "body" && data.row.index === 0 && data.column.index === 1) {
          data.cell.styles.fontStyle = "bold";
        }

        if (data.section === "body" && data.column.index === 4) {
          const label = String(data.cell.raw || "").trim().toUpperCase();

          if (label === "TOTAL AMOUNT") {
            for (let i = 0; i <= 5; i++) {
              data.row.cells[i].styles.fillColor = [205, 220, 245];
              data.row.cells[i].styles.fontStyle = "bold";
            }
          }

          if (label === "SUBTOTAL" || label === "VAT 5%" || label === "DISCOUNT") {
            data.row.cells[4].styles.fontStyle = "bold";
          }
        }
      }
    });

    cursorY = doc.lastAutoTable.finalY + 10;
  }

  tables.forEach(renderOneTable);

  if (notesInput.value.trim()) {
    ensureSpace(20);
    doc.setFont("helvetica", "bold");
    doc.setFontSize(10);
    doc.setTextColor(0);
    doc.text("Notes:", MARGIN_L, cursorY);

    doc.setFont("helvetica", "normal");
    doc.setFontSize(9);
    const lines = doc.splitTextToSize(notesInput.value.trim(), PAGE_W - MARGIN_L - MARGIN_R);
    doc.text(lines, MARGIN_L, cursorY + 5);
    cursorY += 5 + (lines.length * 4.2) + 6;
  }

  const CLIENT_TO_PROVIDE = [
    "• 13-amp power supply",
    "• Smooth and plain surface area for setup",
    "• Shaded area if the setup location is outdoor",
    "• All required artworks to be provided by the client",
  ];

  const TERMS = [
    "1. Validity",
    "• Our booking policy is 1st come 1st serve basis. Without proof of payment and LPO, booking is not confirmed.",
    "",
    "2. Pricing",
    "• Any additional costs incurred due to modifications or special requirements will be charged separately.",
    "• Additional hours more than the specified timing in the quotation will be charged based on the selected package extra-hour rate.",
    "",
    "3. 100% Payment Terms",
    "• A 100% advance payment is required upon acceptance of the quotation.",
    "• Payment must be made via [bank transfer/cheque/cash] as specified in the invoice.",
    "",
    "4. 50% Payment Terms",
    "• The Client agrees to pay 50% of the total amount as advance payment to confirm the booking.",
    "• The remaining 50% balance payment must be paid before the setup, prior to the system going live or being handed over for use.",
    "• If the balance payment is not cleared during setup and test run, the Company reserves the right not to activate, operate, or allow use of the system until full payment is received.",
    "• In the event of pending or delayed balance payment, the Company shall not be held responsible for any system downtime, non-operation, loss of service, loss of revenue, or complaints from the Client or the Client’s end customers.",
    "• The Client fully understands and accepts that any interruption or non-functioning of the system due to pending payment is solely the Client’s responsibility.",
    "• By confirming the booking and proceeding with the service, the Client acknowledges, agrees to, and accepts these Terms & Conditions in full and confirms that no claims, disputes, or complaints shall be raised against the Company in relation to the system’s operation caused by non-payment.",
    "",
    "5. Delivery & Setup Time",
    "• Free setup is 2 hours before your event starts.",
    "• Advance setup within Dubai is 500 AED and outside Dubai 700 AED with 2 hours setup duration only.",
    "• The company is not liable for delays caused by third-party vendors, shipping carriers, or force majeure events.",
    "",
    "6. Cancellation & Modification",
    "• Any cancellation after acceptance of the quotation may be subject to a cancellation fee of 30% of the total amount.",
    "• Modifications requested after acceptance may result in price adjustments and extended timelines.",
    "",
    "7. Confidentiality",
    "• All pricing, specifications, and terms in this quotation are confidential and intended solely for the recipient.",
    "• Disclosure of these details to third parties without prior consent is prohibited.",
    "",
    "8. Acceptance of Terms",
    "• By approving this quotation, the client agrees to the above terms and conditions.",
    "• Any disputes shall be governed by the laws of UAE",
  ];

  function renderSection(title, linesArray) {
    ensureSpace(18);

    if (title) {
      doc.setFont("helvetica", "bold");
      doc.setFontSize(10);
      doc.setTextColor(0);
      doc.text(title, MARGIN_L, cursorY);
      cursorY += 6;
    }

    doc.setFont("helvetica", "normal");
    doc.setFontSize(9);

    const maxW = PAGE_W - MARGIN_L - MARGIN_R;
    const joined = linesArray.join("\n");
    const wrapped = doc.splitTextToSize(joined, maxW);

    wrapped.forEach(line => {
      ensureSpace(6);
      doc.text(line, MARGIN_L, cursorY);
      cursorY += 4.2;
    });

    cursorY += 6;
  }

  renderSection("Client to Provide:", CLIENT_TO_PROVIDE);
  renderSection("Terms and Conditions:", TERMS);

  ensureSpace(35);

  const leftLines = [
    "Best regards,",
    "MAHA KHAN",
    "055-5531443",
    "info@mirrorboothdubai.com",
    "www.mirrorboothdubai.com",
  ];

  const rightLines = [
    "Accepted/Approved By:",
    "__________",
    "Client’s Name & Signature"
  ];

  const leftX = MARGIN_L;
  const rightX = PAGE_W - MARGIN_R;

  let yyL = cursorY;
  doc.setFont("helvetica", "normal");
  doc.setFontSize(9);
  doc.setTextColor(0);

  leftLines.forEach(line => {
    doc.text(line, leftX, yyL);
    yyL += 4.2;
  });

  let yyR = cursorY;
  rightLines.forEach(line => {
    doc.text(line, rightX, yyR, { align: "right" });
    yyR += 4.8;
  });

  doc.save("quotation.pdf");
});
</script>
@endsection