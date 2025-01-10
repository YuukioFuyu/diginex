// ============================ This Custom js file only for back part ============================
$(function(){
    'use strict';

    // Tagify
    var input = $('.tags-evs');
    if (input.length > 0) {
        new Tagify(input[0]);
    }

    // Summernote Text Editor
    function initializeSummernote(selector) {
        $(selector).summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['help']],
            ],
            styleTags: [
                'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
            ],
            placeholder: 'Write Your Message',
            height: 300,
            focus: true
        });
    }
    initializeSummernote('.summernote'); //initializeSummernote

    $('.note-editable').css('font-weight', '400');


});

