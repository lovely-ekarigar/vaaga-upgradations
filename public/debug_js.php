<?php
// Debug script to check JavaScript issues
?>
<!DOCTYPE html>
<html>
<head>
    <title>JS Debug</title>
    <!-- Load same resources as your main page -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>JavaScript Debug</h1>
        
        <div id="checks">
            <h3>Library Checks:</h3>
            <ul id="lib-checks"></ul>
        </div>
        
        <hr>
        
        <h3>Test Raise Objection Button:</h3>
        <button class="btn btn-danger btn-objection" 
                data-id="123" 
                data-date="06 Mar 2026" 
                data-duration="52 mins">
            Raise Objection
        </button>
        
        <hr>
        
        <h3>Console Log:</h3>
        <pre id="console-log" style="background: #f5f5f5; padding: 10px; border-radius: 5px;"></pre>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="objectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Raise Objection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Date: <span id="modalDate"></span></p>
                    <p>Duration: <span id="modalDuration"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function log(msg) {
            document.getElementById('console-log').innerHTML += msg + '\n';
            console.log(msg);
        }
        
        // Check libraries
        $(document).ready(function() {
            var checks = document.getElementById('lib-checks');
            
            // jQuery check
            if (typeof jQuery !== 'undefined') {
                checks.innerHTML += '<li style="color:green">✓ jQuery loaded (v' + jQuery.fn.jquery + ')</li>';
            } else {
                checks.innerHTML += '<li style="color:red">✗ jQuery NOT loaded</li>';
            }
            
            // Bootstrap check
            if (typeof bootstrap !== 'undefined') {
                checks.innerHTML += '<li style="color:green">✓ Bootstrap 5 loaded</li>';
            } else {
                checks.innerHTML += '<li style="color:orange">⚠ Bootstrap 5 NOT loaded (might be BS4)</li>';
            }
            
            // SweetAlert check
            if (typeof Swal !== 'undefined') {
                checks.innerHTML += '<li style="color:green">✓ SweetAlert2 loaded</li>';
            } else {
                checks.innerHTML += '<li style="color:red">✗ SweetAlert2 NOT loaded</li>';
            }
            
            // Test event delegation
            $(document).on('click', '.btn-objection', function(e) {
                e.preventDefault();
                log('Button clicked!');
                
                var recordingId = $(this).data('id');
                var date = $(this).data('date');
                var duration = $(this).data('duration');
                
                log('Data: id=' + recordingId + ', date=' + date + ', duration=' + duration);
                
                $('#modalDate').text(date);
                $('#modalDuration').text(duration);
                
                // Try to show modal
                try {
                    var modalElement = document.getElementById('objectionModal');
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var modal = new bootstrap.Modal(modalElement);
                        modal.show();
                        log('Modal shown using Bootstrap 5 API');
                    } else if ($ && $.fn && $.fn.modal) {
                        $('#objectionModal').modal('show');
                        log('Modal shown using jQuery API (BS4)');
                    } else {
                        log('ERROR: No modal API available');
                    }
                } catch (err) {
                    log('ERROR: ' + err.message);
                }
            });
            
            log('Event handlers attached');
        });
    </script>
</body>
</html>
