$( document ).ready(function() {

    $('#modal-delete').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);

        var lib = button.data('libelle');
        var path = button.data('pathdel');

        var modal = $(this);
        modal.find('.modal-body').text('Voulez-vous vraiment supprimer \' ' + lib + ' \' ?');
        modal.find('.modal-footer a').attr('href', path);

    });

});
