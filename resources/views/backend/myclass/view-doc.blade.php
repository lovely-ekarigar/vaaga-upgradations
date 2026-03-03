@extends('frontend.layout.sub-master')

@section('title')
<title>View Document | {{ env('APP_NAME') }}</title>
@stop

@section('content')

<style>
    .doc-viewer-container {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .doc-iframe {
        width: 100%;
        height: 700px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background: #fff;
    }
    
    .viewer-option {
        margin-bottom: 15px;
        padding: 15px;
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    
    .viewer-option h6 {
        margin-bottom: 10px;
        color: #495057;
    }
    
    .fallback-notice {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 4px;
        padding: 12px 15px;
        margin-bottom: 20px;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
        .doc-iframe {
            height: 500px;
        }
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
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                ← Back
            </a>
        </div>

        <div class="fallback-notice">
            <i class="bi bi-info-circle"></i>
            <strong>Note:</strong> Document viewer uses your browser's built-in rendering. 
            If it doesn't display correctly, please use the <strong>Download</strong> button.
        </div>

        <!-- Browser's Built-in DOCX Viewer -->
        <div class="viewer-option">
            <h6><i class="bi bi-file-earmark-word"></i> Document Viewer</h6>
            <div class="doc-viewer-container">
                <iframe src="{{ route('lesson.doc.serve', $media->id) }}" 
                        class="doc-iframe"
                        type="application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                </iframe>
            </div>
        </div>

        <!-- Alternative Options -->
        <div class="viewer-option">
            <h6><i class="bi bi-tools"></i> Alternative Options</h6>
            <div class="action-buttons">
                <a href="{{ asset('storage/uploads/' . $media->file_name) }}" 
                   class="btn btn-primary" 
                   download>
                    <i class="bi bi-download"></i> Download Document
                </a>
                
                @php
                    $fileUrl = urlencode(asset('storage/uploads/' . $media->file_name));
                    $googleDocsUrl = 'https://docs.google.com/gview?embedded=1&url=' . $fileUrl;
                    $microsoftViewerUrl = 'https://view.officeapps.live.com/op/view.aspx?src=' . $fileUrl;
                @endphp
                
                <a href="{{ $microsoftViewerUrl }}" 
                   target="_blank" 
                   class="btn btn-success">
                    <i class="bi bi-microsoft"></i> Try Microsoft Viewer
                </a>
                
                <a href="{{ $googleDocsUrl }}" 
                   target="_blank" 
                   class="btn btn-info">
                    <i class="bi bi-box-arrow-up-right"></i> Open in Google Docs
                </a>
            </div>
        </div>

        <!-- File Info -->
        <div class="viewer-option">
            <h6><i class="bi bi-file-earmark-text"></i> File Information</h6>
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

        <div class="mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                ← Back to Lessons
            </a>
        </div>

    </div>
</div>

</section> </main>

@stop
