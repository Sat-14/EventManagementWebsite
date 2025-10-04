/**
 * Global JavaScript for Event Management Website
 * Common functions and utilities
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {

        // Form validation helper
        if (typeof $.fn.validate !== 'undefined') {
            $('form').each(function() {
                $(this).validate({
                    errorClass: 'is-invalid',
                    validClass: 'is-valid'
                });
            });
        }

        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if(target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 800);
            }
        });

        // Auto-hide alerts after 5 seconds
        $('.alert').each(function() {
            var alert = $(this);
            setTimeout(function() {
                alert.fadeOut('slow');
            }, 5000);
        });

        // Add loading state to forms on submit
        $('form').on('submit', function() {
            var btn = $(this).find('button[type="submit"]');
            if (btn.length) {
                btn.prop('disabled', true);
                btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            }
        });

        // Confirm delete actions
        $('.btn-delete, [data-action="delete"]').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                return false;
            }
        });

        // Initialize tooltips if Bootstrap is available
        if (typeof $().tooltip !== 'undefined') {
            $('[data-toggle="tooltip"]').tooltip();
        }

        // Initialize popovers if Bootstrap is available
        if (typeof $().popover !== 'undefined') {
            $('[data-toggle="popover"]').popover();
        }

    });

})(jQuery);
