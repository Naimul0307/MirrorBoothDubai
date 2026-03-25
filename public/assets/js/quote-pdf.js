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
            const dateCell = `${state.getDateRangeText(item)}\n${state.getTotalDays(item)} day(s)`;

            body.push([
                dateCell,
                descCell,
                String(Number(item.qty)),
                state.formatIncludedHours(item),
                fmt(item.price),
                fmt(Number(item.price) * Number(item.qty) * state.getTotalDays(item))
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
                    ? `${a.name}${noteText} - ${state.formatDate(a.selectedDate)}`
                    : `${a.name}${noteText}`;

                const addonDuration = isAdvance ? "2 hours" : "";
                const addonQty = Number(a.qty || 1);
                const addonTotal = Number(a.price) * addonQty;

                body.push([
                    "",
                    title,
                    String(addonQty),
                    addonDuration,
                    fmt(a.price),
                    fmt(addonTotal)
                ]);
            });

            const extraHourRows = state.getItemExtraHoursBreakdown(item).filter(ex => ex.extraHours > 0);

            extraHourRows.forEach(ex => {
                body.push([
                    state.formatDate(ex.date),
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

        const notes = state.getNotes();
        if (notes.trim()) {
            ensureSpace(20);
            doc.setFont("helvetica", "bold");
            doc.setFontSize(10);
            doc.setTextColor(0);
            doc.text("Notes:", MARGIN_L, cursorY);

            doc.setFont("helvetica", "normal");
            doc.setFontSize(9);
            const lines = doc.splitTextToSize(notes.trim(), PAGE_W - MARGIN_L - MARGIN_R);
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
});