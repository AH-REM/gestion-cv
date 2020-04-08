$( document ).ready(function() {

    $('form').submit(function(e) {

        $('button:submit').attr("disabled", true);

    });

});
