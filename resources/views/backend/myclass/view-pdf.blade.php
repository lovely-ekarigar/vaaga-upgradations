@extends('frontend.layout.sub-master')

@section('title')
<title>View PDF | {{ env('APP_NAME') }}</title>
@stop

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/docx-preview@0.3.2/dist/docx-preview.min.js"></script>

@section('content')

<style>
body {
    user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
}

#pdf-preloader {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    gap: 16px;
}
.spinner {
    width: 48px;
    height: 48px;
    border: 5px solid #e0e0e0;
    border-top-color: #4e73df;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
#pdf-preloader p { color: #666; font-size: 14px; margin: 0; }
#pdf-progress { font-size: 13px; color: #4e73df; font-weight: 600; }

.pdf-wrapper {
    position: relative;
    width: 100%;
    min-height: 500px;
    background: #ffffff;
}

#pdf-container {
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
    box-sizing: border-box;
    padding: 20px 0;
}

/* ✅ Placeholder — loading animation ke saath */
.page-placeholder {
    width: 100%;
    min-height: 600px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #bbb;
    font-size: 13px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 2px;
}
@keyframes shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

#pdf-container canvas {
    display: block;
    width: 100% !important;
    height: auto !important;
    margin: 0 auto 15px auto;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    background: white;
}

.docx-wrapper {
    background: #ffffff !important;
    padding: 0 !important;
    margin: 0 !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    width: 100% !important;
}
.docx-wrapper > section.docx {
    background: white !important;
    width: 100% !important;
    min-width: unset !important;
    max-width: 100% !important;
    margin: 0 auto 15px auto !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15) !important;
    box-sizing: border-box !important;
    overflow: visible !important;
}
.docx-wrapper img, .docx img {
    max-width: 100% !important;
    height: auto !important;
    display: block;
}
.docx-wrapper table {
    max-width: 100% !important;
    table-layout: auto !important;
}
canvas {
    pointer-events: none;
}

@media (max-width: 768px) {
    #pdf-container { padding: 10px 0; }
    .docx-wrapper > section.docx {
        margin: 0 0 8px 0 !important;
        box-shadow: none !important;
        padding: 12px !important;
    }
}
</style>

<div class="container mt-10">
    <div class="pdf-wrapper">
        <div id="pdf-preloader">
            <div class="spinner"></div>
            <p>Loading document, please wait...</p>
            <span id="pdf-progress"></span>
        </div>
        <div id="pdf-container" style="display:none;"></div>
    </div>
</div>

<script>
const url       = "{{ asset('uploads/'.$media->file_name) }}";
const ext       = url.split('.').pop().toLowerCase();
const container = document.getElementById("pdf-container");
const preloader = document.getElementById("pdf-preloader");
const progress  = document.getElementById("pdf-progress");

function hidePreloader() {
    preloader.style.display = 'none';
    container.style.display = 'block';
}

/* ─────────── PDF with Lazy Loading ─────────── */
if (ext === "pdf") {
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

    let pdfDoc       = null;
    const rendered   = new Set();
    const rendering  = new Set(); // duplicate render prevent karo

    // ✅ Page render function
    function renderPage(pageNum) {
        if (rendered.has(pageNum) || rendering.has(pageNum)) return;
        rendering.add(pageNum);

        pdfDoc.getPage(pageNum).then(function(page) {
         const containerWidth  = container.clientWidth || window.innerWidth;
const defaultViewport = page.getViewport({ scale: 1 });

const baseScale  = containerWidth / defaultViewport.width;
const pixelRatio = Math.min(window.devicePixelRatio || 1, 2);
const finalScale = baseScale * pixelRatio * 1.5;

const viewport = page.getViewport({ scale: finalScale });

const canvas = document.createElement("canvas");
const ctx    = canvas.getContext("2d");

ctx.imageSmoothingEnabled = true;
ctx.imageSmoothingQuality = "high";
            canvas.height             = viewport.height;
            canvas.width              = viewport.width;
            canvas.style.width        = "100%";
            canvas.style.height       = "auto";
            canvas.style.display      = "block";
            canvas.style.marginBottom = "15px";
            canvas.style.boxShadow    = "0 2px 8px rgba(0,0,0,0.2)";

            const ph = document.getElementById("page-ph-" + pageNum);
            if (ph) ph.replaceWith(canvas);

            page.render({ canvasContext: ctx, viewport }).promise.then(() => {
                rendered.add(pageNum);
                rendering.delete(pageNum);
                progress.textContent = rendered.size + " / " + pdfDoc.numPages + " pages rendered";
                if (rendered.size === pdfDoc.numPages) {
                    progress.textContent = "";
                }
            });
        });
    }

    pdfjsLib.getDocument(url).promise.then(function(pdf) {
        pdfDoc = pdf;
        const totalPages = pdf.numPages;

        // ✅ Step 1: Saare placeholders pehle banao (fast — sirf divs)
        for (let i = 1; i <= totalPages; i++) {
            const ph            = document.createElement("div");
            ph.className        = "page-placeholder";
            ph.id               = "page-ph-" + i;
            ph.dataset.pageNum  = i;
            ph.textContent      = "Page " + i;
            container.appendChild(ph);
        }

        hidePreloader(); // ✅ Placeholders bante hi dikhao

        // ✅ Step 2: Intersection Observer lagao
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const pageNum = parseInt(entry.target.dataset.pageNum);
                    observer.unobserve(entry.target); // ek baar kafi hai
                    renderPage(pageNum);
                }
            });
        }, {
            root: null,
            rootMargin: '300px', // 300px pehle se load shuru
            threshold: 0.01
        });

        // ✅ Saare placeholders observe karo
        document.querySelectorAll(".page-placeholder").forEach(ph => {
            observer.observe(ph);
        });

    }).catch(function(err) {
        hidePreloader();
        container.innerHTML = `<p style='color:red;padding:20px'>
            Error loading PDF: ${err.message}</p>`;
    });
}

/* ─────────── DOCX ─────────── */
else if (ext === "docx") {
    fetch(url)
        .then(r => {
            if (!r.ok) throw new Error("HTTP Error: " + r.status);
            return r.arrayBuffer();
        })
        .then(buffer => {
            container.innerHTML = '';
            hidePreloader();
            return docx.renderAsync(buffer, container, null, {
                className     : "docx",
                inWrapper     : true,
                ignoreWidth   : true,
                ignoreHeight  : false,
                breakPages    : true,
                renderHeaders : true,
                renderFooters : true,
                useBase64URL  : true,
            });
        })
        .then(() => console.log("DOCX rendered!"))
        .catch(err => {
            hidePreloader();
            container.innerHTML =
                `<p style='color:red;padding:20px'>Error: ${err.message}</p>`;
        });
}

else {
    hidePreloader();
    container.innerHTML = "<p style='padding:20px'>Preview not available</p>";
}
</script>

@stop
