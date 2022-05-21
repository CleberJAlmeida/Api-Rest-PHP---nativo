<?php

namespace classes;

use DateTime;

class CreateToken
{
    public static function GerarToken($login, $id)
    {
        $key = 'cle321';
        //header token
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        //payload / content
        $content = [
            'exp' => (new DateTime('now'))->getTimestamp(),
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
        $sign = hash_hmac('sha256', $header . "." . $content, $key, true);
        $sign = base64_encode($sign);

        //Token
        $token = $header . '.' . $content . '.' . $sign;

        print $token;
    }
}
