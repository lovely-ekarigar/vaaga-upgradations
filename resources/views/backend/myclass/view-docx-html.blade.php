@extends('frontend.layout.sub-master')

@section('title')
<title>View Document | {{ env('APP_NAME') }}</title>
@stop

@section('content')

<style>
    .docx-viewer-container {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .docx-content {
        width: 100%;
        min-height: 600px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background: #fff;
        padding: 40px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .docx-content h1, 
    .docx-content h2, 
    .docx-content h3, 
    .docx-content h4 {
        color: #333;
        margin-top: 1.5em;
        margin-bottom: 0.5em;
    }
    
    .docx-content p {
        line-height: 1.6;
        margin-bottom: 1em;
    }
    
    .docx-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1em 0;
    }
    
    .docx-content table td,
    .docx-content table th {
        border: 1px solid #ddd;
        padding: 8px;
    }
    
    .docx-content ul,
    .docx-content ol {
        margin-left: 2em;
        margin-bottom: 1em;
    }
    
    .docx-content img {
        max-width: 100%;
        height: auto;
    }
    
    .loading-spinner {
        text-align: center;
        padding: 50px;
        color: #666;
    }
    
    .loading-spinner i {
        font-size: 3rem;
        margin-bottom: 15px;
        display: block;
    }
    
    .error-message {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 20px;
        border-radius: 4px;
        text-align: center;
    }
    
    .error-message i {
        font-size: 2rem;
        margin-bottom: 10px;
        display: block;
    }
    
    .viewer-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
        .docx-content {
            padding: 20px;
            min-height: 400px;
        }
    }
</style>

@include("frontend.include.user-menu")
</div>

<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        
        <div class="viewer-header">
            <div>
                <h4 class="mb-1">{{ $media->name ?? 'Document' }}</h4>
                <small class="text-muted">{{ $media->file_name }}</small>
            </div>
            <div class="action-buttons">
                <a href="{{ asset('storage/uploads/' . $media->file_name) }}" 
                   class="btn btn-primary btn-sm" 
                   download>
                    <i class="bi bi-download"></i> Download
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                    ← Back
                </a>
            </div>
        </div>

        <!-- DOCX Viewer -->
        <div class="docx-viewer-container">
            <div id="document-content" class="docx-content">
                <div class="loading-spinner">
                    <i class="bi bi-file-earmark-text"></i>
                    <div>Loading document...</div>
                    <small class="text-muted">Please wait while we process your file</small>
                </div>
            </div>
        </div>

        <!-- File Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> File Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered mb-0">
                    <tbody>
                        <tr>
                            <td width="150"><strong>File Name</strong></td>
                            <td>{{ $media->file_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Original Name</strong></td>
                            <td>{{ $media->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Size</strong></td>
                            <td>{{ isset($media->size) ? number_format($media->size / 1024, 2) . ' KB' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Uploaded</strong></td>
                            <td>{{ $media->created_at ? $media->created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</section> </main>

<!-- Mammoth.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const documentContent = document.getElementById('document-content');
        const fileUrl = '{{ asset("storage/uploads/" . $media->file_name) }}';
        
        // Fetch the DOCX file
        fetch(fileUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load document: ' + response.statusText);
                }
                return response.arrayBuffer();
            })
            .then(arrayBuffer => {
                // Convert DOCX to HTML using Mammoth
                return mammoth.convertToHtml({
                    arrayBuffer: arrayBuffer,
                    options: {
                        // Style map for better conversion
                        styleMap: [
                            "p[style-name='Heading 1'] => h1",
                            "p[style-name='Heading 2'] => h2",
                            "p[style-name='Heading 3'] => h3",
                            "p[style-name='Heading 4'] => h4",
                            "p[style-name='Heading 5'] => h5",
                            "p[style-name='Heading 6'] => h6",
                        ]
                    }
                });
            })
            .then(result => {
                // Display the converted HTML
                documentContent.innerHTML = result.value;
                
                // Log any conversion warnings to console
                if (result.messages && result.messages.length > 0) {
                    console.log('Document conversion messages:', result.messages);
                }
            })
            .catch(error => {
                console.error('Error loading document:', error);
                documentContent.innerHTML = `
                    <div class="error-message">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div><strong>Error loading document</strong></div>
                        <div class="small mt-2">${error.message}</div>
                        <div class="mt-3">
                            <a href="${fileUrl}" class="btn btn-primary btn-sm" download>
                                <i class="bi bi-download"></i> Download to View
                            </a>
                        </div>
                    </div>
                `;
            });
    });
</script>

@stop
