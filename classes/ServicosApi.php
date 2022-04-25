<?php

namespace classes;

use Exception;

class ServicosApi
{
    public static function Listar(string $tabela, ?string $id) // se acaso o $id não estiver declaro 
    {
        $conti_sql = '';
        if (isset($id) && $id != "") {
            $conti_sql = "WHERE id=$id";
        }
        $db = ComDB::connect();
        $rs = $db->prepare("SELECT * FROM $tabela $conti_sql");
        $rs->execute();
        $obj = $rs->fetchAll(\PDO::FETCH_ASSOC);
        if ($obj) {
            echo json_encode(["dados" => $obj], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["dados" => "sem dados"], JSON_UNESCAPED_UNICODE);
        }
    }
    public static function Inserir(string $tabela, $dados) // se acaso o $id não estiver declaro 
    {
        $colunas = null;
        foreach ($dados as $chave => $valor) { // pega somente o nome dentro do array, não pega o valor do array
            $colunas .= $chave . ",";
        }
        $colunas = (substr($colunas, 0, strlen($colunas) - 1)); // prepara a string pra fica com os parametros das colunas a serem inserida no banco
        $strin = "INSERT INTO {$tabela}({$colunas}) VALUES ('" . $dados['nome'] . "','" . $dados['login'] . "','" . $dados['senha'] . "')";
        $db = ComDB::connect();
        $rs = $db->prepare($strin);
        $rs->execute();
        if ($rs->rowCount() > 0) {
            echo json_encode(["Usuarios inserido com sucesso"], JSON_UNESCAPED_UNICODE);
        } else {
            throw new Exception("Falha ao inserir");
        }
    }
    public static function Deletar(string $tabela, string $id) // se acaso o $id não estiver declaro 
    {
        if (!isset($id) || ($id == "")) {
            echo json_encode(["erro" => "id não especificado"], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $db = ComDB::connect();
        $rs = $db->prepare("DELETE FROM {$tabela} WHERE id={$id}");
        $rs->execute();
        $obj = $rs->fetchAll(\PDO::FETCH_ASSOC);
        if ($obj) {
            echo json_encode(["dados" => $obj], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["dados" => "sem dados"], JSON_UNESCAPED_UNICODE);
        }
    }
}
