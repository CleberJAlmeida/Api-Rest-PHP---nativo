<?php
// Allow from any origin
// should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
// whitelist of safe domains
header("Access-Control-Allow-Origin: * ");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');    // cache for 1 day
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: * ");

$metodo = $_SERVER['REQUEST_METHOD'];
$tipos_servicos = ['listar', 'inserir', 'deletar', 'editar', 'estrutura'];
//separa os paramentro em array
if (isset($_GET['path'])) {
    $path = explode("/", $_GET['path']); //path esta declarada no arquivo .htaccess, como a variavel que vai receber os dados get  
    $pegaToken = "";
    if (isset($path[0])) { //verifica se foi declarado um serviço no path 0
        $api = $path[0]; // coloca na api qual tabela o serviço ira trabalhar
        if (isset($path[1])) {
            $servico = strtolower($path[1]); //Recebe a ação que a rota ira executar  //ucfirst deixa a primeira letra maiuscula... 
            //se for diferente de login
            //se não for encontrado o serviço no array entao é finalizada a rotina
            if ($api != 'login' && !in_array($servico, $tipos_servicos, false)) {
                print json_encode(["Serviço não encontrado!"], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        if (isset($path[2])) {
            $int = intval($path[2]); //tranforma em um numero inteiro, se retorna certo o path 2 pega como paramentro, 
            //caso contrario percorre ate o final do array pra pegar o token
            if (is_int($int) && $int != 0) {
                $parametro = $path[2]; //recebe o parametro da ação
                for ($i = 3; $i < count($path); $i++) {
                    $pegaToken .= $path[$i] . "/";
                }
            } else {
                for ($i = 2; $i < count($path); $i++) {
                    $pegaToken .= $path[$i] . "/";
                }
            }
            $pegaToken = trim($pegaToken); //retira os espaços vazio na string
            $pegaToken = rtrim($pegaToken, "/"); //retirar o ultimo caracter com rtrim
        }
    } else {
        echo "o nome do serviço foi declarado incorretamente";
        exit;
    }
} else {
    echo "o caminho não foi declarado";
    exit;
}

include_once('autoload.php');
include_once('classes/config.php');

use classes\GerirToken;
use classes\ServicosApi;
/*
//se for diferente de login
//se não for encontrado o serviço no array entao é finalizada a rotina
if ($api != 'login' && !in_array($servico, $tipos_servicos, false)) {
    print json_encode(["Serviço não encontrado!"], JSON_UNESCAPED_UNICODE);
    exit;
}
*/

$gerirToken = new GerirToken(); // instanciando a classe que trata os token

// só usar caso for usar o Bearer
function PegaToken()
{
    // pegando o token no bearer
    $http_header = apache_request_headers();
    //evitar da diferença entre maiuscula e minuscula no authorization
    $bearer = isset($http_header['authorization']) ? explode(" ", $http_header['authorization']) : explode(" ", $http_header['Authorization']);
    $token = $bearer[1];
    return ($token);
}

if ($metodo == "GET") {
    if ($pegaToken != "") { //verifica se exite essa variável
        $token = $pegaToken;
        $gerirToken->VerificarToken($token) ? ServicosApi::Listar($api, $parametro ?? null) : print(json_encode(["Token inválido!"], JSON_UNESCAPED_UNICODE)); // se parametro estiver um valor recebe o valor, se não fica null
    } else {
        print(json_encode(["Token inexistente!"], JSON_UNESCAPED_UNICODE));
    }
}

if ($metodo == "POST" && isset($servico)) { //verifica se o serviço existe, pois o metodo post está sendo usado no login tbm
    if ($servico == "inserir") {
        $dados = json_decode(file_get_contents("php://input", "r"), true);
        if (isset($dados) && $pegaToken != "") { //verifica se exite essa variável
            $token = $pegaToken;
            $gerirToken->VerificarToken($token) ? ServicosApi::Inserir($api, $dados) : print(json_encode(["Token inválido!"], JSON_UNESCAPED_UNICODE)); // se parametro estiver um valor recebe o valor, se não fica null
        } else {
            print(json_encode(["Token inexistente!"], JSON_UNESCAPED_UNICODE));
        }
    }
}

if ($metodo == "DELETE") {
    if ($pegaToken != "") { //verifica se exite essa variável
        $token = $pegaToken;
        $gerirToken->VerificarToken($token) ? ServicosApi::Deletar($api, $parametro) : print(json_encode(["Token inválido!"], JSON_UNESCAPED_UNICODE)); // se parametro estiver um valor recebe o valor, se não fica null
    } else {
        print(json_encode(["Token inexistente!"], JSON_UNESCAPED_UNICODE));
    }
}

if ($metodo == "PUT") {
    $dados = json_decode(file_get_contents("php://input", "r"), true);
    if (isset($dados) && $pegaToken != "") { //verifica se exite essa variável
        $token = $pegaToken;
        $gerirToken->VerificarToken($token) ? ServicosApi::Editar($api, $parametro, $dados) : print(json_encode(["Token inválido!"], JSON_UNESCAPED_UNICODE)); // se parametro estiver um valor recebe o valor, se não fica null
    } else {
        print(json_encode(["Token inexistente!"], JSON_UNESCAPED_UNICODE));
    }
}

// login não precisa de token, pois através do login bem sucedido que será gerado um token para o cliente
if ($metodo == "POST" && $api == "login") {
    $dados = json_decode(file_get_contents("php://input", "r"), true);
    if ($dados != null) {
        ServicosApi::Login($dados); //prepara pra fazer login e retornar o token
    } else {
        echo (json_encode(["dados vázio!"], JSON_UNESCAPED_UNICODE));
    }
}
