document.addEventListener('DOMContentLoaded', function () {
    const PACKAGES       = window.quoteCalculatorData?.packages || [];
    const PACKAGE_TIMES  = window.quoteCalculatorData?.packageTimes || [];
    const LOCATIONS      = window.quoteCalculatorData?.locations || [];
    const ADDONS         = window.quoteCalculatorData?.addons || [];
    const ADVANCE_SETUPS = window.quoteCalculatorData?.advanceSetups || [];
    const BRANDING       = window.quoteCalculatorData?.branding || [];

    const packageTimeSelect = document.getElementById('packageTime');
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

    const advanceModeEl        = document.getElementById('advanceMode');
    const advanceSelectEl      = document.getElementById('advanceSelect');
    const advanceDropdownWrap  = document.getElementById('advanceDropdownWrap');
    const advanceManualWrap    = document.getElementById('advanceManualWrap');
    const advanceManualNameEl  = document.getElementById('advanceManualName');
    const advanceManualPriceEl = document.getElementById('advanceManualPrice');
    const advanceManualNoteEl  = document.getElementById('advanceManualNote');
    const advanceDateBox       = document.getElementById('advanceDateBox');
    const advanceDateEl        = document.getElementById('advanceDate');

    let selectedDates = [];
    let selectedItems = [];
    let editingItemId = null;

    let discountType  = discountTypeSelect.value;
    let discountValue = parseFloat(discountValueInput.value) || 0;

    let pkgTS = null;
    let branTS = null;
    let advanceTS = null;

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

        packageTimeSelect.innerHTML = '';

        const ptEmpty = document.createElement('option');
        ptEmpty.value = '';
        ptEmpty.textContent = 'Select package time...';
        packageTimeSelect.appendChild(ptEmpty);

        PACKAGE_TIMES.forEach(pt => {
            const opt = document.createElement('option');
            opt.value = String(pt.id);
            opt.textContent = `${pt.name} (${pt.timer} hrs)`;
            packageTimeSelect.appendChild(opt);
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

    function renderAddonsCheckboxes() {
        addonsBoxEl.innerHTML = '';

        ADDONS.forEach(a => {
            const div = document.createElement('div');
            div.className = "addon-row";
            div.style.marginBottom = "10px";

            div.innerHTML = `
                <label style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                    <input type="checkbox" value="${a.id}" class="addon-checkbox">
                    <span>${a.name} (+${a.price} AED)</span>
                </label>

                <div class="addon-qty-wrap" id="addon-qty-wrap-${a.id}" style="display:none; margin-left:24px;">
                    <label style="font-size:12px;">Quantity</label>
                    <input
                        type="number"
                        min="1"
                        value="1"
                        class="addon-qty-input"
                        id="addon-qty-${a.id}"
                        style="width:100px;"
                    >
                </div>
            `;

            addonsBoxEl.appendChild(div);
        });

        addonsBoxEl.querySelectorAll('.addon-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const addonId = this.value;
                const qtyWrap = document.getElementById(`addon-qty-wrap-${addonId}`);
                const qtyInput = document.getElementById(`addon-qty-${addonId}`);

                if (this.checked) {
                    qtyWrap.style.display = 'block';
                    qtyInput.value = qtyInput.value || 1;
                } else {
                    qtyWrap.style.display = 'none';
                    qtyInput.value = 1;
                }

                updateSummary();
            });
        });

        addonsBoxEl.querySelectorAll('.addon-qty-input').forEach(input => {
            input.addEventListener('input', updateSummary);
        });
    }

    function formatDate(d) {
        const dt = new Date(d);
        if (Number.isNaN(dt.getTime())) return d;
        return dt.toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' });
    }

    function stripHtml(s) {
        return String(s || "")
            .replace(/<\s*br\s*\/?>/gi, "\n")
            .replace(/<\s*\/p\s*>/gi, "\n")
            .replace(/<\s*p[^>]*>/gi, "")
            .replace(/<\s*\/div\s*>/gi, "\n")
            .replace(/<\s*div[^>]*>/gi, "")
            .replace(/<\s*\/li\s*>/gi, "\n")
            .replace(/<\s*li[^>]*>/gi, "• ")
            .replace(/<\s*\/ul\s*>/gi, "\n")
            .replace(/<\s*\/ol\s*>/gi, "\n")
            .replace(/<[^>]*>/g, "")
            .replace(/&nbsp;/gi, " ")
            .replace(/&amp;/gi, "&")
            .replace(/&lt;/gi, "<")
            .replace(/&gt;/gi, ">")
            .replace(/\r/g, "")
            .replace(/\n{2,}/g, "\n")
            .trim();
    }

    function descToLines(desc) {
        const clean = stripHtml(desc);
        if (!clean) return [];

        return clean
            .split('\n')
            .map(line => line.trim())
            .filter(Boolean)
            .map(line => line.replace(/^•\s*/, '').trim())
            .filter(Boolean);
    }
    
    function normalizeSpaces(value) {
        return String(value || '').replace(/\s+/g, ' ').trim();
    }

    function limitText(value, max = 60) {
        const text = normalizeSpaces(value);
        return text.length > max ? text.slice(0, max) + '...' : text;
    }

    function safePdfText(value, max = 60) {
        return limitText(
            String(value || '')
                .replace(/Logistic labor setup & dismantling/gi, 'Logistic labour setup & dismantling')
                .replace(/Additional Hours/gi, 'Extra Hours'),
            max
        );
    }

    function calcDurationHours(start, end) {
        if (!start || !end) return 0;
        const [sh, sm] = start.split(':').map(Number);
        const [eh, em] = end.split(':').map(Number);
        let duration = (eh + em / 60) - (sh + sm / 60);
        if (duration < 0) duration += 24;
        return duration;
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

    function getIncludedHours(item) {
        return Number(item.includedHours || 0);
    }

    function formatIncludedHours(item) {
        const h = getIncludedHours(item);
        return Number.isInteger(h) ? `${h} hours` : `${h.toFixed(2)} hrs`;
    }

    function isGameReskinningItem(item) {
        const categoryName = String(item.categoryName || '').trim().toUpperCase();
        const categorySlug = String(item.categorySlug || '').trim().toLowerCase();

        return categoryName === 'GAME RESKINNING' || categorySlug === 'game-reskinning';
    }

    function getGameReskinningDurationFromAddons(item) {
        const totalDays = getTotalDays(item);
        const perDayHours = getIncludedHours(item);
        const totalHours = perDayHours * totalDays;

        if (totalDays > 1) {
            return `${totalDays} day(s) × ${Number.isInteger(perDayHours) ? perDayHours : perDayHours.toFixed(2)} hrs = ${Number.isInteger(totalHours) ? totalHours : totalHours.toFixed(2)} hrs`;
        }

        return Number.isInteger(perDayHours) ? `${perDayHours} hours` : `${perDayHours.toFixed(2)} hrs`;
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
        if (isGameReskinningItem(item)) {
            return [];
        }

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
        let s = 0;

        if (isGameReskinningItem(item)) {
            s = Number(item.price) * Number(item.qty);
        } else {
            s = Number(item.price) * Number(item.qty) * totalDays;
        }

        s += (item.locations || []).reduce((acc, l) => acc + Number(l.surcharge), 0);
        s += (item.branding || []).reduce((acc, b) => acc + Number(b.price), 0);

        s += (item.addons || []).reduce((acc, a) => {
            const qty = Number(a.qty || 1);

            if (a.type === 'advance') {
                return acc + (Number(a.price) * qty);
            }

            if (isGameReskinningItem(item)) {
                return acc + (Number(a.price) * qty * totalDays);
            }

            return acc + (Number(a.price) * qty);
        }, 0);

        if (!isGameReskinningItem(item)) {
            const extraHour = getItemTotalExtraHourCharge(item);
            s += Number(extraHour.total || 0);
        }

        return s;
    }

    function toggleDateModeUI() {
        if (getDateMode() === 'single') {
            singleDateBox.style.display = 'block';
            multipleDateBox.style.display = 'none';
        } else {
            singleDateBox.style.display = 'none';
            multipleDateBox.style.display = 'block';
        }
    }

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
        packageTimeSelect.value = '';

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

        addonsBoxEl.querySelectorAll('.addon-checkbox').forEach(cb => cb.checked = false);
        addonsBoxEl.querySelectorAll('.addon-qty-wrap').forEach(wrap => {
            wrap.style.display = 'none';
        });
        addonsBoxEl.querySelectorAll('.addon-qty-input').forEach(input => {
            input.value = 1;
        });

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

        packageTimeSelect.value = item.packageTimeId ? String(item.packageTimeId) : '';

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

        const addonMap = {};
        (item.addons || []).filter(a => a.type === 'addon').forEach(a => {
            addonMap[String(a.id)] = a;
        });

        addonsBoxEl.querySelectorAll('.addon-checkbox').forEach(cb => {
            const addonId = String(cb.value);
            const addon = addonMap[addonId];
            const qtyWrap = document.getElementById(`addon-qty-wrap-${addonId}`);
            const qtyInput = document.getElementById(`addon-qty-${addonId}`);

            if (addon) {
                cb.checked = true;
                qtyWrap.style.display = 'block';
                qtyInput.value = addon.qty || 1;
            } else {
                cb.checked = false;
                qtyWrap.style.display = 'none';
                qtyInput.value = 1;
            }
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
                const locTxt = (it.locations || []).map(x => String(x.name || '')).join(', ') || '-';
                const brTxt  = (it.branding || []).map(x => String(x.name || '')).join(', ') || '-';
                const adTxt  = (it.addons || []).map(a => {
                    const noteText = a.note ? ` (${a.note})` : '';
                    const qtyText = a.type === 'addon' ? ` x${a.qty || 1}` : '';

                    let addonPreviewTotal = Number(a.price) * Number(a.qty || 1);
                    if (a.type === 'addon' && isGameReskinningItem(it)) {
                        addonPreviewTotal = Number(a.price) * Number(a.qty || 1) * getTotalDays(it);
                    }

                    const totalText = a.type === 'addon'
                        ? ` = ${addonPreviewTotal.toFixed(2)} AED`
                        : '';

                    return a.selectedDate
                        ? `${a.name}${noteText}${qtyText} - ${formatDate(a.selectedDate)}${totalText}`
                        : `${a.name}${noteText}${qtyText}${totalText}`;
                }).join(', ') || '-';

                itemsListHtml += `
                    <tr>
                        <td>${idx + 1}</td>
                        <td style="white-space:normal; word-break:break-word;">
                            ${String(it.name || '')}<br>
                            <small>${String(it.packageTimeName || '-')}</small>
                        </td>
                        <td class="right">${it.qty}</td>
                        <td class="right">${getTotalDays(it)}</td>
                        <td>${getDateRangeText(it)}</td>
                        <td style="white-space:normal; word-break:break-word;">${locTxt}</td>
                        <td style="white-space:normal; word-break:break-word;">${brTxt}</td>
                        <td style="white-space:normal; word-break:break-word;">${adTxt}</td>
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
                    ? `<div style="margin-top:6px; font-size:12px; line-height:1.5; font-weight:normal;">
                        ${descLines.map(x => `<div style="font-weight:normal;">• ${x}</div>`).join("")}
                    </div>`
                    : "";

                const packageBaseTotal = isGameReskinningItem(item)
                    ? (Number(item.price) * Number(item.qty))
                    : (Number(item.price) * Number(item.qty) * getTotalDays(item));

                let rows = `
                    <tr>
                        <td style="white-space:normal; word-break:break-word;">
                            ${getDateRangeText(item)}
                            <div style="margin-top:4px; font-size:12px;">${getTotalDays(item)} day(s)</div>
                        </td>
                        <td style="white-space:normal; word-break:break-word;">
                            <b>${String(item.name || '')}</b>
                            ${descHtml}
                        </td>
                        <td class="right" style="white-space:normal; word-break:break-word;">${Number(item.qty)}</td>
                        <td class="right" style="white-space:normal; word-break:break-word;">
                            ${isGameReskinningItem(item)
                                ? getGameReskinningDurationFromAddons(item)
                                : formatIncludedHours(item)}
                        </td>
                        <td class="right" style="white-space:normal; word-break:break-word;">${Number(item.price).toFixed(2)}</td>
                        <td class="right" style="white-space:normal; word-break:break-word;">${packageBaseTotal.toFixed(2)}</td>
                    </tr>`;

                    
                const extraHourRows = getItemExtraHoursBreakdown(item).filter(ex => ex.extraHours > 0);

                extraHourRows.forEach(ex => {
                    rows += `
                        <tr>
                            <td style="white-space:normal; word-break:break-word;">${formatDate(ex.date)}</td>
                            <td style="white-space:normal; word-break:break-word;">Extra Hours (${ex.start} - ${ex.end})</td>
                            <td class="right">${ex.qty}</td>
                            <td class="right">${ex.extraHours} hour${ex.extraHours > 1 ? 's' : ''}</td>
                            <td class="right">${ex.rate.toFixed(2)}</td>
                            <td class="right">${ex.total.toFixed(2)}</td>
                        </tr>`;
                });

                (item.branding || []).forEach(br => {
                    rows += `
                        <tr>
                            <td></td>
                            <td style="white-space:normal; word-break:break-word;">${String(br.name || '')}</td>
                            <td class="right">1</td>
                            <td></td>
                            <td class="right">${Number(br.price).toFixed(2)}</td>
                            <td class="right">${Number(br.price).toFixed(2)}</td>
                        </tr>`;
                });

                const normalAddons = (item.addons || []).filter(a => a.type !== 'advance');
                const advanceAddons = (item.addons || []).filter(a => a.type === 'advance');

                normalAddons.forEach(a => {
                    const noteText = a.note ? ` (${a.note})` : '';
                    const title = a.selectedDate
                        ? `${a.name}${noteText} - ${formatDate(a.selectedDate)}`
                        : `${a.name}${noteText}`;
                    const addonQty = Number(a.qty || 1);

                    let addonTotal = 0;
                    let addonDuration = '';

                    if (isGameReskinningItem(item)) {
                        addonTotal = Number(a.price) * addonQty * getTotalDays(item);
                        addonDuration = `${getTotalDays(item)} day(s)`;
                    } else {
                        addonTotal = Number(a.price) * addonQty;
                    }

                    rows += `
                        <tr>
                            <td style="white-space:normal; word-break:break-word;"></td>
                            <td style="white-space:normal; word-break:break-word;">${title}</td>
                            <td class="right" style="white-space:normal; word-break:break-word;">${addonQty}</td>
                            <td class="right" style="white-space:normal; word-break:break-word;">${addonDuration}</td>
                            <td class="right" style="white-space:normal; word-break:break-word;">${Number(a.price).toFixed(2)}</td>
                            <td class="right" style="white-space:normal; word-break:break-word;">${addonTotal.toFixed(2)}</td>
                        </tr>`;
                });

                (item.locations || []).forEach(loc => {
                    rows += `
                        <tr>
                            <td></td>
                            <td style="white-space:normal; word-break:break-word;">Logistic labour setup & dismantling - ${String(loc.name || '')}</td>
                            <td class="right">1</td>
                            <td></td>
                            <td class="right">${Number(loc.surcharge).toFixed(2)}</td>
                            <td class="right">${Number(loc.surcharge).toFixed(2)}</td>
                        </tr>`;
                });

                advanceAddons.forEach(a => {
                    const noteText = a.note ? ` (${a.note})` : '';
                    const title = a.selectedDate
                        ? `${a.name}${noteText} - ${formatDate(a.selectedDate)}`
                        : `${a.name}${noteText}`;

                    const addonQty = Number(a.qty || 1);
                    const addonTotal = Number(a.price) * addonQty;
                    const addonDuration = '2 hours';

                    rows += `
                        <tr>
                            <td style="white-space:normal; word-break:break-word;"></td>
                            <td style="white-space:normal; word-break:break-word;">${title}</td>
                            <td class="right" style="white-space:normal; word-break:break-word;">${addonQty}</td>
                            <td class="right" style="white-space:normal; word-break:break-word;">${addonDuration}</td>
                            <td class="right" style="white-space:normal; word-break:break-word;">${Number(a.price).toFixed(2)}</td>
                            <td class="right" style="white-space:normal; word-break:break-word;">${addonTotal.toFixed(2)}</td>
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
                    <h3>${String(item.name || '')} — ${getDateRangeText(item)}</h3>
                    <table border="1" style="width:100%; border-collapse:collapse; margin-bottom:12px; table-layout:fixed;">
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

        const packageTimeId = packageTimeSelect.value;
        const selectedPackageTime = PACKAGE_TIMES.find(pt => String(pt.id) === String(packageTimeId));

        if (!selectedPackageTime) {
            alert("Please select a package time.");
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
                        qty: 1,
                        total: Number(a.price),
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
                    qty: 1,
                    total: manualPrice,
                    selectedDate: advDate,
                    type: 'advance',
                    isManual: true,
                    note: manualNote
                });
            }
        }

        addonsBoxEl.querySelectorAll('.addon-checkbox:checked').forEach(cb => {
            const a = ADDONS.find(x => String(x.id) === String(cb.value));
            if (!a) return;

            const qtyInput = document.getElementById(`addon-qty-${a.id}`);
            const addonQty = Math.max(1, parseInt(qtyInput?.value || 1));

            itemAddons.push({
                id: a.id,
                name: a.name,
                price: Number(a.price),
                qty: addonQty,
                total: Number(a.price) * addonQty,
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

        const safeLocations = itemLocs.map(locItem => ({
            ...locItem,
            name: String(locItem.name || '').trim()
        }));

        const safeBranding = itemBrands.map(brItem => ({
            ...brItem,
            name: String(brItem.name || '').trim()
        }));

        const safeAddons = itemAddons.map(addon => ({
            ...addon,
            name: String(addon.name || '').trim(),
            note: String(addon.note || '').trim()
        }));

        const snapshot = {
            pkgId: pkg.id,
            name: String(pkg.name || '').trim(),
            desc: stripHtml(pkg.desc || "").trim(),
            price: Number(pkg.price),
            categoryName: pkg.category_name || '',
            categorySlug: pkg.category_slug || '',
            packageTimeId: selectedPackageTime.id,
            packageTimeName: safePdfText(selectedPackageTime.name, 25),
            includedHours: Number(selectedPackageTime.timer || 0),
            extraHourRate: Number(getPackageExtraHourRate(pkg) || 0),
            qty,
            dateMode: getDateMode(),
            locations: safeLocations,
            branding: safeBranding,
            addons: safeAddons,
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

    populateDropdowns();
    populateAdvanceDropdown();
    initSearchSelects();
    initAdvanceSelect();
    renderAddonsCheckboxes();

    if (pkgTS) pkgTS.setValue('', true);
    if (branTS) branTS.setValue('', true);
    if (advanceTS) advanceTS.setValue('', true);
    locSelect.value = '';
    packageTimeSelect.value = '';

    toggleDateModeUI();
    toggleAdvanceModeUI();
    renderDateList();
    renderManualTimes();
    setEditingMode(false);
    updateSummary();

    window.quoteCalculatorState = {
        getSelectedItems: () => selectedItems,
        getDiscountType: () => discountType,
        getDiscountValue: () => discountValue,
        getNotes: () => notesInput?.value || '',
        getClientName: () => document.getElementById("clientName")?.value || "N/A",
        getClientAddress1: () => document.getElementById("clientAddress1")?.value || "",
        getClientAddress2: () => document.getElementById("clientAddress2")?.value || "",
        getClientPhone: () => document.getElementById("clientPhone")?.value || "",
        getClientEmail: () => document.getElementById("clientEmail")?.value || "",
        getItemSubtotal,
        getItemTotalExtraHourCharge,
        getItemExtraHoursBreakdown,
        getDateRangeText,
        getTotalDays,
        formatDate,
        formatIncludedHours,
        isGameReskinningItem,
        getGameReskinningDurationFromAddons
    };
});