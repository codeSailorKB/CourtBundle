(function ($) {


    $('.case-export-modal-button').on('click', function (e) {
        e.preventDefault();
        $('.case-export-modal').modal('show');
    });

    $('.export-case').on('click', function () {
        $('#make_court_caseexport_exportType').val($(this).data('export-format'));
        $(document).find('.case-export-form').submit();
        $('.case-export-modal').modal('hide');
    });


})(jQuery);

