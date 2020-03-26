$( document ).ready(function() {

    function update_niveau(niveau) {
        var val = $("#intervenant_search_niveau option[niveau="+niveau+"]");
        if (val) $('#intervenant_search_niveau').val(val.val()).change();
    }

    $('.select2-control-diplome').on('change', function() {
        var niveau = $('option:selected', this).attr('niveau');
        if (niveau) update_niveau(niveau);
    });

    $('#intervenant_search_niveau').on('change', function() {
        var niveau = $(this).find(":checked").attr('niveau');
        var dip_niveau = $('.select2-control-diplome option:selected').attr('niveau');

        // Si les niveau sont different
        if (niveau != dip_niveau) {
            // On changele diplome
            $('.select2-control-diplome').val(null).change();
        }
    });

    $(".select2-control").select2({
        theme: 'bootstrap4',
    });

    $(".select2-control-domaines").select2({
        placeholder: 'Choisissez un ou plusieurs dommaines',
        theme: 'bootstrap4',
    });

});
