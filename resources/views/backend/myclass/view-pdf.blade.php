@extends('frontend.layout.sub-master')

@section('title')
<title>View PDF | {{ env('APP_NAME') }}</title>
@stop
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
@section('content')

<style>
body{
    user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
}

/* wrapper */
.pdf-wrapper{
    position: relative;
    width: 100%;
    min-height: 500px;
}

/* iframe */
/* .pdf-wrapper iframe{
    width:100%;
    height:100%;
    border:none;
} */

/* protection layer */
.pdf-protect{
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:transparent;
    z-index:10;
}
#pdf-render{
    width:100%;
    display:block;
    margin:0 auto;
}

#pdf-container{
    width:100%;
}
</style>

@php
    // Build the file URL
    $pdfUrl = $media->url;
    if (empty($pdfUrl)) {
        $pdfUrl = asset('storage/uploads/' . $media->file_name);
    }
    
    // Check if file exists
    $filePath = public_path('storage/uploads/' . $media->file_name);
    $fileExists = file_exists($filePath);
@endphp

<div class="container mt-10">
    <div class="mb-3">
        <h4>{{ $media->name ?? 'Document' }}</h4>
    </div>

    @if(!$fileExists)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> 
            <strong>File not found:</strong> The PDF file could not be found on the server. 
            Please contact support or try downloading directly.
        </div>
    @else
        <div class="pdf-wrapper">
            <div id="pdf-container"></div>
            <div id="pdf-error" class="alert alert-danger d-none">
                Unable to load PDF. Please try again later or contact support.
            </div>
        </div>
    @endif
</div>
 

<script>
// Disable right click
document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
});

// Disable text select
document.addEventListener('selectstart', e => e.preventDefault());

// Disable long press (mobile)
document.addEventListener('touchstart', function(e){
    if(e.touches.length > 0){
        e.preventDefault();
    }
}, {passive:false});

// Block keys
document.onkeydown = function(e) {
    if (
        (e.ctrlKey && ['c','s','p','u'].includes(e.key.toLowerCase())) ||
        (e.key === 'F12')
    ) {
        e.preventDefault();
    }
};
</script>
<script>
const url = "{{ $pdfUrl }}";
const container = document.getElementById("pdf-container");
const errorDiv = document.getElementById("pdf-error");

// Check if URL is valid
if (!url || url === '' || url === '{{ asset('storage/uploads/') }}') {
    container.innerHTML = '<div class="alert alert-warning">PDF URL not available.</div>';
} else {
    pdfjsLib.GlobalWorkerOptions.workerSrc =
    "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

    pdfjsLib.getDocument(url).promise.then(function(pdf) {
        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
            pdf.getPage(pageNum).then(function(page) {
                const viewport = page.getViewport({ scale: 1.5 });
                const canvas = document.createElement("canvas");
                const context = canvas.getContext("2d");

                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.style.width = "100%";
                canvas.style.marginBottom = "15px";

                container.appendChild(canvas);

                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            });
        }
    }).catch(function(error) {
        console.error('PDF loading error:', error);
        container.style.display = 'none';
        errorDiv.classList.remove('d-none');
    });
}
</script>

@stop
