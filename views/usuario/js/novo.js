$(document).ready(function() {
    $('#tabs').tab();
    //alterar um usuario
    $(document).on('click', '#usuario', function() {

        setTimeout("$('#pageContent').load('http://localhost/Ldaps/usuario/novo');", 1000);
        $("#esconder").hide();
    });

    $(document).on('click', '#altera', function() {

        var id = $(this).attr('rel');
        console.log(id);
        setTimeout("$('#pageContent').load('http://localhost/Ldaps/usuario/update/" + id + "');", 1000);
        $("#esconder").hide();
    });


    // apagar um usuario
    $(document).on('click', '#delete', function() {
        if (confirm('Pretendes Apagar este Item?')) {

            //recebe o nome do usuario via atributo rel no formulario
            var id = $(this).attr('rel');

            $.post("http://localhost/Ldaps/usuario/deletar/", {'id': id})
                    .done(function(data) {
                        console.log(data);
                    });
        }
    });

    $(document).on('click', '#retira', function() {
        if (confirm('Pretendes retirar o usuario da rede?')) {

            //recebe o nome do usuario via atributo rel no formulario
            var id = $(this).attr('rel');

            $.post("http://localhost/Ldaps/usuario/retirar/", {'id': id})
                    .done(function(data) {
                        console.log(data);


                    });
        }
    });



    $(document).on('click', '#dados', function() {

        var id = $(this).attr('rel');
        console.log(id);
        setTimeout("$('#pageContent').load('http://localhost/Ldaps/usuario/dados/?id=" + id + "');", 1000);
        $("#esconder").hide();
    });

    //alterar um usuario
    $(document).on('click', '#enviar', function() {
        var id = $("#nome").val();

        setTimeout("$('#pageContent').load('http://localhost/Ldaps/usuario/pesquisa/?id=" + id + "');", 1000);
        $("#esconder").hide();

    });
});

function enviar() {
    $(document).on('submit', '#novogrupo', function() {

        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.post(url, data)
                .done(function(data) {
                    alert("usuario adicionado");
                });

        return false;
    });


}


