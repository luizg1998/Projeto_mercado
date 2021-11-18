<?php
require_once 'controllers/tipos_produtos.php';
require_once 'controllers/produtos.php';
require_once 'controllers/venda.php';

switch ($_POST['destino']) {
    case 'tipos_produtos':
        $tiposProdutos = new TiposProdutos();
        $response = $tiposProdutos->tipos_produtos_handler($_POST);
        echo json_encode($response);
        break;
    case 'produtos':
        $produtos = new Produtos();
        $response = $produtos->produtos_handler($_POST);
        echo json_encode($response);
        break;
    case 'venda':
        $venda = new Venda();
        $response = $venda->venda_handler($_POST);
        echo json_encode($response);
        break;
    default:
        echo json_encode(['status' => 0]);
        break;
}

exit;
switch ($_POST['function']) {
    case 'get_all':
        echo json_encode(get_all($_POST['table']));
        break;
    case 'get_by_id':
        echo json_encode(get_by_id($_POST['table'], $_POST['id']));
        break;
    case 'insert':
        echo json_encode(insert($_POST['table'], $_POST['dados']));
        break;
    case 'update':
        echo json_encode(update($_POST['table'], $_POST['dados'], $_POST['id']));
        break;
    case 'delete':
        echo json_encode(delete($_POST['table'], $_POST['id']));
        break;
    default:
        echo 0;
        break;
}
