@extends('frontend.layout.sub-master')

@section('title')
<title>View Document | {{ $media->name ?? 'Document' }}</title>
@stop

@section('content')

<style>
    .doc-viewer-container {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        min-height: 600px;
        padding: 40px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .doc-content {
        font-family: 'Times New Roman', Times, serif;
        font-size: 14px;
        line-height: 1.6;
        color: #333;
    }
    
    .doc-content h1, .doc-content h2, .doc-content h3 {
        font-family: Arial, sans-serif;
        margin-top: 24px;
        margin-bottom: 12px;
    }
    
    .doc-content p {
        margin-bottom: 12px;
    }
    
    .doc-content table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
    }
    
    .doc-content table td, .doc-content table th {
        border: 1px solid #ddd;
        padding: 8px;
    }
    
    .doc-content ul, .doc-content ol {
        margin-bottom: 12px;
        padding-left: 30px;
    }
    
    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 400px;
        flex-direction: column;
    }
    
    .loading-spinner .spinner-border {
        width: 3rem;
        height: 3rem;
        margin-bottom: 15px;
    }
    
    .error-message {
        text-align: center;
        padding: 40px;
    }
    
    .viewer-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 15px 20px;
        margin: -40px -40px 30px -40px;
        border-radius: 8px 8px 0 0;
    }
</style>

@include("frontend.include.user-menu")
</div>

<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">{{ $media->name ?? 'Document' }}</h4>
                <small class="text-muted">{{ $media->file_name }}</small>
            </div>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                    ← Back
                </a>
            </div>
        </div>

        <div class="doc-viewer-container">
            <div class="viewer-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-word text-primary"></i> Document Viewer</span>
                <span class="text-muted small">Rendering in browser...</span>
            </div>
            
            <div id="document-content">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted">Loading document...</p>
                </div>
            </div>
        </div>

    </div>
</div>

</section> </main>

<!-- Mammoth.js for DOCX to HTML conversion -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Use direct file URL through Laravel route (works even if storage symlink is broken)
        const docUrl = '{{ route('lesson.doc.serve', $media->id) }}';
        const contentDiv = document.getElementById('document-content');
        
        // Debug: Log the URL
        console.log('Loading document from:', docUrl);
        
        // Fetch and convert DOCX
        fetch(docUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load document: ' + response.status);
                }
                return response.arrayBuffer();
            })
            .then(arrayBuffer => {
                // Convert DOCX to HTML using Mammoth
                return mammoth.convertToHtml(
                    { arrayBuffer: arrayBuffer },
                    {
                        styleMap: [
                            "p[style-name='Heading 1'] => h1",
                            "p[style-name='Heading 2'] => h2", 
                            "p[style-name='Heading 3'] => h3",
                            "p[style-name='Title'] => h1.title"
                        ]
                    }
                );
            })
            .then(result => {
                // Display the converted HTML
                contentDiv.innerHTML = '<div class="doc-content">' + result.value + '</div>';
                
                // Log any conversion warnings
                if (result.messages.length > 0) {
                    console.log('Conversion messages:', result.messages);
                }
            })
            .catch(error => {
                console.error('Error loading document:', error);
                contentDiv.innerHTML = `
                    <div class="error-message">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <strong>Unable to display document</strong>
                            <p class="mb-0 mt-2">${error.message}</p>
                            <p class="mt-2">Please try again later or contact support.</p>
                        </div>
                    </div>
                `;
            });
    });
</script>

@stop
