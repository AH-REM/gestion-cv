$( document ).ready(function() {

    $("#intervenant_niveau option[value='']").prop("disabled", true);

    function update_niveau(niveau) {
        var val = $("#intervenant_niveau option[niveau="+niveau+"]");
        if (val) $('#intervenant_niveau').val(val.val()).change();
    }

    $(".select2-control-emploi").select2({
        placeholder: "Choisissez un emploi",
        //maximumSelectionLength: 1,
        theme: 'bootstrap4',
        tags: true
    });

    $(".select2-control-diplome").select2({
        placeholder: "Choisissez un diplome",
        theme: 'bootstrap4',
        tags: true
    });

    $('.select2-control-diplome').on('change', function(e) {
        var niveau = $('option:selected', this).attr('niveau');
        update_niveau(niveau ? niveau : null);
    });

    $(".select2-control-domaines").select2({
        placeholder: "Choisissez un domaine",
        theme: 'bootstrap4',
        tags: true
    });

    $('#intervenant_file').on('change', function(e) {
        var filename = $(this).val().replace(/.*(\/|\\)/, '');
        $(".custom-file label[for='intervenant_file']").text(filename);
    });

});
