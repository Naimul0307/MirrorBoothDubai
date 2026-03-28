document.addEventListener('DOMContentLoaded', function () {
    const copyBtn = document.getElementById('copy');
    if (!copyBtn) return;

    copyBtn.addEventListener('click', async () => {
        const state = window.quoteCalculatorState;
        if (!state) {
            alert('Quote calculator not loaded properly.');
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');

        const pdfData = window.quotePdfData || {};
        const COMPANY_DETAILS = pdfData.companyDetails || {};

        const CLIENT_TO_PROVIDE = Array.isArray(pdfData.clientToProvide)
            ? pdfData.clientToProvide
            : [];

        const TERMS = Array.isArray(pdfData.terms)
            ? pdfData.terms
            : [];

        const fmt = (num) => Number(num).toFixed(2);

        const PAGE_W = doc.internal.pageSize.getWidth();
        const PAGE_H = doc.internal.pageSize.getHeight();

        // little bigger table with same left/right gap
        const MARGIN_L = 7;
        const MARGIN_R = 7;

        const HEADER_H = 55;
        const START_Y_FIRST = 70;
        const START_Y_OTHER = 60;
        const FOOTER_H = 10;

        const FOOTER_TEXT = COMPANY_DETAILS.footerText ||
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

        const clientName = state.getClientName();
        const clientAddress1 = state.getClientAddress1();
        const clientAddress2 = state.getClientAddress2();
        const clientPhone = state.getClientPhone();
        const clientEmail = state.getClientEmail();

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

            // logo aligned with table left
            const logoX = MARGIN_L;
            const logoY = 12;
            const logoW = 62;
            const logoH = 20;

            doc.addImage(logoBase64, "PNG", logoX, logoY, logoW, logoH);

            // red line starts EXACTLY after logo (no gap, no overlap)
            const barX = logoX + logoW;
            const barY = logoY + 7;
            const barW = PAGE_W - (MARGIN_R - 2) - barX;
            const barH = 6;

            doc.setFillColor(230, 0, 0);
            doc.rect(barX, barY, barW, barH, "F");

            // quotation number on right side
            doc.setFont("helvetica", "bold");
            doc.setFontSize(8.5);
            doc.setTextColor(255, 255, 255);

            doc.text(`QUOTE #: ${quotationNo}`, barX + barW - 2, barY + 4.2, {
                align: "right"
            });

            // reset
            doc.setTextColor(0, 0, 0);
            doc.setFont("helvetica", "normal");
        }
        
        function drawBillToFirstPage() {
            const billX = MARGIN_L;
            const billY = 12 + 20 + 12;

            doc.setTextColor(0, 0, 0);
            doc.setFont("helvetica", "normal");
            doc.setFontSize(10);
            doc.text("Bill To:", billX, billY);

            doc.setFont("helvetica", "normal");
            doc.setFontSize(10);

            const billLines = [
                clientName !== "N/A" ? clientName : "",
                clientAddress1 || "",
                clientAddress2 || "",
                clientPhone ? `Phone: ${clientPhone}` : "Phone: N/A",
                clientEmail ? `Email: ${clientEmail}` : "Email: N/A"
            ].filter(Boolean);

            let yy = billY + 5;
            billLines.forEach(line => {
                doc.text(line, billX, yy);
                yy += 4.5;
            });
        }

        function drawQuoteMetaFirstPage() {
            const tableRight = PAGE_W - MARGIN_R - 0.5;
            const metaY1 = START_Y_FIRST - 6; // little lower, less bottom gap

            doc.setTextColor(0, 0, 0);
            doc.setFont("helvetica", "normal");
            doc.setFontSize(8.5);
            doc.text(todayText, tableRight, metaY1, { align: "right", maxWidth: 32 });
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

            const rawLines = clean
                .split(/\r?\n/)
                .map(line => line.trim())
                .filter(Boolean);

            const lines = [];

            rawLines.forEach(line => {
                const normalized = line.replace(/^•\s*/, '').trim();
                if (!normalized) return;

                if (!lines.length) {
                    lines.push(normalized);
                    return;
                }

                const prev = lines[lines.length - 1];

                const shouldJoin =
                    !/[:.;!?)]$/.test(prev) &&
                    /^[a-z(]/.test(normalized);

                if (shouldJoin) {
                    lines[lines.length - 1] = `${prev} ${normalized}`;
                } else {
                    lines.push(normalized);
                }
            });

            return lines.map(line => `• ${line}`);
        }

        function breakLongWord(word, maxLen = 40) {
            if (!word || word.length <= maxLen) return word;
            let out = '';
            for (let i = 0; i < word.length; i += maxLen) {
                out += word.slice(i, i + maxLen);
                if (i + maxLen < word.length) out += '-\n';
            }
            return out;
        }

        function safeWrapText(value, maxWordLen = 40) {
            return String(value || '')
                .split('\n')
                .map(line =>
                    line
                        .split(/\s+/)
                        .map(word => breakLongWord(word, maxWordLen))
                        .join(' ')
                )
                .join('\n');
        }

        drawHeader();
        drawBillToFirstPage();
        drawQuoteMetaFirstPage();
        drawFooter();

        const selectedItems = state.getSelectedItems();
        const discountType = state.getDiscountType();
        const discountValue = state.getDiscountValue();

        const tables = [];
        let allSubtotals = 0;

        selectedItems.forEach(item => {
            const subtotal = state.getItemSubtotal(item);
            const extraHour = state.getItemTotalExtraHourCharge(item);

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

            doc.setFont("helvetica", "normal");
            doc.setFontSize(10);
            doc.setTextColor(0);
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

            const descCell = safeWrapText(descParts.join("\n"), 40);
            const dateCell = safeWrapText(
                `${state.getDateRangeText(item)}\n${state.getTotalDays(item)} day(s)`,
                18
            );

            const packageBaseTotal = state.isGameReskinningItem(item)
                ? (Number(item.price) * Number(item.qty))
                : (Number(item.price) * Number(item.qty) * state.getTotalDays(item));

            body.push([
                dateCell,
                descCell,
                String(Number(item.qty)),
                safeWrapText(
                    state.isGameReskinningItem(item)
                        ? state.getGameReskinningDurationFromAddons(item)
                        : state.formatIncludedHours(item),
                    18
                ),
                fmt(item.price),
                fmt(packageBaseTotal)
            ]);

            const extraHourRows = state.getItemExtraHoursBreakdown(item).filter(ex => ex.extraHours > 0);

            extraHourRows.forEach(ex => {
                body.push([
                    safeWrapText(state.formatDate(ex.date), 18),
                    safeWrapText(`Extra Hours (${ex.start} - ${ex.end})`, 40),
                    String(ex.qty),
                    safeWrapText(`${ex.extraHours} hour${ex.extraHours > 1 ? 's' : ''}`, 18),
                    fmt(ex.rate),
                    fmt(ex.total)
                ]);
            });

            (item.branding || []).forEach(br => {
                body.push([
                    "",
                    safeWrapText(String(br.name || ""), 40),
                    "1",
                    "",
                    fmt(br.price),
                    fmt(br.price)
                ]);
            });

            const normalAddons = (item.addons || []).filter(a => {
                const isAdvance =
                    a.type === 'advance' ||
                    String(a.name).trim().toUpperCase().includes("ADVANCE");
                return !isAdvance;
            });

            const advanceAddons = (item.addons || []).filter(a => {
                const isAdvance =
                    a.type === 'advance' ||
                    String(a.name).trim().toUpperCase().includes("ADVANCE");
                return isAdvance;
            });

            normalAddons.forEach(a => {
                const noteText = a.note ? ` (${a.note})` : '';
                const title = a.selectedDate
                    ? `${a.name}${noteText}\n${state.formatDate(a.selectedDate)}`
                    : `${a.name}${noteText}`;

                const addonQty = Number(a.qty || 1);

                let addonDuration = "";
                let addonTotal = Number(a.price) * addonQty;

                if (state.isGameReskinningItem(item)) {
                    addonDuration = `${state.getTotalDays(item)} day(s)`;
                    addonTotal = Number(a.price) * addonQty * state.getTotalDays(item);
                }

                body.push([
                    "",
                    safeWrapText(title, 40),
                    String(addonQty),
                    safeWrapText(addonDuration, 18),
                    safeWrapText(fmt(a.price), 18),
                    safeWrapText(fmt(addonTotal), 18)
                ]);
            });

            (item.locations || []).forEach(loc => {
                body.push([
                    "",
                    safeWrapText(`Logistic labour setup & dismantling - ${loc.name}`, 40),
                    "1",
                    "",
                    fmt(loc.surcharge),
                    fmt(loc.surcharge)
                ]);
            });

            advanceAddons.forEach(a => {
                const noteText = a.note ? ` (${a.note})` : '';
                const title = a.selectedDate
                    ? `${a.name}${noteText}\n${state.formatDate(a.selectedDate)}`
                    : `${a.name}${noteText}`;

                const addonQty = Number(a.qty || 1);
                const addonDuration = "2 hours";
                const addonTotal = Number(a.price) * addonQty;

                body.push([
                    "",
                    safeWrapText(title, 40),
                    String(addonQty),
                    safeWrapText(addonDuration, 18),
                    safeWrapText(fmt(a.price), 18),
                    safeWrapText(fmt(addonTotal), 18)
                ]);
            });

            body.push(["", "", "", "", "SUBTOTAL", fmt(subtotal)]);
            body.push(["", "", "", "", "DISCOUNT", "-" + fmt(safeDiscount)]);
            body.push(["", "", "", "", "VAT 5%", fmt(vat)]);
            body.push(["", "", "", "", "TOTAL AMOUNT", fmt(total)]);

            ensureSpace(80);

            doc.autoTable({
                startY: cursorY,
                theme: "grid",
                margin: { left: MARGIN_L, right: MARGIN_R },
                tableWidth: PAGE_W - MARGIN_L - MARGIN_R,
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
                    fontStyle: "normal",
                    fontSize: 8.5,
                    cellPadding: { top: 3.2, right: 2.5, bottom: 3.2, left: 2.5 },
                    minCellHeight: 9,
                    lineWidth: 0.25,
                    lineColor: [0, 0, 0],
                    textColor: [0, 0, 0],
                    valign: "top",
                    halign: "left",
                    overflow: "linebreak"
                },
                headStyles: {
                    fillColor: [0, 0, 0],
                    textColor: 255,
                    font: "helvetica",
                    fontStyle: "normal",
                    fontSize: 8.5,
                    halign: "center",
                    valign: "middle",
                    minCellHeight: 11,
                    lineWidth: 0.25
                },
                bodyStyles: {
                    font: "helvetica",
                    fontStyle: "normal",
                    fontSize: 8.5,
                    overflow: "linebreak"
                },
                // little bigger table
                columnStyles: {
                    0: { cellWidth: 25 },
                    1: { cellWidth: 96 },
                    2: { cellWidth: 12, halign: "center" },
                    3: { cellWidth: 23, halign: "center" },
                    4: { cellWidth: 21, halign: "center" },
                    5: { cellWidth: 21, halign: "center" }
                },
                didParseCell: function (data) {
                    if (data.section === "body" && data.column.index === 4) {
                        const label = String(data.cell.raw || "").trim().toUpperCase();

                        if (label === "SUBTOTAL" || label === "VAT 5%" || label === "DISCOUNT") {
                            if (data.row.cells[4]) {
                                data.row.cells[4].styles.fontStyle = "normal";
                                data.row.cells[4].styles.fontSize = 8.5;
                            }
                            if (data.row.cells[5]) {
                                data.row.cells[5].styles.fontStyle = "normal";
                                data.row.cells[5].styles.fontSize = 8.5;
                            }
                        }

                        if (label === "TOTAL AMOUNT") {
                            for (let i = 0; i <= 5; i++) {
                                if (data.row.cells[i]) {
                                    data.row.cells[i].styles.fillColor = [205, 220, 245];
                                    data.row.cells[i].styles.fontStyle = "normal";
                                    data.row.cells[i].styles.fontSize = 8.5;
                                }
                            }
                        }
                    }
                }
            });

            cursorY = doc.lastAutoTable.finalY + 10;
        }

        tables.forEach(renderOneTable);

        const notes = state.getNotes();
        if (notes.trim()) {
            ensureSpace(20);
            doc.setFont("helvetica", "normal");
            doc.setFontSize(10);
            doc.setTextColor(0);
            doc.text("Notes:", MARGIN_L, cursorY);

            doc.setFont("helvetica", "normal");
            doc.setFontSize(10);
            const lines = doc.splitTextToSize(notes.trim(), PAGE_W - MARGIN_L - MARGIN_R);
            doc.text(lines, MARGIN_L, cursorY + 5);
            cursorY += 5 + (lines.length * 4.8) + 8;
        }

        function renderSection(title, linesArray) {
            const safeLines = Array.isArray(linesArray)
                ? linesArray.map(line => String(line || '').trim())
                : [];

            if (!safeLines.length) return;

            ensureSpace(18);

            doc.setFont("helvetica", "normal");
            doc.setFontSize(10);
            doc.setTextColor(0);

            if (title) {
                doc.text(title, MARGIN_L, cursorY);
                cursorY += 8;
            }

            const maxW = PAGE_W - MARGIN_L - MARGIN_R;

            safeLines.forEach((line, index) => {
                if (!line) return;

                const isNumber = /^\d+\.\s/.test(line);
                const isBullet = /^•/.test(line);

                // 👉 space BEFORE new number section (except first)
                if (isNumber && index > 0) {
                    cursorY += 6;
                }

                // 👉 space BEFORE first bullet under number
                if (isBullet) {
                    cursorY += 2;
                }

                const wrapped = doc.splitTextToSize(line, maxW);

                wrapped.forEach((wrapLine) => {
                    ensureSpace(6);

                    // 👉 BOLD for number headings
                    if (isNumber) {
                        doc.setFont("helvetica", "bold");
                    } else {
                        doc.setFont("helvetica", "normal");
                    }

                    doc.setFontSize(10);
                    doc.setTextColor(0);

                    doc.text(wrapLine, MARGIN_L, cursorY);
                    cursorY += 5;
                });

                // ❌ NO extra spacing after number line
                // ❌ NO extra spacing after bullet
            });

            cursorY += 10;
        }

        renderSection("Client to Provide:", CLIENT_TO_PROVIDE);
        renderSection("Terms and Conditions:", TERMS);

        ensureSpace(35);

        const leftLines = [
            "Best regards,",
            COMPANY_DETAILS.senderName || "MAHA KHAN",
            COMPANY_DETAILS.senderPhone || "",
            COMPANY_DETAILS.senderEmail || "",
            COMPANY_DETAILS.senderWebsite || "",
        ].filter(Boolean);

        const rightLines = [
            "Accepted/Approved By:",
            "______________________________",
            "Client’s Name & Signature"
        ];

        const leftX = MARGIN_L;
        const rightX = PAGE_W - MARGIN_R;

        let yyL = cursorY;
        doc.setFont("helvetica", "normal");
        doc.setFontSize(10);
        doc.setTextColor(0);

        leftLines.forEach(line => {
            doc.text(line, leftX, yyL);
            yyL += 5;
        });

        let yyR = cursorY;
        rightLines.forEach(line => {
            doc.text(line, rightX, yyR, { align: "right" });
            yyR += 5;
        });

        doc.save("quotation.pdf");
    });
});