<?php
header("Access-Control-Allow-Origin: *"); //controla qual site pode ter acesseo ao api
header("Content-Type: application/json"); //controla o retorno da api, que no caso vai ser em JSON
$metodo = $_SERVER['REQUEST_METHOD'];
$tipos_servicos = ['Listar', 'Inserir', 'Deletar', 'Editar', 'Strutura'];
//separa os paramentro em array
if (isset($_GET['path'])) {
    $path = explode("/", $_GET['path']); //path esta declarada no arquivo .htaccess, como a variavel que vai receber os dados get
    if (isset($path[0])) { //verifica se foi declarado um serviço no path 0
        $api = $path[0]; // coloca na api qual tabela o serviço ira trabalhar
        if (isset($path[1])) {
            $servico = ucFirst($path[1]); //Recebe a ação que a rota ira executar
        }
        if (isset($path[2])) {
            $parametro = $path[2]; //recebe o parametro da ação
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
//trata as string, pra deixa-lás uniforme
$api = ucfirst($api);
//se for diferente de login
//se não for encontrado o serviço no array entao é finalizada a rotina
if ($api != 'Login' && !in_array($servico, $tipos_servicos, false)) { //ucfirst deixa a primeira letra maiuscula... 
    print json_encode(["Serviço não encontrado!"], JSON_UNESCAPED_UNICODE);
    exit;
}

$gerirToken = new GerirToken(); // instanciando a classe que trata os token

if ($metodo == "GET") {
    $dados = json_decode(file_get_contents("php://input", "r"));
    $token = $dados->token;
    $gerirToken->VerificarToken($token) ? ServicosApi::Listar($api, $parametro ?? null) : print(json_encode(["Token inválido!"], JSON_UNESCAPED_UNICODE)); // se parametro estiver um valor recebe o valor, se não fica null
}
if ($metodo == "POST" && $servico == "Inserir") {
    $dados = json_decode(file_get_contents("php://input", "r"), true);
    $token = $dados['token'];
    $dados_input = array_slice($dados, 0, -1); // essa função inicia em 0 e retira o token do array
    $gerirToken->VerificarToken($token) ? ServicosApi::Inserir($api, $dados_input) : print(json_encode(["Token inválido!"], JSON_UNESCAPED_UNICODE));; // se parametro estiver um valor recebe o valor, se não fica null
}
if ($metodo == "DELETE") {
    $dados = json_decode(file_get_contents("php://input", "r"), true);
    $token = $dados['token'];
    $gerirToken->VerificarToken($token) ? ServicosApi::Deletar($api, $parametro) : print(json_encode(["Token inválido!"], JSON_UNESCAPED_UNICODE));; // se parametro estiver um valor recebe o valor, se não fica null
}

if ($metodo == "PUT") {
    $dados = json_decode(file_get_contents("php://input", "r"), true);
    $token = $dados['token'];
    $dados_input = array_slice($dados, 0, -1); // essa função inicia em 0 e retira o token do array
    $gerirToken->VerificarToken($token) ? ServicosApi::Editar($api, $parametro, $dados_input) : print(json_encode(["Token inválido!"], JSON_UNESCAPED_UNICODE));; // se parametro estiver um valor recebe o valor, se não fica null
}

// login não precisa de token, pois através do login bem sucedido que será gerado um token para o cliente
if ($metodo == "POST" && $api == "Login") {
    if ($_POST != null) {
        ServicosApi::Login($_POST); //prepara pra fazer login e retornar o token
    } else {
        echo (json_encode(["dados vázio!"], JSON_UNESCAPED_UNICODE));
    }
}
