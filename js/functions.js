$(document).ready(function () {
    trocaPagina(null, 'inicio');

    $(document).on('keydown keypress input blur', '.maskPercentualOuValor', function () {
        $(this).mask('#0,00', { reverse: true });
    });

});


function trocaPagina(elem = null, nome_pagina, dropdown = false) {
    // elem => elemento clicado

    $('#main').load('../pages/' + nome_pagina + '.html');

    $('#menu li').removeClass('active');

    $(elem).closest('li').addClass('active');
    if (dropdown) {
        $(elem).closest('li.dropdown').addClass('active');
    }

    if (!elem) {
        $('#li_inicio').addClass('active');
    }
}

function loader(elem) {
    $(document).ajaxStart(function () {
        $(elem).find('i').addClass('icn-spinner fa-spinner');
        $(elem).find('i').removeClass('fa-check');
    }).ajaxStop(function () {
        $(elem).find('i').removeClass('icn-spinner fa-spinner');
        $(elem).find('i').addClass('fa-check');
    });
}

function getAll(table = null) {
    if (!table) {
        return false;
    }

    var data_post = {
        'function': 'get_all',
        'table': table
    };

    $.post('../functions.php', data_post, function (response) {
        response = JSON.parse(response);

        if (response.status == 0) {
            $('#table_content').html(`<tr>
                                        <td colspan="2" class="text-center">Nenhum registro encontrado</td>
                                    </tr>`);
            return false;
        }

        var linhas = montaLinhas(response.dados);

        $('#table_content').html(linhas);
    });
}

function inserir_atualizar(elem, id = null, table = null, dados = null) {
    loader(elem);

    if (!table || !dados) {
        return false;
    }

    var data_post = {
        'function': 'insert',
        'table': table,
        'dados': dados
    };

    if (id) {
        data_post.function = 'update';
        data_post.id = id;
    }

    $.post('../functions.php', data_post, function (response) {
        response = JSON.parse(response);

        if (response.status == 1) {
            simpleMsg('ok', 'Ação efetuada com sucesso!');
            resetInputs();
            if (id) {
                retornaEdicao();
            }
            getAll(table);
        } else {
            simpleMsg('erro', 'Erro ao efetuar ação. Tente novamente!');
        }

    });
}

function simpleMsg(tipo, msg) {

    switch (tipo) {
        case 'erro':
            var titulo = 'Atenção!';
            var type_color = 'red';
            break;
        case 'ok':
            var titulo = 'Sucesso!'
            var type_color = 'green';
            break;

        default:
            break;
    }

    $.alert({
        title: titulo,
        content: msg,
        type: type_color,
        animation: 'rotateX',
        closeAnimation: 'rotateX',
        animateFromElement: false
    });
}

function editar(elem, id, table) {
    var data_post = {
        'function': 'get_by_id',
        'table': table,
        'id': id
    };

    $.post('../functions.php', data_post, function (response) {
        response = JSON.parse(response);
        preencherInputsEditar(response.dados);

        $('#btnSalvar').attr('data-id', id);
        $('#btnCancelar').show();

        $('#info_acao').html('<small><b>Você está editando o registro ID ' + id + '</b></small>');

        $("html, body").animate({ scrollTop: 0 }, "fast");
    });

}

function resetInputs() {
    // esvazia todos os inputs em uma certa área de edição
    $('#div_edicao').find('input, select').val('');
}

function retornaEdicao() {
    $('#btnSalvar').attr('data-id', '');
    $('#btnCancelar').hide();
    $('#info_acao').html('');
}

function excluir(elem, id, table, obs = '') {
    var msg = `Você tem deseja que excluir o registro ID ${id}?`;
    msg += (!obs) ? '' : `<br><b>Observação: ${obs}</b>`;

    $.confirm({
        title: 'Confirmar exclusão',
        content: msg,
        type: 'orange',
        animation: 'rotateX',
        closeAnimation: 'rotateX',
        animateFromElement: false,
        buttons: {
            nao: {
                text: 'Não',
                btnClass: 'btn-red'
            },
            sim: {
                text: 'Sim',
                btnClass: 'btn-green',
                action: function () {
                    var data_post = {
                        'function': 'delete',
                        'table': table,
                        'id': id
                    };

                    $.post('../functions.php', data_post, function (response) {
                        response = JSON.parse(response);

                        if (response.status == 1) {
                            simpleMsg('ok', 'Registro excluído com sucesso!');
                            getAll(table);
                        } else {
                            simpleMsg('erro', 'Erro ao excluir registro!');
                        }

                    });
                }
            }
        }
    });
}

function percentual(numero, perc) {
    return (numero / 100) * perc;
}