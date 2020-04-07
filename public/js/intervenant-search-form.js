$( document ).ready(function() {

    function update_niveau(niveau) {
        var val = $("#niveau option[niveau="+niveau+"]");
        if (val) $('#niveau').val(val.val()).change();
    }

    $('.select2-control-diplome').on('change', function() {
        var niveau = $('option:selected', this).attr('niveau');
        if (niveau) update_niveau(niveau);
    });

    $('#niveau').on('change', function() {
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
        placeholder: 'Choisissez un ou plusieurs domaines',
        theme: 'bootstrap4',
    });

    $('.datepicker').datepicker({
        language: 'fr'
    });

    $('.datepicker').val('');
    $('.datepicker').attr('placeholder','Choisissez une date *');

});
