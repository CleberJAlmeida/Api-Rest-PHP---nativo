<?php

namespace classes;

use DateTime;

class GerirToken
{
    private $key;

    function GerirToken()
    {
        $this->key = "cle321";
    }

    public function GerarToken($login, $id)
    {
        //header token
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        //payload / content
        $content = [
            // 'exp' => (new DateTime('now'))->getTimestamp(),
            'login' => $login,
            'uid' => $id
        ];

        //converter
        //JSON
        $header = json_encode($header);
        $content = json_encode($content);
        //Base64
        $header = base64_encode($header);
        $content = base64_encode($content);
        //Sign
        $sign = hash_hmac('sha256', $header . "." . $content, $this->key, true);
        $sign = base64_encode($sign);

        //Token
        $token = $header . '.' . $content . '.' . $sign;

        return $token;
    }

    function VerificarToken($token)
    {
        $statusToken = false;
        // pegando o token no bearer
        // $http_header = apache_request_headers();

        //evitar da diferença entre maiuscula e minuscula no authorization
        //$bearer =isset($http_header['authorization']) ? explode(" ", $http_header['authorization']) : explode(" ", $http_header['Authorization']);

        // $token = $bearer[1];

        $part = explode(".", $token);
        $header = $part[0];
        $content = $part[1];
        $signature = $part[2];
        $signature = str_replace("/", "\/", ($signature));
        
        $valid = hash_hmac('sha256', $header . "." . $content, $this->key, true);
        $valid = str_replace("/", "\/", base64_encode($valid));
        //var_dump($signature, $valid);
        if ($signature == $valid) {
            $statusToken = true;
        }

        return $statusToken;
    }
}
