<?php
header("Access-Control-Allow-Origin: *"); //controla qual site pode ter acesseo ao api
header("Content-Type: application/json"); //controla o retorno da api, que no caso vai ser em JSON
$metodo = $_SERVER['REQUEST_METHOD'];
$tipos_servicos = ['Listar', 'Inserir', 'Deletar', 'Editar', 'Strutura'];
//separa os paramentro em array
if (isset($_GET['path'])) {
    $path = explode("/", $_GET['path']); //path esta declarada no arquivo .htaccess, como a variavel que vai receber os dados get
    if (isset($path[0])) { //verifica se foi declarado um serviço no path 0
        $api = $path[0]; // coloca na api qual serviço será requisitado
        if (isset($path[1])) {
            $servico = $path[1]; //Recebe a ação que a rota ira executar
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

use classes\ServicosApi;
//se não for encontrado o serviço entao é finalizada a rotina
if (!in_array(ucFirst($servico), $tipos_servicos, false)) { //ucfirst deixa a primeira letra maiuscula... 
    echo "serviço não encontrador";
    exit;
}
if ($metodo == "GET") {
    ServicosApi::Listar($api, $parametro ?? null); // se parametro estiver um valor recebe o valor, se não fica null
}
if ($metodo == "POST") {
    ServicosApi::Inserir($api, $_POST); // se parametro estiver um valor recebe o valor, se não fica null
}
if ($metodo == "DELETE") {
    ServicosApi::Deletar($api, $parametro); // se parametro estiver um valor recebe o valor, se não fica null
}
