$(document).ready(function() {
    $("#dtBox").DateTimePicker();
    manipular_dados();
    validar()
});

function manipular_dados() {


    $(document).on('click', '#usuario', function() {

        setTimeout("$('#pageContent').load('http://172.16.4.108/Ldaps/usuario/novo');", 1000);
        $("#esconder").hide();
    });

    $(document).on('click', '#altera', function() {

        var id = $(this).attr('rel');
        console.log(id);
        setTimeout("$('#pageContent').load('http://172.16.4.108/Ldaps/usuario/update/" + id + "');", 1000);
        $("#esconder").hide();
    });


    // apagar um usuario
    $(document).on('click', '#delete', function() {
        if (confirm('Pretendes Apagar este Item?')) {

            //recebe o nome do usuario via atributo rel no formulario
            var id = $(this).attr('rel');

            $.post("http://172.16.4.108/Ldaps/usuario/deletar/", {'id': id})
                    .done(function(data) {
                        var url = "http://172.16.4.108/Ldaps/usuario/";
                        $(location).attr('href', url);
                    });
        }
    });

    $(document).on('click', '#retira', function() {
        if (confirm('Pretendes retirar o usuario da rede?')) {

            //recebe o nome do usuario via atributo rel no formulario
            var id = $(this).attr('rel');

            $.post("http://172.16.4.108/Ldaps/usuario/retirar/", {'id': id})
                    .done(function(data) {
                        var url = "http://172.16.4.108/Ldaps/usuario/";
                        $(location).attr('href', url);
                    });
        }
    });



    $(document).on('click', '#dados', function() {

        var id = $(this).attr('rel');
        console.log(id);
        setTimeout("$('#pageContent').load('http://172.16.4.108/Ldaps/usuario/dados/?id=" + id + "');", 1000);
        $("#esconder").hide();
    });

    //alterar um usuario
    $(document).on('click', '#enviar', function() {
        var id = $("#nome").val();

        setTimeout("$('#pageContent').load('http://172.16.4.108/Ldaps/usuario/pesquisa/?id=" + id + "');", 1000);
        $("#esconder").hide();

    });

}

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


function validar() {

    $("#formu").validate({
        rules: {
            primeiro: {
                required: true,
                minlength: 5
            },
            ultimo: {
                required: true,
                minlength: 5
            },
            empresa: {
                required: true,
                minlength: 5
            },
            telefone: {
                required: true,
                minlength: 8,
                maxlength: 9
            }


        },
        messages: {
            primeiro: {
                required: "Preencha um nome valido"
            },
            ultimo: {
                required: "Preencha um nome valido"
            },
            empresa: {
                required: "Preencha uma morada valida"

            },
            telefone: {
                required: "Preencha um numero valido"

            }
        }
    });
}


