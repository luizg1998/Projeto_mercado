<?php

require 'pgsql_connection.php';
$pg = new PgSql();

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

function get_all($table)
{
    $db = $GLOBALS['pg'];

    $sql = "SELECT * FROM $table ORDER BY id ASC";
    $select = $db->select($sql);

    if ($select->num_rows == 0) {
        return [
            'status' => 0,
        ];
    }

    return [
        'status' => 1,
        'dados' => $select->rows,
    ];
}

function get_by_id($table, $id)
{
    $db = $GLOBALS['pg'];

    $sql = "SELECT * FROM $table WHERE id = " . $id;
    $row = $db->getRow($sql);

    if (!$row) {
        return [
            'status' => 0,
        ];
    }

    return [
        'status' => 1,
        'dados' => $row,
    ];
}

function insert($table, $dados)
{
    $db = $GLOBALS['pg'];

    // String que vai ser usada no sql para definir quais colunas vão ser atualizadas
    $columns = '';
    // String que vai ser usada no sql para definir quais valores vão ser atualizadas
    $values = '';

    foreach ($dados as $key => $value) {
        $columns .= ($columns != '') ? ", " . $key : $key;

        // Se a variável é uma string, adiciona as aspas simples para não quebrar o SQL
        if (is_string($value)) {$value = "'$value'";}

        $values .= ($values != '') ? ", " . $value : $value;
    }

    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    $insert_id = $db->insert($sql);

    if (!!$insert_id) {
        return [
            'status' => 1,
        ];
    } else {
        return [
            'status' => 0,
        ];
    }
}

function update($table, $dados, $id)
{
    if (!$id || !$dados) {
        return false;
    }

    $db = $GLOBALS['pg'];

    // String que vai ser usada na parte 'SET' do sql
    $set = '';

    foreach ($dados as $key => $value) {
        // Se a variável é uma string, adiciona as aspas simples para não quebrar o SQL
        if (is_string($value)) {$value = "'$value'";}

        $set .= ($set != '') ? ', ' . $key . ' = ' . $value : $key . ' = ' . $value;
    }

    if (!$set) {
        return false;
    }

    $sql = "UPDATE $table SET " . $set . " WHERE id = " . $id;
    $aff_rows = $db->exec($sql);

    if (!!$aff_rows) {
        return [
            'status' => 1,
            'aff_rows' => $aff_rows,
        ];
    } else {
        return [
            'status' => 0,
        ];
    }
}

function delete($table, $id)
{
    if (!$id) {
        return false;
    }

    $db = $GLOBALS['pg'];

    $sql = "DELETE FROM $table WHERE id = " . $id;
    $aff_rows = $db->exec($sql);

    if (!!$aff_rows) {
        return [
            'status' => 1,
            'aff_rows' => $aff_rows,
        ];
    } else {
        return [
            'status' => 0,
        ];
    }
}

$pg->close();
?>