<?php
require_once 'db/db_functions.php';
require_once 'functions.php';

class Venda
{
    private $table = 'venda';

    public $color;
    public $weight;

    public function venda_handler($dados_post)
    {
        switch ($dados_post['function']) {
            case 'confirmar_venda':
                return $this->confirmar_venda($dados_post);
                break;
            default:
                return ['status' => 0];
                break;
        }
    }

    private function confirmar_venda($dados_post)
    {
        $produtos = $dados_post['produtos'];

        $valor_produtos = 0;
        $valor_impostos = 0;

        $dataAgora = getDataAgora()->format('Y-m-d');

        $insert_venda = insert($this->table, ['data_venda' => $dataAgora]);
        $id_venda = $insert_venda['insert_id'];

        foreach ($produtos as $p) {
            $queryProduto = get_by_id('produtos', $p['id_produto']);
            $dadosProduto = $queryProduto['dados'];

            $queryTipoProduto = get_by_id('tipos_produtos', $dadosProduto->id_tipo_produto);
            $perc_imposto = $queryTipoProduto['dados']->perc_imposto;

            $total_valor_produto = $dadosProduto->valor * $p['qtd_produto'];
            $total_valor_imposto = percentual($total_valor_produto, $perc_imposto);

            $valor_produtos += $total_valor_produto;
            $valor_impostos += $total_valor_imposto;

            $dados_detalhes_venda = [
                'id_venda' => $id_venda,
                'id_produto' => $p['id_produto'],
                'qtd_produto' => $p['qtd_produto'],
                'total_valor_produto' => $total_valor_produto,
                'total_valor_imposto' => $total_valor_imposto,
            ];

            insert('detalhes_venda', $dados_detalhes_venda);
        }

        $dadosUpdate = [
            'valor_produtos' => $valor_produtos,
            'valor_impostos' => $valor_impostos,
        ];
        return update($this->table, $dadosUpdate, $id_venda);
    }

}