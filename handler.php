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