jQuery(function($) {
    'use strict';
    $('#pr-fixed').on('click', function() {
        $('.extended').fadeToggle().toggleClass('hidden');
    });
    $('#pr-filter-by-category').on('click', function() {
        $('#included-categories, #categories-container').toggleClass('softened');
        $('#categories-container-mask').toggleClass('active');
    });
});
