/**
 * Allows you to add data-method="METHOD to links to automatically inject a form
 * with the method on click
 *
 * Example: <a href="{{route('customers.destroy', $customer->id)}}"
 * data-method="delete" name="delete_item">Delete</a>
 *
 * Injects a form with that's fired on click of the link with a DELETE request.
 * Good because you don't have to dirty your HTML with delete forms everywhere.
 */

/**
 * Place any jQuery/helper plugins in here.
 * Use window.jQuery for module compatibility (Vite/ES modules).
 */

// Ensure jQuery is globally available for inline handlers and other scripts
if (typeof window.jQuery !== 'undefined') {
    window.$ = window.jQuery;
}

// Use global jQuery
const $ = window.jQuery;

function addDeleteForms() {
    $('[data-method]').each(function() {
        const $link = $(this);
        
        // Only add form if it doesn't already exist
        if ($link.find('form').length === 0) {
            const formHtml = "\n<form action='" + $link.attr('href') + "' method='POST' name='delete_item' style='display:none'>\n" +
                "<input type='hidden' name='_method' value='" + $link.attr('data-method') + "'>\n" +
                "<input type='hidden' name='_token' value='" + $('meta[name="csrf-token"]').attr('content') + "'>\n" +
                '</form>\n';
            $link.append(formHtml);
        }
        
        // Set href and click handler if not already set
        if ($link.attr('href') !== '#') {
            $link.attr('href', '#');
        }
        
        // Use native JS onclick to avoid jQuery scope issues with minified bundles
        $link.attr('style', 'cursor:pointer;');
        $link.off('click.deleteForm').on('click.deleteForm', function(e) {
            e.preventDefault();
            const form = this.querySelector('form[name="delete_item"]');
            if (form) {
                // Trigger submit event that will be caught by the delegated handler
                $(form).trigger('submit');
            }
        });
    });
}

// Make addDeleteForms globally available
window.addDeleteForms = addDeleteForms;

$(function () {
    /**
     * Add the data-method="delete" forms to all delete links
     */
    addDeleteForms();

    /**
     * Disable all submit buttons once clicked
     */
    $('form').submit(function () {
        $(this).find('input[type="submit"]').attr('disabled', true);
        $(this).find('button[type="submit"]').attr('disabled', true);
        return true;
    });

    /**
     * Generic confirm form delete using Sweet Alert 2
     */
    $('body').on('submit', 'form[name=delete_item]', function (e) {
        e.preventDefault();

        const form = this;
        const $link = $(form).closest('a[data-method="delete"]');
        const cancel = $link.attr('data-trans-button-cancel') || 'Cancel';
        const confirmText = $link.attr('data-trans-button-confirm') || 'Yes, delete';
        const title = $link.attr('data-trans-title') || 'Are you sure you want to delete this item?';

        // Use SweetAlert2 API (swal.fire instead of swal)
        swal.fire({
            title: title,
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancel,
            icon: 'warning'
        }).then(function(result) {
            if (result.isConfirmed) {
                // Submit the form without jQuery to avoid event re-triggering
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });

    /**
     * Generic 'are you sure' confirm box for links with confirm_item name
     */
    $('body').on('click', 'a[name=confirm_item]', function (e) {
        e.preventDefault();

        const link = $(this);
        const title = link.attr('data-trans-title') || 'Are you sure you want to do this?';
        const cancel = link.attr('data-trans-button-cancel') || 'Cancel';
        const confirmText = link.attr('data-trans-button-confirm') || 'Continue';

        // Use SweetAlert2 API
        swal.fire({
            title: title,
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancel,
            icon: 'info'
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.assign(link.attr('href'));
            }
        });
    });
});
