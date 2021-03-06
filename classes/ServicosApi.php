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
        $strin = "SELECT * FROM $tabela $conti_sql";
        $rs = $db->prepare($strin);
        $rs->execute();
        $obj = $rs->fetchAll(\PDO::FETCH_ASSOC);
        if ($obj) {
            echo json_encode(["dados" => $obj], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["Erro:" => "sem dados"], JSON_UNESCAPED_UNICODE);
        }
    }
    public static function Inserir(string $tabela, $dados) // se acaso o $id não estiver declaro 
    {
        $valores = null;
        $colunas = null;
        foreach ($dados as $chave => $valor) { // pega somente o nome dentro do array, não pega o valor do array
            $colunas .= $chave . ",";
            $valores .= is_numeric($valor) ? $valor . "," : "'{$valor}'" . ",";
        }
        $valores = (substr($valores, 0, strlen($valores) - 1));; // retira a ultima virgula pra ficar com a sintaxe correta, usada no sql
        $colunas = (substr($colunas, 0, strlen($colunas) - 1)); // prepara a string pra fica com os parametros das colunas a serem inserida no banco
        $strin = "INSERT INTO {$tabela}({$colunas}) VALUES ({$valores})";
        $db = ComDB::connect();
        $rs = $db->prepare($strin);
        $rs->execute();
        if ($rs->rowCount() > 0) {
            echo json_encode(["Sucesso: informação inserida com sucesso"], JSON_UNESCAPED_UNICODE);
        } else {
            throw new Exception("Erro: Falha ao inserir");
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
        if ($rs->rowCount() > 0) {
            echo json_encode(["Sucesso: informação deletada"], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["Erro: falha de execução" => "sem dados"], JSON_UNESCAPED_UNICODE);
        }
    }
    public static function Editar(string $tabela, string $id, $dados) // se acaso o $id não estiver declaro 
    {
        if (!isset($id) || ($id == "")) {
            echo json_encode(["erro" => "id não especificado"], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $colunas = null;
        foreach ($dados as $chave => $valor) { // pega somente o nome dentro do array, não pega o valor do array
            if (is_string($valor)) {
                $valor = "'{$valor}'";
            }
            $colunas .= "{$chave}={$valor},";
        }
        $colunas = (substr($colunas, 0, strlen($colunas) - 1)); // prepara a string pra fica com os parametros das colunas a serem inserida no banco
        $strin = "UPDATE {$tabela} SET {$colunas} WHERE id={$id}";
        $db = ComDB::connect();
        $rs = $db->prepare($strin);
        $rs->execute();
        if ($rs->rowCount() > 0) {
            echo json_encode(["Sucesso:" => "informação editada"], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["Erro: " => "Falha na execução"], JSON_UNESCAPED_UNICODE);
        }
    }
    public static function Login($dados)
    {
        $tabela = 'usuarios';
        $colunas = [];
        $valores = [];
        foreach ($dados as $chave => $valor) { // pega somente o nome dentro do array, não pega o valor do array
            array_push($colunas, $chave);
            Array_push($valores, $valor);
        }
        //verifica o conteudo do login
        if ($colunas[0] == 'login') {
            $login = $colunas[0];
            $valor_login = $valores[0];
        } else {
            throw new Exception("Erro: Falha de login");
        }
        //verifica o conteudo da senha
        if ($colunas[1] == 'senha') {
            $senha = $colunas[1];
            $valor_senha = $valores[1];
        } else {
            throw new Exception("Erro: Falha de conteúdo");
        }

        $strin = "SELECT * FROM {$tabela} WHERE {$login}='{$valor_login}' AND {$senha}='{$valor_senha}'";

        $db = ComDB::connect();
        $rs = $db->prepare($strin);
        $rs->execute();
        $obj = $rs->fetch(\PDO::FETCH_ASSOC);

        if ($obj) {
            $gerirToken = new GerirToken();
            $token = $gerirToken->GerarToken($obj['login'], $obj['id']);
            echo json_encode([
                "dados" => [
                    "login" => $obj['login'],
                    "id" => $obj['id'],
                    "nome" => $obj['nome']
                ],
                "token" => $token
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["Erro:" => "Login ou senha incorretos"], JSON_UNESCAPED_UNICODE);
        }
    }
}
