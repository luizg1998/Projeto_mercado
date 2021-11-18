<?php
require_once 'db/db_functions.php';
require_once 'functions.php';

class TiposProdutos
{
    private $table = 'tipos_produtos';

    public function tipos_produtos_handler($dados_post)
    {
        switch ($dados_post['function']) {
            case 'get_all':
                return get_all($this->table);
                break;
            case 'get_by_id':
                return get_by_id($this->table, $dados_post['id']);
                break;
            case 'insert':
                return insert($this->table, $dados_post['dados']);
                break;
            case 'update':
                return update($this->table, $dados_post['dados'], $dados_post['id']);
                break;
            case 'delete':
                return delete($this->table, $dados_post['id']);
                break;
            default:
                return ['status' => 0];
                break;
        }
    }
}
