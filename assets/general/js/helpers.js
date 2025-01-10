//====================== This Helpers File For All Part =======================
function notifyEvs(type, message) {
    "use strict";
    let title = type;
    new Notify({
        status: type,
        title: title.charAt(0).toUpperCase() + title.slice(1),
        text: message,
        effect: 'slide',
        speed: 500,
        customClass: '',
        customIcon: '',
        showIcon: true,
        showCloseButton: true,
        autoclose: true,
        autotimeout: 2300,
        gap: 20,
        distance: 20,
        type: 1,
        position: 'right top',
        customWrapper: '',
    })

}

function validateNumber(e) {
    "use strict";
    const pattern = /^[0-9]$/;
    return pattern.test(e.key)
}

function validateDouble($value) {
    "use strict";
    return $value.replace(/[^0-9.]/g, '')
        // Remove any additional decimal points.
        .replace(/(\..*?)\..*/g, '$1');
}

function readURL(input, imagePreview) {
    'use strict';
    // Check if there is a selected file
    if (input.files && input.files[0]) {
        // Create a new FileReader
        var reader = new FileReader();
        // Define the onload event
        reader.onload = function (e) {
            // Set the background image of the image preview element
            imagePreview.css('background-image', 'url(' + e.target.result + ')');
            // Hide the image preview and then fade it in
            imagePreview.hide().fadeIn(400);
        };
        // Read the data URL of the selected file
        reader.readAsDataURL(input.files[0]);
    }
}

// Function to handle image preview for dynamically added elements
function handleImagePreview() {
    'use strict';
    var hostname = window.location.hostname;
    $(".imageUpload").change(function () {
        var previewId = $(this).data("preview-id");
        var imagePreview = $("#" + previewId);
        readURL(this, imagePreview);
    });
    $(".imageRemove").on('click', function (event) {
        var previewId = $(this).prev().data("preview-id");
        var imagePreview = $("#" + previewId);

        // Change to default placeholder image using the hostname
        imagePreview.css('background-image', 'url(http://' + hostname + '/assets/general/static/placeholder.png)');
        // Set value to indicate removal
        var imageInput = $("#" + previewId + "-remove");
        imageInput.val('coevs-remove');

        var imageNameInput = $("#" + previewId + "_upload");
        imageInput.attr('name', imageNameInput.attr('name'));
    });
}

handleImagePreview()

function deleteItem(url, text = "You won't be able to revert this!") {
    Swal.fire({
        title: "Are you sure?",
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    notifyEvs(data['status'], data['message']);
                    setTimeout(function () {
                        location.reload();
                    }, 1300);
                }
            });
        }
    });
}

function generateSlug(value) {
    return value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
}

tooltipTriger = function () {
    'use strict';

    // Get all elements with the class 'modal-tooltip'
    const tooltipTriggerList = document.getElementsByClassName('modal-tooltip');

    // Convert the HTMLCollection to an array and initialize the tooltips
    Array.from(tooltipTriggerList).forEach(tooltipTriggerEl => {
        try {
            new bootstrap.Tooltip(tooltipTriggerEl);
        } catch (error) {
            console.error('Tooltip initialization failed:', error);
        }
    });
};



$('.delete').on('click', ({target}) => {
    // Retrieve the data-url attribute from the clicked element
    const url = $(target).data('url');

    if (typeof url !== 'undefined') {
        // Set the action attribute of the #delete-form-modal element to the retrieved URL
        $('#delete-form-modal').attr('action', url);

        // Display the #delete_modal modal
        $('#delete_modal').modal('show');
    }
});

editFormByModal = function (modalShowId, modalDataAppendId, isFile = true, tooltip = false) {
    const $modal = $('#' + modalShowId);
    const $modalContent = $('#' + modalDataAppendId);

    $(document).on('click', '.edit-modal', function () {
        const url = $(this).data('edit-url');
        const loadingHtml = `
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

        $modal.modal('show');
        $modalContent.html(loadingHtml);

        $.get(url, data => {
            $modalContent.html(data);
            if (tooltip) tooltipTriger()
            if (isFile) handleImagePreview();
        });
    });
};
$('input[name="daterange"]').daterangepicker();

// Helper function for Date Range Picker
function setupDateRangePicker(selector, start, end, callback) {
    // Date range picker initialization
    $(selector).daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, callback);

    // Set "Last 30 Days" as the default selection when the page loads
    callback(start, end);
}

// Callback function to update the UI and input fields
function dateRangeCallback(start, end) {
    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    $('input[name="daterange"]').val(start.format('YYYY-MM-DD') + ',' + end.format('YYYY-MM-DD'));
}

// Default start and end dates (Last 30 Days)
var start = moment().subtract(29, 'days');
var end = moment();

// Use the helper function to initialize the date range picker with default 30 days
setupDateRangePicker('#reportrange', start, end, dateRangeCallback);

// Listen for submit events on all forms
document.querySelectorAll('form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        // Find the submit button that was clicked
        const submitButton = e.submitter || form.querySelector('button[type="submit"], input[type="submit"]');

        if (submitButton) {
            submitButton.disabled = true; // Disable the button
        }
    });
});

function copyToClipboard(elementId,message='Copied to clipboard!') {
    const input = document.getElementById(elementId);
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(input.value).then(() => {
            notifyEvs('success', message);
        }).catch(err => {
            console.error("Failed to copy text: ", err);
        });
    } else {
        // Fallback for unsupported browsers
        input.select();
        document.execCommand("copy");
        notifyEvs('success', message);
    }
}
